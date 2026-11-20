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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && (
    !isset($_SESSION['csrf']) || !is_string($_POST['csrf'] ?? null)
    || !hash_equals($_SESSION['csrf'], $_POST['csrf'])
)) {
    http_response_code(400);
    $error = 'Sesi formulir kedaluwarsa. Silakan coba kembali.';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

                session_regenerate_id(true);
                $_SESSION['id'] = $user['user_id'];
                $_SESSION['nama'] = $user['user_nama'];
                $_SESSION['username'] = $user['user_username'];
                $_SESSION['role'] = $user['user_role'];
                $_SESSION['status'] = "login";

                header("Location: dashboard");
                exit();
            } else {
                $error = "Nama pengguna atau kata sandi salah.";
            }
        } else {
            $error = "Nama pengguna atau kata sandi salah.";
        }
    } else {
        $error = "Harap isi username dan password.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <?php head_assets(); ?>
    <title>Masuk | E-Arsip</title>
</head>
<body class="page-auth">

    <div class="auth-container">
        <div class="auth-card glass-panel">
            <div class="auth-header">
                <?= brand() ?>
                <h2>Masuk ke Sistem</h2>
                <p>E-Arsip Samsat Banda Aceh</p>
            </div>

            <?php if($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="login" method="POST">
                <?= csrf_field() ?>
                <div class="form-group">
                    <label for="username">Nama pengguna</label>
                    <input type="text" id="username" name="username" class="form-control" required autocomplete="username">
                </div>
                
                <div class="form-group">
                    <label for="password">Kata sandi</label>
                    <input autocomplete="current-password" type="password" id="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100 mt-4">Login</button>
            </form>
            
            <div class="text-center mt-4">
                <span style="color: var(--text-light); font-size: 0.9rem;">Belum punya akun? </span>
                <a href="register" style="color: var(--accent-color); text-decoration: none; font-size: 0.9rem; font-weight: bold;">Daftar di sini</a>
            </div>

            <div class="text-center mt-4">
                <a href="./" style="color: var(--text-light); text-decoration: none; font-size: 0.9rem;">Kembali ke beranda</a>
            </div>
        </div>
    </div>

</body>
</html>