<?php

namespace App\Services;

class ArticleParserService
{
    protected $words = [];

    public function setWords($words)
    {
        $this->words = $this->extractWords($words);
    }

    public function getCountWords()
    {
        return count($this->words);
    }

    public function getWords()
    {
        return $this->words;
    }

    protected function extractWords($text)
    {
        $pattern = '/[\p{L}\p{N}-]+/u';
        preg_match_all($pattern, $text, $matches);
        return $matches[0];
    }
}
