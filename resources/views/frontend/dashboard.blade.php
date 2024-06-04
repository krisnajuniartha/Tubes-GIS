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
  <link href="{{ asset('frontend/css/main.css') }}" rel="stylesheet">

<!-- Include leaflet-encoded-polyline plugin -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-encoded-polyline/1.0.0/leaflet.polyline.encoded.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/polyline-encoded@0.0.9/Polyline.encoded.min.js"></script>

</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
        <a href="index.html" class="logo d-flex align-items-center">
            <h1>Ruas Jalan GIS</h1>
        </a>

        <div class="d-flex align-items-center">
          <!-- Link yang mengarah ke form -->
          <a href="{{ route('form') }}" >
            <h1>Add Jalan</h1>
          </a>
        </div>
        

        <i class="mobile-nav-toggle mobile-nav-show bi bi-list"><span>Add Jalan</span></i>
        <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>
        <nav id="navbar" class="navbar">
            <li class="nav-item dropdown pe-3">
                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <img src="{{ asset('frontend/img/krisnaa.jpg') }}" alt="Profile" class="rounded-circle img-thumbnail" style="width: 40px; height: 40px;">
                    <span class="d-none d-md-block dropdown-toggle ps-2">User</span>
                </a><!-- End Profile Image Icon -->

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <h6>Krisna</h6>
                        <span>Web Designer</span>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <a class="dropdown-item d-flex align-items-center" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Sign Out</span>
                        </a>
                    </li>
                </ul><!-- End Profile Dropdown Items -->
            </li><!-- End Profile Nav -->
        </nav><!-- .navbar -->
    </div>
</header><!-- End Header -->

  <!-- ======= Hero Section ======= -->
  <section id="hero" class="d-flex flex-column justify-content-center align-items-center">
    <div class="hero-container" data-aos="fade-in">
      <h1>GIS Ruas Jalan</h1>
      <p>by Krisna<span class="typed"></span></p>
    </div>
  </section><!-- End Hero -->

  <main id="main">
    <div class="map-container">
        <div class="section-title">
            <h2>Leaflet Map</h2>
        </div>
        <div id="mapid" style="height: 500px;"></div>
        <div id="placesTableContainer" class="mt-3">
            <h3>Places</h3>
            <table id="placesTable" class="table">
                <thead>
                    <tr>
                        <th>Nama Ruas</th>
                        <th>Panjang Jalan (km)</th>
                        <th>Eksisting ID</th>
                        <th>Kondisi ID</th>
                        <th>Jenis Jalan ID</th>
                        <th>Keterangan</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="placesTableBody">
                    <!-- Tempat untuk menambahkan baris data tempat secara dinamis -->
                </tbody>
            </table>
        </div>
        <button id="getRuasJalanButton" class="btn btn-primary mt-3">Get Ruas Jalan</button>
    </div>
</main>

<script>
    var mymap = L.map('mapid').setView([-8.790008703311203, 115.16780687368956], 13);
    var mapbiasa = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
        maxZoom: 18,
    }).addTo(mymap);

    var getRuasJalanButton = document.getElementById('getRuasJalanButton');
    var polylineGroup = L.layerGroup().addTo(mymap);

    getRuasJalanButton.addEventListener('click', function() {
        fetchRuasJalan();
    });

    function fetchRuasJalan() {
    var apiToken = "{{ Session::get('api_token') }}";
    fetch("https://gisapis.manpits.xyz/api/ruasjalan", {
        headers: {
            'Authorization': 'Bearer ' + apiToken
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('API Response:', data);
        if (data.status === 'success') {
            // Kosongkan tabel sebelum menambahkan baris baru
            document.getElementById('placesTableBody').innerHTML = '';

            data.ruasjalan.forEach(ruas => {
                console.log('Processing ruas:', ruas);
                if (ruas.paths) {
                    let decodedPaths = L.Polyline.fromEncoded(ruas.paths).getLatLngs();
                    console.log('Decoded paths:', ruas.paths, '=>', decodedPaths);
                    if (decodedPaths && decodedPaths.length > 0) {
                        let polyline = L.polyline(decodedPaths, { color: 'blue' }).addTo(polylineGroup);
                        polyline.on('click', function(e) {
                            L.popup()
                                .setLatLng(e.latlng)
                                .setContent(`
                                    <div>
                                        <h5>${ruas.nama_ruas}</h5>
                                        <p><strong>Panjang Jalan:</strong> ${ruas.panjang} km</p>
                                        <p><strong>Eksisting ID:</strong> ${ruas.eksisting_id}</p>
                                        <p><strong>Kondisi ID:</strong> ${ruas.kondisi_id}</p>
                                        <p><strong>Jenis Jalan ID:</strong> ${ruas.jenisjalan_id}</p>
                                        <p><strong>Keterangan:</strong> ${ruas.keterangan}</p>
                                        <button class="btn btn-warning btn-sm">Edit</button>
                                        <button class="btn btn-danger btn-sm">Delete</button>
                                    </div>
                                `)
                                .openOn(mymap);
                        });

                        // Tambahkan baris ke tabel
                        document.getElementById('placesTableBody').innerHTML += `
                            <tr>
                                <td>${ruas.nama_ruas}</td>
                                <td>${ruas.panjang}</td>
                                <td>${ruas.eksisting_id}</td>
                                <td>${ruas.kondisi_id}</td>
                                <td>${ruas.jenisjalan_id}</td>
                                <td>${ruas.keterangan}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm">Edit</button>
                                    <button class="btn btn-danger btn-sm">Delete</button>
                                </td>
                            </tr>
                        `;
                    } else {
                        console.error('Empty or invalid coordinates for ruas:', ruas.paths);
                    }
                } else {
                    console.error('No paths attribute found for ruas:', ruas);
                }
            });
        } else {
            console.error('Failed to fetch ruas jalan data:', data);
        }
    })
    .catch(error => console.error('Error fetching data:', error));
}

</script>


<!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="container">
      <div class="row gy-4">
        <div class="col-lg-5 col-md-12 footer-info">
          <a href="index.html" class="logo d-flex align-items-center">
            <span>Ruas Jalan Bali</span>
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
  </footer><!-- End Footer -->

  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

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

  <script>
    document.addEventListener('DOMContentLoaded', function() {
        var apiToken = "{{ Session::get('api_token') }}";
        console.log('API Token:', apiToken);
    });
  </script>

</body>

</html>
