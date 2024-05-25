<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Register</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ asset('frontend/img/icon_web.png') }}" rel="icon">
    <link href="{{ asset('auth/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="{{ asset('https://fonts.gstatic.com') }}" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('auth/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('auth/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('auth/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('auth/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('auth/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('auth/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('auth/vendor/simple-datatables/style.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('auth/css/style.css') }}" rel="stylesheet">

</head>

<body>

    <main>
        <section class="vh-100">
            <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 text-black">
        
                <div class="px-5 ms-xl-4">
                    <i class="fas fa-crow fa-2x me-3 pt-5 mt-xl-4" style="color: #709085;"></i>
                    <span class="h1 fw-bold mb-0">Ruas Jalan Bali</span>
                </div>
        
                <div class="d-flex align-items-center h-custom-2 px-5 ms-xl-4 mt-5 pt-5 pt-xl-0 mt-xl-n5">
        
                    <form style="width: 23rem;" method="POST" action="{{ url('/register') }}">
                        @csrf
                        <h3 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Register</h3>
                        
                        <div data-mdb-input-init class="form-outline mb-4">
                            <label for="name" class="form-label">Your Name</label>
                            <input type="text" name="name" class="form-control form-control-lg" id="name" required>
                            <div class="invalid-feedback">Please, enter your name!</div>
                        </div>
                        
                        <div data-mdb-input-init class="form-outline mb-4">
                            <label for="email" class="form-label">Your Email</label>
                            <input type="email" name="email" class="form-control form-control-lg" id="email" required>
                            <div class="invalid-feedback">Please enter a valid Email address!</div>
                        </div>
                    
                        <div data-mdb-input-init class="form-outline mb-4">
                            <label for="password" class="form-label">Your Password</label>
                            <input type="password" name="password" class="form-control form-control-lg" id="password" required>
                            <div class="invalid-feedback">Please enter your password!</div>
                        </div>
                    
                        <div data-mdb-input-init class="form-outline mb-4">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control form-control-lg" id="password_confirmation" required>
                            <div class="invalid-feedback">Please confirm your password!</div>
                        </div>
                    
                        <div class="pt-1 mb-4">
                            <button data-mdb-button-init data-mdb-ripple-init class="btn btn-info btn-lg btn-block" type="submit">Register</button>
                        </div>
                    
                        <p class="small mb-5 pb-lg-2"><a class="text-muted" href="#!">Forgot password?</a></p>
                        
                        <div class="col-12">
                            <p class="small mb-5 pb-lg-2">Already have an account? <a href="{{ url('/login') }}">Log in</a></p>
                        </div>
                    </form>
                    
        
                </div>
        
                </div>
                <div class="col-sm-6 px-0 d-none d-sm-block">
                <img src="https://facts.net/wp-content/uploads/2023/06/44-facts-about-indonesia-1688112759.jpeg"
                    alt="Login image" class="w-100 vh-100" style="object-fit: cover; object-position: left;">
                </div>
            </div>
            </div>
        </section>
    </main><!-- End #main -->

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="{{ asset('backend/assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/chart.js/chart.umd.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/echarts/echarts.min.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/quill/quill.min.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('backend/assets/vendor/php-email-form/validate.js') }}"></script>

<!-- Template Main JS File -->
<script src="{{ asset('backend/assets/js/main.js') }}"></script>

</body>

</html>