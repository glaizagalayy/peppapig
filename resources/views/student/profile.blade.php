@extends('layouts.student')

@section('title', 'Profile')
@section('page-title', 'Student Profile')

@section('content')
<div class="container">
    <h3>Profile Information</h3>
    <p><strong>Name:</strong> {{ $student->first_name }} {{ $student->last_name }}</p>
    <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
    <p><strong>Student ID:</strong> {{ $student->student_id }}</p>
</div>
@endsection