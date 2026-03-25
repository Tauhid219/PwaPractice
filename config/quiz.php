<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Quiz App Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can specify the global configuration values for the quiz system,
    | such as pagination counts, passing percentages, etc.
    |
    */

    'passing_percentage' => env('QUIZ_PASSING_PERCENTAGE', 80),

    'pagination' => [
        'frontend_questions' => env('PAGINATION_FRONTEND_QUESTIONS', 10),
        'admin_questions' => env('PAGINATION_ADMIN_QUESTIONS', 50),
        'admin_users' => env('PAGINATION_ADMIN_USERS', 50),
        'admin_user_attempts' => env('PAGINATION_ADMIN_USER_ATTEMPTS', 15),
    ],
];
