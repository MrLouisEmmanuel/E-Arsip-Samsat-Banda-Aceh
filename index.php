<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="E-Arsip Samsat Banda Aceh - Sistem Manajemen Arsip Digital Kendaraan Bermotor yang Aman, Cepat, dan Transparan.">
    <title>E-Arsip Samsat Banda Aceh</title>
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/logo/logo6.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        /* ===== RESET ===== */
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: #1e293b;
            overflow-x: hidden;
            background: #f8fafc;
        }

        img { max-width: 100%; height: auto; display: block; }
        a { text-decoration: none; }

        /* ===== NAVBAR ===== */
        .lp-navbar {
            position: fixed;
            top: 0; left: 0;
            width: 100%;
            z-index: 999;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 5%;
            background: transparent;
            transition: background 0.4s, padding 0.4s, box-shadow 0.4s;
        }

        .lp-navbar.scrolled {
            background: rgba(12, 45, 87, 0.97);
            padding: 10px 5%;
            box-shadow: 0 4px 30px rgba(0,0,0,0.15);
        }

        .lp-navbar .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #fff;
            font-weight: 800;
            font-size: 1.25rem;
            letter-spacing: -0.02em;
        }

        .lp-navbar .brand img {
            height: 34px;
            width: 34px;
            object-fit: contain;
        }

        .lp-nav-links {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .lp-nav-links a {
            color: rgba(255,255,255,0.85);
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.3s;
        }

        .lp-nav-links a:hover { color: #fff; }

        .lp-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 22px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            line-height: 1;
        }

        .lp-btn-primary {
            background: #2563eb;
            color: #fff;
        }

        .lp-btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37,99,235,0.35);
        }

        .lp-btn-outline {
            background: rgba(255,255,255,0.08);
            color: #fff;
            border: 1.5px solid rgba(255,255,255,0.3);
        }

        .lp-btn-outline:hover {
            background: rgba(255,255,255,0.15);
            border-color: rgba(255,255,255,0.5);
            transform: translateY(-2px);
        }

        .lp-btn-gold {
            background: #d4a853;
            color: #0c2d57;
            font-weight: 700;
            padding: 12px 28px;
        }

        .lp-btn-gold:hover {
            background: #f0d78c;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(212,168,83,0.4);
        }

        .lp-btn-sm {
            padding: 8px 18px;
            font-size: 0.9rem;
        }

        /* ===== HERO ===== */
        .lp-hero {
            position: relative;
            width: 100%;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 130px 5% 100px;
            background: linear-gradient(145deg, #0c2d57 0%, #1a4a8a 50%, #1e5da0 100%);
            overflow: hidden;
        }

        .lp-hero::before {
            content: '';
            position: absolute;
            top: -40%; right: -15%;
            width: 700px; height: 700px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(37,99,235,0.15), transparent 70%);
            animation: glow 6s ease-in-out infinite;
            pointer-events: none;
        }

        .lp-hero::after {
            content: '';
            position: absolute;
            bottom: -25%; left: -8%;
            width: 500px; height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(212,168,83,0.1), transparent 70%);
            animation: glow 8s ease-in-out infinite reverse;
            pointer-events: none;
        }

        @keyframes glow {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 1; }
        }

        .lp-hero-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
            background-size: 60px 60px;
            pointer-events: none;
        }

        .lp-hero-inner {
            position: relative;
            z-index: 2;
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            display: flex;
            align-items: center;
            gap: 50px;
        }

        .lp-hero-text {
            flex: 1.2;
            min-width: 0;
        }

        .lp-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.15);
            padding: 8px 16px;
            border-radius: 40px;
            font-size: 0.82rem;
            color: #f0d78c;
            margin-bottom: 22px;
            font-weight: 500;
        }

        .lp-hero-badge i { font-size: 0.45rem; animation: blink 2s ease-in-out infinite; }

        @keyframes blink { 0%,100%{opacity:1;} 50%{opacity:.3;} }

        .lp-hero-text h1 {
            font-size: 3rem;
            font-weight: 900;
            color: #fff;
            line-height: 1.15;
            letter-spacing: -0.03em;
            margin-bottom: 18px;
        }

        .lp-hero-text h1 .gold {
            background: linear-gradient(135deg, #d4a853, #f0d78c);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .lp-hero-text p {
            font-size: 1.05rem;
            color: rgba(255,255,255,0.7);
            line-height: 1.7;
            max-width: 500px;
            margin-bottom: 28px;
        }

        .lp-hero-buttons {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .lp-hero-img {
            flex: 0.8;
            min-width: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .lp-hero-img img {
            max-width: 380px;
            width: 100%;
            filter: drop-shadow(0 25px 50px rgba(0,0,0,0.3));
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%,100% { transform: translateY(0); }
            50% { transform: translateY(-14px); }
        }

        /* ===== STATS BAR ===== */
        .lp-stats {
            max-width: 880px;
            margin: -42px auto 0;
            position: relative;
            z-index: 10;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .lp-stat {
            padding: 26px 20px;
            text-align: center;
            border-right: 1px solid #f1f5f9;
            transition: background 0.3s;
        }

        .lp-stat:last-child { border-right: none; }
        .lp-stat:hover { background: #f8fafc; }

        .lp-stat i {
            font-size: 1.5rem;
            margin-bottom: 8px;
            display: block;
        }

        .lp-stat span {
            font-size: 0.88rem;
            color: #64748b;
            font-weight: 600;
        }

        /* ===== FEATURES ===== */
        .lp-features {
            padding: 90px 5% 80px;
            background: #f8fafc;
        }

        .lp-section-head {
            text-align: center;
            margin-bottom: 55px;
        }

        .lp-section-head .subtitle {
            display: inline-block;
            color: #2563eb;
            font-weight: 600;
            font-size: 0.83rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .lp-section-head h2 {
            font-size: 2.1rem;
            font-weight: 800;
            color: #0c2d57;
            margin-bottom: 12px;
            letter-spacing: -0.02em;
        }

        .lp-section-head p {
            color: #64748b;
            max-width: 540px;
            margin: 0 auto;
            line-height: 1.6;
            font-size: 1rem;
        }

        .lp-features-grid {
            max-width: 1080px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 22px;
        }

        .lp-fcard {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 32px 24px;
            text-align: center;
            position: relative;
            overflow: hidden;
            transition: transform 0.35s ease, box-shadow 0.35s ease, border-color 0.35s ease;
        }

        .lp-fcard::after {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 3px;
            background: linear-gradient(90deg, #2563eb, #d4a853);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.35s ease;
        }

        .lp-fcard:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 40px rgba(0,0,0,0.08);
            border-color: transparent;
        }

        .lp-fcard:hover::after { transform: scaleX(1); }

        .lp-fcard-icon {
            width: 56px; height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 1.3rem;
            color: #fff;
        }

        .lp-fcard h3 {
            font-size: 1.05rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .lp-fcard p {
            font-size: 0.88rem;
            color: #64748b;
            line-height: 1.6;
        }

        /* ===== CTA ===== */
        .lp-cta {
            padding: 75px 5%;
            background: linear-gradient(135deg, #0c2d57, #1a4a8a);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .lp-cta::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }

        .lp-cta-inner {
            position: relative;
            z-index: 2;
            max-width: 580px;
            margin: 0 auto;
        }

        .lp-cta h2 {
            color: #fff;
            font-size: 1.9rem;
            font-weight: 800;
            margin-bottom: 14px;
        }

        .lp-cta p {
            color: rgba(255,255,255,0.7);
            margin-bottom: 28px;
            font-size: 1rem;
            line-height: 1.65;
        }

        .lp-cta-btns {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* ===== FOOTER ===== */
        .lp-footer {
            background: #0a2240;
            color: rgba(255,255,255,0.6);
            padding: 32px 5%;
        }

        .lp-footer-inner {
            max-width: 1080px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .lp-footer-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #fff;
            font-weight: 700;
            font-size: 0.95rem;
        }

        .lp-footer-brand img {
            height: 26px;
            width: 26px;
            object-fit: contain;
            opacity: 0.9;
        }

        .lp-footer-copy { font-size: 0.82rem; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 968px) {
            .lp-hero-inner {
                flex-direction: column;
                text-align: center;
            }
            .lp-hero-text h1 { font-size: 2.3rem; }
            .lp-hero-text p { max-width: 100%; margin-left: auto; margin-right: auto; }
            .lp-hero-buttons { justify-content: center; }
            .lp-hero-img img { max-width: 280px; }

            .lp-stats {
                grid-template-columns: 1fr;
                margin: -28px 20px 0;
                border-radius: 12px;
            }
            .lp-stat { border-right: none; border-bottom: 1px solid #f1f5f9; padding: 18px; }
            .lp-stat:last-child { border-bottom: none; }

            .lp-features-grid {
                grid-template-columns: 1fr 1fr;
                max-width: 600px;
            }
        }

        @media (max-width: 640px) {
            .lp-nav-links a.nav-text { display: none; }

            .lp-hero {
                padding: 100px 5% 60px;
                min-height: auto;
            }
            .lp-hero-text h1 { font-size: 1.8rem; }
            .lp-hero-text p { font-size: 0.95rem; }

            .lp-features-grid { grid-template-columns: 1fr; max-width: 400px; }

            .lp-footer-inner { flex-direction: column; text-align: center; }
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="lp-navbar" id="navbar">
        <a href="index.php" class="brand">
            <img src="assets/img/logo/logo6.png" alt="Logo">
            E-Arsip Samsat
        </a>
        <div class="lp-nav-links">
            <a href="#fitur" class="nav-text">Fitur</a>
            <a href="#tentang" class="nav-text">Tentang</a>
            <a href="login.php" class="lp-btn lp-btn-primary lp-btn-sm">
                <i class="fas fa-sign-in-alt"></i> Masuk
            </a>
        </div>
    </nav>

    <!-- HERO -->
    <section class="lp-hero">
        <div class="lp-hero-grid"></div>
        <div class="lp-hero-inner">
            <div class="lp-hero-text">
                <div class="lp-hero-badge">
                    <i class="fas fa-circle"></i> Sistem Arsip Resmi Pemerintah Aceh
                </div>
                <h1>Arsip Digital <span class="gold">Kendaraan Bermotor</span> Terpadu</h1>
                <p>Portal resmi pengelolaan dokumen STNK, BPKB, dan pembayaran pajak kendaraan bermotor Samsat Kota Banda Aceh secara elektronik, cepat, aman, dan transparan.</p>
                <div class="lp-hero-buttons">
                    <a href="login.php" class="lp-btn lp-btn-primary">
                        <i class="fas fa-arrow-right"></i> Mulai Sekarang
                    </a>
                    <a href="#fitur" class="lp-btn lp-btn-outline">
                        <i class="fas fa-play-circle"></i> Pelajari Fitur
                    </a>
                </div>
            </div>
            <div class="lp-hero-img">
                <img src="gambar/depan/11.png" alt="Samsat Digital">
            </div>
        </div>
    </section>

    <!-- STATS BAR -->
    <div class="lp-stats">
        <div class="lp-stat">
            <i class="fas fa-file-alt" style="color:#2563eb;"></i>
            <span>Arsip Dokumen Digital</span>
        </div>
        <div class="lp-stat">
            <i class="fas fa-shield-alt" style="color:#10b981;"></i>
            <span>Enkripsi Keamanan Bcrypt</span>
        </div>
        <div class="lp-stat">
            <i class="fas fa-clock" style="color:#d4a853;"></i>
            <span>Akses Real-time 24/7</span>
        </div>
    </div>

    <!-- FEATURES -->
    <section id="fitur" class="lp-features">
        <div class="lp-section-head">
            <div class="subtitle">Keunggulan Sistem</div>
            <h2>Fitur Unggulan E-Arsip</h2>
            <p>Inovasi digital untuk mempermudah dan mempercepat pelayanan arsip kendaraan bermotor di Samsat Banda Aceh.</p>
        </div>

        <div class="lp-features-grid">
            <div class="lp-fcard">
                <div class="lp-fcard-icon" style="background:linear-gradient(135deg,#0c2d57,#2563eb);">
                    <i class="fas fa-camera"></i>
                </div>
                <h3>Scan Dokumen Mobile</h3>
                <p>Petugas lapangan dapat langsung memfoto dokumen fisik menggunakan kamera HP yang terhubung ke server.</p>
            </div>

            <div class="lp-fcard">
                <div class="lp-fcard-icon" style="background:linear-gradient(135deg,#10b981,#34d399);">
                    <i class="fas fa-route"></i>
                </div>
                <h3>Pelacakan Real-time</h3>
                <p>Wajib pajak dapat melacak status dokumen secara transparan dari tahap verifikasi hingga selesai.</p>
            </div>

            <div class="lp-fcard">
                <div class="lp-fcard-icon" style="background:linear-gradient(135deg,#d4a853,#f59e0b);">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Keamanan Terenkripsi</h3>
                <p>Seluruh data dilindungi dengan enkripsi Bcrypt dan pertahanan anti-injeksi PDO berskala industri.</p>
            </div>

            <div class="lp-fcard">
                <div class="lp-fcard-icon" style="background:linear-gradient(135deg,#8b5cf6,#a78bfa);">
                    <i class="fas fa-users-cog"></i>
                </div>
                <h3>Multi-Role Access</h3>
                <p>Hak akses berlapis untuk Admin, Petugas, dan Wajib Pajak memastikan keamanan data setiap level.</p>
            </div>

            <div class="lp-fcard">
                <div class="lp-fcard-icon" style="background:linear-gradient(135deg,#ec4899,#f472b6);">
                    <i class="fas fa-folder-open"></i>
                </div>
                <h3>Kategorisasi Cerdas</h3>
                <p>Dokumen terorganisir rapi berdasarkan kategori yang dapat dikelola secara dinamis.</p>
            </div>

            <div class="lp-fcard">
                <div class="lp-fcard-icon" style="background:linear-gradient(135deg,#06b6d4,#67e8f9);">
                    <i class="fas fa-history"></i>
                </div>
                <h3>Riwayat Audit</h3>
                <p>Setiap aktivitas upload dan download tercatat otomatis dalam log audit transparan.</p>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section id="tentang" class="lp-cta">
        <div class="lp-cta-inner">
            <h2>Siap Mendigitalkan Arsip Anda?</h2>
            <p>Bergabunglah dengan sistem E-Arsip Samsat Banda Aceh untuk pengelolaan dokumen kendaraan bermotor yang lebih efisien, aman, dan modern.</p>
            <div class="lp-cta-btns">
                <a href="register.php" class="lp-btn lp-btn-gold">
                    <i class="fas fa-user-plus"></i> Daftar Akun Baru
                </a>
                <a href="login.php" class="lp-btn lp-btn-outline">
                    <i class="fas fa-sign-in-alt"></i> Masuk Sistem
                </a>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="lp-footer">
        <div class="lp-footer-inner">
            <div class="lp-footer-brand">
                <img src="assets/img/logo/logo6.png" alt="Logo">
                E-Arsip Samsat Banda Aceh
            </div>
            <div class="lp-footer-copy">
                &copy; <?= date('Y') ?> Kantor Bersama SAMSAT Kota Banda Aceh &mdash; Provinsi Aceh
            </div>
        </div>
    </footer>

    <script>
        // Navbar scroll effect
        var navbar = document.getElementById('navbar');
        window.addEventListener('scroll', function() {
            if (window.scrollY > 60) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                var target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        // Scroll reveal
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.lp-fcard, .lp-stat').forEach(function(el) {
            el.style.opacity = '0';
            el.style.transform = 'translateY(25px)';
            el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            observer.observe(el);
        });
    </script>
</body>
</html>