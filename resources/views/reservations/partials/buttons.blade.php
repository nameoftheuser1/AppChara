<div class="flex gap-5">
    <!-- Back to Reservations -->
    <a href="{{ route('reservations.index') }}"
        class="text-white bg-gray-600 hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2 transition-colors duration-200">
        Back to orders
    </a>

    <a href="{{ route('reservations.refunded') }}"
        class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2 transition-colors duration-200">
        View Refunded
    </a>

    <a href="{{ route('reservations.cancel') }}"
        class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2 transition-colors duration-200">
        View Cancelled
    </a>

    <!-- Pending Reservations -->
    <a href="{{ route('reservations.pending') }}"
        class="text-white bg-yellow-600 hover:bg-yellow-700 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-4 py-2 transition-colors duration-200">
        View Pending
    </a>

    <!-- Processing Reservations -->
    <a href="{{ route('reservations.processing') }}"
        class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 transition-colors duration-200">
        View Processing
    </a>

    <!-- Ready to Pickup -->
    <a href="{{ route('reservations.ready-to-pickup') }}"
        class="text-white bg-purple-600 hover:bg-purple-700 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-4 py-2 transition-colors duration-200">
        View Ready to Pickup
    </a>

    <!-- Completed Reservations -->
    <a href="{{ route('reservations.complete') }}"
        class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 transition-colors duration-200">
        View Completed
    </a>

    <!-- All Reservations -->
    <a href="{{ route('reservations.all') }}"
        class="text-white bg-gray-600 hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2 transition-colors duration-200">
        View All
    </a>
</div>
