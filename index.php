<?php require_once 'portal.php'; ?>
<!doctype html><html lang="id"><head><?php head_assets(); ?><title>E-Arsip | Samsat Banda Aceh</title>
<meta name="description" content="Portal arsip kendaraan bermotor Samsat Banda Aceh. Temukan dokumen, lihat status, dan unduh salinannya."></head>
<body class="landing"><a class="skip" href="#utama">Ke konten utama</a>
<header class="mast"><div class="wrap mast-inner"><?= brand() ?><nav aria-label="Menu utama"><a href="#layanan">Layanan</a><a href="#panduan">Panduan</a><a href="#lokasi">Lokasi</a><a class="btn primary" href="login.php"><?= icon('sign-in') ?> Masuk</a></nav></div></header>
<main id="utama">
<section class="hero-band">
    <div class="hero wrap">
        <div class="hero-copy">
            <p class="eyebrow"><span></span> SAMSAT BANDA ACEH</p>
            <h1>Arsip digital<br><em>kendaraan bermotor.</em></h1>
            <p class="lead">Temukan dokumen kendaraan Anda, pantau status pengarsipan, dan unduh salinannya dalam satu portal.</p>
            <div class="actions">
                <a class="btn primary" href="login.php"><?= icon('folder-open-o') ?> Buka ruang arsip</a>
                <a class="btn outline" href="register.php">Daftar akun</a>
            </div>
        </div>
        <ul class="hero-agencies" aria-label="Instansi Samsat Banda Aceh">
            <li>
                <img class="mark-ditlantas" src="gambar/depan/11.png" alt="" width="596" height="529">
                <span>Ditlantas Polda Aceh</span>
            </li>
            <li>
                <img class="mark-aceh" src="assets/img/logo/pemerintah-aceh.png" alt="" width="222" height="227">
                <span>Pemerintah Aceh</span>
            </li>
            <li>
                <img class="mark-jr" src="assets/img/logo/jasa-raharja.png" alt="" width="394" height="360">
                <span>Jasa Raharja</span>
            </li>
        </ul>
    </div>
</section>
<section class="services" id="layanan"><div class="wrap"><div class="section-head"><div><h2>Layanan kearsipan</h2></div><p>Dari penerimaan berkas hingga akses salinan.</p></div>
<div class="service-grid">
<article><div class="tile-icon"><?= icon('files-o') ?></div><h3>Pengumpulan berkas</h3><p>Petugas dapat mengunggah PDF atau memotret dokumen dan menentukan penerimanya.</p></article>
<article><div class="tile-icon"><?= icon('sliders') ?></div><h3>Penelusuran arsip</h3><p>Temukan berkas melalui judul, kode, kategori, atau status pengarsipan.</p></article>
<article><div class="tile-icon"><?= icon('file-text-o') ?></div><h3>Akses dokumen</h3><p>Baca dokumen yang tersedia untuk akun Anda dan simpan salinannya saat diperlukan.</p></article>
</div></div></section>
<section class="guide wrap" id="panduan"><div><p class="eyebrow"><span></span> PANDUAN</p><h2>Menggunakan E-Arsip</h2></div><div>
<details open><summary>Bagaimana cara melihat dokumen?</summary><p>Masuk ke akun Anda, pilih Data arsip, lalu buka detail dokumen. Anda dapat melihat status, membaca pratinjau, atau mengunduh berkas.</p></details>
<details><summary>Bagaimana petugas menambahkan berkas?</summary><p>Pilih Unggah dokumen, isi data berkas dan penerimanya, lalu pilih file atau gunakan kamera pada ponsel yang mendukung.</p></details>
<details><summary>Bagaimana saya mendapatkan akun?</summary><p>Gunakan <a href="register.php">pendaftaran wajib pajak</a>. Administrator mengelola akun petugas melalui ruang kerja.</p></details>
</div></section>
<section class="location wrap" id="lokasi" aria-labelledby="location-title">
    <div class="location-info">
        <p class="eyebrow"><?= icon('map-marker') ?> Lokasi kantor</p>
        <h2 id="location-title">SAMSAT Banda Aceh</h2>
        <p>Gunakan pin kantor bersama ini untuk merencanakan perjalanan Anda.</p>
        <a class="btn primary" href="https://maps.app.goo.gl/VhmaJMSSvGG9G5i8A" target="_blank" rel="noopener noreferrer">
            <?= icon('map-o') ?> Buka di Google Maps
        </a>
    </div>
    <iframe title="Peta lokasi Kantor Bersama SAMSAT Banda Aceh"
        src="https://www.google.com/maps?q=5.5228753,95.3304951&amp;hl=id&amp;z=17&amp;output=embed"
        width="720" height="360" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
</section>
</main><footer><div class="wrap footer-inner"><?= brand() ?><span>Kantor Bersama SAMSAT Kota Banda Aceh &middot; <?= date('Y') ?></span></div></footer></body></html>
