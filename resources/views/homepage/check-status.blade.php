<x-layout>
    <div class="col-span-8 mx-auto container">
        <div class=" p-2 text-blue-500">
            <a href="{{ route('reservation-form.form') }}"><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
        </div>
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Check Reservation Status</h1>
            <p class="text-gray-600 mt-2">Enter your transaction key to check your reservation status</p>
        </div>

        @if (session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if (!isset($order))
            {{-- Search Form --}}
            <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-100">
                <form action="{{ route('check.status') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label for="transaction_key" class="block text-sm font-medium text-gray-700">Transaction
                            Key</label>
                        <input type="text" name="transaction_key" id="transaction_key"
                            value="{{ session('transaction_key') }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                    <div class="text-right">
                        <button type="submit"
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            Check Status
                        </button>
                    </div>
                </form>
            </div>
        @else
            {{-- Order Status Display --}}
            <div id="printable-content" class="bg-white rounded-lg shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800">Order #{{ $order->transaction_key }}</h2>
                            <p class="text-gray-600 mt-1">Placed on {{ $order->created_at->format('F d, Y h:i A') }}</p>
                        </div>
                        <div
                            class="@if ($order->status === 'confirmed') bg-green-100 text-green-800
                                  @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                  @elseif($order->status === 'completed') bg-blue-100 text-blue-800
                                  @else bg-yellow-100 text-yellow-800 @endif
                                  px-4 py-2 rounded-full text-sm font-semibold uppercase">
                            {{ $order->status }}
                        </div>
                    </div>

                    {{-- Order Details --}}
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Order Details</h3>
                        <div class="space-y-4">
                            @foreach ($order->orderDetails as $detail)
                                <div class="flex items-center justify-between py-4 border-b border-gray-100">
                                    <div class="flex items-center space-x-4">
                                        @if ($detail->product->img_path)
                                            <img src="{{ asset($detail->product->img_path) }}"
                                                alt="{{ $detail->product->name }}"
                                                class="w-16 h-16 object-cover rounded-lg">
                                        @else
                                            <div
                                                class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                        <div>
                                            <h4 class="text-gray-800 font-medium">{{ $detail->product->name }}</h4>
                                            <p class="text-gray-600">Quantity: {{ $detail->quantity }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-gray-800 font-semibold">
                                            ₱{{ number_format($detail->amount, 2) }}</p>
                                        <p class="text-sm text-gray-600">
                                            ₱{{ number_format($detail->product->price, 2) }} each
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Order Summary --}}
                    <div class="mt-6 border-t border-gray-200 pt-6">
                        <div class="flex justify-between text-lg font-bold text-gray-800">
                            <span>Total Amount</span>
                            <span>₱{{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>

                    @if ($order->reservation)
                        {{-- Reservation Details --}}
                        <div class="mt-6 border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Reservation Information</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-gray-600">Pick-up Date</p>
                                    <p class="font-medium">{{ $order->reservation->pick_up_date->format('F d, Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="mt-6 flex items-center justify-between">
                <a href="{{ route('check.status.form') }}"
                    class="inline-flex items-center text-gray-600 hover:text-gray-800">
                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Check Another Order
                </a>


                @if ($order->status !== 'cancelled')
                    <button onclick="printOrder()"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Print Order
                    </button>
                    <button type="button" onclick="openModal()"
                        class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        Cancel Order
                    </button>
                @endif
            </div>

            <div id="cancelModal"
                class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
                <div class="bg-white rounded-lg shadow-lg max-w-sm w-full p-6">
                    <h2 class="text-lg font-semibold text-gray-800">Cancel Order</h2>
                    <p class="text-gray-600 mt-2">Are you sure you want to cancel this order? This action cannot be
                        undone.</p>
                    <div class="mt-6 flex justify-end space-x-4">
                        <button type="button" onclick="closeModal()"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">
                            Close
                        </button>
                        <form action="{{ route('order.cancel', $order->transaction_key) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                                onclick="showLoadingOverlay()">
                                Confirm
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @include('partials.loading-script')

    <script>
        function openModal() {
            document.getElementById('cancelModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('cancelModal').classList.add('hidden');
        }
    </script>

    {{-- Add this script section at the bottom of your layout or in a separate JS file --}}
    <script>
        function printOrder() {
            window.print();
        }
    </script>


    {{-- Add print-specific styles --}}
    <style media="print">
        @page {
            margin: 1cm;
        }

        body * {
            visibility: hidden;
        }

        #printable-content,
        #printable-content * {
            visibility: visible;
        }

        #printable-content {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }

        .no-print {
            display: none !important;
        }
    </style>
</x-layout>
