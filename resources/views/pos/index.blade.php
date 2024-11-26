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
            @include('pos.partials.product-grid')

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
                        <div class="mb-4">
                            <form action="{{ route('pos.apply-discount') }}" method="POST" class="flex space-x-2">
                                @csrf
                                <input type="number" name="discount" min="0" step="0.01"
                                    class="w-20 rounded border border-gray-300 px-2 py-1" value="{{ $discount ?? 0 }}">
                                <button type="submit" class="px-3 py-1 bg-gray-200 rounded text-sm">
                                    Apply Discount
                                </button>
                            </form>
                        </div>

                        <div class="flex justify-between mb-4">
                            <span class="font-semibold text-gray-700">Total</span>
                            <span class="font-semibold text-gray-800">₱{{ number_format($total, 2) }}</span>
                        </div>

                        {{-- Payment Section --}}
                        <form action="{{ route('pos.checkout') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm text-gray-600 mb-2">Amount Received</label>
                                <input type="number" id="amountReceived" name="amount_received" step="0.01" required
                                    class="w-full rounded border border-gray-300 px-3 py-2" min="{{ $total }}">

                            </div>
                            {{-- Change Display --}}
                            <div class="flex justify-between mb-4">
                                <span class="font-semibold text-gray-700">Change</span>
                                <span id="changeAmount" class="font-semibold text-gray-800">₱0.00</span>
                            </div>

                            <button type="submit"
                                class="w-full bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 transition-colors duration-200 {{ $cart_items->isEmpty() ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ $cart_items->isEmpty() ? 'disabled' : '' }}>
                                Complete Order
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quantity Modal --}}
    @include('pos.partials.quantity-modal')

    @include('pos.partials.toast-container')

    <script>
        let currentStock = 0;

        function showToast(type, message) {
            const toast = $(`#${type}-toast`);
            const messageElement = $(`#${type}-message`);

            messageElement.text(message);
            toast.removeClass('hidden translate-x-full').addClass('translate-x-0');

            // Auto hide after 5 seconds
            setTimeout(() => {
                closeToast(`${type}-toast`);
            }, 5000);
        }

        function closeToast(toastId) {
            const toast = $(`#${toastId}`);
            toast.addClass('translate-x-full');
            setTimeout(() => {
                toast.addClass('hidden');
            }, 300);
        }

        function openQuantityModal(productId, productName, price, stock) {
            currentStock = stock;
            $('#modalProductId').val(productId);
            $('#modalProductName').text(productName);
            $('#modalStock').text(stock);
            $('#quantityInput').val(1);
            $('#quantityModal').removeClass('hidden');
        }

        function closeQuantityModal() {
            $('#quantityModal').addClass('hidden');
        }

        function incrementQuantity() {
            const input = $('#quantityInput');
            const currentValue = parseInt(input.val()) || 0;
            if (currentValue < currentStock) {
                input.val(currentValue + 1);
            } else {
                showToast('error', 'Cannot exceed available stock');
            }
        }

        function decrementQuantity() {
            const input = $('#quantityInput');
            const currentValue = parseInt(input.val()) || 0;
            if (currentValue > 1) {
                input.val(currentValue - 1);
            }
        }

        // Add form validation
        $('#addToCartForm').on('submit', function(e) {
            const quantity = parseInt($('#quantityInput').val());
            if (quantity > currentStock) {
                e.preventDefault();
                showToast('error', 'Quantity cannot exceed available stock');
            }
        });

        // Handle session messages
        @if (session('error'))
            showToast('error', "{{ session('error') }}");
        @endif

        @if (session('change'))
            showToast('success', "Change amount: ₱{{ number_format(session('change'), 2) }}");
        @endif

        // Optional: Add success message for when items are added to cart
        @if (session('success'))
            showToast('success', "{{ session('success') }}");
        @endif

        // Update change when amount received is typed
        document.getElementById('amountReceived').addEventListener('input', function() {
            const amountReceived = parseFloat(this.value) || 0;
            const totalAmount = {{ $total }};
            const change = amountReceived - totalAmount;

            // Update the change amount
            document.getElementById('changeAmount').textContent = `₱${change >= 0 ? change.toFixed(2) : '0.00'}`;
        });
    </script>

</x-admin-layout>
