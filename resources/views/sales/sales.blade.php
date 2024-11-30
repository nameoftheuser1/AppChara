<x-admin-layout>
    <div class="space-y-6 p-6">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-900">Sales History</h2>

            <!-- Month Filter -->
            <form action="{{ route('sales.list') }}" method="GET" class="flex items-center space-x-4">
                <select name="month"
                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    @foreach (range(1, 12) as $month)
                        <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                    Filter
                </button>
            </form>
        </div>

        <!-- Sales Table -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sale
                            ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total
                            Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($sales as $sale)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #{{ $sale->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $sale->sale_date->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                ₱{{ $sale->formatted_total_amount }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $sale->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($sale->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <button onclick="showSaleDetails({{ $sale->id }})"
                                    class="text-indigo-600 hover:text-indigo-900">
                                    View Details
                                </button>
                                <a href="{{ route('pos.receipt', ['sale_id' => $sale->id]) }}"
                                    class="text-green-600 hover:text-green-900 ml-4">
                                    View Receipt
                                </a>
                                <form action="{{ route('sales.refund', ['sale_id' => $sale->id]) }}" method="POST"
                                    class="inline-block">
                                    @csrf
                                    @method('POST')
                                    <button type="submit"
                                        class="text-red-600 hover:text-red-900 ml-4">
                                        Refund
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Sale Details Modal -->
        <div id="saleDetailsModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-lg bg-white">
                <div class="flex flex-col space-y-4">
                    <!-- Modal Header -->
                    <div class="flex justify-between items-center border-b pb-3">
                        <h3 class="text-xl font-semibold text-gray-900">Sale Details</h3>
                        <button type="button" id="closeModalButton" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Content -->
                    <div id="saleDetailsContent"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showSaleDetails(saleId) {
            // Fetch sale details via AJAX
            fetch(`/sale/sales/${saleId}`)
                .then(response => response.json())
                .then(data => {
                    // Populate modal with sale details
                    const content = document.getElementById('saleDetailsContent');
                    content.innerHTML = `
                        <p><strong>Sale ID:</strong> #${data.id}</p>
                        <p><strong>Date:</strong> ${new Date(data.sale_date).toLocaleString()}</p>
                        <p><strong>Total Amount:</strong> ₱${data.formatted_total_amount}</p>
                        <p><strong>Status:</strong> ${data.status.charAt(0).toUpperCase() + data.status.slice(1)}</p>
                        <h4>Sale Details:</h4>
                        <ul>
                            ${data.sale_details.map(detail => `
                                                                    <li>${detail.product.name} (₱${detail.product_price}) - Quantity: ${detail.quantity}</li>
                                                                `).join('')}
                        </ul>
                    `;
                    // Show modal
                    document.getElementById('saleDetailsModal').classList.remove('hidden');
                });
        }

        // Close modal functionality
        document.getElementById('closeModalButton').addEventListener('click', () => {
            document.getElementById('saleDetailsModal').classList.add('hidden');
        });
    </script>
</x-admin-layout>
