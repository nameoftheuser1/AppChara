<div class="col-span-8">
    <div class="grid grid-cols-3 gap-4">
        @forelse ($products as $product)
            <div class="bg-white rounded-lg shadow-md overflow-hidden cursor-pointer hover:shadow-lg transition-shadow duration-200"
                onclick="openQuantityModal({{ $product->id }}, '{{ $product->name }}', {{ $product->price }}, {{ $product->inventory->quantity }})">
                @if ($product->img_path)
                    <img src="{{ asset($product->img_path) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-500">No image</span>
                    </div>
                @endif
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-800">{{ $product->name }}</h3>
                    <div class="mt-2 flex justify-between items-center">
                        <span class="text-gray-600">₱{{ number_format($product->price, 2) }}</span>
                        <span class="text-sm text-gray-500">Stock:
                            {{ $product->inventory->quantity }}</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-8">
                <p class="text-gray-500">No products found</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $products->links() }}
    </div>
</div>
