@extends('layout')

@section('content')
    @if (isset($ruasJalanDetails) && !empty($ruasJalanDetails))
        <div id="ruasJalanDetails" style="display: none;">{{ json_encode($ruasJalanDetails) }}</div>
    @endif

    <!-- Include the CSS file -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    <section class="page-section">
        <div class="container" style="padding-top: 0;">
            <div class="text-center" style="padding-top: 0;">
                <div id="polylineText"></div>
                <script>
                    var polylineData = JSON.parse(document.getElementById('polylineData') ? document.getElementById('polylineData').textContent : '[]');
                    console.log(polylineData);
                </script>
                <h2 class="section-heading text-uppercase">Lihat Map Ruas Jalan</h2>
            </div>
            {{-- <div class="map-container">

                <h5 class="text-center" style="padding-top: 1%;">Filter Kondisi Jalan</h5>
                <div class="checkbox-road-view">
                    <label class="checkbox-label">
                        <input type="checkbox" class="kondisi-checkbox" value="1" checked>
                        <span class="legend-color green"></span> Jalan Baik
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" class="kondisi-checkbox" value="2" checked>
                        <span class="legend-color yellow"></span> Jalan Sedang
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" class="kondisi-checkbox" value="3" checked>
                        <span class="legend-color red"></span> Jalan Rusak
                    </label>
                </div>

               
            </div> --}}
            <div id="mapid" style="background-color: #f0f0f0;"></div>


            <h5 class="text-center" style="padding-top: 1%;">Filter Kondisi Jalan</h5>
            <div class="checkbox-group mt-4">
                <label class="checkbox-label">
                    <input type="checkbox" class="kondisi-checkbox" value="1" checked>
                    <span class="legend-color green"></span> Jalan Baik
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" class="kondisi-checkbox" value="2" checked>
                    <span class="legend-color yellow"></span> Jalan Sedang
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" class="kondisi-checkbox" value="3" checked>
                    <span class="legend-color red"></span> Jalan Rusak
                </label>
            </div>

            <script>
                var mymap = L.map('mapid').setView([-8.4095188, 115.188919], 10);

                var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 18,
                }).addTo(mymap);

                var ruasJalanDetails = JSON.parse(document.getElementById('ruasJalanDetails').textContent);
                console.log(ruasJalanDetails);

                var polylines = [];

                function addPolylines() {
                // Hapus semua polyline beserta tooltip sebelum menambah yang baru
                polylines.forEach(function(polyline, index) {
                    mymap.removeLayer(polyline);
                    mymap.removeLayer(polylineTexts[index]);
                });
                polylines = [];
                polylineTexts = [];

                ruasJalanDetails.forEach(function(ruas) {
                    var kondisiCheckbox = document.querySelector('input.kondisi-checkbox[value="' + ruas.kondisi_id + '"]');
                    if (kondisiCheckbox && kondisiCheckbox.checked) {
                        var color;
                        switch (ruas.kondisi_id) {
                            case 1:
                                color = 'green'; // baik
                                break;
                            case 2:
                                color = 'yellow'; // sedang
                                break;
                            case 3:
                                color = 'red'; // rusak
                                break;
                            default:
                                color = 'gray'; // default
                        }

                        var polyline = L.polyline(ruas.paths, {
                            color: color,
                            weight: 5,
                            kode_ruas: ruas.kode_ruas
                        }).addTo(mymap);

                        var polylineText = L.tooltip({
                            permanent: true,
                            direction: 'center',
                            className: 'kode-ruas-label'
                        })
                        .setContent(ruas.kode_ruas)
                        .setLatLng(polyline.getBounds().getCenter())
                        .addTo(mymap);

                        polylines.push(polyline);
                        polylineTexts.push(polylineText);

                        polyline.on('click', function(e) {
                            var popupContent = `
                                <div class="popup-content">
                                    <h5>${ruas.nama_ruas}</h5>
                                    <p><strong>Panjang Jalan:</strong> ${ruas.panjang} km</p>
                                    <p><strong>Kode Ruas:</strong> ${ruas.kode_ruas}</p>
                                    <p><strong>Kondisi ID:</strong> ${ruas.kondisi_id}</p>
                                    <p><strong>Jenis Jalan ID:</strong> ${ruas.jenisjalan_id}</p>
                                    <p><strong>Keterangan:</strong> ${ruas.keterangan}</p>
                                </div>
                            `;
                            L.popup()
                                .setLatLng(e.latlng)
                                .setContent(popupContent)
                                .openOn(mymap);
                        });
                    }
                });
            }

                document.querySelectorAll('input.kondisi-checkbox').forEach(function(checkbox) {
                    checkbox.addEventListener('change', addPolylines);
                });

                addPolylines();

                document.addEventListener('DOMContentLoaded', function() {
                    var apiToken = "{{ Session::get('token') }}";
                    console.log('API Token:', apiToken);
                });
            </script>
        </div>
    </section>
@endsection
