@extends('frontend.layouts.master')
@section('title', 'Contact Us')

@section('content')
    @include('frontend.layouts.page_header', ['title' => 'Contact Us', 'active' => 'Contact Us'])
    @include('frontend.sections.contact')
@endsection
