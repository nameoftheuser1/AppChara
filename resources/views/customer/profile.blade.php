<x-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white shadow-md rounded-lg overflow-hidden">
            <div class="bg-green-800 text-white p-6">
                <div class="flex items-center space-x-4">
                    <div class="bg-white/20 rounded-full w-16 h-16 flex items-center justify-center">
                        <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold">{{ Auth::user()->username }}</h2>
                        <p class="text-sm text-green-100">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <h3 class="text-lg font-semibold text-green-800 mb-4">Reservation History</h3>

                @if ($reservations->isEmpty())
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                        <p class="text-green-800">No reservations found.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-green-800">
                            <thead class="text-xs text-green-700 uppercase bg-green-50">
                                <tr>
                                    <th scope="col" class="px-4 py-3">Transaction Key</th>
                                    <th scope="col" class="px-4 py-3">Pick-up Date</th>
                                    <th scope="col" class="px-4 py-3">Status</th>
                                    <th scope="col" class="px-4 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reservations as $reservation)
                                    <tr class="border-b hover:bg-green-50 transition-colors">
                                        <td class="px-4 py-3">{{ $reservation->transaction_key }}</td>
                                        <td class="px-4 py-3">{{ $reservation->pick_up_date->format('M d, Y') }}</td>
                                        <td class="px-4 py-3">
                                            <span
                                                class="px-2 py-1 rounded-full text-xs
                                            @switch($reservation->order->status)
                                                @case('pending')
                                                    bg-yellow-100 text-yellow-800
                                                    @break
                                                @case('confirmed')
                                                    bg-blue-100 text-blue-800
                                                    @break
                                                @case('cancelled')
                                                    bg-red-100 text-red-800
                                                    @break
                                                @case('completed')
                                                    bg-green-100 text-green-800
                                                    @break
                                                @case('processing')
                                                    bg-orange-100 text-orange-800
                                                    @break
                                                @case('ready to pickup')
                                                    bg-teal-100 text-teal-800
                                                    @break
                                                @case('refunded')
                                                    bg-purple-100 text-purple-800
                                                    @break
                                            @endswitch">
                                                {{ ucfirst($reservation->order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <a href="{{ route('check.status', ['transaction_key' => $reservation->transaction_key]) }}"
                                                class="text-green-600 hover:text-green-800 transition-colors">
                                                View Details
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="p-6 bg-green-50 border-t border-green-200">
                <h3 class="text-lg font-semibold text-green-800 mb-4">Account</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <form action="{{ route('logout') }}" method="POST" id="logout-form">
                        @csrf
                        <button type="submit"
                            class="bg-white border border-green-200 rounded-lg p-4 hover:bg-green-100 transition-colors flex items-center justify-between w-1/2">
                            <span class="text-green-800">Logout</span>
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>
