<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Chapter;
use App\Models\Question;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $categoryCount = Category::count();
        $chapterCount = Chapter::count();
        $questionCount = Question::count();

        return view('admin.dashboard', compact('categoryCount', 'chapterCount', 'questionCount'));
    }
}
