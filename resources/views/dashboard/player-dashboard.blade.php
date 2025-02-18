@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-semibold text-center mb-8">
        Welcome to your Player Dashboard, {{ Auth::user()->name }}!
    </h1>

    <div class="bg-blue-100 p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-xl font-semibold">Player Dashboard</h2>
        <p>Here you can see your upcoming matches, player stats, and team information.</p>
    </div>
@endsection
