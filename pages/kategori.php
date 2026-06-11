<?php
// pages/kategori.php
if (!isset($_SESSION['status']) || !in_array($_SESSION['role'], ['admin', 'petugas'])) exit();

// Handle Tambah Kategori
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'tambah') {
    $nama = trim($_POST['kategori_nama']);
    $keterangan = trim($_POST['kategori_keterangan']);
    
    if (!empty($nama) && !empty($keterangan)) {
        $stmt = $pdo->prepare("INSERT INTO kategori (kategori_nama, kategori_keterangan) VALUES (?, ?)");
        if ($stmt->execute([$nama, $keterangan])) {
            echo "<script>showAlert('Berhasil!', 'Kategori baru berhasil ditambahkan.', 'success').then(() => { window.location='?page=kategori'; });</script>";
        } else {
            echo "<script>showAlert('Gagal!', 'Terjadi kesalahan sistem.', 'error');</script>";
        }
    } else {
        echo "<script>showAlert('Peringatan!', 'Harap isi semua kolom.', 'warning');</script>";
    }
}

// Handle Hapus Kategori
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    // Pastikan tidak menghapus kategori default/penting jika perlu (misal id 1)
    if ($id == 1) {
        echo "<script>showAlert('Ditolak!', 'Kategori default tidak dapat dihapus.', 'error').then(() => { window.location='?page=kategori'; });</script>";
    } else {
        $stmt = $pdo->prepare("DELETE FROM kategori WHERE kategori_id = ?");
        if ($stmt->execute([$id])) {
            echo "<script>showAlert('Berhasil!', 'Kategori berhasil dihapus.', 'success').then(() => { window.location='?page=kategori'; });</script>";
        }
    }
}

// Fetch Kategori
$stmt = $pdo->query("SELECT * FROM kategori ORDER BY kategori_id DESC");
$kategoris = $stmt->fetchAll();
?>

<div style="display: flex; gap: 20px; flex-wrap: wrap;">
    
    <!-- Form Tambah -->
    <div class="glass-panel" style="flex: 1; min-width: 300px; padding: 20px; height: fit-content;">
        <h4 style="margin-bottom: 15px; color: var(--primary-color);"><i class="fas fa-plus"></i> Tambah Kategori</h4>
        <form method="POST">
            <input type="hidden" name="action" value="tambah">
            <div class="form-group">
                <label>Nama Kategori</label>
                <input type="text" name="kategori_nama" class="form-control" required placeholder="Contoh: Surat Izin">
            </div>
            <div class="form-group">
                <label>Keterangan</label>
                <textarea name="kategori_keterangan" class="form-control" rows="3" required placeholder="Penjelasan singkat kategori..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-save"></i> Simpan Kategori</button>
        </form>
    </div>

    <!-- Tabel Data -->
    <div style="flex: 2; min-width: 400px; overflow-x: auto;">
        <h4 style="margin-bottom: 15px; color: var(--primary-color);"><i class="fas fa-list"></i> Daftar Kategori</h4>
        <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <thead style="background: var(--secondary-color); color: var(--text-color);">
                <tr>
                    <th style="padding: 12px; text-align: left; width: 50px;">No</th>
                    <th style="padding: 12px; text-align: left;">Nama Kategori</th>
                    <th style="padding: 12px; text-align: left;">Keterangan</th>
                    <th style="padding: 12px; text-align: center; width: 80px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no=1; foreach($kategoris as $k): ?>
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 12px;"><?= $no++ ?></td>
                    <td style="padding: 12px; font-weight: 500;"><?= htmlspecialchars($k['kategori_nama']) ?></td>
                    <td style="padding: 12px; color: var(--text-light); font-size: 0.9rem;"><?= htmlspecialchars($k['kategori_keterangan']) ?></td>
                    <td style="padding: 12px; text-align: center;">
                        <?php if($k['kategori_id'] != 1): ?>
                        <a href="?page=kategori&delete=<?= $k['kategori_id'] ?>" class="btn" style="background: var(--danger-color); color: white; padding: 5px 10px; font-size: 0.8rem;" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Arsip yang terhubung dengan kategori ini mungkin ikut terhapus.')" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </a>
                        <?php else: ?>
                        <span style="font-size: 0.8rem; color: #9ca3af;">Default</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(count($kategoris) == 0): ?>
                <tr><td colspan="4" style="text-align: center; padding: 20px;">Belum ada kategori.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>
