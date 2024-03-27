<x-app-layout>

    @push('css-internal')
        <!-- Leaflet Fullscreen CSS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet-fullscreen/dist/leaflet.fullscreen.css" />
    @endpush
    <x-breadcrumb name="cluster.clustering" />
    <x-card-container class="mb-6">
        <h2 class="font-semibold text-xs mb-8">Sesuaikan Klaster</h2>
        <div class="flex items-end gap-4">
            <x-input id="epsilon" label="Epsilon" name="epsilon" type="number" value="0.002839" required />
            <x-input id="minPoints" label="Min Points" name="minPoints" type="number" value="1" required />
            <x-button type="submit" class="bg-primary mb-4" id="buttonDbscan">Klasterkan</x-button>
        </div>
    </x-card-container>

    <x-card-container class="mb-4">
        <h2 class="font-semibold text-xs mb-8">Jumlah Klaster Terbentuk</h2>
        <div class="clusterContainer grid grid-cols-4 gap-6"></div>
    </x-card-container>

    <x-card-container>
        <p class="text-xs font-semibold mb-8">Klaster</p>
        <div id="map" style="height: 400px;"></div>
    </x-card-container>

    @push('js-internal')
        <script src="https://unpkg.com/leaflet-fullscreen/dist/Leaflet.fullscreen.js"></script>
        <script src="{{ asset('assets/js/dbscanclustering.js') }}"></script>
        <script>
            let listDistrict = [];
            let map = null;
            async function setupClustering(data) {
                // Remove the existing map container initialization
                if (map) {
                    map.remove();
                }

                // Reinitialize the map container with the desired view
                map = L.map("map").setView([-7.265757, 112.734146], 13);
                L.tileLayer(
                    "https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}", {
                        attribution: '&copy; <a href="https://www.mapbox.com/">Mapbox</a> &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
                        maxZoom: 18,
                        id: "mapbox/light-v11",
                        tileSize: 512,
                        zoomOffset: -1,
                        accessToken: "{{ env('MAPBOX_TOKEN') }}",
                    }
                ).addTo(map);

                // Add Leaflet Fullscreen control
                map.addControl(new L.Control.Fullscreen());

                function generateColor() {
                    return (
                        "#" +
                        (0x1000000 + Math.random() * 0xffffff).toString(16).substr(1, 6)
                    );
                }

                let colorMap = {};

                function getColor(cluster) {
                    if (!colorMap.hasOwnProperty(cluster)) {
                        colorMap[cluster] = generateColor();
                    }
                    return colorMap[cluster];
                }

                data.forEach((item, index) => {
                    listDistrict.push(item.map((i) => i));
                    let clusterCenter = {
                        lat: 0,
                        lon: 0,
                    }; // Initialize the cluster center

                    item.forEach((i, idx) => {
                        // Calculate the center of the cluster by averaging the coordinates
                        clusterCenter.lat += parseFloat(i.latitude);
                        clusterCenter.lon += parseFloat(i.longitude);

                        let markerIcon = L.divIcon({
                            className: "custom-div-icon",
                            html: `<div style="background-color: ${getColor(i.cluster)}; width: 10px; height: 10px; border-radius: 50%;" class="marker-pin"></div><span class="text-xs font-semibold">${i.cluster + 1}</span>`,
                        });

                        // Create a marker and bind the popup
                        let marker = L.marker([i.latitude, i.longitude], {
                            icon: markerIcon,
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
                                        <li>DENV. 1: ${i.denv_1 ?? "-"}</li>
                                        <li>DENV. 2: ${i.denv_2 ?? "-"}</li>
                                        <li>DENV. 3: ${i.denv_3 ?? "-"}</li>
                                        <li>DENV. 4: ${i.denv_4 ?? "-"}</li>
                                    </ul>
                                </td>
                            </tr>
                        </tbody>
                    </table>`;
                        marker.bindPopup(table);

                        // Add a circle border around the cluster
                        L.circle([clusterCenter.lat, clusterCenter.lon], {
                                radius: 400, // Adjust the radius based on your preference
                                color: getColor(i.cluster), // Use the same color as the cluster
                                fill: true,
                                fillOpacity: 0.1,
                                weight: 2, // Border weight
                                opacity: 0.7, // Border opacity
                            })
                            .addTo(map)
                            .on("click", function() {
                                // tampilkan jumlah data di cluster
                                let clusterCount = item.length;
                                let totalDENV = 0;
                                let totalDENV1 = 0;
                                let totalDENV2 = 0;
                                let totalDENV3 = 0;
                                let totalDENV4 = 0;
                                let totalMorphotype = 0;
                                let listOfDistric = [];
                                item.forEach((i) => {
                                    listOfDistric.push(
                                        i.district != null ? i.district : "-"
                                    );
                                    totalDENV += i.denv_1 + i.denv_2 + i.denv_3 + i.denv_4;
                                    totalDENV1 += i.denv_1;
                                    totalDENV2 += i.denv_2;
                                    totalDENV3 += i.denv_3;
                                    totalDENV4 += i.denv_4;
                                    totalMorphotype +=
                                        i.morphotype_1 +
                                        i.morphotype_2 +
                                        i.morphotype_3 +
                                        i.morphotype_4 +
                                        i.morphotype_5 +
                                        i.morphotype_6 +
                                        i.morphotype_7;
                                });
                                let table = `<table class="table-auto">
                                    <tbody>
                                        <tr>
                                            <td class="border font-semibold px-2">Nomor Cluster</td>
                                            <td class="border">${i.cluster+1}</td>
                                        </tr>
                                        <tr>
                                            <td class="border font-semibold px-2">Jumlah Data</td>
                                            <td class="border">${clusterCount}</td>
                                        </tr>
                                        <tr>
                                            <td class="border font-semibold px-2">Jumlah DENV 1</td>
                                            <td class="border">${totalDENV1 ?? "-"}</td>
                                        </tr>
                                        <tr>
                                            <td class="border font-semibold px-2">Jumlah DENV 2</td>
                                            <td class="border">${totalDENV2 ?? "-"}</td>
                                        </tr>
                                        <tr>
                                            <td class="border font-semibold px-2">Jumlah DENV 3</td>
                                            <td class="border">${totalDENV3 ?? "-"}</td>
                                        </tr>
                                        <tr>
                                            <td class="border font-semibold px-2">Jumlah DENV 4</td>
                                            <td class="border">${totalDENV4 ?? "-"}</td>
                                        </tr>
                                        <tr>
                                            <td class="border font-semibold px-2">Jumlah DENV</td>
                                            <td class="border">${totalDENV ?? "-"}</td>
                                        </tr>
                                        <tr>
                                            <td class="border font-semibold px-2">Jumlah Morfotipe</td>
                                            <td class="border">${
                                                totalMorphotype ?? "-"
                                            }</td>
                                        </tr>
                                        <tr>
                                            <td class="border font-semibold px-2">Lokasi</td>
                                            <td class="border">${
                                                listOfDistric
                                                    .filter(
                                                        (item, index) =>
                                                            listOfDistric.indexOf(item) ===
                                                            index
                                                    )
                                                    .join(", ") ?? "-"
                                            }</td>
                                        </tr>
                                    </tbody>
                                </table>`;
                                L.popup()
                                    .setLatLng([clusterCenter.lat, clusterCenter.lon])
                                    .setContent(table)
                                    .openOn(map);
                            });
                    });
                    clusterCenter.lat /= item.length;
                    clusterCenter.lon /= item.length;

                    const uniqueClusters = new Set();
                    item.forEach((i) => {
                        uniqueClusters.add(i.cluster);
                    });

                    uniqueClusters.forEach((cluster) => {
                        let totalDENV1 = 0;
                        let totalDENV2 = 0;
                        let totalDENV3 = 0;
                        let totalDENV4 = 0;
                        let totalMorphotype = 0;
                        let clusterCount = item.filter((i) => i.cluster === cluster).length;
                        let listOfDistric = [];
                        item.forEach((i) => {
                            if (i.cluster === cluster) {
                                listOfDistric.push(
                                    i.district != null ? i.district : "-"
                                );
                                totalDENV1 += i.denv_1;
                                totalDENV2 += i.denv_2;
                                totalDENV3 += i.denv_3;
                                totalDENV4 += i.denv_4;
                                totalMorphotype +=
                                    i.morphotype_1 +
                                    i.morphotype_2 +
                                    i.morphotype_3 +
                                    i.morphotype_4 +
                                    i.morphotype_5 +
                                    i.morphotype_6 +
                                    i.morphotype_7;
                            }
                        });

                        let clusterContainer = document.querySelector(".clusterContainer");
                        let clusterCard = document.createElement("div");
                        clusterCard.classList.add("bg-white", "rounded-md", "border", "border-gray-300",
                            "p-4");
                        clusterCard.innerHTML = `
                            <p class="text-sm font-semibold">Cluster: ${cluster + 1}</p>
                            <p class="text-xs">Jumlah Data: ${clusterCount}</p>
                            <p class="text-xs">Jumlah DENV 1: ${totalDENV1}</p>
                            <p class="text-xs">Jumlah DENV 2: ${totalDENV2}</p>
                            <p class="text-xs">Jumlah DENV 3: ${totalDENV3}</p>
                            <p class="text-xs">Jumlah DENV 4: ${totalDENV4}</p>
                            <p class="text-xs">Jumlah Morfotipe: ${totalMorphotype}</p>
                            <p class="text-xs">Lokasi: ${listOfDistric.filter((item, index) => listOfDistric.indexOf(item) === index).join(", ")}</p>
                        `;
                        clusterContainer.appendChild(clusterCard);
                    });
                });
            }

            (async function() {
                var data = @json($cluster);
                await setupClustering(data);
            })();

            $("#buttonDbscan").on("click", function() {
                let epsilon = $("#epsilon").val();
                let minPoints = $("#minPoints").val();
                $.ajax({
                    url: "{{ route('admin.cluster.clustering') }}",
                    type: "GET",
                    data: {
                        _token: "{{ csrf_token() }}",
                        epsilon: epsilon,
                        minPts: minPoints,
                    },
                    success: function(response) {
                        listDistrict = [];
                        $(".clusterContainer").empty();
                        setupClustering(response);
                    },
                });
            });
        </script>
    @endpush
</x-app-layout>
