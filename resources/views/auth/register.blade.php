<x-layout>
    <div
        class="min-h-screen bg-gradient-to-br from-yellow-400 to-green-500 flex items-center justify-center p-4 absolute top-0 w-full">
        <div class="w-full max-w-md">
            <div class="bg-white shadow-xl rounded-xl overflow-hidden">
                <div class="p-8">
                    <div class="flex justify-center mb-6">
                        <img src="{{ asset('img/appchara-logo.png') }}" alt="Logo" class="max-h-20">
                    </div>

                    @if (session('error'))
                        <div class="bg-red-500 text-white text-sm p-2 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="bg-green-500 text-white text-sm p-2 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('register') }}" method="POST" onsubmit="showLoadingOverlay()">
                        @csrf
                        <div class="mb-4">
                            <label for="username" class="block text-gray-700 font-bold text-xl mb-2">Username</label>
                            <input type="text" name="username" id="username"
                                class="w-full border border-gray-300 rounded-full p-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('username') border-red-500 @enderror"
                                required>
                            @error('username')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 font-bold text-xl mb-2">Email</label>
                            <input type="email" name="email" id="email"
                                class="w-full border border-gray-300 rounded-full p-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                                required>
                            @error('email')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block text-gray-700 font-bold text-xl mb-2">Password</label>
                            <p class="text-sm text-gray-500 mt-1 p-2 ring-1 m-2 rounded-lg ring-yellow-300">
                                At least 8 characters, 1 uppercase letter, 1 lowercase letter, and 1 number.
                            </p>
                            <input type="password" name="password" id="password"
                                class="w-full border border-gray-300 rounded-full p-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror"
                                required>

                            @error('password')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="password_confirmation"
                                class="block text-gray-700 font-bold text-xl mb-2">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="w-full border border-gray-300 rounded-full p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                        </div>

                        <button type="submit"
                            class="w-full bg-gradient-to-r from-yellow-500 to-green-500 text-white font-bold py-2 px-4 rounded-full hover:from-yellow-600 hover:to-green-600 transition duration-300">
                            Register
                        </button>
                        <div class="text-center mt-4">
                            <a href="{{ route('auth.login') }}" class="text-blue-500 hover:text-blue-700">Already have
                                an account? Login here</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('partials.loading-script')
</x-layout>
