<?php
require_once __DIR__ . '/app/session.php';
require 'koneksi.php';
require_once __DIR__ . '/app/portal.php';

// Jika sudah login, arahkan ke dashboard
if(isset($_SESSION['status']) && $_SESSION['status'] == 'login') {
    header("Location: dashboard");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && (
    !isset($_SESSION['csrf']) || !is_string($_POST['csrf'] ?? null)
    || !hash_equals($_SESSION['csrf'], $_POST['csrf'])
)) {
    http_response_code(400);
    $error = 'Sesi formulir kedaluwarsa. Silakan coba kembali.';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    <?php head_assets(); ?>
    <title>Pendaftaran | E-Arsip</title>
</head>
<body class="page-auth">

    <div class="auth-container">
        <div class="auth-card" >
            <div class="auth-header">
                <?= brand() ?>
                <h2>Pendaftaran Akun</h2>
                <p>Buat akun wajib pajak untuk mengakses dokumen Anda.</p>
            </div>

            <?php if($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <?php if($success): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($success) ?><br><br>
                    <a href="login" class="btn btn-primary w-100">Menuju Halaman Login</a>
                </div>
            <?php else: ?>

            <form action="register" method="POST">
                <?= csrf_field() ?>
                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" class="form-control" required maxlength="255" autocomplete="name" value="<?= isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : '' ?>">
                </div>

                <div class="form-group">
                    <label for="username">Nama pengguna</label>
                    <input type="text" id="username" maxlength="255" name="username" class="form-control" required autocomplete="username" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">Kata sandi</label>
                    <input autocomplete="new-password" type="password" id="password" name="password" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Konfirmasi kata sandi</label>
                    <input autocomplete="new-password" type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100 mt-4">Daftar Akun Baru</button>
            </form>
            
            <div class="text-center mt-4">
                <span style="color: var(--text-light); font-size: 0.9rem;">Sudah punya akun? </span>
                <a href="login" style="color: var(--accent-color); text-decoration: none; font-size: 0.9rem; font-weight: bold;">Masuk di sini</a>
            </div>
            
            <?php endif; ?>
            
            <div class="text-center mt-4">
                <a href="./" style="color: var(--text-light); text-decoration: none; font-size: 0.9rem;">Kembali ke beranda</a>
            </div>
        </div>
    </div>

</body>
</html>
