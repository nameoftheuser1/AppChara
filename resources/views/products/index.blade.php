<x-admin-layout>
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Products</h2>
                <p class="mt-1 text-sm text-gray-600">Manage your product inventory</p>
            </div>
            <a href="{{ route('products.create') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-yellow-400 to-green-500 hover:from-yellow-500 hover:to-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add New Product
            </a>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="mb-6 bg-white rounded-lg shadow-sm p-4">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex-1 min-w-0">
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text"
                        class="pl-10 w-full rounded-md border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                        placeholder="Search products...">
                </div>
            </div>
            <div class="flex items-center gap-4">
                <select
                    class="rounded-md border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                    <option>All Products</option>
                    <option>Low Stock</option>
                    <option>Out of Stock</option>
                </select>
                <select
                    class="rounded-md border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                    <option>Sort by Latest</option>
                    <option>Price: Low to High</option>
                    <option>Price: High to Low</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach ($products as $product)
            <div class="group relative bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                <!-- Product Image -->
                <div class="aspect-w-4 aspect-h-3 bg-gray-200 group-hover:opacity-75 transition-opacity">
                    @if ($product->img_path)
                        <img src="{{ asset('storage/' . $product->img_path) }}" alt="{{ $product->name }}"
                            class="w-full h-full object-cover">
                    @else
                        <div class="flex items-center justify-center h-full bg-gray-100">
                            <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="p-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $product->name }}</h3>
                    <p class="text-xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</p>

                    <!-- Action Buttons -->
                    <div class="mt-4 flex items-center justify-between">
                        <a href="{{ route('products.edit', $product) }}"
                            class="text-sm font-medium text-green-600 hover:text-green-500">
                            Edit
                        </a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline-block"
                            onsubmit="return confirm('Are you sure you want to delete this product?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-500">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Clickable Overlay -->
                <a href="{{ route('products.show', $product) }}" class="absolute inset-0">
                    <span class="sr-only">View details for {{ $product->name }}</span>
                </a>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $products->links() }}
    </div>

    <!-- Empty State -->
    @if ($products->isEmpty())
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No products</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by creating a new product.</p>
            <div class="mt-6">
                <a href="{{ route('products.create') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-yellow-400 to-green-500 hover:from-yellow-500 hover:to-green-600">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Product
                </a>
            </div>
        </div>
    @endif
</x-admin-layout>
