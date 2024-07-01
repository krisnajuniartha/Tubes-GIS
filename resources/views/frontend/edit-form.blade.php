@extends('layout')

@section('content')
    @if (isset($ruasJalanDetails) && !empty($ruasJalanDetails))
        <div id="ruasJalanDetails" style="display: none;">{{ json_encode($ruasJalanDetails) }}</div>
    @endif
    <section class="page-section" id="map-form-section">
        <div class="container">
            <div class="row">
                <div class="col-md-8 map-container">
                    <!-- Left Side (Map) -->
                    <div class="p-4">
                        <h2>Edit Map</h2>
                        <div id="map" class="map"></div>
                        <button type="button" class="btn btn-secondary" id="resetMap">Reset</button>
                    </div>
                </div>
                <div class="col-md-4 form-container">
                    <!-- Right Side (Form) -->
                    <div class="p-4">
                        <h2>Edit</h2>
                        <form id="ruasForm" method="POST" class="ruas-jalan-form" action="">
                            @csrf
                            @method('PUT') <!-- Hidden field to spoof PUT method -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="id_ruas">ID Ruas:</label>
                                        <input type="text" id="id_ruas" name="id_ruas" class="form-control" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="provinsi_id">Provinsi:</label>
                                        <select id="provinsi_id" name="provinsi_id" class="form-control">
                                            <option value="">Pilih Provinsi</option>
                                            <option value="1">Bali</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="kabupaten_id">Kabupaten:</label>
                                        <select id="kabupaten_id" name="kabupaten_id" class="form-control">
                                            <option value="">Pilih Kabupaten</option>
                                            <option value="1">Jembrana</option>
                                            <option value="2">Tabanan</option>
                                            <option value="3">Badung</option>
                                            <option value="4">Denpasar</option>
                                            <option value="5">Buleleng</option>
                                            <option value="6">Gianyar</option>
                                            <option value="7">Bangli</option>
                                            <option value="8">Klungkung</option>
                                            <option value="9">Karangasem</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="nama_ruas">Nama Ruas:</label>
                                        <input type="text" id="nama_ruas" name="nama_ruas" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="eksisting_id">Eksisting ID:</label>
                                        <select id="eksisting_id" name="eksisting_id" class="form-control">
                                            <option value="">Pilih Eksisting</option>
                                            <option value="1">Tanah</option>
                                            <option value="2">Tanah/Beton</option>
                                            <option value="3">Perkerasan</option>
                                            <option value="4">Koral</option>
                                            <option value="5">Lapen</option>
                                            <option value="6">Paving</option>
                                            <option value="7">Hotmix</option>
                                            <option value="8">Beton</option>
                                            <option value="9">Beton/Lapen</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="jenisjalan_id">Jenis Jalan:</label>
                                        <select id="jenisjalan_id" name="jenisjalan_id" class="form-control">
                                            <option value="">Pilih Jenis Jalan</option>
                                            <option value="1">Jalan Desa</option>
                                            <option value="2">Jalan Kabupaten</option>
                                            <option value="3">Jalan Provinsi</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="kondisi_id">Kondisi:</label>
                                        <select id="kondisi_id" name="kondisi_id" class="form-control">
                                            <option value="">Pilih Kondisi</option>
                                            <option value="1">Kondisi Baik</option>
                                            <option value="2">Kondisi Sedang</option>
                                            <option value="3">Kondisi Rusak</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="desa_id">ID Desa:</label>
                                        <input type="text" id="desa_id" name="desa_id" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="paths_get">Path:</label>
                                        <input type="text" id="paths_get" name="paths_get" class="form-control" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="kecamatan_id">Kecamatan:</label>
                                        <input type="text" id="kecamatan_id" name="kecamatan_id" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="desa_nama">Desa:</label>
                                        <input type="text" id="desa_nama" name="desa_nama" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="kode_ruas">Kode Ruas:</label>
                                        <input type="text" id="kode_ruas" name="kode_ruas" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="panjang">Panjang (KM):</label>
                                        <input type="text" id="panjang" name="panjang" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="lebar">Lebar:</label>
                                        <input type="number" id="lebar" name="lebar" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="keterangan">Keterangan:</label>
                                <input type="text" id="keterangan" name="keterangan" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-warning btn-sm">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            var map = L.map('map').setView([-8.4095188, 115.188919], 10);
            var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            var markers = [];
            var currentPolyline = null;
            var canAddMarker = true;
            var isOnDrag = false;
            var currentRuasId = null;

            var ruasJalanDetails = JSON.parse(document.getElementById('ruasJalanDetails').textContent);
            console.log(ruasJalanDetails);

            var polycolors = ['blue', 'green', 'purple', 'orange', 'yellow', 'pink', 'brown', 'black'];

            ruasJalanDetails.forEach(function(ruas, index) {
                var color = polycolors[index % polycolors.length];
                var polyline = L.polyline(ruas.paths, {
                    color: color,
                    weight: 6 
                }).addTo(map);

                var polylineText = L.tooltip({
                    permanent: true,
                    direction: 'center',
                    className: 'kode-ruas-label'
                })
                .setContent(ruas.kode_ruas)
                .setLatLng(polyline.getBounds().getCenter())
                .addTo(map);

                polyline.on('click', function(e) {
                    console.log(`Polyline ${index + 1} selected`);
                    // var popupContent = `
                    //     <div class="popup-content">
                    //         <p>ID Ruas: ${ruas.id}</p>
                    //         <p>Kode Ruas: ${ruas.kode_ruas}</p>
                    //         <p>Nama Ruas: ${ruas.nama_ruas}</p>
                    //         <p>Panjang: ${ruas.panjang}</p>
                    //         <p>Lebar: ${ruas.lebar}</p>
                    //         <p>Keterangan: ${ruas.keterangan}</p>
                    //     </div>
                    // `;
                    // L.popup()
                    //     .setLatLng(e.latlng)
                    //     .setContent(popupContent)
                    //     .openOn(map);

                    document.getElementById('id_ruas').value = ruas.id;
                    document.getElementById('paths_get').value = ruas.paths2;
                    document.getElementById('desa_id').value = ruas.desa_id;
                    document.getElementById('kode_ruas').value = ruas.kode_ruas;
                    document.getElementById('nama_ruas').value = ruas.nama_ruas;
                    document.getElementById('panjang').value = ruas.panjang;
                    document.getElementById('lebar').value = ruas.lebar;
                    document.getElementById('eksisting_id').value = ruas.eksisting_id;
                    document.getElementById('kondisi_id').value = ruas.kondisi_id;
                    document.getElementById('jenisjalan_id').value = ruas.jenisjalan_id;
                    document.getElementById('keterangan').value = ruas.keterangan;

                    currentRuasId = ruas.id;

                    // Remove existing markers and polyline
                    clearMarkers();
                    clearPolyline();

                    // Add markers for the clicked polyline
                    ruas.paths.forEach(function(latlng, i) {
                        addMarker(latlng, i);
                    });

                    updatePolyline();
                    updatePathsInput();
                    updateFormAction();
                });
            });

            function updateFormAction() {
                var form = document.getElementById('ruasForm');
                form.action = `/ruasjalan/update/${currentRuasId}`;
            }

            function addMarker(latlng, index) {
                var marker = L.marker(latlng, {
                    draggable: true
                }).addTo(map);

                var popup = L.popup({
                    offset: [0, -30]
                });

                marker.bindPopup(popup);

                marker.on('click', function(event) {
                    popup.setLatLng(marker.getLatLng()).setContent(formatContent(marker.getLatLng().lat, marker.getLatLng().lng, index)).openOn(map);
                });

                marker.on('dragstart', function(event) {
                    isOnDrag = true;
                });

                marker.on('drag', function(event) {
                    popup.setLatLng(marker.getLatLng()).setContent(formatContent(marker.getLatLng().lat, marker.getLatLng().lng, index));
                });

                marker.on('dragend', function(event) {
                    updatePolyline();
                    setTimeout(function() {
                        isOnDrag = false;
                    }, 500);
                });

                marker.on('contextmenu', function(event) {
                    map.removeLayer(marker);
                    markers.splice(index, 1);
                    updatePolyline();
                    updatePathsInput();
                });

                markers.push(marker);
            }

            function clearMarkers() {
                markers.forEach(function(marker) {
                    map.removeLayer(marker);
                });
                markers = [];
            }

            function clearPolyline() {
                if (currentPolyline) {
                    map.removeLayer(currentPolyline);
                    currentPolyline = null;
                }
            }

            function updatePolyline() {
                clearPolyline();
                var latLngs = markers.map(function(marker) {
                    return marker.getLatLng();
                });

                currentPolyline = L.polyline(latLngs, {
                    color: 'red'
                }).addTo(map);
                updatePathsInput();
            }

            function formatContent(lat, lng, index) {
                return `<b>Marker ${index + 1}</b><br>
                    Lat: ${lat.toFixed(5)}<br>
                    Lng: ${lng.toFixed(5)}<br>
                    Right click to delete`;
            }

            function updatePathsInput() {
                var latLngs = markers.map(function(marker) {
                    return marker.getLatLng();
                });
                var encodedPolyline = encodePolyline(latLngs);
                document.getElementById('paths_get').value = encodedPolyline;
                var totalDistance = calculatePolylineDistance(latLngs);
                document.getElementById('panjang').value = totalDistance.toFixed(2);
            }

            function encodePolyline(latlngs) {
                var encoded = '';
                var lastPoint = [0, 0];

                for (var i = 0; i < latlngs.length; i++) {
                    var currentPoint = [
                        Math.round(latlngs[i].lat * 1e5),
                        Math.round(latlngs[i].lng * 1e5)
                    ];

                    var relativePoint = [
                        currentPoint[0] - lastPoint[0],
                        currentPoint[1] - lastPoint[1]
                    ];

                    encoded += encodePoint(relativePoint[0]) + encodePoint(relativePoint[1]);

                    lastPoint = currentPoint;
                }

                return encoded;
            }

            function encodePoint(point) {
                point = point < 0 ? ~(point << 1) : (point << 1);
                var chunks = '';

                while (point >= 0x20) {
                    chunks += String.fromCharCode((0x20 | (point & 0x1f)) + 63);
                    point >>= 5;
                }

                chunks += String.fromCharCode(point + 63);
                return chunks;
            }

            function deg2rad(deg) {
                return deg * (Math.PI / 180);
            }

            function calculatePolylineDistance(latlngs) {
                var totalDistance = 0;

                for (var i = 0; i < latlngs.length - 1; i++) {
                    var lat1 = latlngs[i].lat;
                    var lon1 = latlngs[i].lng;
                    var lat2 = latlngs[i + 1].lat;
                    var lon2 = latlngs[i + 1].lng;

                    var dLat = deg2rad(lat2 - lat1);
                    var dLon = deg2rad(lon2 - lon1);

                    var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                        Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
                        Math.sin(dLon / 2) * Math.sin(dLon / 2);
                    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                    var distance = 6371 * c;

                    totalDistance += distance;
                }

                return totalDistance;
            }

            function submitForm(method) {
            var form = document.getElementById('ruasForm');
            var methodInput = document.getElementById('_method');
            methodInput.value = method;

            if (method === 'DELETE') {
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.action = `/ruasjalan/delete/${currentRuasId}`;
                        form.submit();
                    }
                });
            } else {
                form.submit();
                Swal.fire({
                    title: 'Sukses!',
                    text: 'Form berhasil disimpan',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            }
        }


        
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('resetMap').addEventListener('click', function() {
                    resetMap();
                });

                map.on('click', function(event) {
                    if (!canAddMarker) return;
                    if (!isOnDrag) {
                        addMarker(event.latlng, markers.length);
                        updatePolyline();
                        updatePathsInput();
                    }
                });

                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Delete' || event.key === 'Backspace') {
                        deleteSelectedPolyline();
                    }
                });

                // Event listener for form submission
                document.getElementById('ruasForm').addEventListener('submit', function(event) {
                    event.preventDefault(); // Prevent the default form submission

                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Berhasil mengedit ruas jalan',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        // Submit the form after the alert is shown
                        event.target.submit();
                    });
                });
            });


            function resetMap() {
                clearMarkers();
                clearPolyline();
                document.getElementById('paths_get').value = "";
                document.getElementById('id_ruas').value = "";
                document.getElementById('panjang').value = "";
            }
        </script>
    </section>
@endsection
