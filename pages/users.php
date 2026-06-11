<?php
// pages/users.php
if (!isset($_SESSION['status']) || $_SESSION['role'] != 'admin') exit();

// Handle Add / Edit
$edit_mode = false;
$edit_data = null;

if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $edit_mode = true;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->execute([$_GET['id']]);
    $edit_data = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'simpan') {
        $nama = trim($_POST['nama']);
        $username = trim($_POST['username']);
        $role_input = $_POST['role_input'];
        $password = $_POST['password'];

        if (!empty($nama) && !empty($username) && !empty($role_input)) {
            // Check username duplicate
            $stmt = $pdo->prepare("SELECT user_id FROM users WHERE user_username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                echo "<script>showAlert('Gagal!', 'Username sudah digunakan.', 'error');</script>";
            } else {
                if (empty($password)) {
                    $password = '123456'; // Default password
                }
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (user_nama, user_username, user_password, user_role) VALUES (?, ?, ?, ?)");
                if ($stmt->execute([$nama, $username, $hashed, $role_input])) {
                    echo "<script>showAlert('Berhasil!', 'Pengguna baru berhasil ditambahkan.', 'success').then(() => { window.location='?page=users'; });</script>";
                }
            }
        }
    } elseif ($_POST['action'] == 'update') {
        $id = $_POST['user_id'];
        $nama = trim($_POST['nama']);
        $username = trim($_POST['username']);
        $role_input = $_POST['role_input'];
        $password = $_POST['password'];

        if (!empty($password)) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET user_nama=?, user_username=?, user_role=?, user_password=? WHERE user_id=?");
            $stmt->execute([$nama, $username, $role_input, $hashed, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET user_nama=?, user_username=?, user_role=? WHERE user_id=?");
            $stmt->execute([$nama, $username, $role_input, $id]);
        }
        echo "<script>showAlert('Berhasil!', 'Data pengguna diperbarui.', 'success').then(() => { window.location='?page=users'; });</script>";
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if ($id == $_SESSION['id']) {
        echo "<script>showAlert('Gagal!', 'Anda tidak dapat menghapus akun Anda sendiri.', 'error');</script>";
    } else {
        $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt->execute([$id]);
        echo "<script>showAlert('Berhasil!', 'Pengguna dihapus.', 'success').then(() => { window.location='?page=users'; });</script>";
    }
}

// Fetch Users
$stmt = $pdo->query("SELECT * FROM users ORDER BY user_role ASC, user_nama ASC");
$users = $stmt->fetchAll();
?>

<div style="display: flex; gap: 20px; flex-wrap: wrap;">
    
    <!-- Form Area -->
    <div class="glass-panel" style="flex: 1; min-width: 300px; padding: 20px; height: fit-content;">
        <h4 style="margin-bottom: 15px; color: var(--primary-color);">
            <i class="fas <?= $edit_mode ? 'fa-edit' : 'fa-user-plus' ?>"></i> 
            <?= $edit_mode ? 'Edit Pengguna' : 'Tambah Pengguna Baru' ?>
        </h4>
        <form method="POST" action="?page=users">
            <input type="hidden" name="action" value="<?= $edit_mode ? 'update' : 'simpan' ?>">
            <?php if($edit_mode): ?>
                <input type="hidden" name="user_id" value="<?= $edit_data['user_id'] ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" required value="<?= $edit_mode ? htmlspecialchars($edit_data['user_nama']) : '' ?>">
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required value="<?= $edit_mode ? htmlspecialchars($edit_data['user_username']) : '' ?>">
            </div>
            <div class="form-group">
                <label>Hak Akses (Role)</label>
                <select name="role_input" class="form-control" required>
                    <option value="user" <?= ($edit_mode && $edit_data['user_role']=='user') ? 'selected' : '' ?>>User (Wajib Pajak)</option>
                    <option value="petugas" <?= ($edit_mode && $edit_data['user_role']=='petugas') ? 'selected' : '' ?>>Petugas</option>
                    <option value="admin" <?= ($edit_mode && $edit_data['user_role']=='admin') ? 'selected' : '' ?>>Admin</option>
                </select>
            </div>
            <div class="form-group">
                <label>Password <?= $edit_mode ? '<small style="color:red;">(Kosongkan jika tidak ingin diubah)</small>' : '' ?></label>
                <input type="password" name="password" class="form-control" <?= $edit_mode ? '' : 'required' ?>>
            </div>
            
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-save"></i> <?= $edit_mode ? 'Update Data' : 'Simpan Pengguna' ?></button>
                <?php if($edit_mode): ?>
                    <a href="?page=users" class="btn btn-outline" style="text-align:center;">Batal</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Tabel Data -->
    <div style="flex: 2; min-width: 400px; overflow-x: auto;">
        <h4 style="margin-bottom: 15px; color: var(--primary-color);"><i class="fas fa-users"></i> Daftar Semua Pengguna</h4>
        <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <thead style="background: var(--secondary-color); color: var(--text-color);">
                <tr>
                    <th style="padding: 12px; text-align: left;">Nama Lengkap</th>
                    <th style="padding: 12px; text-align: left;">Username</th>
                    <th style="padding: 12px; text-align: left;">Hak Akses</th>
                    <th style="padding: 12px; text-align: center; width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users as $u): ?>
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 12px; font-weight: 500;"><?= htmlspecialchars($u['user_nama']) ?>
                        <?php if($u['user_id'] == $_SESSION['id']): ?>
                            <span style="font-size:0.7rem; background:#10b981; color:white; padding:2px 5px; border-radius:4px; margin-left:5px;">Anda</span>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 12px;"><?= htmlspecialchars($u['user_username']) ?></td>
                    <td style="padding: 12px;">
                        <?php 
                        $badge_color = $u['user_role'] == 'admin' ? '#ef4444' : ($u['user_role'] == 'petugas' ? '#3b82f6' : '#64748b');
                        ?>
                        <span style="background: <?= $badge_color ?>; color: white; padding: 3px 8px; border-radius: 4px; font-size: 0.8rem;">
                            <?= ucfirst($u['user_role']) ?>
                        </span>
                    </td>
                    <td style="padding: 12px; text-align: center;">
                        <a href="?page=users&action=edit&id=<?= $u['user_id'] ?>" class="btn" style="background: #f59e0b; color: white; padding: 5px 10px; font-size: 0.8rem;" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <?php if($u['user_id'] != $_SESSION['id']): ?>
                        <a href="?page=users&delete=<?= $u['user_id'] ?>" class="btn" style="background: var(--danger-color); color: white; padding: 5px 10px; font-size: 0.8rem;" onclick="return confirm('Hapus pengguna ini secara permanen?')" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>
