<x-admin-layout>

    <div class="mb-6">
        @if (session('success'))
            <div class="mb-4 p-4 rounded-md bg-green-50 border border-green-200 text-green-800">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-4 rounded-md bg-red-50 border border-red-200 text-red-800">
                {{ session('error') }}
            </div>
        @endif
    </div>

    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Add New Sale</h2>
                <p class="mt-1 text-sm text-gray-600">Record a new sale transaction</p>
            </div>
            <a href="{{ route('sales.index') }}"
                class="flex items-center text-sm font-medium text-gray-600 hover:text-gray-800 transition-colors">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Sales
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-sm p-6 max-w-2xl mx-auto">
        <form action="{{ route('sales.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Sale Date -->
            <div class="space-y-1">
                <label for="sale_date" class="block text-sm font-medium text-gray-700">Sale Date</label>
                <input type="date" id="sale_date" name="sale_date"
                    class="p-2 w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 transition-colors"
                    required>
                @error('sale_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Total Amount -->
            <div class="space-y-1">
                <label for="total_amount" class="block text-sm font-medium text-gray-700">Total Amount</label>
                <div class="relative rounded-lg shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">₱</span>
                    </div>
                    <input type="number" id="total_amount" name="total_amount"
                        class="p-2 pl-7 w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 transition-colors"
                        placeholder="0.00" step="0.01" required>
                </div>
                @error('total_amount')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end space-x-3 pt-4">
                <button type="button" onclick="window.location='{{ route('sales.index') }}'"
                    class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Cancel
                </button>
                <button type="submit"
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-yellow-400 to-green-500 hover:from-yellow-500 hover:to-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                    Save Sale
                </button>
            </div>
        </form>
    </div>

    <!-- Sales Table -->
    <div class="mt-10 bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-medium text-gray-800 mb-4">Recent Sales</h3>
        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 border-b">Sale Date</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 border-b">Total Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sales as $sale)
                    <tr>
                        <td class="px-4 py-2 border-b text-sm text-gray-600">{{ $sale->sale_date->format('F d, Y') }}
                        </td>
                        <td class="px-4 py-2 border-b text-sm text-gray-600">₱{{ $sale->formatted_total_amount }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-4 py-2 text-center text-sm text-gray-500">No sales data available.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $sales->links() }}
        </div>
    </div>
</x-admin-layout>
