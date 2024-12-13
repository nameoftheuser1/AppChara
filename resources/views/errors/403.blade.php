<x-layout>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="text-center">
            {{-- Cute Lost Cat 404 SVG --}}
            <div class="flex justify-center">
                <svg width="250px" height="250px" viewBox="0 0 400 400" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M104.613 165C62.4895 136.517 97.2059 92.081 125 137.46" stroke="#000000" stroke-opacity="0.9"
                        stroke-width="16" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M259 133.798C279.706 100.527 328.781 104.891 298.253 150" stroke="#000000"
                        stroke-opacity="0.9" stroke-width="16" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M161.153 159C160.362 154.1 162.845 149.364 164 145" stroke="#000000" stroke-opacity="0.9"
                        stroke-width="16" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M194 165C194.409 156.616 194.948 148.211 196 140" stroke="#000000" stroke-opacity="0.9"
                        stroke-width="16" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M228 159C228 154.661 228 150.329 228 146" stroke="#000000" stroke-opacity="0.9"
                        stroke-width="16" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M153 223C160.473 220.915 168.386 220.023 176 219" stroke="#000000" stroke-opacity="0.9"
                        stroke-width="16" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M225 219C232.895 217.426 240.281 217.931 248 219" stroke="#000000" stroke-opacity="0.9"
                        stroke-width="16" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M188 256.005C221.5 238.742 217.338 264.602 191.479 260.565" stroke="#000000"
                        stroke-opacity="0.9" stroke-width="16" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M201 267C199.054 288.306 181.973 290.175 167 283.734" stroke="#000000" stroke-opacity="0.9"
                        stroke-width="16" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M200.041 267C198.864 295.299 223.581 291.006 237 277.407" stroke="#000000"
                        stroke-opacity="0.9" stroke-width="16" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M111 243C96.3264 238.228 80.8117 237.965 66 236" stroke="#000000" stroke-opacity="0.9"
                        stroke-width="16" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M116 267C99.8675 270.808 83.7433 273.752 68 279" stroke="#000000" stroke-opacity="0.9"
                        stroke-width="16" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M293 233C304.501 229.96 315.688 225.62 327 222" stroke="#000000" stroke-opacity="0.9"
                        stroke-width="16" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M297 261C308.857 259.497 322.138 260.027 333 260.429" stroke="#000000" stroke-opacity="0.9"
                        stroke-width="16" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>

            <h1 class="text-4xl font-bold text-gray-800 mb-4">403: This is page is not for you!</h1>
            <p class="text-gray-600 mb-6">This page seems to have wandered off...</p>


            <a href="{{ route('home.index') }}"
                class="px-6 py-3 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">
                Return Home
            </a>

        </div>
    </div>
</x-layout>