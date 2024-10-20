<?php

namespace App\Services;

class ArticleParserService
{
    public static function getCountWords($text)
    {
        $pattern = '/[^\p{L}\p{N}]+/u';
        $count = preg_match_all($pattern, $text, $words);
        return $count;
    }

    public static function getWords($text)
    {
        $pattern = '/[\p{L}\p{N}-]+/u';
        $count = preg_match_all($pattern, $text, $words);
        return $words[0];
    }
}
