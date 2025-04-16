@extends('layouts.finance')

@section('title', 'Profile')
@section('page-title', 'Finance Profile')

@section('content')
<div class="container">
    <h3>Profile Information</h3>
    <p><strong>Name:</strong> {{ $finance->first_name }} {{ $finance->last_name }}</p>
    <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
    <p><strong>Department:</strong> {{ $finance->department }}</p>
</div>
@endsection