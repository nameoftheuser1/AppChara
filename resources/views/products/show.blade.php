<x-admin-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="grid md:grid-cols-2 gap-6">
            <!-- Product Image and Basic Info -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex flex-col items-center">
                    @if ($product->img_path)
                        <img src="{{ asset($product->img_path) }}" alt="{{ $product->name }}"
                            class="w-64 h-64 object-cover rounded-lg mb-4">
                    @else
                        <div class="w-64 h-64 bg-gray-200 flex items-center justify-center rounded-lg mb-4">
                            <span class="text-gray-500">No Image</span>
                        </div>
                    @endif

                    <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $product->name }}</h1>
                    <p class="text-xl text-green-600 font-semibold">₱{{ $product->formatted_price }}</p>
                </div>
            </div>

            <!-- Inventory and Sales Information -->
            <div class="space-y-4">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-700">Inventory Details</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Current Stock</p>
                            <p class="font-bold text-lg">
                                {{ $product->inventory->quantity ?? 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Last Restocked</p>
                            <p class="font-bold text-lg">
                                {{ $product->inventory->updated_at ? $product->inventory->updated_at->format('M d, Y') : 'Never' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-700">Sales Performance</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Total Sales</p>
                            <p class="font-bold text-lg">{{ $salesCount }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Sales Amount</p>
                            <p class="font-bold text-lg">
                                ₱{{ number_format($totalSalesAmount, 2) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Sales and Order History -->
        <div class="mt-8 grid md:grid-cols-2 gap-6">
            <!-- Sales History -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-700">Recent Sales History</h2>
                @if ($saleDetails->count() > 0)
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-xs uppercase">
                            <tr>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3">Quantity</th>
                                <th class="px-4 py-3">Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($saleDetails as $saleDetail)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        {{ $saleDetail->sale->sale_date->format('M d, Y') }}
                                    </td>
                                    <td class="px-4 py-3">{{ $saleDetail->quantity }}</td>
                                    <td class="px-4 py-3">₱{{ $saleDetail->formatted_amount }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $saleDetails->links() }} <!-- Render pagination links -->
                    </div>
                @else
                    <p class="text-gray-500">No sales history found.</p>
                @endif
            </div>

            <!-- Order History -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-700">Recent Order History</h2>
                @if ($orderDetails->count() > 0)
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-xs uppercase">
                            <tr>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3">Quantity</th>
                                <th class="px-4 py-3">Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orderDetails as $orderDetail)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        {{ $orderDetail->order->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-4 py-3">{{ $orderDetail->quantity }}</td>
                                    <td class="px-4 py-3">₱{{ number_format($orderDetail->amount, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $orderDetails->links() }} <!-- Render pagination links -->
                    </div>
                @else
                    <p class="text-gray-500">No order history found.</p>
                @endif
            </div>

            <!-- Action Buttons -->

        </div>
        <div class="mt-6 flex justify-end space-x-4">
            <a href="{{ route('products.edit', $product) }}"
                class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition">
                Edit Product
            </a>
            <a href="{{ route('products.index') }}"
                class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                Back to Products
            </a>
        </div>
</x-admin-layout>
