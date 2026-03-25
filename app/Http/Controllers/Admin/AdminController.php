<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Question;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $categoryCount = Category::count();
        $questionCount = Question::count();
        $userCount = User::count();

        return view('admin.dashboard', compact('categoryCount', 'questionCount', 'userCount'));
    }
}
