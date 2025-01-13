<x-layout>
    <div
        class="min-h-screen bg-gradient-to-br from-yellow-400 to-green-500 flex items-center justify-center p-4 absolute top-0 w-full">
        <div class="w-full max-w-md">
            <div class="bg-white shadow-xl rounded-xl overflow-hidden">
                <div class="p-8">
                    <div class="flex justify-center mb-6">
                        <img src="{{ asset('img/appchara-logo.png') }}" alt="Logo" class="max-h-20">
                    </div>

                    @if (session('success'))
                        <div class="bg-green-500 text-white text-sm p-2 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 font-bold text-xl mb-2">Email</label>
                            <input type="text" name="email" id="email" value="{{ old('email') }}"
                                class="w-full border border-gray-300 rounded-full p-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                                required>
                            @error('email')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="password" class="block text-gray-700 font-bold text-xl mb-2">Password</label>
                            <div class="relative">
                                <input type="password" name="password" id="password"
                                    class="w-full border border-gray-300 rounded-full p-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror"
                                    required>
                                <button type="button" onclick="togglePassword()"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" id="eye-icon">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit"
                            class="w-full bg-gradient-to-r from-yellow-500 to-green-500 text-white font-bold py-2 px-4 rounded-full hover:from-yellow-600 hover:to-green-600 transition duration-300">
                            Login
                        </button>
                        <a href="{{ route('auth.register') }}"
                            class="text-blue-500 hover:text-blue-700 text-center">Create an account here</a>
                    </form>
                    <script>
                        function togglePassword() {
                            const passwordInput = document.getElementById('password');
                            const eyeIcon = document.getElementById('eye-icon');

                            if (passwordInput.type === 'password') {
                                passwordInput.type = 'text';
                                eyeIcon.innerHTML = `
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                `;
                            } else {
                                passwordInput.type = 'password';
                                eyeIcon.innerHTML = `
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                `;
                            }
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-layout>
