<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'TeamManager Dashboard' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans">

    <nav class="bg-white p-4 flex justify-between items-center sticky top-0 z-50 shadow-md">
        <div class="flex space-x-4">
            <a href="{{ url('/dashboard') }}" class="font-bold text-xl transition duration-300">
                TeamManager
            </a>
            <a href="/grafiks" class="font-bold text-xl transition duration-300">Grafiks</a>
        </div>
        <!-- Added flex container for the links -->
        <div class="flex space-x-4 ml-auto">
            <a href="/profile" class="text-gray-600 hover:text-gray-900 transition duration-300">Profile</a>

        </div>
    </nav>

    <div class="container mx-auto p-8">
        <h1 class="text-3xl font-semibold text-center mb-8">Welcome to your Dashboard!</h1>
        
        <!-- Logout Form -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                Logout
            </button>
        </form>
    </div>

    <!-- Include the footer -->
    @include('footer')

</body>
</html>
