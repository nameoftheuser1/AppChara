<x-layout>
    <div class="min-h-screen flex items-center justify-center">
        <div class="p-8 rounded w-full max-w-md">
            <img src="{{ asset('img/appchara-logo.png') }}" alt="" srcset="">
            @if (session('error'))
                <div class="bg-red-500 text-white text-sm p-2 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-bold text-xl">Username</label>
                    <input type="text" name="email" id="email"
                        class="w-full border-gray-300 rounded-full p-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                        required>
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 font-bold text-xl">Password</label>
                    <input type="password" name="password" id="password"
                        class="w-full border-gray-300 rounded-full p-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror"
                        required>
                    @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit"
                    class="w-full bg-gradient-to-r from-yellow-500 to-green-500 text-white font-bold py-2 px-4 rounded hover:from-yellow-600 hover:to-green-600">
                    Login
                </button>
            </form>
        </div>
    </div>
</x-layout>
