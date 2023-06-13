<x-app-layout>
    <x-breadcrumb name="dashboard" />
    <div class="xl:grid grid-cols-2 gap-x-4">
        <x-card-container height="" style="height: 220px">
            <p class="text-xs 2xl:text-sm font-semibold">
                Statistik Sampel
            </p>
            <canvas id="samplePerYear"></canvas>
        </x-card-container>
        <div class="xl:grid grid-cols-2 gap-4 md:flex md:flex-wrap">
            <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow flex items-center mb-4 md:mb-0">
                <i class="fas fa-users fa-2x text-primary mr-4 "></i>
                <div>
                    <a href="#">
                        <h5 class="text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                            {{ number_format($usersCount, 0, ',', '.') }}
                        </h5>
                    </a>
                    <p class="font-normal text-xs 2xl:text-sm text-gray-500">Pengguna</p>
                </div>
            </div>
            <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow flex items-center mb-4 md:mb-0">
                <i class="fas fa-chart-simple fa-2x text-success mr-4"></i>
                <div>
                    <a href="#">
                        <h5 class="text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                            {{number_format($totalSample, 0, ',', '.')}}
                        </h5>
                    </a>
                    <p class="font-normal text-xs 2xl:text-sm text-gray-500">Sampel Nyamuk</p>
                </div>
            </div>
            <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow flex items-center mb-4 md:mb-0">
                <i class="fas fa-mosquito fa-2x text-error mr-4"></i>
                <div>
                    <a href="#">
                        <h5 class="text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                            {{number_format($totalMosquito, 0, ',', '.')}}
                        </h5>
                    </a>
                    <p class="font-normal text-xs 2xl:text-sm text-gray-500">Total Nyamuk</p>
                </div>
            </div>
            <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow flex items-center mb-4 md:mb-0">
                <i class="fas fa-worm fa-2x text-warning mr-4"></i>
                <div>
                    <a href="#">
                        <h5 class="mb-1 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                            {{number_format($totalLarva, 0, ',', '.')}}
                        </h5>
                    </a>
                    <p class="font-normal text-xs 2xl:text-sm text-gray-500">Total Larva</p>
                </div>
            </div>
        </div>
    </div>

    @push('js-internal')
        <script>
            $(function() {
                let samplePerYear = @json($samplePerYear);

                // Mengambil bulan dan jumlah dari setiap entri data
                var labels = samplePerYear.map(entry => entry.month);
                var counts = samplePerYear.map(entry => entry.count);

                // Mengambil jenis nyamuk dari setiap entri samplePerYear
                var mosquitoTypes = samplePerYear[0].type.map(entry => entry.name);

                // Mengambil jumlah nyamuk dari setiap entri samplePerYear
                var mosquitoAmounts = samplePerYear.map(entry => entry.type.map(type => type.amount));

                // Membuat chart dengan Chart.js
                var ctx = document.getElementById('samplePerYear').getContext('2d');
                // width 100%
                ctx.canvas.width = '100%';
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: mosquitoTypes.map((type, index) => ({
                            label: type,
                            data: mosquitoAmounts.map(amounts => amounts[index]),
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                            stack: 'stack'
                        }))
                    },
                    options: {
                        responsive: true,
                        interaction: {
                            mode: 'index',
                            intersect: false
                        },
                        scales: {
                            y: {
                                // stack the bar
                                stacked: true,
                                grid: {
                                    display: false,
                                },
                                ticks: {
                                    beginAtZero: true,
                                    precision: 0,
                                    stepSize: 1,
                                },
                            },
                            x: {
                                // stack the bar
                                stacked: true,
                                grid: {
                                    display: false,
                                },
                                ticks: {
                                    beginAtZero: true,
                                    precision: 0,
                                    stepSize: 1,
                                },
                            },
                        },
                        plugins: {
                            tooltip: {
                                mode: 'index',
                                intersect: false
                            },
                            legend: {
                                labels: {
                                    usePointStyle: true,
                                    boxWidth: 5,
                                    boxHeight: 5,
                                },
                            },
                        },
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
