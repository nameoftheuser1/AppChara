<x-layout>
    <div
        class="min-h-screen bg-gradient-to-br from-yellow-400 to-green-500 flex items-center justify-center p-4 absolute top-0 w-full">
        <div class="w-full max-w-md">
            <div class="bg-white shadow-xl rounded-xl overflow-hidden">
                <div class="p-8">
                    <div class="flex justify-center mb-6">
                        <img src="{{ asset('img/appchara-logo.png') }}" alt="Logo" class="max-h-20">
                    </div>

                    <h1 class="text-2xl font-bold text-gray-700 text-center mb-4">Verify Your Email Address</h1>
                    <p class="text-gray-600 text-center mb-6">
                        We have sent a verification link to your email. Please check your inbox and click the link to verify your email address.
                    </p>

                    @if (session('message'))
                        <div class="bg-green-500 text-white text-sm p-2 rounded mb-4">
                            {{ session('message') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit"
                            class="w-full bg-gradient-to-r from-yellow-500 to-green-500 text-white font-bold py-2 px-4 rounded-full hover:from-yellow-600 hover:to-green-600 transition duration-300">
                            Resend Verification Email
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>
