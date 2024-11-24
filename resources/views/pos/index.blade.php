{{-- resources/views/pos/index.blade.php --}}
<x-admin-layout>
    <div class="container px-6 mx-auto grid">
        <div class="flex justify-between items-center my-6">
            <h2 class="text-2xl font-semibold text-gray-700">Point of Sale</h2>

            {{-- Search Bar --}}
            <div class="w-1/3">
                <form action="{{ route('pos.index') }}" method="GET">
                    <input type="text" name="search" class="w-full rounded-lg border border-gray-300 px-4 py-2"
                        placeholder="Search products..." value="{{ request('search') }}">
                </form>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-6">
            {{-- Products Grid --}}
            <div class="col-span-8">
                <div class="grid grid-cols-3 gap-4">
                    @forelse ($products as $product)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden cursor-pointer hover:shadow-lg transition-shadow duration-200"
                            onclick="openQuantityModal({{ $product->id }}, '{{ $product->name }}', {{ $product->price }}, {{ $product->inventory_count }})">
                            @if ($product->img_path)
                                <img src="{{ asset($product->img_path) }}" alt="{{ $product->name }}"
                                    class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-500">No image</span>
                                </div>
                            @endif
                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-800">{{ $product->name }}</h3>
                                <div class="mt-2 flex justify-between items-center">
                                    <span class="text-gray-600">₱{{ number_format($product->price, 2) }}</span>
                                    <span class="text-sm text-gray-500">Stock: {{ $product->inventory->quantity }}</span>
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

            {{-- Cart Section --}}
            <div class="col-span-4">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">Current Order</h3>

                    <div class="divide-y divide-gray-200">
                        @forelse($cart_items as $item)
                            <div class="py-4 flex justify-between items-center">
                                <div>
                                    <h4 class="font-medium">{{ $item->product->name }}</h4>
                                    <p class="text-sm text-gray-500">
                                        ₱{{ number_format($item->price, 2) }} x {{ $item->quantity }}
                                    </p>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span
                                        class="font-medium">₱{{ number_format($item->price * $item->quantity, 2) }}</span>
                                    <form action="{{ route('pos.remove-item') }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="py-4 text-gray-500 text-center">No items in cart</p>
                        @endforelse
                    </div>

                    {{-- Cart Summary --}}
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="text-gray-800">₱{{ number_format($subtotal, 2) }}</span>
                        </div>

                        {{-- Discount Section --}}
                        {{-- <div class="mb-4">
                            <form action="{{ route('pos.apply-discount') }}" method="POST" class="flex space-x-2">
                                @csrf
                                <input type="number" name="discount" min="0" max="100" step="0.01"
                                    class="w-20 rounded border border-gray-300 px-2 py-1" value="{{ $discount ?? 0 }}">
                                <button type="submit" class="px-3 py-1 bg-gray-200 rounded text-sm">
                                    Apply Discount
                                </button>
                            </form>
                        </div> --}}

                        <div class="flex justify-between mb-4">
                            <span class="font-semibold text-gray-700">Total</span>
                            <span class="font-semibold text-gray-800">₱{{ number_format($total, 2) }}</span>
                        </div>

                        {{-- Payment Section --}}
                        {{-- <form action="{{ route('pos.checkout') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm text-gray-600 mb-2">Amount Received</label>
                                <input type="number" name="amount_received" step="0.01" required
                                    class="w-full rounded border border-gray-300 px-3 py-2" min="{{ $total }}">
                            </div>
                            <button type="submit"
                                class="w-full bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors duration-200 {{ $cart_items->isEmpty() ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ $cart_items->isEmpty() ? 'disabled' : '' }}>
                                Complete Order
                            </button>
                        </form> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quantity Modal --}}
    <div id="quantityModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Add to Cart</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500" id="modalProductName"></p>
                    <p class="text-sm text-gray-500">Available Stock: <span id="modalStock"></span></p>

                    {{-- <form action="{{ route('pos.add-item') }}" method="POST" id="addToCartForm">
                        @csrf
                        <input type="hidden" name="product_id" id="modalProductId">
                        <div class="mt-4 flex items-center justify-center space-x-3">
                            <button type="button" onclick="decrementQuantity()"
                                class="px-3 py-1 bg-gray-200 rounded-lg">-</button>
                            <input type="number" name="quantity" id="quantityInput" value="1" min="1"
                                class="w-20 text-center border rounded-lg px-2 py-1">
                            <button type="button" onclick="incrementQuantity()"
                                class="px-3 py-1 bg-gray-200 rounded-lg">+</button>
                        </div>

                        <div class="mt-4">
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                Add to Cart
                            </button>
                            <button type="button" onclick="closeQuantityModal()"
                                class="ml-2 px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                                Cancel
                            </button>
                        </div>
                    </form> --}}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let currentStock = 0;

            function openQuantityModal(productId, productName, price, stock) {
                currentStock = stock;
                document.getElementById('modalProductId').value = productId;
                document.getElementById('modalProductName').textContent = productName;
                document.getElementById('modalStock').textContent = stock;
                document.getElementById('quantityInput').value = 1;
                document.getElementById('quantityModal').classList.remove('hidden');
            }

            function closeQuantityModal() {
                document.getElementById('quantityModal').classList.add('hidden');
            }

            function incrementQuantity() {
                const input = document.getElementById('quantityInput');
                const currentValue = parseInt(input.value) || 0;
                if (currentValue < currentStock) {
                    input.value = currentValue + 1;
                }
            }

            function decrementQuantity() {
                const input = document.getElementById('quantityInput');
                const currentValue = parseInt(input.value) || 0;
                if (currentValue > 1) {
                    input.value = currentValue - 1;
                }
            }
        </script>
    @endpush
</x-admin-layout>
