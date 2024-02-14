<x-app-layout>

    @push('css-internal')
        <!-- Leaflet Fullscreen CSS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet-fullscreen/dist/leaflet.fullscreen.css" />
    @endpush
    <x-breadcrumb name="cluster.clustering" />
    <x-card-container>
        <h2 class="font-semibold text-xs mb-8">Sesuaikan Klaster</h2>
        <div class="flex items-end gap-4">
            <x-input id="epsilon" label="Epsilon" name="epsilon" type="number" required />
            <x-input id="minPoints" label="Min Points" name="minPoints" type="number" required />
            <x-button type="submit" class="bg-primary mb-4">Klasterkan</x-button>
        </div>
    </x-card-container>

    <x-card-container>
        <p class="text-xs font-semibold mb-8">Klaster</p>
        <div id="map" style="height: 400px;"></div>
    </x-card-container>

    @push('js-internal')
        <!-- Leaflet Fullscreen JavaScript -->
        <script src="https://unpkg.com/leaflet-fullscreen/dist/Leaflet.fullscreen.js"></script>

        <script>
            var data = @json($cluster);
            let map = L.map('map').setView([-7.265757, 112.734146], 13);
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

            // Add Leaflet Fullscreen control
            map.addControl(new L.Control.Fullscreen());

            function generateColor() {
                return '#' + (0x1000000 + (Math.random()) * 0xffffff).toString(16).substr(1, 6);
            }

            let colorMap = {};

            function getColor(cluster) {
                if (!colorMap.hasOwnProperty(cluster)) {
                    colorMap[cluster] = generateColor();
                }
                return colorMap[cluster];
            }

            data.forEach((item, index) => {
                let clusterCenter = {
                    lat: 0,
                    lon: 0
                }; // Initialize the cluster center

                item.forEach((i, idx) => {
                    // Calculate the center of the cluster by averaging the coordinates
                    clusterCenter.lat += parseFloat(i.latitude);
                    clusterCenter.lon += parseFloat(i.longitude);

                    let markerIcon = L.divIcon({
                        className: 'custom-div-icon',
                        html: `<div style="background-color: ${getColor(i.cluster)}; width: 10px; height: 10px; border-radius: 50%;" class="marker-pin"></div><span class="text-xs font-semibold">${i.cluster}</span>`
                        // You can customize the popup content here
                    });

                    // Create a marker and bind the popup
                    let marker = L.marker([i.latitude, i.longitude], {
                        icon: markerIcon
                    }).addTo(map);
                    let table = `<table class="table-auto">
                        <tbody>
                            <tr>
                                <td class="border font-semibold px-2">Lokasi</td>
                                <td class="border">${i.location_name}</td>
                            </tr>
                            <tr>
                                <td class="border font-semibold px-2">Jenis Lokasi</td>
                                <td class="border">${i.location_type}</td>
                            </tr>
                            <tr>
                                <td class="border font-semibold px-2">Coordinate</td>
                                <td class="border">${i.latitude} | ${i.longitude}</td>
                            </tr>
                            <tr>
                                <td class="border font-semibold px-2">
                                    <h1>Morfotipe</h1>
                                    <small>(jenis) | (jumlah)</small>
                                </td>
                                <td class="border">
                                    <ul class="list-disc list-inside">
                                        <li>Morf. 1: ${i.morphotype_1}</li>
                                        <li>Morf. 2: ${i.morphotype_2}</li>
                                        <li>Morf. 3: ${i.morphotype_3}</li>
                                        <li>Morf. 4: ${i.morphotype_4}</li>
                                        <li>Morf. 5: ${i.morphotype_5}</li>
                                        <li>Morf. 6: ${i.morphotype_6}</li>
                                        <li>Morf. 7: ${i.morphotype_7}</li>
                                    </ul>
                                </td>
                            </tr>
                            <tr>
                                <td class="border font-semibold px-2">
                                    <h1>DENV</h1>
                                    <small>(jenis) | (jumlah)</small>
                                </td>
                                <td class="border">
                                    <ul class="list-disc list-inside">
                                        <li>DENV. 1: ${i.denv_1 ?? '-'}</li>
                                        <li>DENV. 2: ${i.denv_2 ?? '-'}</li>
                                        <li>DENV. 3: ${i.denv_3 ?? '-'}</li>
                                        <li>DENV. 4: ${i.denv_4 ?? '-'}</li>
                                    </ul>
                                </td>
                            </tr>
                        </tbody>
                    </table>`;
                    marker.bindPopup(table);

                    // Add a circle border around the cluster
                    L.circle([clusterCenter.lat, clusterCenter.lon], {
                        radius: 100, // Adjust the radius based on your preference
                        color: getColor(i.cluster), // Use the same color as the cluster
                        fill: false,
                        weight: 2, // Border weight
                        opacity: 0.7 // Border opacity
                    }).addTo(map);
                });

                // Calculate the average center of the cluster
                clusterCenter.lat /= item.length;
                clusterCenter.lon /= item.length;
            });
        </script>
    @endpush
</x-app-layout>
