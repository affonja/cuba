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
            $model = Word::where('word', $word)->first();
            if ($model) {
                $this->update($model, $count);
            } else {
                $this->store($word, $count);
            }
        }
    }

    public function store($word, $count)
    {
        $model = Word::create(['word' => $word]);
        $this->article->words()->attach($model->id, ['count' => $count]);
    }

    public function update($model, $count)
    {
        $modelSvaz = DB::table('article_word')->where('word_id', $model->id)->where(
            'article_id',
            $this->article->id
        )->first();
        if ($modelSvaz) {
            DB::table('article_word')->where('word_id', $model->id)->where('article_id', $this->article->id)->update(
                ['count' => $count]
            );
        } else {
            DB::table('article_word')->insert(
                ['word_id' => $model->id, 'article_id' => $this->article->id, 'count' => $count]
            );
        }
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
