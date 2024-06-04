<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.3/dist/leaflet.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <title>Tugas GIS</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
  integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
  crossorigin=""/>

  <!-- Make sure you put this AFTER Leaflet's CSS -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
  integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
  crossorigin=""></script>

  <!-- Favicons -->
  <link href="{{ asset('frontend/img/icon_web.png') }}" rel="icon">
  <link href="{{ asset('frontend/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="{{ asset('https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap') }}" rel="stylesheet">

  <link href="{{ asset('frontend/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('frontend/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet ">
  <link href="{{ asset('frontend/vendor/fontawesome-free/css/all.min.css') }} " rel="stylesheet">
  <link href="{{ asset('frontend/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset('frontend/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
  <link href="{{ asset('frontend/vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{ asset('frontend/css/form.css') }}" rel="stylesheet">
</head>

<body>
    <main id="main">
        <div class="map-container">
            <div class="section-title">
                <h2>Leaflet Map</h2>
            </div>
            <div id="mapid"></div>
        </div>
        <div class="form-container">
            <div class="section-title">
                <h2>Input New Marker</h2>
            </div>
            <form id="markerForm">
                @csrf
                <div class="mb-3">
                    <label for="kode_ruas" class="form-label">Kode Ruas</label>
                    <input type="text" class="form-control" id="kode_ruas" name="kode_ruas" required>
                </div>
                <div class="mb-3">
                    <label for="nama_ruas" class="form-label">Nama Ruas</label>
                    <input type="text" class="form-control" id="nama_ruas" name="nama_ruas" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="panjang" class="form-label">Panjang</label>
                        <input type="number" step="0.01" class="form-control" id="panjang" name="panjang" readonly required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lebar" class="form-label">Lebar</label>
                        <input type="number" step="0.01" class="form-control" id="lebar" name="lebar" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="paths" class="form-label">Paths</label>
                    <input type="text" class="form-control" id="paths" name="paths" readonly required>
                </div>
                <div class="mb-3">
                    <label for="provinsi" class="form-label">Provinsi</label>
                    <select id="provinsi" class="form-control" required>
                        <option value="">Pilih Provinsi</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="kabupaten" class="form-label">Kabupaten</label>
                    <select id="kabupaten" class="form-control" required>
                        <option value="">Pilih Kabupaten</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="kecamatan" class="form-label">Kecamatan</label>
                    <select id="kecamatan" class="form-control" required>
                        <option value="">Pilih Kecamatan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="eksisting_id" class="form-label">Eksisting ID</label>
                    <input type="number" class="form-control" id="eksisting_id" name="eksisting_id" required>
                </div>
                <div class="mb-3">
                    <label for="kondisi_id" class="form-label">Kondisi ID</label>
                    <input type="number" class="form-control" id="kondisi_id" name="kondisi_id" required>
                </div>
                <div class="mb-3">
                    <label for="jenisjalan_id" class="form-label">Jenis Jalan ID</label>
                    <input type="number" class="form-control" id="jenisjalan_id" name="jenisjalan_id" required>
                </div>
                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <input type="text" class="form-control" id="keterangan" name="keterangan" required>
                </div>
                <button type="button" id="saveButton" class="btn btn-primary">Save Polyline</button>
            </form>
        </div>
    </main><!-- End #main -->

    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.3/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/polyline/1.1.1/polyline.js"></script>
    <script>
        var mymap = L.map('mapid').setView([-8.790008703311203, 115.16780687368956], 13);

        var mapbiasa = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
            maxZoom: 18,
        }).addTo(mymap);

        var polylinePoints = [];
        var polyline = L.polyline(polylinePoints, {color: 'red'}).addTo(mymap);

        mymap.on('click', function(e) {
            polylinePoints.push([e.latlng.lat, e.latlng.lng]);
            polyline.setLatLngs(polylinePoints);
            updatePolylineData();
            L.popup()
                .setLatLng(e.latlng)
                .setContent("Lat: " + e.latlng.lat + "<br>Lng: " + e.latlng.lng)
                .openOn(mymap);
        });

        function updatePolylineData() {
            let encodedPath = polyline.encode(polylinePoints);
            document.getElementById('paths').value = encodedPath;
            document.getElementById('panjang').value = calculatePolylineLength(polylinePoints);
        }

        function calculatePolylineLength(points) {
            let totalLength = 0;
            for (let i = 1; i < points.length; i++) {
                totalLength += getDistance(points[i-1], points[i]);
            }
            return totalLength.toFixed(6);
        }

        function getDistance(point1, point2) {
            const R = 6371000; // Radius of the Earth in meters
            const dLat = (point2[0] - point1[0]) * Math.PI / 180;
            const dLng = (point2[1] - point1[1]) * Math.PI / 180;
            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                      Math.cos(point1[0] * Math.PI / 180) * Math.cos(point2[0] * Math.PI / 180) *
                      Math.sin(dLng/2) * Math.sin(dLng/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            return R * c;
        }

        // Function to fetch and populate dropdowns
        function populateDropdown(endpoint, dropdownElement, keyName) {
            fetch(endpoint)
                .then(response => response.json())
                .then(data => {
                    dropdownElement.innerHTML = '<option value="">Pilih ' + keyName + '</option>';
                    data.forEach(item => {
                        dropdownElement.innerHTML += '<option value="' + item.id + '">' + item[keyName] + '</option>';
                    });
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        // Populate Provinsi dropdown
        populateDropdown('https://gisapis.manpits.xyz/api/provinsi/1', document.getElementById('provinsi'), 'provinsi');

        // Event listener for Provinsi dropdown change
        document.getElementById('provinsi').addEventListener('change', function () {
            var provinsiId = this.value;
            var kabupatenDropdown = document.getElementById('kabupaten');
            populateDropdown('https://gisapis.manpits.xyz/api/kabupaten/' + provinsiId, kabupatenDropdown, 'kabupaten');
        });

        // Event listener for Kabupaten dropdown change
        document.getElementById('kabupaten').addEventListener('change', function () {
            var kabupatenId = this.value;
            var kecamatanDropdown = document.getElementById('kecamatan');
            populateDropdown('https://gisapis.manpits.xyz/api/kecamatan/' + kabupatenId, kecamatanDropdown, 'kecamatan');
        });

        // Function to save polyline
        function savePolyline() {
            if (polylinePoints.length < 2) {
                alert('Please create at least two points to form a polyline.');
                return;
            }

            let encodedPath = polyline.encode(polylinePoints);
            let formData = new FormData(document.getElementById('markerForm'));
            formData.append('paths', encodedPath);

            fetch('{{ route('ruasjalan.store') }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert('Polyline saved successfully!');
                console.log(data);
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        document.getElementById('saveButton').addEventListener('click', savePolyline);
    </script>

    <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <div id="preloader"></div>
    <script src="{{ asset('frontend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('frontend/vendor/purecounter/purecounter_vanilla.js') }}"></script>
    <script src="{{ asset('frontend/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('frontend/vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/typed.js/typed.umd.js') }}"></script>
    <script src="{{ asset('frontend/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('frontend/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('frontend/js/main.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var apiToken = "{{ Session::get('api_token') }}";
            console.log('API Token:', apiToken);
        });
    </script>
</body>

</html>
