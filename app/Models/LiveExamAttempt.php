<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveExamAttempt extends Model
{
    protected $fillable = ['live_exam_id', 'user_id', 'score', 'passed', 'tab_switches', 'status'];

    protected static function booted()
    {
        $invalidateCache = function ($attempt) {
            \Illuminate\Support\Facades\Cache::put("exam_results_version_{$attempt->live_exam_id}", now()->timestamp);
        };

        static::saved($invalidateCache);
        static::deleted($invalidateCache);
    }

    public function exam()
    {
        return $this->belongsTo(LiveExam::class, 'live_exam_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
