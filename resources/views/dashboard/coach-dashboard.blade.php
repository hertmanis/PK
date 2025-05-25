@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-semibold text-center mb-8">
        Sveicināts, {{ Auth::user()->name }}!
    </h1>

    

    <div class="bg-green-100 p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-xl font-semibold">Trenera panelis</h2>
        <p>Pārvaldi savu komandu.</p>

        @if(Auth::user()->team)
            <div class="mt-4 bg-white p-4 rounded shadow">
                <h3 class="text-lg font-semibold">Komandas kods</h3>
                <p class="text-2xl font-bold text-green-700">{{ Auth::user()->team->team_code }}</p>
                <p class="text-gray-600">Dalies ar kodu, lai spēlētāji var pievienoties.</p>
            </div>
        @endif
    </div>
@endsection
