<x-admin-layout>
    <div class="container-fluid">
        <div class="space-y-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Inventory Dashboard</h1>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Total Stock Value Card --}}
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-yellow-400 to-green-500 p-4 flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="bg-white/25 p-3 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-white font-semibold text-lg">Total Stock Value</h3>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-white">{{ number_format($totalStockValue, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Stock vs Sales Comparison</h5>
                        </div>
                        <div class="card-body">
                            <div id="chart"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Product Stocks Table --}}
            <div class="bg-white rounded-lg overflow-hidden">
                <div class="p-4 bg-gray-50 border-b">
                    <h3 class="text-xl font-semibold text-gray-800">Product Stocks</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100">
                            <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <th class="px-6 py-3">Product Name</th>
                                <th class="px-6 py-3">Stock Quantity</th>
                                <th class="px-6 py-3">Stock Value</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($stocks as $stock)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $stock['product_name'] ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($stock['stock_quantity'] ?? 0, 0) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($stock['stock_value'] ?? 0, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                        No stock information available
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <link href="https://cdn.jsdelivr.net/npm/apexcharts@3.41.1/dist/apexcharts.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.41.1/dist/apexcharts.min.js"></script>
    <script>
        // Assuming 'products' is passed to the view as a PHP array
        const products = @json($products);

        const stockValues = products.map(product => product.stock_value);
        const salesValues = products.map(product => product.total_sales);
        const productNames = products.map(product => product.product_name);

        var options = {
            series: [{
                    name: 'Stock Value',
                    group: 'budget',
                    data: stockValues
                },
                {
                    name: 'Total Sales',
                    group: 'actual',
                    data: salesValues
                }
            ],
            chart: {
                type: 'bar',
                height: 350,
                stacked: true,
            },
            stroke: {
                width: 1,
                colors: ['#fff']
            },
            dataLabels: {
                formatter: (val) => {
                    return '₱' + val
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false
                }
            },
            xaxis: {
                categories: productNames
            },
            fill: {
                opacity: 1
            },
            colors: ['#80c7fd', '#008FFB'],
            yaxis: {
                labels: {
                    formatter: (val) => {
                        return '₱' + val
                    }
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'left'
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    </script>

</x-admin-layout>
