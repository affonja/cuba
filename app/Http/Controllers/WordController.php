<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WordController extends Controller
{
    protected $words;
    protected $article;

    public function __construct($words = [], $article = null)
    {
        $this->words = $words;
        $this->article = $article;
    }

    public function parseWords($words)
    {
        $words = array_count_values($words);
        foreach ($words as $word => $count) {
            $wordModel = Word::firstOrCreate(['word' => $word]);

            if ($this->article->words()->where('word_id', $wordModel->id)->exists()) {
                $this->update($wordModel, $count);
            } else {
                $this->store($wordModel, $count);
            }
        }
    }

    public function store($wordModel, $count)
    {
        $this->article->words()->attach($wordModel->id, [
            'count' => $count,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function update($wordModel, $count)
    {
        $this->article->words()->updateExistingPivot($wordModel->id, [
            'count' => $count,
            'updated_at' => now(),
        ]);
    }

    public function searchWord(Request $request)
    {
        $keyWord = $request->keyWord;
        $wordId = Word::where('word', $keyWord)->first()->id;
        $sovpadeniaCount = DB::table('article_word')->where('word_id', $wordId)->count();
        $sovpadenia = DB::table('article_word')->where('word_id', $wordId)->get()->toArray();
        foreach ($sovpadenia as $item) {
            $sovpadeniaArray[] = [
                'article_id' => $item->article_id,
                'count' => $item->count,
                'title' => DB::table('articles')->where('id', $item->article_id)->first()->title,
            ];
        }
        return $sovpadeniaArray ?? [];
    }
}
