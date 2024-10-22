<?php

namespace App\Services;

class ArticleParserService
{
    protected array $words = [];

    /** Set words from article
     * @param  string  $words
     */
    public function setWords(string $words): void
    {
        $this->words = $this->extractWords($words);
    }

    /** Get count words from article
     * @return int
     */

    public function getCountWords(): int
    {
        return count($this->words);
    }

    /** Get words from article
     * @return array
     */

    public function getWords(): array
    {
        return $this->words;
    }

    /** Extract words from article
     * @param  string  $text
     * @return array
     */
    protected function extractWords(string $text): array
    {
        $pattern = '/[\p{L}\p{N}-]+/u';
        preg_match_all($pattern, $text, $matches);
        return $matches[0];
    }
}
