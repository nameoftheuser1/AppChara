<x-admin-layout>
    <div class="container min-gh-w">
        <h1>Dashboard</h1>

        <div class="max-h-56 h-2">
            <div id="chart"></div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

        <script>
            // Data passed from the controller
            const salesData = @json($salesData);
            const predictions = @json($predictions);

            // Prepare sales data for the chart
            const months = salesData.map(data => `${data.month}-${data.year}`);
            const totalSales = salesData.map(data => data.total_amount);

            // Create a chart with sales data and predictions
            const options = {
                chart: {
                    type: 'line',
                    height: 350
                },
                series: [{
                        name: 'Actual Sales',
                        data: totalSales
                    },
                    {
                        name: 'Predicted Sales',
                        data: predictions // Predictions for next 3 months
                    }
                ],
                xaxis: {
                    categories: [...months, 'Prediction 1', 'Prediction 2', 'Prediction 3'] // Append prediction months
                },
                title: {
                    text: 'Sales Data and Predictions',
                    align: 'center'
                },
                markers: {
                    size: 5
                }
            };

            const chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        </script>
    </div>
</x-admin-layout>
