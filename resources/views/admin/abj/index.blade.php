<x-app-layout>
    <x-breadcrumb name="abj" />
    <x-card-container>
        <div id="map" class="z-0 mb-4" style="height: 350px; border-radius: 6px"></div>
        <div class="flex flex-col gap-3 md:flex-row md:justify-end mb-4">
            <x-button type="button" data-modal-toggle="defaultModal" color="gray" type="button" class="justify-center">
                Tambah
            </x-button>
        </div>
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
    <!-- Modal -->
    <div id="defaultModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 flex items-center justify-center">
        <div class="relative p-4 w-screen max-w-2xl">
            <!-- Modal content -->
            <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                <!-- Modal header -->
                <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                        Tambah Data
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="defaultModal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form action="" method="POST">
                    @csrf
                    <div class="xl:grid grid-cols-2 gap-x-4">
                        <x-select id="regency_id" label="Kabupaten/Kota" name="regency_id" isFit="true" required>
                            @foreach ($regencies as $regency)
                            <option value="{{ $regency->id }}">{{ $regency->name }}</option>
                            @endforeach
                        </x-select>
                        <x-select id="district_id" label="Kecamatan" name="district_id" isFit="true" required />
                        <x-select id="village_id" label="Desa" name="village_id" isFit="true" required />
                        <x-input id="jumlah_sampel" type="text" name="jumlah_sampel" label="Jumlah Sampel" required />
                        <x-input id="jumlah_pemeriksaan" type="text" name="jumlah_pemeriksaan" label="Jumlah Pemeriksaan" required />
                        <x-input id="abj" type="text" name="abj" label="ABJ (%)" required />
                        <x-input-datepicker id="tanggal" name="tanggal" label="Tanggal" required />
                    </div>
                    <div class="flex flex-col gap-2 md:flex-row md:justify-end mt-6 items-end">
                        <x-button type="button" data-modal-toggle="defaultModal" color="gray" class="justify-center w-full md:w-auto">
                            Batal
                        </x-button>
                        <x-button type="submit" class="bg-primary justify-center w-full md:w-auto">
                            Simpan
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('js-internal')
    <script>
        $("#regency_id").on("change", function() {
            regency = $(this).val();
            $("#district_id").empty();
            $("#village_id").empty();
            $("#district_id").append(
                `<option value="" selected disabled>Pilih Kecamatan</option>`
            );
            $("#village_id").append(
                `<option value="" selected disabled>Pilih Desa</option>`
            );
            $.ajax({
                url: "{{ route('admin.district.list') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    regency_id: regency,
                },
                success: function(data) {
                    let districts = Object.values(data);
                    districts.forEach((district) => {
                        $("#district_id").append(
                            `<option value="${district.id}">${district.name}</option>`
                        );
                    });
                },
            });
        });

        $("#district_id").on("change", function() {
            district = $(this).val();
            $("#village_id").empty();
            $("#village_id").append(
                `<option value="" selected disabled>Pilih Desa</option>`
            );
            $.ajax({
                url: "{{ route('admin.village.list') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    district_id: district,
                },
                success: function(data) {
                    let villages = Object.values(data);
                    villages.forEach((village) => {
                        $("#village_id").append(
                            `<option value="${village.id}">${village.name}</option>`
                        );
                    });
                },
            });
        });

        $("#village_id").on("change", function() {
            village = $(this).val();
            $.ajax({
                url: "{{ route('admin.village.show', ':id') }}".replace(
                    ":id",
                    village
                ),
                type: "GET",
                success: function(data) {
                    $('#address').val(data.address);
                    $("#address").text(data.address);
                },
            });
        });

        function getColor(abj_total) {
            return abj_total > 90 ? '#1cc88a' :
                abj_total >= 15 && abj_total < 90 ? '#f6c23e' :
                abj_total <= 15 ? '#e74a3b' :
                '#858796';
        }

        const map = L.map('map').setView([ -7.2575, 112.7521], 11);

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
        // full screen
        L.control.fullscreen().addTo(map);
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