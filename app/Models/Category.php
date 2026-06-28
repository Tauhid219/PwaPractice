<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory;

    protected $fillable = ['name', 'slug', 'icon', 'description', 'order'];

    protected static function booted()
    {
        static::saved(function ($category) {
            Cache::forget('global_categories_all');
            Cache::forget('categories_all');
            Cache::forget('category_full_' . $category->slug);
            if ($category->wasChanged('slug')) {
                Cache::forget('category_full_' . $category->getOriginal('slug'));
            }
        });

        static::deleted(function ($category) {
            Cache::forget('global_categories_all');
            Cache::forget('categories_all');
            Cache::forget('category_full_' . $category->slug);
        });
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function levels()
    {
        return $this->hasMany(Level::class)->orderBy('order');
    }
}
