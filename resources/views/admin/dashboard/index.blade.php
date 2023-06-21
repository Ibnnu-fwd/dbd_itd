<x-app-layout>
    <x-breadcrumb name="dashboard" />
    <div id="map" class="z-0 mb-4" style="height: 350px; border-radius: 6px; margin-top:30px;"></div>
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
        <script src="https://api.mapbox.com/mapbox-gl-js/v2.6.1/mapbox-gl.js"></script>
        <link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet">
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>
    
        <script>
            function getColor(abj_total) {
                return abj_total > 80 ? '#1cc88a' :
                    abj_total > 60 ? '#f6c23e' :
                    abj_total > 40 ? '#e74a3b' :
                    '#858796';
            }

            mapboxgl.accessToken = 'pk.eyJ1IjoiaWJudTIyMDQyMiIsImEiOiJjbGltd3BkdnowMGpsM3JveGVteG52NWptIn0.Ficg1JfyGMJHRgnU48gDdg';

            const map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/mapbox/light-v11',
                center: [113.717332, -8.1624029],
                zoom: 8
            });
                let larvae = Object.values(@json($larvae));
                let sample = Object.values(@json($sample));
                let centerCoordinateSample =[];
                for (let i = 0; i < sample.length; i++) {
                    centerCoordinateSample.push([sample[i].latitude, sample[i].longitude]);
                }
                console.log(centerCoordinateSample);
                let centerCoordinate = [];
                for (let i = 0; i < larvae.length; i++) {
                    centerCoordinate.push([larvae[i].latitude, larvae[i].longitude]);
                }

                centerCoordinateSample.forEach(coordinate => {
                    var el = document.createElement('div');
                    el.className = 'custom-marker';
                    el.innerHTML = '<<img src="{{ asset('assets/images/vector/mosquito-icon.png') }}" class="w-6 h-6">';

                    // Membuat marker dengan ikon kustom
                    var marker = new mapboxgl.Marker({
                        element: el,
                        anchor: 'center'
                    })
                        .setLngLat([parseFloat(coordinate[1]), parseFloat(coordinate[0])])
                        .addTo(map);
                    });
                centerCoordinate.forEach(coordinate => {
                    var el = document.createElement('div');
                    el.className = 'custom-marker';
                    el.innerHTML = '<img src="{{ asset('assets/images/larvae/icon.jpg') }}" class="w-6 h-6">';

                    // Membuat marker dengan ikon kustom
                    var marker = new mapboxgl.Marker({
                        element: el,
                        anchor: 'center'
                    })
                        .setLngLat([parseFloat(coordinate[1]), parseFloat(coordinate[0])])
                        .addTo(map);
                    });
                    
            map.on('load', () => {
               

                // Other map-related code...

                updateMapData(); // Update map data
            });
            let geojson = {
                type: 'FeatureCollection',
                crs: {
                    type: 'name',
                    properties: {
                        name: 'urn:ogc:def:crs:OGC:1.3:CRS84'
                    }
                },
                features: []
            };

            function updateMapData() {
                let abj = Object.values(@json($abj));
                
               
                fetch("{{ asset('assets/geojson/indonesia_villages_border.geojson') }}")
                    .then((response) => response.json())
                    .then((data) => {
                        const geojson = {
                            type: 'FeatureCollection',
                            features: []
                        };

                        data.forEach((dataItem) => {
                            abj.forEach((abjItem) => {
                                if (abjItem.district === dataItem.sub_district) {
                                    geojson.features.push({
                                        type: 'Feature',
                                        geometry: {
                                            type: 'Polygon',
                                            coordinates: [dataItem.border]
                                        },
                                        properties: {
                                            color: getColor(abjItem.abj_total),
                                            regency: dataItem.district,
                                            district: dataItem.sub_district,
                                            village: dataItem.name,
                                            abj: abjItem.abj_total,
                                            total_sample: abjItem.total_sample,
                                            total_check: abjItem.total_check
                                        }
                                    });
                                }
                            });
                        });
                        console.log(geojson);

                        map.getSource('geojson-data').setData(geojson);
                    });
            }

            map.on('load', () => {
                map.addSource('geojson-data', {
                    type: 'geojson',
                    data: geojson
                });

                map.addLayer({
                    id: 'geojson-layer',
                    type: 'fill',
                    source: 'geojson-data',
                    paint: {
                        'fill-color': ['get', 'color'],
                        'fill-opacity': 0.5,
                    }
                });

                map.on('click', 'geojson-layer', (e) => {
                    const coordinates = e.lngLat;
                    const properties = e.features[0].properties;

                    const popup = new mapboxgl.Popup()
                        .setLngLat(coordinates)
                        .setHTML(`
                            <p><strong>Kabupaten/Kota:</strong> ${properties.regency}</p>
                            <p><strong>Kecamatan:</strong> ${properties.district}</p>
                            <p><strong>ABJ:</strong> ${properties.abj}%</p>
                            <p><strong>Total Sampel:</strong> ${properties.total_sample}</p>
                            <p><strong>Total Pemeriksaan:</strong> ${properties.total_check}</p>
                        `)
                        .addTo(map);

                    // Zoom to the clicked feature
                    const bounds = new mapboxgl.LngLatBounds();
                    const coordinatesArray = e.features[0].geometry.coordinates[0];
                    coordinatesArray.forEach((coordinate) => {
                        bounds.extend(coordinate);
                    });
                    map.fitBounds(bounds, { padding: 100 });
                });


                //ketika mouse masuk ke area
                map.on('mouseenter', 'geojson-layer', () => {
                    map.getCanvas().style.cursor = 'pointer';
                });
                // ketika mouse tidak di dalam area
                map.on('mouseleave', 'geojson-layer', () => {
                    map.getCanvas().style.cursor = '';
                });

                updateMapData(); // map update

                // ketika area di klik maka akan zoom in ke area tersebut
                map.on('click', 'geojson-layer', (e) => {
                    const coordinates = e.features[0].geometry.coordinates[0][0];
                    map.flyTo({
                        center: coordinates,
                        zoom: 11
                    });
                });

                // full screen
                map.addControl(new mapboxgl.FullscreenControl());
            });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
