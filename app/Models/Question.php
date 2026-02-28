<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    /** @use HasFactory<\Database\Factories\QuestionFactory> */
    use HasFactory;

    protected $fillable = ['chapter_id', 'question_text', 'answer_text'];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
}
