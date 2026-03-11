<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveExamAttempt extends Model
{
    protected $fillable = ['live_exam_id', 'user_id', 'score', 'passed'];

    public function exam()
    {
        return $this->belongsTo(LiveExam::class, 'live_exam_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
