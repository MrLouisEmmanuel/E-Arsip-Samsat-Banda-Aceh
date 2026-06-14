<?php
session_start();
require 'koneksi.php';

// Jika sudah login, arahkan ke dashboard
if(isset($_SESSION['status']) && $_SESSION['status'] == 'login') {
    header("Location: dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        // Cek user menggunakan PDO Prepared Statement
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user) {
            $is_password_correct = false;
            $needs_rehash = false;

            // Cek apakah password masih MD5 (panjang 32 karakter dan tidak diawali $2y$)
            if (strlen($user['user_password']) == 32 && strpos($user['user_password'], '$2y$') !== 0) {
                if (md5($password) === $user['user_password']) {
                    $is_password_correct = true;
                    $needs_rehash = true;
                }
            } else {
                // Gunakan Bcrypt
                if (password_verify($password, $user['user_password'])) {
                    $is_password_correct = true;
                }
            }

            if ($is_password_correct) {
                // Lakukan migrasi otomatis ke Bcrypt jika masih MD5
                if ($needs_rehash) {
                    $new_hash = password_hash($password, PASSWORD_DEFAULT);
                    $update_stmt = $pdo->prepare("UPDATE users SET user_password = ? WHERE user_id = ?");
                    $update_stmt->execute([$new_hash, $user['user_id']]);
                }

                // Set session
                $_SESSION['id'] = $user['user_id'];
                $_SESSION['nama'] = $user['user_nama'];
                $_SESSION['username'] = $user['user_username'];
                $_SESSION['role'] = $user['user_role'];
                $_SESSION['status'] = "login";

                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Password yang Anda masukkan salah.";
            }
        } else {
            $error = "Username tidak ditemukan.";
        }
    } else {
        $error = "Harap isi username dan password.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-Arsip Samsat</title>
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/logo/logo6.png">
    <link rel="stylesheet" href="assets/css/modern-style.css">
</head>
<body class="page-auth">

    <div class="auth-container">
        <div class="auth-card glass-panel">
            <div class="auth-header">
                <h2>Masuk ke Sistem</h2>
                <p>E-Arsip Samsat Banda Aceh</p>
            </div>

            <?php if($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" required autocomplete="off">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100 mt-4">Login</button>
            </form>
            
            <div class="text-center mt-4">
                <span style="color: var(--text-light); font-size: 0.9rem;">Belum punya akun? </span>
                <a href="register.php" style="color: var(--accent-color); text-decoration: none; font-size: 0.9rem; font-weight: bold;">Daftar di sini</a>
            </div>

            <div class="text-center mt-4">
                <a href="index.php" style="color: var(--text-light); text-decoration: none; font-size: 0.9rem;">&larr; Kembali ke Beranda</a>
            </div>
        </div>
    </div>

</body>
</html>