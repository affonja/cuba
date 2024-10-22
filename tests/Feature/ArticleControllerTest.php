<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $response = $this->get(route('article.index'));
        $response->assertStatus(200);
        $response->assertViewIs('index');
        $response->assertViewHas('articles');
    }

    public function testShow()
    {
        $article = Article::factory()->create();
        $response = $this->get(route('article.show', $article->id));
        $response->assertStatus(200);
        $response->assertSee($article->content);
    }

    public function testUpdateTable()
    {
        $article = Article::factory()->create();
        $response = $this->get(route('article.updTable'));
        $response->assertStatus(200);
        $response->assertSee($article->title);
    }

    public function testGetArticleFromApi()
    {
        $response = $this->post(route('article.import'), ['titleWord' => 'test']);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'articleData' => ['title', 'content', 'link', 'length', 'wordsCount'],
            'executionTime'
        ]);
    }
}
