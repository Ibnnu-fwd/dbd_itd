<x-app-layout>
    <x-breadcrumb name="tcases" />
    <x-card-container>
        <div class="z-0 relative mb-4" style="height: 350px; border-radius: 6px;">
            <!-- Legenda -->
            <div class="absolute bottom-0 right-0 p-2 bg-white shadow" style="z-index: 2;">
                <h5 class="mb-2 legend-text ">Legend</h5>
                <ul class="list-unstyled">
                    <li>
                        <span class="legend-color legend-green"></span>
                        Kasus Rendah
                    </li>
                    <li>
                        <span class="legend-color legend-yellow"></span>
                        Kasus Sedang
                    </li>
                    <li>
                        <span class="legend-color legend-red"></span>
                        Kasus Tinggi
                    </li>
                    <!-- Tambahkan elemen li sesuai dengan legenda Anda -->
                </ul>
            </div>
            <style>
                .legend-color {
                    width: 20px;
                    height: 20px;
                    display: inline-block;
                    margin-right: 5px;
                    border: 1px solid #ccc;
                    border-radius: 4px;
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
            <!-- Peta -->
            <div id="map" style="height: 100%; position: relative; z-index: 1;"></div>
        </div>
        <div class="flex flex-col gap-3 md:flex-row md:justify-end mb-4">
            <x-link-button route="{{ route('admin.tcases.create') }}" color="gray" type="button" class="justify-center">
                Tambah
            </x-link-button>
        </div>
        <table id="casesTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tahun</th>
                    <th>Kabupaten</th>
                    <th>Kecamatan</th>
                    <th>Desa</th>
                    <th>Jenis Vektor</th>
                    <th>Total Kasus</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </x-card-container>
    @push('js-internal')
    <script>
        function btnDelete(id, name) {
            let url = "{{ route('admin.tcases.destroy', ':id') }}";
            url = url.replace(':id', id);

            Swal.fire({
                title: 'Apakah anda yakin?',
                text: `Apakah anda yakin ingin menghapus virus ${name}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Tidak',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                }).then(() => {
                                    $('#casesTable').DataTable().ajax.reload();
                                    location.reload();
                                })
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: response.message
                                })
                            }
                        }
                    });
                }
            });
        }
        @if(Session::has('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ Session::get('
            success ') }}'
        })
        @endif

        @if(Session::has('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: '{{ Session::get('
            error ') }}'
        })
        @endif
        const map = L.map('map').setView([-7.2756196, 112.7106256], 11.5);

        function getColor(tcase_total) {
            if (tcase_total < 15) {
                return '#1cc88a'; // Less than 15 cases/month
            } else if (tcase_total >= 16 && tcase_total <= 30) {
                return '#ffff00'; // 16 - 30 cases/month
            } else if (tcase_total > 30) {
                return '#e74a3b';
            } else {
                return '#858796'; // Default
            }
        }

        L.tileLayer(
            'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
                attribution: '&copy; <a href="https://www.mapbox.com/">Mapbox</a> &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
                maxZoom: 18,
                id: 'mapbox/light-v11',
                tileSize: 512,
                zoomOffset: -1,
                accessToken: 'pk.eyJ1IjoiaWJudTIyMDQyMiIsImEiOiJjbGltd3BkdnowMGpsM3JveGVteG52NWptIn0.Ficg1JfyGMJHRgnU48gDdg',
            }
        ).addTo(map);
        function updateMapData() {
            let tcases = Object.values(@json($tcases));
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

                        tcases.forEach((tcasesItem) => {

                            // console.log(kecamatan);
                            if (tcasesItem.district.toUpperCase() === kecamatan.toUpperCase()) {
                                // Sekarang Anda memiliki array koordinat dari fitur yang sesuai
                                const coordinates = feature.geometry.coordinates;
                                // Ubah koordinat jika diperlukan
                                const coordinates2 = coordinates[0];
                                // console.log(tcasesItem.cases_total);

                                geojson.features.push({
                                    type: 'Feature',
                                    geometry: {
                                        type: 'Polygon',
                                        coordinates: [coordinates2]
                                    },
                                    properties: {
                                        color: getColor(tcasesItem.cases_total),
                                        district: properties.KECAMATAN,
                                        village: properties.KELURAHAN,
                                        totalCase: tcasesItem.cases_total,
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
                                    <p><strong>Kecamatan:</strong> ${properties.district}</p>
                                    <p><strong>Kelurahan:</strong> ${properties.village}</p>
                                    <p><strong>Total Kasus:</strong> ${properties.totalCase}</p>
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
        // full screen
        L.control.fullscreen().addTo(map);
        $(document).ready(function() {
            var casesTable = $('#casesTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                ajax: "{{ route('admin.tcases.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'date',
                        name: 'date',
                    },
                    {
                        data: 'regency',
                        name: 'regency',
                    },
                    {
                        data: 'district',
                        name: 'district',
                    },
                    {
                        data: 'village',
                        name: 'village',
                    },
                    {
                        data: 'vector_type',
                        name: 'vector_type',
                    },
                    {
                        data: 'cases_total',
                        name: 'cases_total',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                    },
                ],
            });
        });
    </script>
    @endpush
</x-app-layout>