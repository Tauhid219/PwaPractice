<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveExamQuestion extends Model
{
    protected $fillable = ['live_exam_id', 'question_id'];

    public function exam()
    {
        return $this->belongsTo(LiveExam::class, 'live_exam_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
