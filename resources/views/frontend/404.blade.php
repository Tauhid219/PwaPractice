@extends('frontend.layouts.master')
@section('title', '404 Error')

@section('content')
    @include('frontend.layouts.page_header', ['title' => '404 Error', 'active' => '404 Error'])
    @include('frontend.sections.404')
@endsection
