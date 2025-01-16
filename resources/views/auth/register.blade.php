<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-center">Kāda ir Jūsu loma klubā?</h2>

            <div class="grid grid-cols-2 gap-6">

                <a href="{{ route('register.player') }}" class="bg-white rounded-lg shadow-md p-4 cursor-pointer hover:bg-gray-50 transition duration-300 block text-center">
                    <div class="flex flex-col items-center">
                        <div class="text-5xl text-green-500 mb-2">
                            <i class="fas fa-tshirt"></i> <span class="text-2xl">10</span>
                        </div>
                        <span class="font-medium">Sportists</span>
                    </div>
                </a>

                <a href="{{ route('register.coach') }}" class="bg-white rounded-lg shadow-md p-4 cursor-pointer hover:bg-gray-50 transition duration-300 block text-center">
                    <div class="flex flex-col items-center">
                        <div class="text-5xl text-green-500 mb-2">
                            <i class="fas fa-briefcase"></i> <i class="fas fa-bullhorn"></i>
                        </div>
                        <span class="font-medium">Treneris vai menedžeris</span>
                    </div>
                </a>

            </div>

        </div>
    </div>
</x-guest-layout>