<x-admin-layout>
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <!-- Receipt Header -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Sales Receipt</h1>
            <p class="text-gray-600">{{ config('app_name', 'AppChara') }}</p>
            <p class="text-sm text-gray-500">{{ now()->format('F d, Y h:i A') }}</p>
        </div>

        <!-- Transaction Details -->
        <div class="mb-6">
            <div class="flex justify-between text-sm text-gray-600 mb-2">
                <span>Transaction #:</span>
                <span>{{ $sale->id }}</span>
            </div>
        </div>

        <!-- Items List -->
        <div class="border-t border-b border-gray-200 py-4 mb-6">
            <table class="w-full">
                <thead>
                    <tr class="text-sm text-gray-600">
                        <th class="text-left py-2">Item</th>
                        <th class="text-center">Qty</th>
                        <th class="text-right">Price</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sale->saleDetails as $item)
                        <tr class="text-sm">
                            <td class="py-2">{{ $item->product->name }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-right">₱{{ number_format($item->product->price, 2) }}</td>
                            <td class="text-right">₱{{ number_format($item->product->price * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="space-y-2 mb-6">
            <div class="flex justify-between text-sm">
                <span>Subtotal:</span>
                <span>₱{{ number_format($sale->subtotal, 2) }}</span>
            </div>
            @if ($sale->discount > 0)
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Discount ({{ $sale->discount }}%):</span>
                    <span>-₱{{ number_format($sale->subtotal * ($sale->discount / 100), 2) }}</span>
                </div>
            @endif
            <div class="flex justify-between font-bold border-t border-gray-200 pt-2">
                <span>Total:</span>
                <span>₱{{ number_format($sale->total_amount, 2) }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span>Amount Received:</span>
                <span>₱{{ number_format($sale->amount_received, 2) }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span>Change:</span>
                <span>₱{{ number_format($sale->amount_received - $sale->total_amount, 2) }}</span>
            </div>
        </div>

        <!-- Thank You Message -->
        <div class="text-center space-y-2">
            <p class="text-gray-800 font-medium">Thank you for your purchase!</p>
            <p class="text-sm text-gray-600">Please come again</p>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex justify-center space-x-4">
            <button onclick="window.print()"
                class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                Print Receipt
            </button>
            <a href="{{ route('pos.index') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                Back to POS
            </a>
        </div>
    </div>

    <!-- Print Styles -->
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            .max-w-2xl,
            .max-w-2xl * {
                visibility: visible;
            }

            .max-w-2xl {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            button,
            a {
                display: none;
            }

            table,
            th,
            td {
                border: 1px solid #ddd;
                border-collapse: collapse;
            }

            th,
            td {
                padding: 8px 12px;
                text-align: left;
            }

            th {
                background-color: #f9f9f9;
            }

            .text-center {
                text-align: center;
            }
        }
    </style>
</x-admin-layout>
