@extends('frontend.layouts.master')
@section('title', 'School Facilities')

@section('content')
    @include('frontend.layouts.page_header', ['title' => 'School Facilities', 'active' => 'School Facilities'])
    @include('frontend.sections.facilities')
@endsection
