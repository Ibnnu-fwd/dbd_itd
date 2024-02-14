<x-app-layout>
    <x-breadcrumb name="dashboard" />
    <div class="z-0 relative mb-4" style="height: 350px; border-radius: 6px;">
        <!-- Legenda -->
        <div class="absolute bottom-0 right-0 p-2 mr-2 mb-2 bg-white shadow text-sm" style="z-index: 2;">
            <h5 class="mb-2 legend-text ">Legend</h5>
            <ul class="list-unstyled">
                <li>
                    <span class="legend-color legend-green"></span>
                    ABJ Normal
                </li>
                <li>
                    <span class="legend-color legend-red"></span>
                    ABJ Tidak Normal
                </li>
                <!-- Tambahkan elemen li sesuai dengan legenda Anda -->
            </ul>
        </div>
        <!-- Peta -->
        <div id="map" style="height: 100%; position: relative; z-index: 1;"></div>
    </div>

    <style>
        .legend-color {
            width: 20px;
            height: 20px;
            display: inline-block;
            margin-right: 5px;
            border: 1px solid #ccc;
            border-radius: 2px;
        }

        .legend-green {
            background-color: #1cc88a;
        }

        .legend-yellow {
            background-color: #ffff00;
        }

        .legend-red {
            background-color: #e74a3b;
        }
    </style>
    <div class="xl:grid grid-cols-2 gap-x-4">
        @if ($samplePerYear->count() > 0)
            <x-card-container style="height: 301px">
                <p class="text-sm font-semibold">
                    Statistik Sampel
                </p>
                <canvas id="samplePerYear"></canvas>
            </x-card-container>
        @endif
        <div class="xl:grid grid-cols-2 gap-4 md:flex md:flex-wrap">
            <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow flex items-center mb-4 md:mb-0">
                <i class="fas fa-users fa-2x text-primary mr-4 "></i>
                <div>
                    <a href="#">
                        <h5 class="text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                            {{ number_format($usersCount, 0, ',', '.') }}
                        </h5>
                    </a>
                    <p class="font-normal text-sm text-gray-500">Pengguna</p>
                </div>
            </div>
            <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow flex items-center mb-4 md:mb-0">
                <i class="fas fa-chart-simple fa-2x text-success mr-4"></i>
                <div>
                    <a href="#">
                        <h5 class="text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                            {{ number_format($totalSample, 0, ',', '.') }}
                        </h5>
                    </a>
                    <p class="font-normal text-sm text-gray-500">Sampel Nyamuk</p>
                </div>
            </div>
            <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow flex items-center mb-4 md:mb-0">
                <i class="fas fa-mosquito fa-2x text-error mr-4"></i>
                <div>
                    <a href="#">
                        <h5 class="text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                            {{ number_format($totalMosquito, 0, ',', '.') }}
                        </h5>
                    </a>
                    <p class="font-normal text-sm text-gray-500">Total Nyamuk</p>
                </div>
            </div>
            <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow flex items-center mb-4 md:mb-0">
                <i class="fas fa-worm fa-2x text-warning mr-4"></i>
                <div>
                    <a href="#">
                        <h5 class="mb-1 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                            {{ number_format($totalLarva, 0, ',', '.') }}
                        </h5>
                    </a>
                    <p class="font-normal text-sm text-gray-500">Total Larva</p>
                </div>
            </div>
        </div>
    </div>

    <x-card-container class="mt-8" id="sampleAbjCard" style="height: 410px; max-height: 100%; overflow: hidden">
        <div class="md:flex justify-between items-center">
            <p class="text-sm font-semibold">
                Data Sampel dan ABJ (%)
            </p>
            <x-select id="regency" name="regency" label="Kabupaten">
                @foreach ($regencies as $regency)
                    <option value="{{ $regency->id }}">{{ $regency->name }}</option>
                @endforeach
            </x-select>
        </div>
        <canvas id="sampleAndAbj"></canvas>
    </x-card-container>

    @push('js-internal')
        <script src="https://api.mapbox.com/mapbox-gl-js/v2.6.1/mapbox-gl.js"></script>
        <link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet">
        <script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>

        <script>
            function getColor(abj_total) {
                if (abj_total <= 95) {
                    return '#e74a3b'; // ABJ Sedang
                } else {
                    return '#1cc88a'; // ABJ Rendah1cc88a
                }

            }

            const map = L.map('map').setView([-7.2756196, 112.7106256], 11.5);

            var markers = L.markerClusterGroup();

            L.tileLayer(
                'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
                    attribution: '&copy; <a href="https://www.mapbox.com/">Mapbox</a> &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
                    maxZoom: 18,
                    id: 'mapbox/light-v11',
                    tileSize: 512,
                    zoomOffset: -1,
                    accessToken: 'pk.eyJ1IjoiaWJudTIyMDQyMiIsImEiOiJjbGltd3FobmIwYXZqM3Fsd2NndnFhM3N5In0.uC04E9g0UXy6uRTOcNN80w',
                }
            ).addTo(map);

            @if (count($abj) > 0)
                function updateMapData() {
                    // Menggunakan fetch untuk mengambil data GeoJSON dari URL
                    let abj = Object.values(@json($abj));
                    fetch("{{ asset('assets/geojson/surabaya.geojson') }}")
                        .then((response) => response.json())
                        .then((data) => {
                            const geojson = {
                                type: 'FeatureCollection',
                                features: []
                            };

                            data.features.forEach((feature) => {
                                const properties = feature.properties;
                                const kecamatan = properties.KECAMATAN;

                                abj.forEach((abjItem) => {
                                    if (abjItem.district === kecamatan) {
                                        // Sekarang Anda memiliki array koordinat dari fitur yang sesuai
                                        const coordinates = feature.geometry.coordinates;

                                        // Ubah koordinat jika diperlukan
                                        const coordinates2 = coordinates[0];
                                        // console.log(coordinates2);

                                        geojson.features.push({
                                            type: 'Feature',
                                            geometry: {
                                                type: 'Polygon',
                                                coordinates: [coordinates2]
                                            },
                                            properties: {
                                                color: getColor(abjItem.abj_total),
                                                regency: abjItem.regency,
                                                district: properties.KECAMATAN,
                                                village: properties.KELURAHAN,
                                                abj: abjItem.abj_total,
                                                total_sample: abjItem.total_sample,
                                                total_check: abjItem.total_check
                                            }
                                        });
                                    }
                                });
                            });

                            L.geoJSON(geojson, {
                                style: function(feature) {
                                    return {
                                        fillColor: feature.properties.color,
                                        color: feature.properties.color,
                                        weight: 0.5,
                                        fillOpacity: 0.5,
                                    };
                                },
                                onEachFeature: function(feature, layer) {
                                    layer.on('click', function(e) {
                                        const coordinates = e.latlng;
                                        const properties = feature.properties;

                                        const popupContent = `
                                        <p><strong>Kabupaten/Kota:</strong> ${properties.regency}</p>
                                        <p><strong>Kecamatan:</strong> ${properties.district}</p>
                                        <p><strong>ABJ:</strong> ${properties.abj}%</p>
                                        <p><strong>Total Sampel:</strong> ${properties.total_sample}</p>
                                        <p><strong>Total Pemeriksaan:</strong> ${properties.total_check}</p>
                                    `;

                                        L.popup()
                                            .setLatLng(coordinates)
                                            .setContent(popupContent)
                                            .openOn(map);

                                        // Zoom to the clicked feature
                                        map.fitBounds(layer.getBounds(), {
                                            padding: [100, 100]
                                        });
                                    });


                                    layer.on('mouseover', function(e) {
                                        map.getContainer().style.cursor = 'pointer';
                                    });

                                    layer.on('mouseout', function(e) {
                                        map.getContainer().style.cursor = '';
                                    });
                                }
                            }).addTo(map);
                        })
                        .catch((error) => {
                            console.error("Gagal mengambil data GeoJSON:", error);
                        });


                }

                updateMapData(); // map update
            @endif
            let larvae = Object.values(@json($larvae));
            let sample = Object.values(@json($sample));

            @if ($sample->count() > 0)
                let centerCoordinateSample = [];
                for (let i = 0; i < sample.length; i++) {
                    centerCoordinateSample.push([sample[i].latitude, sample[i].longitude, sample[i]]);
                }

                centerCoordinateSample.forEach(coordinate => {
                    var el = L.divIcon({
                        className: 'custom-marker',
                        html: '<img src="{{ asset('assets/images/vector/mosquito-icon.png') }}" class="w-6 h-6">'
                    });

                    // cluster marker
                    markers.addLayer(L.marker([parseFloat(coordinate[0]), parseFloat(coordinate[1])], {
                        icon: el
                    }).bindPopup(`
                        <table class = "border-collapse border-none">
                            <tbody>
                                <tr>
                                    <th colspan = "3" class = "p-0">Detail Lokasi</th>
                                </tr>
                                <tr>
                                    <td class = "p-0">Provinsi</td>
                                    <td class = "p-0">:</td>
                                    <td class = "p-0">${coordinate[2].province.name}</td>
                                </tr>
                                <tr>
                                    <td class = "p-0">Kabupaten</td>
                                    <td class = "p-0">:</td>
                                    <td class = "p-0">${coordinate[2].regency.name}</td>
                                </tr>
                                <tr>
                                    <td class = "p-0">Kecamatan</td>
                                    <td class = "p-0">:</td>
                                    <td class = "p-0">${coordinate[2].district.name}</td>
                                </tr>
                                <tr>
                                    <td>Lokasi</td>
                                    <td>: </td>
                                    <td>${coordinate[2].location_name}</td>
                                </tr>
                                <tr>
                                    <td>Rumah Sakit</td>
                                    <td>: </td>
                                    <td>${coordinate[2].public_health_name}</td>
                                </tr>
                            </tbody>
                        </table>

                        <table class = "border-collapse border-none mt-4 w-full">
                            <thead>
                                <tr>
                                    <th colspan = "2" class = "p-0">Detail Sampling</th>
                                </tr>
                                <tr class   = "mt-3">
                                <th colspan = "2" class = "p-0">Jenis Virus</th>
                                <th class   = "p-0">Jumlah</th>
                                </tr>
                                </thead>
                            <tbody>
                                ` +
                        Object.values(coordinate[2].type).map(function(type) {
                            return `
                                        <tr>
                                            <td class = "p-0">${type.name}:</td>
                                            <td class = "p-0" align = "right">${type.amount}</td>
                                        </tr>
                                    `;
                        }).join('') +
                        `
                            </tbody>
                        </table>
                    `).openPopup());

                    markers.on('mouseover', function() {
                        markers.openPopup();
                    });

                    map.addLayer(markers);
                });
            @endif

            @if ($larvae->count() > 0)
                let centerCoordinate = [];
                for (let i = 0; i < larvae.length; i++) {
                    centerCoordinate.push([larvae[i].latitude, larvae[i].longitude]);
                }

                centerCoordinate.forEach((coordinate, i) => {
                    var el = L.divIcon({
                        className: 'custom-marker',
                        html: '<img src="{{ asset('assets/images/larva-icon.png') }}" class="w-6 h-6">'
                    });

                    var marker = L.marker([parseFloat(coordinate[0]), parseFloat(coordinate[1])], {
                        icon: el
                    }).addTo(map);

                    marker.bindPopup(
                        `<table class="table-sm">
            <thead>
                <tr>
                    <th>#</th>
                    <th>TPA</th>
                    <th>Larva</th>
                    <th>Telur</th>
                    <th>Nyamuk Dewasa</th>
                    <th>Suhu Air</th>
                    <th>Salinitas</th>
                    <th>PH</th>
                    <th>Tumbuhan Air</th>
                </tr>
            </thead>
            <tbody>
                ` +
                        larvae[i].detail_larvaes.map((data, index) => {
                            return `<tr>
                        <td>${index + 1}</td>
                        <td>${data.tpa_type.name}</td>
                        <td>${data.amount_larva}</td>
                        <td>${data.amount_egg}</td>
                        <td>${data.number_of_adults}</td>
                        <td>${data.water_temperature}</td>
                        <td>${data.salinity}</td>
                        <td>${data.ph}</td>
                        <td>${data.aquatic_plant == 'available' ? 'Ada' : 'Tidak Ada'}</td>
                    </tr>`
                        }).join('') +
                        `</tbody>
        </table>`
                        // adjust width popup
                    ).on('popupopen', function() {
                        $('.leaflet-popup-content').width('auto');
                    });

                    // on click pan to marker
                    marker.on('click', function() {
                        map.setZoom(15);
                        map.panTo(marker.getLatLng());
                    });

                    // on hover show popup
                    marker.on('mouseover', function() {
                        marker.openPopup();
                    });
                });
            @endif



            // full screen
            L.control.fullscreen().addTo(map);
        </script>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            $(function() {
                let samplePerYear = @json($samplePerYear);
                @if (count($samplePerYear) > 0)
                    // Mengambil bulan dan jumlah dari setiap entri data
                    var labels = samplePerYear.map(entry => entry.month);
                    var counts = samplePerYear.map(entry => entry.count);

                    // Mengambil jenis nyamuk dari setiap entri samplePerYear
                    var mosquitoTypes = Object.keys(samplePerYear[0].type);

                    // Mengambil jumlah nyamuk dari setiap entri samplePerYear
                    var mosquitoAmounts = samplePerYear.map(entry => Object.values(entry.type));

                    // Membuat chart dengan Chart.js
                    var ctx = document.getElementById('samplePerYear').getContext('2d');
                    // width 100%
                    ctx.canvas.width = '100%';

                    let purplePallete = [
                        '#4e73df',
                        '#6f42c1',
                        '#9c27b0',
                    ]

                    var datasets = mosquitoTypes.map((type, index) => {
                        return {
                            label: type,
                            data: mosquitoAmounts.map(amounts => amounts[index]),
                            backgroundColor: purplePallete[index],
                            borderColor: purplePallete[index],
                            borderWidth: 1,
                            fill: false,
                            pointRadius: 3,
                            pointHoverRadius: 5,
                            pointHitRadius: 10,
                            pointBackgroundColor: purplePallete[index],
                            pointBorderColor: purplePallete[index],
                            pointHoverBackgroundColor: purplePallete[index],
                            pointHoverBorderColor: purplePallete[index],
                        };
                    });

                    var myChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: datasets
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
                @endif
            });
        </script>

        <script>
            $(function() {
                let sampleAndAbj = @json($sampleAndAbj);

                @if (count($sampleAndAbj) > 0)
                    let orangePalette = [
                        '#f6c23e',
                        '#e74a3b',
                        '#9c27b0'
                    ];

                    // Extract data for labels, total_sample, and total_abj
                    let districtNames = Object.values(sampleAndAbj).map(entry => entry.name);
                    let totalSampleData = Object.values(sampleAndAbj).map(entry => entry.total_sample);
                    let totalAbjData = Object.values(sampleAndAbj).map(entry => entry.total_abj);
                    let totalLarvaData = Object.values(sampleAndAbj).map(entry => entry.total_larva);

                    var ctx = document.getElementById('sampleAndAbj').getContext('2d');
                    // width 100%
                    ctx.canvas.width = '100%';

                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: districtNames,
                            datasets: [{
                                    label: 'Total Sampel',
                                    data: totalSampleData,
                                    backgroundColor: orangePalette[0],
                                    borderColor: orangePalette[0],
                                    borderWidth: 1,
                                    borderRadius: 4,
                                    barPercentage: 0.5,
                                    categoryPercentage: 0.5,
                                },
                                {
                                    label: 'Total ABJ',
                                    data: totalAbjData,
                                    backgroundColor: orangePalette[1],
                                    borderColor: orangePalette[1],
                                    borderWidth: 1,
                                    borderRadius: 4,
                                    barPercentage: 0.5,
                                    categoryPercentage: 0.5,
                                },
                                {
                                    label: 'Total Larva',
                                    data: totalLarvaData,
                                    backgroundColor: orangePalette[2],
                                    borderColor: orangePalette[2],
                                    borderWidth: 1,
                                    borderRadius: 4,
                                    barPercentage: 0.5,
                                    categoryPercentage: 0.5,
                                },
                            ],
                        },
                        options: {
                            responsive: true,
                            interaction: {
                                mode: 'index',
                                intersect: false,
                            },
                            scales: {
                                y: {
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
                            options: {
                                // ... (other options)
                                plugins: {
                                    tooltip: {
                                        mode: 'index',
                                        intersect: false,
                                        callbacks: {
                                            label: function(context) {
                                                var datasetLabel = context.dataset.label || '';
                                                var value = context.parsed.y;
                                                var total = context.dataset.data.reduce(function(
                                                    previousValue, currentValue) {
                                                    return previousValue + currentValue;
                                                });
                                                var percentage = ((value / total) * 100).toFixed(2) + '%';
                                                return datasetLabel + ': ' + percentage;
                                            }
                                        }
                                    },
                                    legend: {
                                        display: true, // Set to true to display the legend
                                        position: 'top', // Change the legend position (e.g., 'top', 'bottom', 'left', 'right')
                                        labels: {
                                            usePointStyle: true,
                                            boxWidth: 5,
                                            boxHeight: 5,
                                            fontColor: 'black', // Change the font color of the legend labels
                                        },
                                    },
                                    // ... (other plugins)
                                },
                            }
                        },
                    });
                @endif

                $('#regency').change(function() {
                    let regencyId = $(this).val();
                    $.ajax({
                        url: '{{ route('admin.dashboard.get-sample-and-abj-by-district') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            regency_id: regencyId
                        },
                        success: function(response) {
                            let sampleAndAbj = response;

                            // Extract data for labels, total_sample, and total_abj
                            let districtNames = Object.values(sampleAndAbj).map(entry => entry
                                .name);
                            let totalSampleData = Object.values(sampleAndAbj).map(entry => entry
                                .total_sample);
                            let totalAbjData = Object.values(sampleAndAbj).map(entry => entry
                                .total_abj);
                            let totalLarvaData = Object.values(sampleAndAbj).map(entry => entry
                                .total_larva);

                            myChart.data.labels = districtNames;
                            myChart.data.datasets[0].data = totalSampleData;
                            myChart.data.datasets[1].data = totalAbjData;
                            myChart.data.datasets[2].data = totalLarvaData;
                            myChart.update();
                        }
                    });
                });

                // set height when canvas already rendered
                $('#sampleAbjCard').height($('#sampleAndAbj').height() + 100);
            });
        </script>
    @endpush
</x-app-layout>
