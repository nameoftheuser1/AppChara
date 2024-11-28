<x-admin-layout>
    <div class="container min-h-screen">
        <h1 class="text-2xl font-bold mb-6">Dashboard Overview</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Point of Sale Card -->
            <div class="bg-white rounded-lg shadow-md p-6 cursor-pointer hover:shadow-lg transition-all duration-300"
                onclick="window.location='{{ route('pos.index') }}'">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800">Point of Sale</h3>
                        <p class="text-gray-500">Process Sales Quickly</p>
                    </div>
                    <div class="bg-yellow-100 text-yellow-600 p-3 rounded-full">
                        <svg class="w-6 h-6" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path
                                d="M17.876.517A1 1 0 0 0 17 0H3a1 1 0 0 0-.876.517A1 1 0 0 0 2 1.5V6h16V1.5a1 1 0 0 0-.124-.983Z" />
                            <path
                                d="M2 7v11.5a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V7H2Zm6 8H4v-2h4v2Zm0-4H4v-2h4v2Zm6 4h-4v-2h4v2Zm0-4h-4v-2h4v2Z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Reservations Card -->
            <div class="bg-white rounded-lg shadow-md p-6 cursor-pointer hover:shadow-lg transition-all duration-300"
                onclick="window.location='{{ route('reservations.all') }}'">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800">Reservations</h3>
                        <p class="text-gray-500">Manage Purchases</p>
                    </div>
                    <div class="bg-green-100 text-green-600 p-3 rounded-full">
                        <svg class="w-6 h-6" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path
                                d="M11.074 4 8.442.408A.5.5 0 0 0 8 .188a.5.5 0 0 0-.442.22L4.926 4H1.5A1.5 1.5 0 0 0 0 5.5v9A1.5 1.5 0 0 0 1.5 16h17a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 18.5 4h-7.426ZM8 13.5a3.5 3.5 0 1 1 0-7 3.5 3.5 0 0 1 0 7Z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Sales List Card -->
            <div class="bg-white rounded-lg shadow-md p-6 cursor-pointer hover:shadow-lg transition-all duration-300"
                onclick="window.location='{{ route('sales.list') }}'">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800">Sales List</h3>
                        <p class="text-gray-500">View Sales History</p>
                    </div>
                    <div class="bg-blue-100 text-blue-600 p-3 rounded-full">
                        <svg class="w-6 h-6" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path
                                d="M17.876.517A1 1 0 0 0 17 0H3a1 1 0 0 0-.876.517A1 1 0 0 0 2 1.5V6h16V1.5a1 1 0 0 0-.124-.983Z" />
                            <path
                                d="M2 7v11.5a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V7H2Zm6 8H4v-2h4v2Zm0-4H4v-2h4v2Zm6 4h-4v-2h4v2Zm0-4h-4v-2h4v2Z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Existing dashboard content -->
        <div class="max-h-56 h-2">
            <div class="bg-white rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">5-Month Combined Sales & Orders</h3>
                <div class="space-y-4">
                    @foreach ($historicalData as $monthData)
                        <div class="flex justify-between items-center border-b py-2">
                            <span
                                class="text-gray-600">{{ \Carbon\Carbon::createFromFormat('Y-m', "{$monthData->year}-{$monthData->month}")->format('F Y') }}</span>
                            <div class="flex items-center">
                                <span class="mr-4 text-gray-500">Total Amount: </span>
                                <span
                                    class="font-semibold text-gray-800">{{ number_format($monthData->total_amount, 2) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Existing charts -->
            <div id="chart" class="mt-5 bg-white rounded-lg p-6"></div>
            <div id="fastMovingProductsChart" class="mt-5 bg-white rounded-lg p-6"></div>
        </div>

        <!-- Include existing scripts -->
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

        @php
            // Ensure $categories is not empty and calculate the index safely
            $predictionStartIndex = max(0, count($categories) - 3);
            $predictionCategory = !empty($categories) ? $categories[$predictionStartIndex] : null;
        @endphp

        <script>
            var options = {
                chart: {
                    type: 'line',
                    height: 350
                },
                series: [{
                    name: 'Sales',
                    data: @json($data).map(function(value) {
                        return value.toFixed(2);
                    }) // Round data to 2 decimals
                }],
                xaxis: {
                    categories: @json($categories)
                },
                annotations: {
                    xaxis: [{
                        x: @json($predictionCategory),
                        strokeDashArray: 0,
                        borderColor: '#775DD0',
                        label: {
                            borderColor: '#775DD0',
                            style: {
                                color: '#fff',
                                background: '#775DD0'
                            },
                            text: 'Prediction Start'
                        }
                    }]
                },
                stroke: {
                    curve: 'smooth',
                    dashArray: [0, 5] // Solid line for actual data, dashed for prediction
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return parseFloat(val).toFixed(2); // Round tooltip to 2 decimals
                        }
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();

            var fastMovingProductsOptions = {
                chart: {
                    type: 'bar',
                    height: 350
                },
                series: [{
                    name: 'Total Quantity',
                    data: @json($fastMovingProducts->pluck('total_quantity')->toArray()).map(function(value) {
                        return value.toFixed(2);
                    })
                }],
                xaxis: {
                    categories: @json($fastMovingProducts->pluck('name')->toArray()),
                    labels: {
                        rotate: -45,
                        rotateAlways: true,
                        style: {
                            fontSize: '12px'
                        }
                    }
                },
                title: {
                    text: 'Top 10 Fast Moving Products (Last 3 Months)',
                    align: 'left',
                    style: {
                        fontSize: '16px',
                        fontWeight: 'bold'
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded'
                    }
                },
                dataLabels: {
                    enabled: false
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return parseFloat(val).toFixed(2);
                        }
                    }
                },
                colors: ['#4CAF50']
            };

            var fastMovingProductsChart = new ApexCharts(
                document.querySelector("#fastMovingProductsChart"),
                fastMovingProductsOptions
            );
            fastMovingProductsChart.render();
        </script>
    </div>
</x-admin-layout>
