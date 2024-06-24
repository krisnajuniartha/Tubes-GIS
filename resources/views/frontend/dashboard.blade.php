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

            <div class="table-responsive mt-4" style="max-width: 100%; overflow-x: auto;">
                @if (!empty($ruasJalanDetails))
                    <table class="table" id="ruasJalanTable">
                        <thead>
                            <tr>
                                <th>Desa ID</th>
                                <th>Kode Ruas</th>
                                <th>Nama Ruas</th>
                                <th>Eksisting ID</th>
                                <th>Kondisi ID</th>
                                <th>Jenis Jalan ID</th>
                                <th>Keterangan</th>
                                <th>Panjang (km)</th>
                                <th>Lebar (m)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ruasJalanDetails as $ruas)
                                <tr>
                                    <td>{{ $ruas['desa_id'] }}</td>
                                    <td>{{ $ruas['kode_ruas'] }}</td>
                                    <td>{{ $ruas['nama_ruas'] }}</td>
                                    <td>
                                        @if($ruas['eksisting_id'] == 1)
                                            Tanah
                                        @elseif($ruas['eksisting_id'] == 2)
                                            Tanah/Beton
                                        @elseif($ruas['eksisting_id'] == 3)
                                            Perkerasan
                                        @elseif($ruas['eksisting_id'] == 4)
                                            Koral
                                        @elseif($ruas['eksisting_id'] == 5)
                                            Lapen
                                        @elseif($ruas['eksisting_id'] == 6)
                                            Paving
                                        @elseif($ruas['eksisting_id'] == 7)
                                            Hotmix
                                        @elseif($ruas['eksisting_id'] == 8)
                                            Beton
                                        @elseif($ruas['eksisting_id'] == 9)
                                            Beton/Lapen
                                        @endif
                                    </td>
                                    <td>
                                        @if($ruas['kondisi_id'] == 1)
                                            Baik
                                        @elseif($ruas['kondisi_id'] == 2)
                                            Sedang
                                        @elseif($ruas['kondisi_id'] == 3)
                                            Rusak
                                        @endif
                                    </td>
                                    <td>
                                        @if($ruas['jenisjalan_id'] == 1)
                                            Desa
                                        @elseif($ruas['jenisjalan_id'] == 2)
                                            Kabupaten
                                        @elseif($ruas['jenisjalan_id'] == 3)
                                            Provinsi
                                        @endif
                                    </td>
                                    <td>{{ $ruas['keterangan'] }}</td>
                                    <td>{{ $ruas['panjang'] }}</td>
                                    <td>{{ $ruas['lebar'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <h5 class="text-center">Silahkan Sign in Terelebih Dahulu</h5>
                @endif
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
                    polylines.forEach(function(polyline) {
                        mymap.removeLayer(polyline);
                    });
                    polylines = [];

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

                            var polyline = L.polyline(ruas.paths, { color: color, weight: 6 }).addTo(mymap); // adjusted weight to 6
                            polylines.push(polyline);

                            polyline.on('click', function(e) {
                                console.log(`Polyline clicked`);
                                var popupContent = `
                                    <div class="popup-content">
                                        <h5>${ruas.nama_ruas}</h5>
                                        <p><strong>Panjang Jalan:</strong> ${ruas.panjang} km</p>
                                        <p><strong>Eksisting ID:</strong> ${ruas.eksisting_id}</p>
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
