<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'required_score_to_unlock', 'is_free'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function userProgress()
    {
        return $this->hasMany(UserProgress::class);
    }

    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }
}
