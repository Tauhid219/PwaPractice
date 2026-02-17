<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index()
    {
        return view('frontend.index');
    }

    public function about()
    {
        return view('frontend.about');
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
