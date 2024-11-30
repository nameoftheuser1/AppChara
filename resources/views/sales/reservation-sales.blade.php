<x-admin-layout>
    <div class="bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Sales and Reservations</h1>

        {{-- Filters --}}
        <form method="GET" class="mb-6 flex items-center space-x-4">
            <select name="month" class="form-select rounded-lg border-gray-300">
                <option value="">All Months</option>
                @foreach (range(1, 12) as $monthNum)
                    <option value="{{ $monthNum }}" {{ request('month') == $monthNum ? 'selected' : '' }}>
                        {{ date('F', mktime(0, 0, 0, $monthNum, 1)) }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                Apply Filter
            </button>
        </form>

        {{-- Combined Table --}}
        <div class="overflow-x-auto">
            <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left">Type</th>
                        <th class="px-4 py-3 text-left">Total Amount</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-blue-600">Reservation</td>
                            <td class="px-4 py-3">{{ number_format($order->total_amount, 2) }}</td>
                            <td class="px-4 py-3">
                                <span
                                    class="
                                    @if ($order->status == 'completed') text-green-600
                                    @elseif($order->status == 'pending') text-yellow-600
                                    @else text-red-600 @endif
                                ">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ $order->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-gray-500">No reservations found</td>
                        </tr>
                    @endforelse

                    @forelse($sales as $sale)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-green-600">Sale</td>
                            <td class="px-4 py-3">{{ number_format($sale->total_amount, 2) }}</td>
                            <td class="px-4 py-3">
                                <span
                                    class="
                                    @if ($sale->status == 'completed') text-green-600
                                    @elseif($sale->status == 'pending') text-yellow-600
                                    @else text-red-600 @endif
                                ">
                                    {{ ucfirst($sale->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ $sale->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-gray-500">No sales found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4 flex justify-between">
            <x-pagination :paginator="$orders" />
            <x-pagination :paginator="$sales" />
        </div>

    </div>
</x-admin-layout>
