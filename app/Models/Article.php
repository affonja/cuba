<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['title', 'content', 'link', 'size', 'wordsCount'];

    public function words()
    {
        return $this->belongsToMany(Word::class, 'article_atom')->withPivot('count');
    }
}
