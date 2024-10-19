<?php

namespace App\Services;

class ArticleParserService
{
    public function parseArticle($text)
    {
//        $words = preg_split('/[^\p{L}\p{N}]+/u', $text);
        $words = preg_split('[]', $text);
        return array_filter($words);
    }

    public static function getCountWords($text)
    {
        $pattern = '/[^\p{L}\p{N}]+/u';
        $count = preg_match_all($pattern, $text, $words);
//        $this->words = array_unique(reset($words));
        return $count;
    }
}
