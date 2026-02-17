@extends('frontend.layouts.master')
@section('title', 'Classes')

@section('content')
    @include('frontend.layouts.page_header', ['title' => 'Classes', 'active' => 'Classes'])
    @include('frontend.sections.classes')
    @include('frontend.sections.appointment')
    @include('frontend.sections.testimonial')
@endsection
