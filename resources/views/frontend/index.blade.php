@extends('frontend.layouts.master')
@section('title', 'Kider - Home')

@section('content')
    @include('frontend.sections.carousel')
    @include('frontend.sections.facilities')
    @include('frontend.sections.about')
    @include('frontend.sections.call-to-action')
    @include('frontend.sections.classes')
    @include('frontend.sections.appointment')
    @include('frontend.sections.team')
    @include('frontend.sections.testimonial')
@endsection
