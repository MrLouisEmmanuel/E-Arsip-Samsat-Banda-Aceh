<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Arsip Samsat Banda Aceh</title>
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/logo/logo6.png">
    <!-- Modern CSS -->
    <link rel="stylesheet" href="assets/css/modern-style.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- Navbar -->
    <nav class="modern-navbar">
        <a href="index.php" class="brand">
            <img src="assets/img/logo/logo6.png" alt="Logo" style="height: 30px;">
            E-Arsip Samsat
        </a>
        <div class="nav-links">
            <a href="login.php" class="btn btn-outline" style="border-color: white; color: white;">Masuk Sistem</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="hero-section">
        <div class="hero-content">
            <h1>Sistem Manajemen Arsip Digital Terpadu</h1>
            <p>
                Selamat datang di portal E-Arsip Samsat Kota Banda Aceh. Sistem ini dirancang untuk memudahkan manajemen, pengarsipan, dan pelacakan dokumen kendaraan bermotor secara elektronik dengan cepat, aman, dan transparan.
            </p>
            <div style="display: flex; gap: 15px;">
                <a href="login.php" class="btn btn-primary">Mulai Sekarang</a>
                <a href="#fitur" class="btn btn-outline glass-panel">Pelajari Fitur</a>
            </div>
        </div>
        <div class="hero-image">
            <!-- As a modern touch, using the old image but letting CSS handle its presentation, or a placeholder if none -->
            <img src="gambar/depan/11.png" alt="Samsat Digital">
        </div>
    </main>

    <!-- Features Section -->
    <section id="fitur" style="padding: 80px 10%; background-color: var(--bg-color);">
        <div style="text-align: center; margin-bottom: 50px;">
            <h2 style="color: var(--primary-color); font-size: 2.5rem; margin-bottom: 10px;">Fitur Unggulan E-Arsip</h2>
            <p style="color: var(--text-light); max-width: 600px; margin: 0 auto;">Inovasi digital untuk mempermudah dan mempercepat pelayanan arsip kendaraan bermotor di Samsat Banda Aceh.</p>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
            <div class="glass-panel" style="padding: 30px; text-align: center; transition: transform 0.3s ease;">
                <div style="width: 70px; height: 70px; background: linear-gradient(135deg, var(--primary-color), var(--accent-color)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                    <i class="fas fa-camera" style="font-size: 2rem; color: white;"></i>
                </div>
                <h3 style="margin-bottom: 15px; color: var(--text-color);">Scan Dokumen Mobile</h3>
                <p style="color: var(--text-light); font-size: 0.95rem;">Petugas di lapangan dapat langsung memfoto dokumen fisik menggunakan kamera HP yang terhubung langsung ke server.</p>
            </div>
            
            <div class="glass-panel" style="padding: 30px; text-align: center; transition: transform 0.3s ease;">
                <div style="width: 70px; height: 70px; background: linear-gradient(135deg, var(--success-color), #34d399); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                    <i class="fas fa-route" style="font-size: 2rem; color: white;"></i>
                </div>
                <h3 style="margin-bottom: 15px; color: var(--text-color);">Real-time Tracking</h3>
                <p style="color: var(--text-light); font-size: 0.95rem;">Wajib pajak dapat melacak status dokumen mereka secara transparan mulai dari tahap diverifikasi hingga selesai diproses.</p>
            </div>
            
            <div class="glass-panel" style="padding: 30px; text-align: center; transition: transform 0.3s ease;">
                <div style="width: 70px; height: 70px; background: linear-gradient(135deg, #f59e0b, #fbbf24); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                    <i class="fas fa-shield-alt" style="font-size: 2rem; color: white;"></i>
                </div>
                <h3 style="margin-bottom: 15px; color: var(--text-color);">Keamanan Terenkripsi</h3>
                <p style="color: var(--text-light); font-size: 0.95rem;">Data privasi dilindungi dengan enkripsi Bcrypt terbaru dan pertahanan anti-injeksi (PDO) berskala industri.</p>
            </div>
        </div>
    </section>

    <footer style="text-align: center; padding: 30px; background: white; color: var(--text-light); border-top: 1px solid #e2e8f0; margin-top: 50px;">
        <p>&copy; <?= date('Y') ?> E-Arsip Samsat Kota Banda Aceh. All rights reserved.</p>
    </footer>

    <!-- Script to enable smooth scrolling -->
    <script>
        document.querySelector('a[href="#fitur"]').addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelector('#fitur').scrollIntoView({ behavior: 'smooth' });
        });
    </script>
</body>
</html>