<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Services\ApiService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ArticleController extends Controller
{
    protected $url = 'https://ru.wikipedia.org/w/api.php';
    protected $apiService;
    const ROUND_FOR_TIME = 2;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $articles = Article::orderBy('updated_at', 'desc')->paginate(10);
        return view('index', compact('articles'));
    }

    /**
     * Get a new resource via the api.
     *
     * @param  Request  $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function getArticleFromApi(Request $request): JsonResponse
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


        $words = $this->apiService->articleParserService->getWords();
        $wordController = new WordController($words, $article);
        $wordController->parseWords();


        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        return response()->json([
            'articleData' => $articleData,
            'executionTime' => round($executionTime / self::ROUND_FOR_TIME)
        ]);
    }

    /**
     * Prepare the data for storage.
     *
     * @param  array  $data
     * @return array
     */
    private function prepareArticleData(array $data): array
    {
        return [
            'content' => $data['content'],
            'link' => $data['link'],
            'size' => $data['length'],
            'wordsCount' => $data['wordsCount'],
        ];
    }

    /**
     * Update article list from storage.
     *
     * @return View
     */
    public function updateTable(): View
    {
        $articles = Article::orderBy('updated_at', 'desc')->paginate(10);
        return view('components.articles_table', compact('articles'));
    }

    /**
     * Display the select articles content.
     *
     * @param  int  $id
     * @return string
     */
    public function show($id)
    {
        $article = Article::findOrFail($id, ['content']);
        return $article->content;
    }
}
