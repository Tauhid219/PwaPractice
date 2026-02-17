@extends('frontend.layouts.master')
@section('title', 'About Us')

@section('content')
    @include('frontend.layouts.page_header', ['title' => 'About Us', 'active' => 'About Us'])
    @include('frontend.sections.about')
    @include('frontend.sections.call-to-action')
    @include('frontend.sections.team')
@endsection
