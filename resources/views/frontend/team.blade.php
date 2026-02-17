@extends('frontend.layouts.master')
@section('title', 'Popular Teachers')

@section('content')
    @include('frontend.layouts.page_header', ['title' => 'Popular Teachers', 'active' => 'Popular Teachers'])
    @include('frontend.sections.team')
@endsection
