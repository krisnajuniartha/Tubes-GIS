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
  <link href="{{ asset('https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap') }}" rel="stylesheet">

  <link href="{{ asset('frontend/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

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
            <form method="post" action="{{route('dashboard')}}">
                @csrf
                <div class="mb-3">
                    <label for="kode_ruas" class="form-label">Kode Ruas</label>
                    <input type="text" class="form-control" id="kode_ruas" name="kode_ruas" required>
                </div>
                <div class="mb-3">
                    <label for="nama_ruas" class="form-label">Nama Ruas</label>
                    <input type="text" class="form-control" id="nama_ruas" name="nama_ruas" required>
                </div>
                <div class="mb-3">
                    <label for="panjang" class="form-label">Panjang</label>
                    <input type="number" step="0.01" class="form-control" id="panjang" name="panjang" required>
                </div>
                <div class="mb-3">
                    <label for="lebar" class="form-label">Lebar</label>
                    <input type="number" step="0.01" class="form-control" id="lebar" name="lebar" required>
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
                <button id="saveButton" class="btn btn-primary">Save Polyline</button>
            </form>
        </div>
    </main> <!-- End #main -->


    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.3/dist/leaflet.min.js"></script>
    <script>
        // Menampilkan peta
        var mymap = L.map('mapid').setView([-8.790008703311203, 115.16780687368956], 13);
    
        // Map layer
        var mapbiasa = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
            maxZoom: 18,
        }).addTo(mymap);
    
        // Polyline related variables
        var polylinePoints = [];
        var polyline = L.polyline(polylinePoints, {color: 'red'}).addTo(mymap);
    
        // Tambahkan event listener click pada peta untuk membuat polyline
        mymap.on('click', function(e) {
            polylinePoints.push([e.latlng.lat, e.latlng.lng]);
            polyline.setLatLngs(polylinePoints);
            L.popup()
                .setLatLng(e.latlng)
                .setContent("Lat: " + e.latlng.lat + "<br>Lng: " + e.latlng.lng)
                .openOn(mymap);
        });
    
        // Function to encode polyline
        function encodePolyline(points) {
            let encoded = '';
            let prevLat = 0, prevLng = 0;
    
            for (let i = 0; i < points.length; i++) {
                let lat = Math.round(points[i][0] * 1e5);
                let lng = Math.round(points[i][1] * 1e5);
    
                encoded += encodeNumber(lat - prevLat);
                encoded += encodeNumber(lng - prevLng);
    
                prevLat = lat;
                prevLng = lng;
            }
    
            return encoded;
        }
    
        function encodeNumber(num) {
            num = num < 0 ? ~(num << 1) : num << 1;
            let encoded = '';
    
            while (num >= 0x20) {
                encoded += String.fromCharCode((0x20 | (num & 0x1f)) + 63);
                num >>= 5;
            }
    
            encoded += String.fromCharCode(num + 63);
            return encoded;
        }
    
        // Function to save polyline
        function savePolyline() {
            if (polylinePoints.length < 2) {
                alert('Please create at least two points to form a polyline.');
                return;
            }
    
            let paths = encodePolyline(polylinePoints);
            let desa_id = document.getElementById('desa_id').value;
            let kode_ruas = document.getElementById('kode_ruas').value;
            let nama_ruas = document.getElementById('nama_ruas').value;
            let panjang = document.getElementById('panjang').value;
            let lebar = document.getElementById('lebar').value;
            let eksisting_id = document.getElementById('eksisting_id').value;
            let kondisi_id = document.getElementById('kondisi_id').value;
            let jenisjalan_id = document.getElementById('jenisjalan_id').value;
            let keterangan = document.getElementById('keterangan').value;
    
            let data = {
                paths: paths,
                desa_id: desa_id,
                kode_ruas: kode_ruas,
                nama_ruas: nama_ruas,
                panjang: panjang,
                lebar: lebar,
                eksisting_id: eksisting_id,
                kondisi_id: kondisi_id,
                jenisjalan_id: jenisjalan_id,
                keterangan: keterangan
            };
    
            fetch('{{ route('ruasjalan.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
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
    
        // Add event listener to the save button
        document.getElementById('saveButton').addEventListener('click', savePolyline);
    </script>


  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">

    <div class="container">
      <div class="row gy-4">
        <div class="col-lg-5 col-md-12 footer-info">
          <a href="index.html" class="logo d-flex align-items-center">
            <span>Krisna Juniartha</span>
          </a>
        
          <div class="social-links d-flex mt-4">
            <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
            <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
            <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
            <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
          </div>
        </div>

  
      </div>
    </div>

    <div class="container mt-4">
      <div class="copyright">
        &copy; Copyright <strong><span>Logis</span></strong>. All Rights Reserved
      </div>
    
    </div>

  </footer><!-- End Footer -->

  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="{{ asset('frontend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('frontend/vendor/purecounter/purecounter_vanilla.js') }}"></script>
  <script src="{{ asset('frontend/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ asset('frontend/vendor/swiper/swiper-bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/typed.js/typed.umd.js') }}"></script>
  <script src="{{ asset('frontend/vendor/aos/aos.js') }}"></script>
  <script src="{{ asset('frontend/vendor/php-email-form/validate.js') }}"></script>

  <!-- Template Main JS File -->
  <script src="{{ asset('frontend/js/main.js') }}"></script>


</body>

</html>