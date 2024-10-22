<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    use HasFactory;

    protected $fillable = ['word'];

    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_word')
            ->withPivot('count')
            ->withTimestamps();
    }

}
