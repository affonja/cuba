<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Number;

class ApiService
{
    protected $client;
    public $articleParserService;
    const HTTP_OK = 200;

    public function __construct(Client $client, ArticleParserService $articleParserService)
    {
        $this->client = $client;
        $this->articleParserService = $articleParserService;
    }

    /** Get an article on the api
     * @param  string  $url
     * @param  array  $key
     * @param  string  $format
     * @return array
     * @throws Exception
     */
    public function getDataArticle(string $url, array $key, string $format = 'json'): array
    {
        $queryParams = $this->buildQueryParams(reset($key), $format);
        $response = $this->sendRequest($url, $queryParams);
        $decodedResponse = $this->processResponse($response);

        return $this->extractArticleData($decodedResponse);
    }

    /** Build query params
     * @param  string  $key
     * @param  string  $format
     * @return array
     */
    public function buildQueryParams(string $key, string $format): array
    {
        return [
            'action' => 'query',
            'format' => $format,
            'titles' => $key,
            'prop' => 'extracts|info',
            'inprop' => 'url',
            'explaintext' => 'true',
            'redirects' => 'true',
        ];
    }

    /** Send request to api
     * @param  string  $url
     * @param  array  $params
     * @return string
     * @throws Exception|GuzzleException
     */
    public function sendRequest(string $url, array $params): string
    {
        $response = $this->client->get($url, [
            'query' => $params,
        ]);
        if ($response->getStatusCode() !== self::HTTP_OK) {
            throw new Exception('Ошибка запроса: ' . $response->getStatusCode());
        }

        return $response->getBody()->getContents();
    }

    /** Response processing
     * @param  string  $response
     * @return array
     * @throws Exception
     */
    public function processResponse(string $response): array
    {
        $data = json_decode($response, true);
        $pages = $data['query']['pages'];
        $page = reset($pages);
        if (isset($page['missing'])) {
            throw new Exception('Контент не найден');
        }

        return $page;
    }

    /** Extracts data from the uploaded article
     * @param  array  $page
     * @return array
     */
    public function extractArticleData(array $page): array
    {
        $this->articleParserService->setWords($page['extract']);
        return [
            'title' => $page['title'],
            'content' => $page['extract'],
            'link' => urldecode($page['fullurl']),
            'length' => $page['length'],
            'wordsCount' => $this->articleParserService->getCountWords(),
        ];
    }
}
