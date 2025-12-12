<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Iuran Warga - RT 05/RW 02</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        /* Reset dan Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        
        a {
            text-decoration: none;
            color: inherit;
        }
        
        ul {
            list-style: none;
        }
        
        /* Layout Styles */
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -15px;
        }
        
        .col-md-4, .col-md-6, .col-md-8 {
            padding: 0 15px;
        }
        
        .col-md-4 {
            width: 33.333%;
        }
        
        .col-md-6 {
            width: 50%;
        }
        
        .col-md-8 {
            width: 66.666%;
        }
        
        /* Navigation */
        .navbar {
            background-color: #0d6efd;
            color: white;
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .navbar-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            display: flex;
            align-items: center;
        }
        
        .navbar-brand i {
            margin-right: 10px;
        }
        
        .navbar-nav {
            display: flex;
        }
        
        .nav-item {
            margin-left: 20px;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 8px 0;
            transition: opacity 0.3s;
        }
        
        .nav-link:hover {
            opacity: 0.8;
        }
        
        .nav-link i {
            margin-right: 5px;
        }
        
        .navbar-toggler {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://via.placeholder.com/1920x600');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            margin-bottom: 50px;
            text-align: center;
        }
        
        .hero-section h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }
        
        .hero-section p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        
        /* Button Styles */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: 500;
            transition: all 0.3s;
            border: 2px solid transparent;
        }
        
        .btn-primary {
            background-color: #0d6efd;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #0b5ed7;
        }
        
        .btn-outline-light {
            background-color: transparent;
            color: white;
            border-color: white;
        }
        
        .btn-outline-light:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .btn-lg {
            padding: 12px 24px;
            font-size: 1.1rem;
        }
        
        /* Card Styles */
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .card-body {
            padding: 20px;
            flex-grow: 1;
            text-align: center;
        }
        
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #0d6efd;
        }
        
        /* Section Styles */
        section {
            padding: 60px 0;
        }
        
        .bg-light {
            background-color: #f8f9fa;
        }
        
        .bg-dark {
            background-color: #212529;
            color: white;
        }
        
        .text-center {
            text-align: center;
        }
        
        /* About Section */
        .list-group {
            margin: 20px 0;
        }
        
        .list-group-item {
            padding: 10px 0;
            display: flex;
            align-items: center;
            background-color: transparent;
        }
        
        .list-group-item i {
            margin-right: 10px;
        }
        
        .img-fluid {
            max-width: 100%;
            height: auto;
        }
        
        .rounded {
            border-radius: 8px;
        }
        
        /* Footer */
        footer {
            padding: 40px 0;
        }
        
        footer hr {
            border-color: rgba(255, 255, 255, 0.1);
            margin: 20px 0;
        }
        
        .text-end {
            text-align: right;
        }
        
        /* Utility Classes */
        .mb-0 {
            margin-bottom: 0;
        }
        
        .mb-4 {
            margin-bottom: 1.5rem;
        }
        
        .mb-5 {
            margin-bottom: 3rem;
        }
        
        .py-4 {
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }
        
        .py-5 {
            padding-top: 3rem;
            padding-bottom: 3rem;
        }
        
        .me-1 {
            margin-right: 0.25rem;
        }
        
        .me-2 {
            margin-right: 0.5rem;
        }
        
        .me-2 {
            margin-right: 0.5rem;
        }
        
        .align-items-center {
            align-items: center;
        }
        
        .justify-content-center {
            justify-content: center;
        }
        
        .justify-content-sm-center {
            justify-content: center;
        }
        
        .d-flex {
            display: flex;
        }
        
        .d-sm-flex {
            display: flex;
        }
        
        .gap-3 {
            gap: 1rem;
        }
        
        /* Responsive Styles */
        @media (max-width: 992px) {
            .col-md-4, .col-md-6, .col-md-8 {
                width: 100%;
                margin-bottom: 20px;
            }
            
            .navbar-nav {
                display: none;
                flex-direction: column;
                width: 100%;
                margin-top: 15px;
            }
            
            .navbar-nav.active {
                display: flex;
            }
            
            .nav-item {
                margin: 5px 0;
            }
            
            .navbar-toggler {
                display: block;
            }
            
            .row {
                flex-direction: column;
            }
            
            .text-end {
                text-align: left;
                margin-top: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container navbar-container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-home"></i>Sistem Iuran Warga
            </a>
            <button class="navbar-toggler" id="navbarToggler">
                <i class="fas fa-bars"></i>
            </button>
            <ul class="navbar-nav" id="navbarNav">
                <li class="nav-item">
                    <a class="nav-link" href="#about">Tentang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#features">Fitur</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/warga/register">
                        <i class="fas fa-user-plus"></i>Daftar
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/warga/login">
                        <i class="fas fa-sign-in-alt"></i>Login Warga
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin/login">
                        <i class="fas fa-lock"></i>Login Admin
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1>Sistem Iuran Warga Digital</h1>
            <p>Membangun kebersamaan melalui sistem pembayaran iuran yang transparan dan terpercaya</p>
            <div>
                <a href="/warga/register" class="btn btn-primary btn-lg">
                    <i class="fas fa-user-plus"></i>Daftar sebagai Warga
                </a>
                <a href="#features" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-info-circle"></i>Pelajari Fitur
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features">
        <div class="container">
            <h2 class="text-center mb-5">Fitur Unggulan Sistem Kami</h2>
            
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="feature-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <h3>Pembayaran Online</h3>
                            <p>Bayar iuran kapan saja dan dimana saja melalui transfer bank atau e-wallet</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="feature-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h3>Laporan Transparan</h3>
                            <p>Pantau alur keuangan iuran warga secara real-time dan transparan</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="feature-icon">
                                <i class="fas fa-bell"></i>
                            </div>
                            <h3>Notifikasi Otomatis</h3>
                            <p>Dapatkan pemberitahuan pembayaran dan informasi penting via WhatsApp</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2>Tentang Sistem Ini</h2>
                    <p>Sistem Iuran Warga RT 05/RW 02 dikembangkan untuk memudahkan pengelolaan keuangan lingkungan secara digital. Dengan sistem ini, seluruh warga dapat memantau dan membayar iuran dengan lebih mudah, sementara pengurus RT dapat mengelola data dengan lebih efisien.</p>
                    <ul class="list-group">
                        <li class="list-group-item"><i class="fas fa-check-circle text-success"></i> Transparansi keuangan</li>
                        <li class="list-group-item"><i class="fas fa-check-circle text-success"></i> Pembayaran fleksibel</li>
                        <li class="list-group-item"><i class="fas fa-check-circle text-success"></i> Data terproteksi</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <img src="https://via.placeholder.com/600x400" alt="About System" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </section>

    <!-- Login Section -->
    <section>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <h2 class="mb-4">Mulai Menggunakan Sistem</h2>
                    <div class="d-sm-flex justify-content-sm-center gap-3">
                        <a href="/warga/register" class="btn btn-primary btn-lg">
                            <i class="fas fa-user-plus"></i>Daftar sebagai Warga
                        </a>
                        <a href="/warga/login" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-sign-in-alt"></i>Login sebagai Warga
                        </a>
                        <a href="/admin/login" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-lock"></i>Login sebagai Admin
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-home"></i> Sistem Iuran Warga</h5>
                    <p><i class="fas fa-map-marker-alt"></i> RT 05/RW 02, Kelurahan Contoh, Kecamatan Sample, Kota Demo</p>
                </div>
                <div class="col-md-6 text-end">
                    <h5><i class="fas fa-phone-alt"></i> Kontak Kami</h5>
                    <p><i class="fas fa-phone"></i> (021) 12345678</p>
                    <p><i class="fas fa-envelope"></i> iuranwarga@contoh.com</p>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p class="mb-0">&copy; 2023 Sistem Iuran Warga. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Toggle mobile menu
        document.getElementById('navbarToggler').addEventListener('click', function() {
            document.getElementById('navbarNav').classList.toggle('active');
        });
        
        // Toggle password visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.nextElementSibling.querySelector('i');
            
            if (field.type === "password") {
                field.type = "text";
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                field.type = "password";
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        // Form submission handling
        document.getElementById('registrationForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form values
            const nama = document.getElementById('nama').value;
            const blokRt = document.getElementById('blok_rt').value;
            const noHp = document.getElementById('no_hp').value;
            const pelakuUsaha = document.getElementById('pelaku_usaha').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            // Validate form
            if (password.length < 8) {
                Swal.fire({
                    icon: 'error',
                    title: 'Password terlalu pendek',
                    text: 'Password minimal harus 8 karakter!',
                    confirmButtonColor: '#0d6efd'
                });
                return;
            }
            
            if (password !== confirmPassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'Password tidak cocok',
                    text: 'Password dan konfirmasi password harus sama!',
                    confirmButtonColor: '#0d6efd'
                });
                return;
            }
            
            if (!document.getElementById('agreeTerms').checked) {
                Swal.fire({
                    icon: 'error',
                    title: 'Persetujuan diperlukan',
                    text: 'Anda harus menyetujui syarat dan ketentuan!',
                    confirmButtonColor: '#0d6efd'
                });
                return;
            }
            
            // Kirim data ke server menggunakan Fetch API
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Tampilkan notifikasi sukses
                    Swal.fire({
                        icon: 'success',
                        title: 'Pendaftaran Berhasil!',
                        text: 'Data Anda telah berhasil disimpan. Silakan login dengan akun Anda.',
                        confirmButtonColor: '#0d6efd',
                        willClose: () => {
                            // Reset form
                            this.reset();
                            // Redirect ke halaman login
                            window.location.href = '/warga/login';
                        }
                    });
                } else {
                    // Tampilkan error dari server
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Mendaftar',
                        text: data.message || 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.',
                        confirmButtonColor: '#0d6efd'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: 'Terjadi kesalahan saat mengirim data. Silakan coba lagi.',
                    confirmButtonColor: '#0d6efd'
                });
            });
        });

        // Tampilkan notifikasi jika ada pesan dari session (untuk redirect dari backend)
        if (typeof Swal !== 'undefined') {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#0d6efd'
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#0d6efd'
                });
            @endif
        }
    </script>
</body>
</html>