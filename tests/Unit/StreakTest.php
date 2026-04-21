<?php

namespace Tests\Unit;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StreakTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test streak increments on consecutive days.
     */
    public function test_streak_increments_on_consecutive_days()
    {
        $user = User::factory()->create([
            'current_streak' => 1,
            'last_quiz_date' => Carbon::yesterday(),
        ]);

        $result = $user->updateStreak();

        $this->assertTrue($result);
        $this->assertEquals(2, $user->current_streak);
        $this->assertEquals(Carbon::today()->toDateString(), $user->last_quiz_date->toDateString());
    }

    /**
     * Test streak resets when a day is missed.
     */
    public function test_streak_resets_when_day_is_missed()
    {
        $user = User::factory()->create([
            'current_streak' => 5,
            'last_quiz_date' => Carbon::now()->subDays(2),
        ]);

        $result = $user->updateStreak();

        $this->assertTrue($result);
        $this->assertEquals(1, $user->current_streak);
        $this->assertEquals(Carbon::today()->toDateString(), $user->last_quiz_date->toDateString());
    }

    /**
     * Test streak does not increment twice on the same day.
     */
    public function test_streak_does_not_increment_twice_on_same_day()
    {
        $user = User::factory()->create([
            'current_streak' => 3,
            'last_quiz_date' => Carbon::today(),
        ]);

        $result = $user->updateStreak();

        $this->assertFalse($result);
        $this->assertEquals(3, $user->current_streak);
    }

    /**
     * Test streak starts at 1 for first time users.
     */
    public function test_streak_starts_at_1_for_new_users()
    {
        $user = User::factory()->create([
            'current_streak' => 0,
            'last_quiz_date' => null,
        ]);

        $result = $user->updateStreak();

        $this->assertTrue($result);
        $this->assertEquals(1, $user->current_streak);
        $this->assertEquals(Carbon::today()->toDateString(), $user->last_quiz_date->toDateString());
    }
}
