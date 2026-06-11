<?php
// pages/profil.php
if (!isset($_SESSION['status'])) exit();

$id = $_SESSION['id'];

// Proses Update Profil
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_profil') {
    $nama = trim($_POST['nama']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Handle File Upload (Foto Profil)
    $foto_query = "";
    $params = [$nama, $username];
    
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $filetmp = $_FILES['foto']['tmp_name'];
        $filename = $_FILES['foto']['name'];
        $fileext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (in_array($fileext, ['jpg','jpeg','png'])) {
            $newfoto = time() . "_profil." . $fileext;
            // Gunakan path gambar/ default sistem lama atau assets/img/
            // Kita simpan di folder 'gambar/' agar kompatibel
            if(!is_dir('gambar')) mkdir('gambar');
            move_uploaded_file($filetmp, "gambar/" . $newfoto);
            
            $foto_query = ", user_foto=?";
            $params[] = $newfoto;
            
            // Hapus foto lama jika ada
            $stmt = $pdo->prepare("SELECT user_foto FROM users WHERE user_id = ?");
            $stmt->execute([$id]);
            $old_foto = $stmt->fetchColumn();
            if ($old_foto && file_exists("gambar/".$old_foto)) {
                @unlink("gambar/".$old_foto);
            }
        }
    }

    if (!empty($password)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $foto_query .= ", user_password=?";
        $params[] = $hashed;
    }

    $params[] = $id;

    $stmt = $pdo->prepare("UPDATE users SET user_nama=?, user_username=? {$foto_query} WHERE user_id=?");
    if ($stmt->execute($params)) {
        // Update session
        $_SESSION['nama'] = $nama;
        $_SESSION['username'] = $username;
        echo "<script>showAlert('Berhasil!', 'Profil Anda telah diperbarui.', 'success').then(() => { window.location='?page=profil'; });</script>";
    } else {
        echo "<script>showAlert('Gagal!', 'Terjadi kesalahan sistem.', 'error');</script>";
    }
}

// Ambil Data Profil Terbaru
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();
$foto_profil = !empty($user['user_foto']) ? "gambar/".$user['user_foto'] : "https://ui-avatars.com/api/?name=".urlencode($user['user_nama'])."&background=0D8ABC&color=fff&size=150";
?>

<div style="max-width: 800px; margin: 0 auto;">
    
    <!-- Header Profil (Modern Banner) -->
    <div style="position: relative; height: 150px; background: linear-gradient(135deg, var(--primary-color), var(--accent-color)); border-radius: 12px 12px 0 0;">
        <div style="position: absolute; bottom: -50px; left: 30px;">
            <img src="<?= htmlspecialchars($foto_profil) ?>" alt="Foto Profil" style="width: 100px; height: 100px; border-radius: 50%; border: 4px solid white; object-fit: cover; background: white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        </div>
    </div>
    
    <!-- Konten Profil -->
    <div class="glass-panel" style="border-radius: 0 0 12px 12px; padding: 60px 30px 30px 30px; border-top: none;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap;">
            <div>
                <h2 style="color: var(--text-color); margin-bottom: 5px;"><?= htmlspecialchars($user['user_nama']) ?></h2>
                <p style="color: var(--text-light); margin-bottom: 15px;">@<?= htmlspecialchars($user['user_username']) ?></p>
                <span style="background: var(--accent-color); color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: bold; text-transform: uppercase;">
                    <?= htmlspecialchars($user['user_role']) ?>
                </span>
            </div>
            
            <div style="margin-top: 20px; width: 100%; max-width: 400px;">
                <h4 style="margin-bottom: 20px; color: var(--primary-color); border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">Edit Informasi Profil</h4>
                
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_profil">
                    
                    <div class="form-group">
                        <label>Foto Profil <small>(Opsional, JPG/PNG)</small></label>
                        <input type="file" name="foto" accept="image/png, image/jpeg" class="form-control" style="padding: 10px;">
                    </div>

                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" required value="<?= htmlspecialchars($user['user_nama']) ?>">
                    </div>

                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required value="<?= htmlspecialchars($user['user_username']) ?>">
                    </div>

                    <div class="form-group">
                        <label>Password Baru <small style="color: red;">(Kosongkan jika tidak ingin ganti password)</small></label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••">
                    </div>

                    <button type="submit" class="btn btn-primary w-100" style="margin-top: 10px;"><i class="fas fa-user-edit"></i> Simpan Perubahan Profil</button>
                </form>
            </div>
        </div>
    </div>
</div>
