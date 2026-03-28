<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Globally bypass CSRF in tests to simplify feature testing
        $this->withoutMiddleware(ValidateCsrfToken::class);
    }
}
