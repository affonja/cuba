<?php

namespace App\Http\Controllers;

use App\Models\Word;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

    /** Saves/updates the words from the article to the database
     */
    public function parseWords(): void
    {
        $words = array_count_values($this->words);
        foreach ($words as $word => $count) {
            $wordModel = Word::firstOrCreate(['word' => $word]);
            $this->saveWord($wordModel, $count);
        }
    }

    /** Saves/updates the word to the database
     * @param  Word  $wordModel
     * @param  int  $count
     */
    public function saveWord(Word $wordModel, int $count): void
    {
        $data = [
            'count' => $count,
            'updated_at' => now(),
        ];

        if ($this->article->words()->where('word_id', $wordModel->id)->exists()) {
            $this->article->words()->updateExistingPivot($wordModel->id, $data);
        } else {
            $data['created_at'] = now();
            $this->article->words()->attach($wordModel->id, $data);
        }
    }

    /** Searches for the word in the database
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
