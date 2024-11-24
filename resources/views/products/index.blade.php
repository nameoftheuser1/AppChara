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
        <form method="GET" action="{{ route('products.index') }}"
            class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex-1 min-w-0">
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="pl-10 w-full rounded-md border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50"
                        placeholder="Search products...">
                </div>
            </div>
            <div class="flex items-center gap-4">
                <select name="filter"
                    class="rounded-md border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                    <option value="">All Products</option>
                    <option value="low_stock" {{ request('filter') === 'low_stock' ? 'selected' : '' }}>Low Stock
                    </option>
                    <option value="out_of_stock" {{ request('filter') === 'out_of_stock' ? 'selected' : '' }}>Out of
                        Stock</option>
                </select>
                <select name="sort"
                    class="rounded-md border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                    <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Sort by Latest</option>
                    <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low to High
                    </option>
                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to
                        Low</option>
                </select>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-500 hover:bg-green-600">
                    Apply
                </button>
            </div>
        </form>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse ($products as $product)
            <div class="flex flex-col bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                <!-- Product Image -->
                <div class="relative aspect-w-4 aspect-h-3 bg-gray-200 group">
                    @if ($product->img_path)
                        <img src="{{ asset($product->img_path) }}" alt="{{ $product->name }}"
                            class="w-full h-full object-cover group-hover:opacity-75 transition-opacity">
                    @else
                        <div class="flex items-center justify-center h-full bg-gray-100">
                            <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                    <a href="{{ route('products.show', $product) }}" class="absolute inset-0">
                        <span class="sr-only">View details for {{ $product->name }}</span>
                    </a>
                </div>

                <!-- Product Info -->
                <div class="flex-1 p-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-2 text-center">{{ $product->name }}</h3>
                    <p class="text-xl font-bold text-gray-900">₱{{ number_format($product->price, 2) }}</p>

                    <!-- Display Product Quantity -->
                    <p class="text-sm text-gray-600">Stock: {{ optional($product->inventory)->quantity ?? 'N/A' }}</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex border-t border-gray-200">
                    <a href="{{ route('products.edit', $product) }}"
                        class="flex-1 px-4 py-3 text-sm font-medium text-green-600 hover:text-green-500 hover:bg-gray-50 transition-colors text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6 mx-auto">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>

                    </a>
                    <div class="w-px bg-gray-200"></div>
                    <a href="{{ route('inventories.edit', $product) }}"
                        class="flex-1 px-4 py-3 text-sm font-medium text-blue-600 hover:text-blue-500 hover:bg-gray-50 transition-colors text-center">
                        Inventory
                    </a>
                    <div class="w-px bg-gray-200"></div>
                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="flex-1"
                        onsubmit="return confirm('Are you sure you want to delete this product?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full px-4 py-3 text-sm font-medium text-red-600 hover:text-red-500 hover:bg-gray-50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6 mx-auto">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
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
        @endforelse
    </div>

</x-admin-layout>
