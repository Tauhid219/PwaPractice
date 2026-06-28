<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class SchedulerTest extends TestCase
{
    /**
     * Test that the Laravel scheduler runs without any runtime exceptions.
     */
    public function test_scheduler_executes_successfully()
    {
        // Run the scheduler via artisan command
        $exitCode = Artisan::call('schedule:run');

        // It should exit with 0 (success)
        $this->assertEquals(0, $exitCode);

        // Optional: Ensure the output doesn't contain error keywords
        $output = Artisan::output();
        $this->assertStringNotContainsString('Exception', $output);
        $this->assertStringNotContainsString('Fatal error', $output);
    }
}
