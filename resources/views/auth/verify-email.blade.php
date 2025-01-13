<x-layout>
    <div
        class="min-h-screen bg-gradient-to-br from-yellow-400 to-green-500 flex items-center justify-center p-4 absolute top-0 w-full">
        <div class="w-full max-w-md transform hover:scale-[1.01] transition-transform duration-300">
            <div class="bg-white shadow-2xl rounded-2xl overflow-hidden border border-green-100">
                <div class="p-8 space-y-6">
                    <div class="flex justify-center">
                        <img src="{{ asset('img/appchara-logo.png') }}" alt="Logo"
                            class="max-h-24 hover:scale-105 transition-transform duration-300">
                    </div>

                    <div class="space-y-4">
                        <h1 class="text-3xl font-bold text-gray-800 text-center">Verify Your Email Address</h1>
                        <p class="text-gray-600 text-center text-lg leading-relaxed">
                            We have sent a verification link to your email. Please check your inbox and click the link
                            to
                            verify your email address.
                        </p>
                    </div>

                    @if (session('message'))
                        <div
                            class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg animate-fade-in">
                            {{ session('message') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('verification.send') }}" class="space-y-6">
                        @csrf
                        <button type="submit"
                            class="w-full bg-gradient-to-r from-yellow-500 to-green-500 text-white font-semibold py-3 px-6 rounded-xl
                            hover:from-yellow-600 hover:to-green-600 transform hover:scale-[1.02] transition-all duration-300
                            shadow-lg hover:shadow-xl">
                            Resend Verification Email
                        </button>
                    </form>

                    <div class="border-t border-gray-200 pt-4">
                        <form action="{{ route('logout') }}" method="POST" id="logout-form">
                            @csrf
                            <button type="submit"
                                class="w-full group bg-white border-2 border-green-200 rounded-xl p-4 hover:bg-green-50
                                transition-all duration-300 flex items-center justify-between">
                                <span class="text-green-800 font-medium group-hover:text-green-900">Logout</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor"
                                    class="size-6 text-green-600 group-hover:translate-x-1 transition-transform duration-300">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
