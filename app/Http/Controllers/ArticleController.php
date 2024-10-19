<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Services\ApiService;
use App\Services\ArticleParserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function Termwind\parse;

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
        $articles = Article::orderBy('updated_at', 'desc')->get();
        return view('index', compact('articles'));
    }

    public function getArticleFromApi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'keyWord' => 'required|max:255',
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

        $article = Article::where('title', $articleData['title'])->first();
        ($article) ? $this->update($article, $articleData) : $this->store($articleData);

//        ArticleParserService::class->parseArticle($articleData['content']);

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

    public function update($article, $data)
    {
        $article->update([
            'content' => $data['content'],
            'link' => $data['link'],
            'size' => $data['length'],
            'wordsCount' => $data['wordsCount'],
        ]);
    }

    public function updateTable()
    {
        $articles = Article::orderBy('updated_at', 'desc')->get();
        return view('components.articles_table', compact('articles'));
    }
}
