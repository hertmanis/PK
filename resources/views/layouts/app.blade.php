<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'TeamManager Dashboard' }}</title>
    @vite('resources/css/app.css')
</head>
<body class="font-sans bg-gray-100">

    <nav class="bg-white p-4 flex justify-between items-center sticky top-0 z-50 shadow-md">
        <div class="flex space-x-4">
            <a href="{{ url('/dashboard') }}" class="font-bold text-xl transition duration-300">
                TeamManager
            </a>
        </div>

        <div class="flex space-x-4 ml-auto">
            <a href="{{ url('/profile') }}" class="text-gray-600 hover:text-gray-900 transition duration-300">Profils</a>

            @auth
                @if(Auth::user()->role == 1) 
                    <!-- Player -->
                    <a href="{{ url('/practices') }}" class="text-gray-600 hover:text-gray-900 transition duration-300">Grafiks</a>
                @elseif(Auth::user()->role == 0) 
                    <!-- Coach -->
                    <a href="{{ url('/manage-team') }}" class="text-gray-600 hover:text-gray-900 transition duration-300">Pārvaldīt komandu</a>
                    <a href="{{ url('/practices') }}" class="text-gray-600 hover:text-gray-900 transition duration-300">Grafiks</a>  <!-- Added this -->
                @endif
            @endauth

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <div class="container mx-auto p-8">
        @yield('content')
    </div>

    @include('footer')

</body>
</html>
