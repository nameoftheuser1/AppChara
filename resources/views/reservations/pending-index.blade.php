<x-admin-layout>
    <div class="p-6 space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-900">Pending Orders</h2>
            <!-- Back Button -->
            @include('reservations.partials.buttons')
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Transaction
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Customer Name
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Email
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Amount
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Created At
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($pendingOrders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $order->transaction_key }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $order->reservation->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $order->reservation->email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    ₱{{ number_format($order->total_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $order->reservation->pick_up_date ? $order->reservation->pick_up_date->format('M d, Y') : 'No date available' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <form action="{{ route('reservations.cancel.update', $order) }}" method="POST"
                                        class="inline" id="cancel-form-{{ $order->id }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 transition-colors duration-200"
                                            onclick="showLoadingOverlay()">
                                            Cancel Order
                                        </button>
                                    </form>
                                    <form action="{{ route('reservations.process', $order) }}" method="POST"
                                        class="inline" id="process-form-{{ $order->id }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 transition-colors duration-200"
                                            onclick="showLoadingOverlay()">
                                            Process Order
                                        </button>
                                    </form>
                                    <button type="button"
                                        class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2"
                                        onclick="showReservationDetails({{ $order->id }})">
                                        View Details
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6"
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    No pending orders found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200">
                {{ $pendingOrders->links() }}
            </div>
        </div>
    </div>

    @include('reservations.partials.view-details')

    <script>
        function showLoadingOverlay() {
            // Create a loading overlay
            const overlay = document.createElement('div');
            overlay.id = 'loading-overlay';
            overlay.classList.add('fixed', 'inset-0', 'z-50', 'flex', 'items-center', 'justify-center', 'bg-black',
                'bg-opacity-50');

            const loader = document.createElement('div');
            loader.classList.add('bg-white', 'p-6', 'rounded-lg', 'shadow-xl', 'flex', 'flex-col', 'items-center');
            loader.innerHTML = `
                <div class="animate-spin rounded-full h-12 w-12 border-t-4 border-green-500 mb-4"></div>
                <p class="text-gray-800 text-lg">Processing your order...</p>
                <p class="text-gray-600 text-sm mt-2">Please do not close or refresh the page</p>
            `;

            overlay.appendChild(loader);
            document.body.appendChild(overlay);
        }
    </script>
</x-admin-layout>
