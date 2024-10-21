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

    const ROUND_FOR_TIME = 2;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function index()
    {
        $articles = Article::orderBy('updated_at', 'desc')->get();
        return view('index', compact('articles'));
    }

    public function getArticleFromApi(Request $request)
    {
        $startTime = microtime(true);

        $validator = Validator::make($request->all(), [
            'titleWord' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();

        try {
            $articleData = $this->apiService->getDataArticle($this->url, $validatedData);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 500);
        }

        $article = Article::updateOrCreate(
            ['title' => $articleData['title']],
            $this->prepareArticleData($articleData)
        );

        $words = $words = $this->apiService->articleParserService->getWords();
        $wordController = new WordController($words, $article);
        $wordController->parseWords();


        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        return response()->json([
            'articleData' => $articleData,
            'executionTime' => round($executionTime / self::ROUND_FOR_TIME)
        ]);
    }

    private function prepareArticleData($data)
    {
        return [
            'content' => $data['content'],
            'link' => $data['link'],
            'size' => $data['length'],
            'wordsCount' => $data['wordsCount'],
        ];
    }

    public function updateTable()
    {
        $articles = Article::orderBy('updated_at', 'desc')->get();
        return view('components.articles_table', compact('articles'));
    }

    public function show($id)
    {
        $article = Article::findorfail($id);
        return $article->content;
    }
}
