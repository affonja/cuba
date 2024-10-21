<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\TransferStats;

class ApiService
{
    protected $client;
    public $articleParserService;

    const HTTP_OK = 200;
    const BYTES_IN_KILOBYTE = 1024;

    public function __construct(Client $client, ArticleParserService $articleParserService)
    {
        $this->client = $client;
        $this->articleParserService = $articleParserService;
    }

    public function getDataArticle($url, $key, $format = 'json')
    {
        $queryParams = $this->buildQueryParams(reset($key), $format);
        $response = $this->sendRequest($url, $queryParams);
        $decodedResponse = $this->processResponse($response);

        return $this->extractArticleData($decodedResponse);
    }

    public function buildQueryParams($key, $format)
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

    public function sendRequest($url, $params)
    {
        $response = $this->client->get($url, [
            'query' => $params,
        ]);
        if ($response->getStatusCode() !== self::HTTP_OK) {
            throw new \Exception('Ошибка запроса: ' . $response->getStatusCode());
        }

        return $response->getBody()->getContents();
    }

    public function processResponse($response)
    {
        $data = json_decode($response, true);
        $pages = $data['query']['pages'];
        $page = reset($pages);
        if (isset($page['missing'])) {
            throw new \Exception('Контент не найден');
        }

        return $page;
    }

    public function extractArticleData($page)
    {
        $this->articleParserService->setWords($page['extract']);
        return [
            'title' => $page['title'],
            'content' => $page['extract'],
            'link' => urldecode($page['fullurl']),
            'length' => $page['length'] / self::BYTES_IN_KILOBYTE,
            'wordsCount' => $this->articleParserService->getCountWords(),
        ];
    }


}
