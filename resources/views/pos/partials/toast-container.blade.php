<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-4">
    {{-- Success Toast Template --}}
    <div id="success-toast" class="hidden transform transition-all duration-300 ease-in-out translate-x-full">
        <div
            class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-lg flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                    </path>
                </svg>
                <span id="success-message"></span>
            </div>
            <button onclick="closeToast('success-toast')" class="text-green-700 hover:text-green-900">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
    </div>

    {{-- Error Toast Template --}}
    <div id="error-toast" class="hidden transform transition-all duration-300 ease-in-out translate-x-full">
        <div
            class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-lg flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span id="error-message"></span>
            </div>
            <button onclick="closeToast('error-toast')" class="text-red-700 hover:text-red-900">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
    </div>
</div>
