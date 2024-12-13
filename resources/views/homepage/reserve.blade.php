<x-layout>

    <body class="bg-gradient-to-br from-yellow-400 to-green-500">
        <div class="col-span-8 mx-auto container">
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Product Reservation</h1>
                    <p class="text-gray-600 mt-2">Select products and quantities for your reservation</p>
                </div>
                <div class="rounded-lg bg-green-800 text-white p-2">
                    <a href="{{ route('check.status.form') }}">Check Status of your order here</a>
                </div>
            </div>

            <form action="{{ route('reservation-form.store') }}" method="POST" id="reservationForm">
                @csrf
                {{-- Personal Information Fields --}}
                @include('homepage.partials.personal-fields')

                {{-- Products Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($products as $product)
                        <div
                            class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-100 transition-transform hover:scale-105">
                            {{-- Product Image --}}
                            <div class="relative">
                                @if ($product->img_path)
                                    <img src="{{ asset($product->img_path) }}" alt="{{ $product->name }}"
                                        class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                <span
                                    class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded-full text-sm">
                                    Stock: {{ $product->inventory->quantity }}
                                </span>
                            </div>

                            {{-- Product Details --}}
                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-800">{{ $product->name }}</h3>
                                <div class="mt-4 space-y-4">
                                    <div class="flex justify-between items-center">
                                        <span
                                            class="text-2xl font-bold text-green-600">₱{{ number_format($product->price, 2) }}</span>
                                    </div>

                                    {{-- Quantity Controls --}}
                                    <div class="flex items-center space-x-3">
                                        <button type="button"
                                            class="quantity-btn minus w-8 h-8 flex items-center justify-center rounded-full bg-red-100 text-red-600 hover:bg-red-200 transition-colors"
                                            data-product-id="{{ $product->id }}">
                                            <span class="text-xl">-</span>
                                        </button>

                                        <input type="number" id="quantity-{{ $product->id }}"
                                            name="products[{{ $product->id }}]" value="0" min="0"
                                            data-price="{{ $product->price }}"
                                            class="product-quantity block w-20 text-center rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">

                                        <button type="button"
                                            class="quantity-btn plus w-8 h-8 flex items-center justify-center rounded-full bg-green-100 text-green-600 hover:bg-green-200 transition-colors"
                                            data-product-id="{{ $product->id }}">
                                            <span class="text-xl">+</span>
                                        </button>
                                    </div>

                                    <div class="text-right">
                                        <span class="text-sm text-gray-600">Subtotal: </span>
                                        <span class="product-subtotal text-lg font-semibold text-gray-800">₱0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 bg-white p-8 rounded-lg shadow-lg text-center">
                            <svg class="w-16 h-16 mx-auto text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p class="mt-4 text-xl text-gray-600">No products found</p>
                        </div>
                    @endforelse
                </div>

                {{-- Order Summary --}}
                <div class="mt-8 bg-white p-6 rounded-lg shadow-lg border border-gray-100">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800">Order Summary</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span id="cart-subtotal" class="font-medium">₱0.00</span>
                        </div>
                        <div class="border-t pt-3 mt-3">
                            <div class="flex justify-between text-lg font-bold text-gray-800">
                                <span>Total Amount</span>
                                <span id="cart-total">₱0.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="mt-6 text-right">
                    <button type="submit"
                        class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 flex items-center space-x-2 disabled:opacity-50"
                        id="submit-btn">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Confirm Reservation</span>
                    </button>
                </div>
            </form>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        </div>
    </body>


    <script>
        $(document).ready(function() {
            // Create loading overlay function
            function showLoadingOverlay() {
                // Create a full-screen loading overlay
                const loadingOverlay = $(`
                    <div id="loading-overlay" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                        <div class="bg-white p-6 rounded-lg shadow-xl flex flex-col items-center">
                            <div class="animate-spin rounded-full h-12 w-12 border-t-4 border-green-500 mb-4"></div>
                            <p class="text-gray-800 text-lg">Processing your reservation...</p>
                            <p class="text-gray-600 text-sm mt-2">Please do not close or refresh the page</p>
                        </div>
                    </div>
                `);

                // Append to body
                $('body').append(loadingOverlay);
            }

            // Debounce function to limit the rate at which a function is called
            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }

            // Format number as currency
            function formatCurrency(number) {
                return '₱' + number.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            }

            // Calculate subtotal for a single product
            function calculateProductSubtotal($input) {
                const quantity = parseInt($input.val()) || 0;
                const price = parseFloat($input.data('price'));
                const max = parseInt($input.attr('max'));
                const productId = $input.attr('id').split('-')[1];

                // Validate quantity
                if (quantity > max) {
                    $input.val(max);
                    return calculateProductSubtotal($input);
                } else if (quantity < 0) {
                    $input.val(0);
                    return 0;
                }

                const subtotal = quantity * price;
                // Find the closest subtotal display within the product card
                const $subtotalDisplay = $input.closest('.p-4').find('.product-subtotal');
                $subtotalDisplay.text(formatCurrency(subtotal));

                // Add animation effect
                $subtotalDisplay.addClass('text-green-600');
                setTimeout(() => {
                    $subtotalDisplay.removeClass('text-green-600');
                }, 200);

                return subtotal;
            }

            // Calculate cart totals
            const calculateCartTotals = debounce(function() {
                let subtotal = 0;
                $('.product-quantity').each(function() {
                    subtotal += calculateProductSubtotal($(this));
                });

                // Update cart summary
                $('#cart-subtotal').text(formatCurrency(subtotal))
                    .addClass('text-green-600');
                $('#cart-total').text(formatCurrency(subtotal))
                    .addClass('text-green-600');

                setTimeout(() => {
                    $('#cart-subtotal, #cart-total').removeClass('text-green-600');
                }, 200);

                // Enable/disable submit button based on total
                $('#submit-btn').prop('disabled', subtotal === 0);
            }, 100); // Reduced debounce time for more responsive updates

            // Handle quantity button clicks
            $('.quantity-btn').click(function() {
                const productId = $(this).data('product-id');
                const $input = $(`#quantity-${productId}`);
                const currentVal = parseInt($input.val()) || 0;

                if ($(this).hasClass('plus')) {
                    $input.val(currentVal + 1);
                } else if ($(this).hasClass('minus') && currentVal > 0) {
                    $input.val(currentVal - 1);
                }

                calculateCartTotals();
            });

            // Handle manual quantity input
            $('.product-quantity').on('input', calculateCartTotals);

            // Handle quantity changes on keyboard arrows
            $('.product-quantity').on('keydown', function(e) {
                const max = parseInt($(this).attr('max'));
                const currentVal = parseInt($(this).val()) || 0;

                if (e.key === 'ArrowUp' && currentVal >= max) {
                    e.preventDefault();
                    $(this).val(max);
                }
                if (e.key === 'ArrowDown' && currentVal <= 0) {
                    e.preventDefault();
                    $(this).val(0);
                }
            });

            // Modify the form submission handling
            $('#reservationForm').on('submit', function(e) {
                const total = parseFloat($('#cart-total').text().replace('₱', '').replace(',', ''));
                if (total === 0) {
                    e.preventDefault();
                    alert('Please select at least one product before submitting the reservation.');
                    return false;
                }

                let hasErrors = false;
                $('.product-quantity').each(function() {
                    const quantity = parseInt($(this).val()) || 0;
                    const max = parseInt($(this).attr('max'));
                    if (quantity > max) {
                        hasErrors = true;
                    }
                });

                if (hasErrors) {
                    e.preventDefault();
                    alert('Please correct the quantities before submitting.');
                    return false;
                }

                // Disable submit button to prevent multiple submissions
                $('#submit-btn').prop('disabled', true);

                // Show loading overlay
                showLoadingOverlay();

                // Optional: Add a timeout to handle potential server issues
                setTimeout(function() {
                    if ($('#loading-overlay').length) {
                        $('#loading-overlay').remove();
                        $('#submit-btn').prop('disabled', false);
                        alert(
                            'The reservation process is taking longer than expected. Please try again.'
                        );
                    }
                }, 60000); // 60 seconds timeout
            });


            // Initialize calculations
            calculateCartTotals();
        });
    </script>
</x-layout>
