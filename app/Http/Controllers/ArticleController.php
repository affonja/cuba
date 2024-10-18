<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{

    protected $url = 'https://ru.wikipedia.org/w/api.php';
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function index()
    {
        $articles = Article::all();
        return view('index', compact('articles'));
    }

    public function getArticleFromApi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'keyWord' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();
        $articleData = $this->apiService->getDataArticle($this->url, $validatedData);
        $this->store($articleData);

        return $articleData;
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

    public function updateTable()
    {
        $articles = Article::all();
        return view('components.articles_table', compact('articles'));
    }
}
