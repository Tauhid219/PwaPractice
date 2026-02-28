<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index()
    {
        $categories = \App\Models\Category::orderBy('order')->get();
        return view('frontend.index', compact('categories'));
    }

    public function about()
    {
        return view('frontend.about');
    }

    public function categoryChapters($slug)
    {
        $category = \App\Models\Category::with(['chapters' => function ($query) {
            $query->orderBy('order');
        }])->where('slug', $slug)->firstOrFail();

        return view('frontend.chapters', compact('category'));
    }

    public function chapterQuestions($slug)
    {
        $chapter = \App\Models\Chapter::with('category')->where('slug', $slug)->firstOrFail();
        $questions = \App\Models\Question::where('chapter_id', $chapter->id)->get();

        return view('frontend.questions', compact('chapter', 'questions'));
    }

    public function classes()
    {
        return view('frontend.classes');
    }

    public function facility()
    {
        return view('frontend.facility');
    }

    public function team()
    {
        return view('frontend.team');
    }

    public function callToActionPage()
    {
        return view('frontend.call-to-action');
    }

    public function appointment()
    {
        return view('frontend.appointment');
    }

    public function testimonial()
    {
        return view('frontend.testimonial');
    }

    public function contact()
    {
        return view('frontend.contact');
    }

    public function notFound()
    {
        return view('frontend.404');
    }
}
