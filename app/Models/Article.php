<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'link', 'size', 'wordsCount'];

    public function words()
    {
        return $this->belongsToMany(Word::class, 'article_word')
            ->withPivot('count')
            ->withTimestamps();
    }
}
