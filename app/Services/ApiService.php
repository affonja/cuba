<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\TransferStats;

class ApiService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
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
            'explaintext' => 'true'
        ];
    }

    public function sendRequest($url, $params)
    {
        $response = $this->client->get($url, [
            'query' => $params,
            'on_stats' => function (TransferStats $stats) use (&$executionTime) {
                $executionTime = $stats->getTransferTime();
            }
        ]);
        if ($response->getStatusCode() !== 200) {
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
        return [
            'title' => $page['title'],
            'content' => $page['extract'],
            'link' => $page['fullurl'],
            'length' => $page['length'],
            'wordsCount' => str_word_count($page['extract']),
        ];
    }


}
