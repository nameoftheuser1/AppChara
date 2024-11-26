<x-admin-layout>
    <div class="p-6 space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-900">Cancelled Orders</h2>
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
                        @forelse ($cancelledOrders as $order)
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
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Cancelled
                                    </span>
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
                                    No cancelled orders found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200">
                {{ $cancelledOrders->links() }}
            </div>
        </div>
    </div>

    <!-- Modal for Viewing Reservation Details -->
    <div id="reservation-modal"
        class="hidden fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex justify-center items-center">
        <div class="bg-white rounded-lg shadow-2xl p-8 w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900" id="reservation-title">
                    Reservation Details
                </h2>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="reservation-content">
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
                        <div class="flex items-center space-x-4 mb-3 border-b pb-3">
                            ${detail.product_image ?
                                `<img src="${detail.product_image}" alt="${detail.product_name}" class="w-16 h-16 object-cover rounded-md">` :
                                '<div class="w-16 h-16 bg-gray-200 rounded-md"></div>'}
                            <div>
                                <p class="font-semibold">${detail.product_name}</p>
                                <div class="text-sm text-gray-600">
                                    <span>₱${detail.product_price} x ${detail.quantity}</span>
                                    <span class="ml-2 font-bold">₱${detail.subtotal}</span>
                                </div>
                            </div>
                        </div>
                    `).join('');

                    const formattedPickUpDate = reservation.pick_up_date ?
                        formatDate(reservation.pick_up_date) :
                        'No date available';

                    document.getElementById('reservation-content').innerHTML = `
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="font-semibold">Customer Information</p>
                                    <p><strong>Name:</strong> ${reservation.name}</p>
                                    <p><strong>Email:</strong> ${reservation.email}</p>
                                    <p><strong>Contact:</strong> ${reservation.contact_number}</p>
                                </div>
                                <div>
                                    <p class="font-semibold">Reservation Details</p>
                                    <p><strong>Pick-Up Date:</strong> ${formattedPickUpDate}</p>
                                    <p><strong>Status:</strong> ${reservation.status}</p>
                                </div>
                            </div>

                            <div>
                                <p class="font-semibold mb-2">Order Items</p>
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
