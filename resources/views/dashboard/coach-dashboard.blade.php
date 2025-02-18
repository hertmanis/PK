@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-semibold text-center mb-8">
        Welcome to your Coach Dashboard, {{ Auth::user()->name }}!
    </h1>

    

    <div class="bg-green-100 p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-xl font-semibold">Coach Dashboard</h2>
        <p>Manage your team's lineups, tactics, and view performance data.</p>

        @if(Auth::user()->team)
            <div class="mt-4 bg-white p-4 rounded shadow">
                <h3 class="text-lg font-semibold">Your Team Code</h3>
                <p class="text-2xl font-bold text-green-700">{{ Auth::user()->team->team_code }}</p>
                <p class="text-gray-600">Share this code with players to let them join your team.</p>
            </div>
        @endif
    </div>
@endsection
