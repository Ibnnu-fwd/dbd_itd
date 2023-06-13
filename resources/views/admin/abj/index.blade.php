<x-app-layout>
    <x-breadcrumb name="abj" />
    <x-card-container>
        <div id="map" class="z-0 mb-4" style="height: 350px; border-radius: 6px"></div>
        <table id="abjTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kecamatan</th>
                    <th>Jumlah Sampel</th>
                    <th>Jumlah Pemeriksaan</th>
                    <th>ABJ (%)</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
        </table>
    </x-card-container>

    @push('js-internal')
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet">
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>
    <script>
        function getColor(abj_total) {
            return abj_total > 80 ? '#1cc88a' :
                abj_total > 60 ? '#f6c23e' :
                abj_total > 40 ? '#e74a3b' :
                '#858796';
        }

        mapboxgl.accessToken =
            'pk.eyJ1IjoiaWJudTIyMDQyMiIsImEiOiJjbGltd3BkdnowMGpsM3JveGVteG52NWptIn0.Ficg1JfyGMJHRgnU48gDdg';
        const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/light-v11', // URL gaya peta
            center: [113.717332, -8.1624029], // koordinat Jember
            zoom: 8 // zoom awal
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
                    zoom: 12
                });
            });
        });
    </script>


        <script>
            $(function() {
                $('#abjTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('admin.abj.index') }}",
                    reponsive: true,
                    autoWidth: false,
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'district',
                            name: 'district',
                        },
                        {
                            data: 'total_sample',
                            name: 'total_sample',
                        },
                        {
                            data: 'total_check',
                            name: 'total_check',
                        },
                        {
                            data: 'abj',
                            name: 'abj'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                    ],
                });
            });
        </script>
    @endpush
</x-app-layout>
