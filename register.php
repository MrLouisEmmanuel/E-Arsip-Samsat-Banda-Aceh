<?php
session_start();
require 'koneksi.php';

// Jika sudah login, arahkan ke dashboard
if(isset($_SESSION['status']) && $_SESSION['status'] == 'login') {
    header("Location: dashboard.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = trim($_POST['nama']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (!empty($nama) && !empty($username) && !empty($password) && !empty($confirm_password)) {
        if ($password !== $confirm_password) {
            $error = "Konfirmasi password tidak cocok.";
        } else {
            // Cek apakah username sudah ada
            $stmt = $pdo->prepare("SELECT user_id FROM users WHERE user_username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                $error = "Username sudah digunakan. Silakan pilih username lain.";
            } else {
                // Hash password menggunakan Bcrypt
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert ke tabel users dengan role 'user'
                $insert_stmt = $pdo->prepare("INSERT INTO users (user_nama, user_username, user_password, user_role) VALUES (?, ?, ?, 'user')");
                if ($insert_stmt->execute([$nama, $username, $hashed_password])) {
                    $success = "Pendaftaran berhasil! Silakan masuk menggunakan akun baru Anda.";
                } else {
                    $error = "Terjadi kesalahan saat mendaftar. Silakan coba lagi.";
                }
            }
        }
    } else {
        $error = "Harap isi semua kolom pendaftaran.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran - E-Arsip Samsat</title>
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/logo/logo6.png">
    <link rel="stylesheet" href="assets/css/modern-style.css">
</head>
<body>

    <div class="auth-container">
        <div class="auth-card glass-panel" style="max-width: 450px;">
            <div class="auth-header">
                <h2>Pendaftaran Akun</h2>
                <p>Mendaftar sebagai Wajib Pajak (User)</p>
            </div>

            <?php if($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <?php if($success): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($success) ?><br><br>
                    <a href="login.php" class="btn btn-primary w-100">Menuju Halaman Login</a>
                </div>
            <?php else: ?>

            <form action="register.php" method="POST">
                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" class="form-control" required autocomplete="off" value="<?= isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : '' ?>">
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" required autocomplete="off" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100 mt-4">Daftar Akun Baru</button>
            </form>
            
            <div class="text-center mt-4">
                <span style="color: var(--text-light); font-size: 0.9rem;">Sudah punya akun? </span>
                <a href="login.php" style="color: var(--accent-color); text-decoration: none; font-size: 0.9rem; font-weight: bold;">Masuk di sini</a>
            </div>
            
            <?php endif; ?>
            
            <div class="text-center mt-4">
                <a href="index.php" style="color: var(--text-light); text-decoration: none; font-size: 0.9rem;">&larr; Kembali ke Beranda</a>
            </div>
        </div>
    </div>

</body>
</html>
