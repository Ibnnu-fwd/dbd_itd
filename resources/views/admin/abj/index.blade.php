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
                style: 'mapbox://styles/mapbox/light-v11', // style URL
                center: [-68.137343, 45.137451], // starting position
                zoom: 5 // starting zoom
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

            let abj = Object.values(@json($abj));

            // get geojson in local public folder
            fetch("{{ asset('geojson/indonesia_villages_border.geojson') }}")
                .then((response) => response.json())
                .then((data) => {
                    data.forEach(dataItem => {
                        abj.forEach((abjItem) => {
                            if (abjItem.district === dataItem.sub_district) {
                                geojson.features.push({
                                    type: 'Feature',
                                    geometry: {
                                        type: 'Polygon',
                                        // make sure the first and last coordinates are the same and between 90 and -90
                                        coordinates: dataItem.border
                                    },
                                    properties: {
                                        color: getColor(abjItem.abj_total),
                                        regency: dataItem.district,
                                        district: dataItem.sub_district,
                                        village: dataItem.name,
                                        abj: abjItem.abj_total,
                                        total_sample: abjItem.total_sample,
                                        total_check: abjItem.total_check,
                                    }
                                });
                            }
                        });
                    });
                });
            console.log(geojson);
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
