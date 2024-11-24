<x-admin-layout>
    <form action="{{ route('inventories.update', ['product' => $product->id]) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Product Name -->
        <div class="space-y-1">
            <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
            <input type="text" id="name" name="name" value="{{ $product->name }}" disabled
                class="p-2 w-full rounded-lg border-gray-300 bg-gray-100 cursor-not-allowed">
        </div>

        <!-- Product Quantity -->
        <div class="space-y-1">
            <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
            <input type="number" id="quantity" name="quantity" value="{{ $inventory->quantity }}"
                class="p-2 w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 transition-colors"
                placeholder="Enter quantity" required>
            @error('quantity')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end space-x-3 pt-4">
            <button type="button" onclick="window.location='{{ route('products.index') }}'"
                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                Cancel
            </button>
            <button type="submit"
                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-yellow-400 to-green-500 hover:from-yellow-500 hover:to-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                Update Inventory
            </button>
        </div>
    </form>
</x-admin-layout>
