@extends('frontend.layouts.master')
@section('title', 'Testimonial')

@section('content')
    @include('frontend.layouts.page_header', ['title' => 'Testimonial', 'active' => 'Testimonial'])
    @include('frontend.sections.testimonial')
@endsection
