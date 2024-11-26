<x-admin-layout>
    <div class="container min-h-screen">
        <h1>AppChara Dashboard</h1>

        <div class="max-h-56 h-2">
            <div class="bg-white rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">5-Month Combined Sales & Orders</h3>
                <div class="space-y-4">
                    <!-- Iterate through historical data for the last 5 months -->
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
            <div id="chart" class="mt-5 bg-white rounded-lg p-6"></div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

        <script>
            var options = {
                chart: {
                    type: 'line',
                    height: 350
                },
                series: [{
                    name: 'Sales',
                    data: @json($data)
                }],
                xaxis: {
                    categories: @json($categories)
                },
                annotations: {
                    xaxis: [{
                        x: @json($categories[count($categories) - 3]), // Get the start of prediction point
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
                }
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        </script>
    </div>
</x-admin-layout>
