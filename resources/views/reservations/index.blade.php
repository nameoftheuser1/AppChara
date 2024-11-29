<x-admin-layout>
    <div class="p-6 space-y-6">
        <h2 class="text-2xl font-bold text-gray-900">Purchases</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6">
            <!-- Pending Reservations -->
            <a href="{{ route('reservations.pending') }}" class="transform transition-all hover:scale-105 duration-200">
                <div class="p-6 bg-yellow-50 rounded-lg shadow-sm border border-yellow-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-yellow-100 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-yellow-700" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-yellow-600">Pending</p>
                                <p class="text-2xl font-bold text-yellow-900">{{ $counts['pending'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Processing Reservations -->
            <a href="{{ route('reservations.processing') }}"
                class="transform transition-all hover:scale-105 duration-200">
                <div class="p-6 bg-blue-50 rounded-lg shadow-sm border border-blue-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-blue-100 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-700" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-blue-600">Processing</p>
                                <p class="text-2xl font-bold text-blue-900">{{ $counts['processing'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Ready to Pickup -->
            <a href="{{ route('reservations.ready-to-pickup') }}"
                class="transform transition-all hover:scale-105 duration-200">
                <div class="p-6 bg-purple-50 rounded-lg shadow-sm border border-purple-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-purple-100 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-purple-700" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-purple-600">Ready to Pickup</p>
                                <p class="text-2xl font-bold text-purple-900">{{ $counts['ready_to_pickup'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Completed Reservations -->
            <a href="{{ route('reservations.complete') }}"
                class="transform transition-all hover:scale-105 duration-200">
                <div class="p-6 bg-green-50 rounded-lg shadow-sm border border-green-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-green-100 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-700" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-green-600">Completed</p>
                                <p class="text-2xl font-bold text-green-900">{{ $counts['completed'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>

            <a href="{{ route('reservations.cancel') }}" class="transform transition-all hover:scale-105 duration-200">
                <div class="p-6 bg-red-50 rounded-lg shadow-sm border border-red-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-red-100 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-700" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-red-600">Cancelled</p>
                                <p class="text-2xl font-bold text-red-900">{{ $counts['cancelled'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>

            <a href="{{ route('reservations.refunded') }}"
                class="transform transition-all hover:scale-105 duration-200">
                <div class="p-6 bg-indigo-50 rounded-lg shadow-sm border border-indigo-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-indigo-100 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-indigo-700" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-indigo-600">Refunded</p>
                                <p class="text-2xl font-bold text-indigo-900">{{ $counts['refunded'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>

            <a href="{{ route('reservations.all') }}" class="transform transition-all hover:scale-105 duration-200">
                <div class="p-6 bg-gray-50 rounded-lg shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-gray-100 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">All Reservations</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $counts['total'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        @if ($recentOrders->count() > 0)
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Updates</h3>
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Transaction</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Amount</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Updated</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($recentOrders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $order->transaction_key }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if ($order->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                            @elseif($order->status === 'ready to pickup') bg-purple-100 text-purple-800
                                            @elseif($order->status === 'completed') bg-green-100 text-green-800
                                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                            @elseif($order->status === 'refunded') bg-indigo-100 text-indigo-800 @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        ₱{{ number_format($order->total_amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $order->updated_at->diffForHumans() }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</x-admin-layout>
