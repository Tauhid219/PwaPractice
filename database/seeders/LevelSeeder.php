<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        Level::create([
            'name' => 'Level 1',
            'required_score_to_unlock' => 0,
            'is_free' => true,
        ]);

        Level::create([
            'name' => 'Level 2',
            'required_score_to_unlock' => 10,
            'is_free' => false,
        ]);

        Level::create([
            'name' => 'Level 3',
            'required_score_to_unlock' => 20,
            'is_free' => false,
        ]);
    }
}
