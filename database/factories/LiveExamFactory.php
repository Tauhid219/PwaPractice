<?php

namespace Database\Factories;

use App\Models\LiveExam;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LiveExam>
 */
class LiveExamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startTime = now()->addMinutes(10);
        $duration = 30;

        return [
            'title' => $this->faker->sentence(),
            'start_time' => $startTime,
            'end_time' => (clone $startTime)->addMinutes($duration),
            'duration_minutes' => $duration,
            'is_active' => true,
        ];
    }
}
