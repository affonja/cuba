<?php

namespace Tests\Feature;

use App\Http\Controllers\WordController;
use App\Models\Article;
use App\Models\Word;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class WordControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $article;
    protected $words;
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->article = Article::factory()->create();
        $this->words = Word::factory()->count(3)->make()->pluck('word')->toArray();
        $this->controller = new WordController($this->words, $this->article);
    }

    public function testParseWords()
    {
        $this->controller->parseWords();

        foreach ($this->words as $word) {
            $this->assertDatabaseHas('words', ['word' => $word]);
            $this->assertEquals(
                array_count_values($this->words)[$word],
                $this->article->words()->where('word', $word)->first()->pivot->count
            );
        }
    }

//    public function testSaveWord()
//    {
//        $wordModel = Word::firstOrCreate(['word' => $this->words[0]]);
//        $this->controller->saveWord($wordModel, 3);
//
//        $this->assertDatabaseHas('article_word', [
//            'article_id' => $this->article->id,
//            'word_id' => $wordModel->id,
//            'count' => 3,
//        ]);
//    }

    public function testSearchWord()
    {
        $this->controller->parseWords();

        $response = $this->post(route('word.search'), ['keyWord' => $this->words[0]]);
        $response->assertStatus(200);
        $responseData = $response->json();

        $this->assertNotEmpty($responseData);
        $this->assertEquals(1, $responseData[0]['count']);
    }
}
