<?php

namespace App\Http\Controllers;

use App\Models\Word;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class WordController extends Controller
{
    protected array $words;
    protected mixed $article;

    public function __construct($words = [], $article = null)
    {
        $this->words = $words;
        $this->article = $article;
    }

    /** Parse and updates the words from the article to the database     */
    public function parseWords(): void
    {
        $words = array_count_values($this->words);
        $this->saveNewWords($words);
        $updateData = $this->prepareData($words);
        $this->updateWords($updateData);
    }

    /**
     * Updates/saves the words in the database
     * @param  array  $updateData
     */
    private function updateWords(array $updateData): void
    {
        DB::table('article_word')->upsert($updateData, ['article_id', 'word_id'], ['count', 'updated_at']);
    }

    /**
     * Prepares the data for update database
     * @param  array  $words
     * @return array
     */
    protected function prepareData(array $words): array
    {
        $existingWords = $this->getWords();

        foreach ($words as $word => $count) {
            $wordId = $existingWords[$word]->id;
            $data[] = [
                'article_id' => $this->article->id,
                'word_id' => $wordId,
                'count' => $count,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        return $data ?? [];
    }

    /**
     * Saves the new words to the database
     * @param  array  $words
     * @return void
     */
    protected function saveNewWords(array $words): void
    {
        $existingWords = $this->getWords();
        $newWords = [];

        foreach ($words as $word => $count) {
            if (!isset($existingWords[$word])) {
                $newWords[] = ['word' => $word, 'created_at' => now(), 'updated_at' => now()];
            }
        }
        if (!empty($newWords)) {
            Word::insert($newWords);
        }
    }

    /**
     * Returns the existing words from the database
     * @return Collection
     */
    protected function getWords(): Collection
    {
        return Word::whereIn('word', $this->words)->get()->keyBy('word');
    }

    /**
     * Searches for the word in the database
     * @param  Request  $request
     * @return array|JsonResponse
     * @throws ValidationException
     */
    public function searchWord(Request $request): array | JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'keyWord' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();

        $word = Word::where('word', $validatedData)->first();
        if ($word) {
            $articlesWithWord = $word->articles()->get();
            foreach ($articlesWithWord as $article) {
                $occurrencesArray[] = [
                    'count' => $article->pivot->count,
                    'title' => $article->title,
                    'link' => '/article/' . $article->id,
                ];
            }
            $collect = collect($occurrencesArray);
            return $collect->sortByDesc('count')->values()->toArray();
        }
        return [];
    }


}
