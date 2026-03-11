<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveExam extends Model
{
    protected $fillable = ['title', 'description', 'start_time', 'end_time', 'duration_minutes', 'is_active'];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function examQuestions()
    {
        return $this->hasMany(LiveExamQuestion::class);
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'live_exam_questions', 'live_exam_id', 'question_id');
    }

    public function attempts()
    {
        return $this->hasMany(LiveExamAttempt::class);
    }
}
