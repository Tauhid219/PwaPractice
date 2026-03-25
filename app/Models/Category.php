<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    protected $fillable = ['name', 'slug', 'icon', 'description', 'order'];

    protected static function booted()
    {
        static::saved(fn () => Cache::flush());
        static::deleted(fn () => Cache::flush());
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
