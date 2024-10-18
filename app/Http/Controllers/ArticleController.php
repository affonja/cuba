<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Services\ApiService;
use Illuminate\Http\Request;

class ArticleController extends Controller
{

    protected $url = 'https://ru.wikipedia.org/w/api.php';
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function getArticleFromApi(Request $request)
    {
        $validated = $request->validate([
            'keyWord' => 'required|max:255'
        ]);

        $articleData = $this->apiService->getDataArticle($this->url, $validated);
        $this->store($articleData);

        return view('index', compact('articleData'));
    }

    public function store($data)
    {
        Article::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'link' => $data['link'],
            'size' => $data['length'],
            'wordsCount' => $data['wordsCount'],
        ]);
    }
}
