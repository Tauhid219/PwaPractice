<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravolt\Avatar\Facade;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'current_streak',
        'last_quiz_date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_quiz_date' => 'date',
        ];
    }

    public function userProgress()
    {
        return $this->hasMany(UserProgress::class);
    }

    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function getAvatarAttribute()
    {
        return Facade::create($this->name)->toBase64();
    }

    /**
     * Update user streak based on activity today.
     * Returns true if streak was incremented today.
     */
    public function updateStreak()
    {
        $today = now()->startOfDay();
        $lastQuizDate = $this->last_quiz_date ? $this->last_quiz_date->startOfDay() : null;

        if ($lastQuizDate && $lastQuizDate->equalTo($today)) {
            return false; // Already updated today
        }

        if ($lastQuizDate && $lastQuizDate->equalTo($today->copy()->subDay())) {
            $this->current_streak += 1;
        } else {
            $this->current_streak = 1;
        }

        $this->last_quiz_date = $today;
        $this->save();

        return true;
    }
}
