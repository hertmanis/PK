@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-semibold text-center mb-8">
        Laipni lūgti trenera panelī, {{ Auth::user()->name }}!
    </h1>

    <div class="bg-green-100 p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-xl font-semibold">Trenera panelis</h2>
        <p>Pārvaldiet savas komandas sastāvu.</p>

        @if(Auth::user()->team)
            <!-- Komandas kods -->
            <div class="mt-4 bg-white p-4 rounded shadow">
                <h3 class="text-lg font-semibold">Jūsu komandas kods</h3>
                <p class="text-2xl font-bold text-green-700">{{ Auth::user()->team->team_code }}</p>
                <p class="text-gray-600">Kopīgojiet šo kodu ar spēlētājiem, lai viņi varētu pievienoties komandai.</p>
            </div>

            <!-- Komandas dalībnieku saraksts -->
            <div class="mt-6 bg-white p-4 rounded shadow">
                <h3 class="text-lg font-semibold">Komandas dalībnieki</h3>
                
                @if($teamMembers->count() > 0)
                    <ul class="mt-2 space-y-2">
                        @foreach($teamMembers as $member)
                            <li class="bg-gray-100 p-2 rounded">
                                <span class="font-medium">{{ $member->name }}</span> - {{ $member->email }} 
                                <span class="text-sm text-gray-600">
                                    ({{ ucfirst($member->role) }})
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500 mt-2">Neviens dalībnieks vēl nav pievienojies komandai.</p>
                @endif
            </div>
        @else
            <p class="text-red-500 font-semibold">Jūs vēl neesat pievienots nevienai komandai.</p>
        @endif
    </div>
@endsection
