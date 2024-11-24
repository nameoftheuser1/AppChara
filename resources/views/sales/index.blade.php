<x-admin-layout>
    <div class="p-4">
        <!-- Clickable Card -->
        <a href="{{ route('pos.index') }}"
            class="block p-6 max-w-sm bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100">
            <!-- Card Content -->
            <div class="flex items-center">
                <!-- SVG Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-8 h-8 text-gray-500 mr-4">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
                </svg>
                <!-- Text -->
                <span class="text-lg font-semibold text-gray-700">Point Of Sale</span>
            </div>
        </a>
    </div>
</x-admin-layout>
