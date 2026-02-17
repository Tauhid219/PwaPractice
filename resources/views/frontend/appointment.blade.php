@extends('frontend.layouts.master')
@section('title', 'Make Appointment')

@section('content')
    @include('frontend.layouts.page_header', ['title' => 'Make Appointment', 'active' => 'Make Appointment'])
    @include('frontend.sections.appointment')
@endsection
