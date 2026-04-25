<?php

namespace App\Models;

use Database\Factories\QuestionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Question extends Model
{
    /** @use HasFactory<QuestionFactory> */
    use HasFactory;

    protected $fillable = ['category_id', 'level_id', 'question_text', 'option_1', 'option_2', 'option_3', 'answer_text'];

    protected static function booted()
    {
        static::saved(fn () => Cache::flush());
        static::deleted(fn () => Cache::flush());
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    /**
     * Get the options for this question in a randomized order.
     * 
     * @return array
     */
    public function shuffledOptions(): array
    {
        $options = [
            $this->option_1,
            $this->option_2,
            $this->option_3,
        ];
        
        shuffle($options);
        
        return $options;
    }
}
