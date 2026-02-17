@extends('frontend.layouts.master')
@section('title', 'Become A Teachers')

@section('content')
    @include('frontend.layouts.page_header', ['title' => 'Become A Teachers', 'active' => 'Become A Teachers'])
    @include('frontend.sections.call-to-action')
@endsection
