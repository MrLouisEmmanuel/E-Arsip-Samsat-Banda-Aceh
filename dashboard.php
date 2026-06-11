<?php
session_start();
require 'koneksi.php';

// Cek status login
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'login') {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Routing halaman
function renderPage($page, $role, $pdo) {
    // Basic Router
    if ($page == 'home') {
        include 'pages/home.php';
    } elseif ($page == 'arsip' && in_array($role, ['admin', 'petugas', 'user'])) {
        include 'pages/arsip.php';
    } elseif ($page == 'upload' && $role == 'petugas') {
        include 'pages/upload.php';
    } elseif ($page == 'kategori' && in_array($role, ['admin', 'petugas'])) {
        include 'pages/kategori.php';
    } elseif ($page == 'users' && $role == 'admin') {
        include 'pages/users.php';
    } elseif ($page == 'riwayat') {
        include 'pages/riwayat.php';
    } elseif ($page == 'profil') {
        include 'pages/profil.php';
    } else {
        echo "<div class='alert alert-danger'>Halaman tidak ditemukan atau Anda tidak memiliki akses.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - E-Arsip Samsat</title>
    <link rel="stylesheet" href="assets/css/modern-style.css">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e2e8f0;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
</head>
<body>

<div class="dashboard-layout">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h3>E-Arsip Samsat</h3>
            <span style="font-size: 0.8rem; opacity: 0.8;">Panel <?= ucfirst($role) ?></span>
        </div>
        <nav class="sidebar-nav">
            <a href="?page=home" class="<?= $page == 'home' ? 'active' : '' ?>">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="?page=arsip" class="<?= $page == 'arsip' ? 'active' : '' ?>">
                <i class="fas fa-folder-open"></i> Data Arsip
            </a>
            
            <?php if($role == 'petugas'): ?>
            <a href="?page=upload" class="<?= $page == 'upload' ? 'active' : '' ?>">
                <i class="fas fa-file-upload"></i> Upload Dokumen
            </a>
            <?php endif; ?>

            <?php if($role == 'admin' || $role == 'petugas'): ?>
            <a href="?page=kategori" class="<?= $page == 'kategori' ? 'active' : '' ?>">
                <i class="fas fa-tags"></i> Kategori Arsip
            </a>
            <?php endif; ?>
            
            <?php if($role == 'admin'): ?>
            <a href="?page=users" class="<?= $page == 'users' ? 'active' : '' ?>">
                <i class="fas fa-users"></i> Manajemen User
            </a>
            <?php endif; ?>

            <a href="?page=riwayat" class="<?= $page == 'riwayat' ? 'active' : '' ?>">
                <i class="fas fa-history"></i> Riwayat
            </a>
            <a href="?page=profil" class="<?= $page == 'profil' ? 'active' : '' ?>">
                <i class="fas fa-user"></i> Profil Saya
            </a>
            <a href="logout.php" style="margin-top: 2rem; color: #fca5a5;">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="header-top">
            <h2>Selamat Datang, <?= htmlspecialchars($_SESSION['nama']) ?></h2>
            <div class="user-info">
                <i class="fas fa-user-circle" style="font-size: 2rem; color: var(--primary-color);"></i>
                <div>
                    <strong><?= htmlspecialchars($_SESSION['nama']) ?></strong>
                    <div style="font-size: 0.8rem; color: var(--text-light);"><?= ucfirst($role) ?></div>
                </div>
            </div>
        </div>

        <div class="content-wrapper glass-panel" style="padding: 20px; background: white; border: 1px solid #e2e8f0; border-radius: var(--border-radius);">
            <?php renderPage($page, $role, $pdo); ?>
        </div>
    </main>
</div>

<!-- SweetAlert2 for modern alerts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // General helper for sweet alerts
    function showAlert(title, text, icon) {
        Swal.fire({
            title: title,
            text: text,
            icon: icon,
            confirmButtonColor: '#3b82f6'
        });
    }
</script>
</body>
</html>
