<x-admin-layout>
    <div class="p-6 space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-900">All Orders</h2>
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
                                Pickup Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($allOrders as $order)
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
                                    @if ($order->status === 'ready-to-pick-up')
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Ready to Pick Up
                                        </span>
                                    @elseif ($order->status === 'complete')
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Complete
                                        </span>
                                    @else
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    ₱{{ number_format($order->total_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $order->reservation->pick_up_date ? $order->reservation->pick_up_date->format('M d, Y') : 'No date available' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    <button type="button"
                                        class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2"
                                        onclick="showReservationDetails({{ $order->id }})">
                                        View Details
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7"
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    No orders found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200">
                {{ $allOrders->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="reservation-modal"
        class="hidden fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex justify-center items-center p-4">
        <div class="bg-white rounded-lg shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="bg-gradient-to-r from-yellow-400 to-green-500 p-6 rounded-t-lg">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-white" id="reservation-title">
                        Reservation Details
                    </h2>
                    <button onclick="closeModal()"
                        class="text-white hover:bg-white/25 rounded-full p-2 transition-all duration-200 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div id="reservation-content" class="p-6 space-y-6">
                <!-- Reservation details will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        function formatDate(dateString) {
            const options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            return new Date(dateString).toLocaleDateString('en-US', options);
        }

        function showReservationDetails(orderId) {
            fetch(`/orders/${orderId}/reservation`)
                .then(response => response.json())
                .then(data => {
                    const reservation = data.reservation;
                    const orderDetails = data.order_details;

                    document.getElementById('reservation-title').textContent =
                        `Reservation Details for Order ${reservation.transaction_key}`;

                    let productsHtml = orderDetails.map(detail => `
                        <div class="flex items-center space-x-4 pb-4 border-b border-gray-200 last:border-b-0">
                            ${detail.product_image ?
                                `<img src="${detail.product_image}" alt="${detail.product_name}"
                                                         class="w-20 h-20 object-cover rounded-md border border-gray-200">` :
                                '<div class="w-20 h-20 bg-gray-200 rounded-md"></div>'}
                            <div class="flex-grow">
                                <p class="font-semibold text-gray-900">${detail.product_name}</p>
                                <div class="text-sm text-gray-600 mt-1">
                                    <span>₱${detail.product_price} x ${detail.quantity}</span>
                                    <span class="ml-2 font-bold text-gray-800">₱${detail.subtotal}</span>
                                </div>
                            </div>
                        </div>
                    `).join('');

                    const formattedPickUpDate = reservation.pick_up_date ?
                        formatDate(reservation.pick_up_date) :
                        'No date available';

                    const formattedCreatedAt = reservation.created_at ?
                        formatDate(reservation.created_at) :
                        'No creation date available';

                    let statusBadge = '';
                    switch (reservation.status) {
                        case 'ready-to-pick-up':
                            statusBadge = 'bg-blue-100 text-blue-800';
                            break;
                        case 'complete':
                            statusBadge = 'bg-green-100 text-green-800';
                            break;
                        default:
                            statusBadge = 'bg-gray-100 text-gray-800';
                    }

                    document.getElementById('reservation-content').innerHTML = `
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3 border-b pb-2">Customer Information</h3>
                                <div class="space-y-2">
                                    <p><span class="font-medium text-gray-600">Name:</span> ${reservation.name}</p>
                                    <p><span class="font-medium text-gray-600">Email:</span> ${reservation.email}</p>
                                    <p><span class="font-medium text-gray-600">Contact:</span> ${reservation.contact_number}</p>
                                </div>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3 border-b pb-2">Reservation Details</h3>
                                <div class="space-y-2">
                                    <p><span class="font-medium text-gray-600">Pick-Up Date:</span> ${formattedPickUpDate}</p>
                                    <p><span class="font-medium text-gray-600">Created At:</span> ${formattedCreatedAt}</p>
                                    <p>
                                        <span class="font-medium text-gray-600">Status:</span>
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold ${statusBadge}">
                                            ${reservation.status.replace(/-/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3 border-b pb-2">Order Items</h3>
                            <div class="space-y-3">
                                ${productsHtml}
                            </div>
                        </div>
                    `;
                    document.getElementById('reservation-modal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error fetching reservation details:', error);
                    alert('Unable to fetch reservation details.');
                });
        }

        function closeModal() {
            document.getElementById('reservation-modal').classList.add('hidden');
        }
    </script>
</x-admin-layout>
