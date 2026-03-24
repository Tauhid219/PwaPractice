<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Level;
use App\Models\Question;
use App\Models\ReadQuestion;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('order')->get();
        return view('frontend.index', compact('categories'));
    }

    public function about()
    {
        return view('frontend.about');
    }

    public function categoryLevels($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        $levels = Level::whereHas('questions', function($q) use ($category) {
            $q->where('category_id', $category->id);
        })->get();

        if ($levels->isEmpty()) {
            $levels = Level::all();
        }

        return view('frontend.levels', compact('category', 'levels'));
    }

    public function levelQuestions($categorySlug, $levelId)
    {
        $category = Category::where('slug', $categorySlug)->firstOrFail();
        $level = Level::findOrFail($levelId);
        
        $query = Question::where('category_id', $category->id)
                         ->where('level_id', $level->id);
                         
        if (!auth()->check() && $levelId > 1) {
            // Unauthenticated users can only view level 1 properly.
            // Though route should protect this, adding explicit fallback check
            abort(403, 'এই লেভেলের প্রশ্নগুলো দেখতে অনুগ্রহ করে লগিন করুন।');
        }

        $questions = $query->paginate(10); // Specifically 10 questions per page based on user preference

        $readQuestionIds = [];
        if (auth()->check()) {
            $readQuestionIds = ReadQuestion::where('user_id', auth()->id())
                ->whereHas('question', function($q) use ($category, $level) {
                     $q->where('category_id', $category->id)->where('level_id', $level->id);
                })->pluck('question_id')->toArray();
        }

        return view('frontend.questions', compact('category', 'level', 'questions', 'readQuestionIds'));
    }

    public function markQuestionAsRead(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }
        
        $request->validate(['question_id' => 'required|exists:questions,id']);
        
        ReadQuestion::firstOrCreate([
            'user_id' => auth()->id(),
            'question_id' => $request->question_id
        ]);
        
        return response()->json(['success' => true]);
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
