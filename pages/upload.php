<?php
// pages/upload.php
if (!isset($_SESSION['status']) || $_SESSION['role'] != 'petugas') exit();

// Fetch Categories for Dropdown
$stmt = $pdo->query("SELECT * FROM kategori");
$kategoris = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && (isset($_FILES['file_upload']) || isset($_FILES['file_scan']))) {
    $kode = "MN-" . time();
    $nama = $_POST['nama'];
    $kategori = $_POST['kategori'];
    $keterangan = $_POST['keterangan'];
    $petugas = $_SESSION['id'];
    $waktu = date('Y-m-d H:i:s');
    
    // Tentukan file mana yang dipakai (scan atau upload biasa)
    $fileInput = !empty($_FILES['file_upload']['name']) ? $_FILES['file_upload'] : $_FILES['file_scan'];
    
    if(!empty($fileInput['name'])) {
        $filename = $fileInput['name'];
        $filetmp = $fileInput['tmp_name'];
        $fileext = pathinfo($filename, PATHINFO_EXTENSION);
        $newfilename = time() . "_" . mt_rand(100,999) . "." . $fileext;
        
        // Validasi file (hanya gambar/pdf)
        $allowed = ['jpg', 'jpeg', 'png', 'pdf'];
        if(in_array(strtolower($fileext), $allowed)) {
            if(move_uploaded_file($filetmp, "arsip/" . $newfilename)) {
                $stmt = $pdo->prepare("INSERT INTO arsip (arsip_waktu_upload, arsip_petugas, arsip_kode, arsip_nama, arsip_jenis, arsip_kategori, arsip_keterangan, arsip_file, arsip_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Diterima Petugas')");
                $stmt->execute([$waktu, $petugas, $kode, $nama, $fileext, $kategori, $keterangan, $newfilename]);
                
                // Catat Riwayat
                $arsip_id = $pdo->lastInsertId();
                $stmt2 = $pdo->prepare("INSERT INTO riwayat (riwayat_waktu, riwayat_user, riwayat_arsip) VALUES (?, ?, ?)");
                $stmt2->execute([$waktu, $petugas, $arsip_id]);
                
                echo "<script>showAlert('Berhasil!', 'Dokumen berhasil diunggah.', 'success').then(() => { window.location='?page=arsip'; });</script>";
            } else {
                echo "<script>showAlert('Gagal!', 'Gagal menyimpan file.', 'error');</script>";
            }
        } else {
            echo "<script>showAlert('Gagal!', 'Format file tidak diizinkan. Gunakan JPG/PNG/PDF.', 'error');</script>";
        }
    } else {
        echo "<script>showAlert('Gagal!', 'Tidak ada file yang dipilih.', 'error');</script>";
    }
}
?>

<div class="auth-card glass-panel" style="max-width: 600px; margin: 0 auto; padding: 2rem;">
    <h3 style="margin-bottom: 20px; color: var(--primary-color);"><i class="fas fa-file-upload"></i> Upload & Scan Dokumen</h3>
    <p style="margin-bottom: 20px; color: var(--text-light); font-size: 0.9rem;">
        Pilih salah satu metode: Upload file dari memori Anda, atau gunakan Scan (Kamera) untuk memfoto dokumen fisik langsung ke server.
    </p>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Nama/Judul Dokumen</label>
            <input type="text" name="nama" class="form-control" required placeholder="Contoh: STNK BA 1234 CD">
        </div>
        
        <div class="form-group">
            <label>Kategori</label>
            <select name="kategori" class="form-control" required>
                <option value="">Pilih Kategori...</option>
                <?php foreach($kategoris as $k): ?>
                    <option value="<?= $k['kategori_id'] ?>"><?= htmlspecialchars($k['kategori_nama']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label>Keterangan Tambahan</label>
            <textarea name="keterangan" class="form-control" rows="3" required placeholder="Detail dokumen..."></textarea>
        </div>

        <div style="display: flex; gap: 10px; margin-bottom: 20px;">
            <!-- Tombol Upload Biasa -->
            <div class="form-group" style="flex: 1; background: #f1f5f9; padding: 20px; border-radius: 8px; text-align: center; border: 2px dashed #cbd5e1;">
                <label style="font-size: 1rem; cursor: pointer; display: block;">
                    <i class="fas fa-folder-open" style="font-size: 2.5rem; color: #64748b; margin-bottom: 10px;"></i><br>
                    Upload File Biasa
                    <input type="file" name="file_upload" accept="image/*,application/pdf" style="display: none;" onchange="document.getElementById('file-name').innerText = this.files[0].name; document.getElementById('scan-input').value='';">
                </label>
            </div>

            <!-- Tombol Scan Kamera -->
            <div class="form-group" style="flex: 1; background: #f0fdfa; padding: 20px; border-radius: 8px; text-align: center; border: 2px dashed #a7f3d0;">
                <label style="font-size: 1rem; cursor: pointer; display: block;">
                    <i class="fas fa-camera" style="font-size: 2.5rem; color: var(--accent-color); margin-bottom: 10px;"></i><br>
                    Scan Kamera HP
                    <input type="file" id="scan-input" name="file_scan" accept="image/*,application/pdf" capture="environment" style="display: none;" onchange="document.getElementById('file-name').innerText = this.files[0].name; document.getElementsByName('file_upload')[0].value='';">
                </label>
            </div>
        </div>
        
        <div id="file-name" style="margin-bottom: 15px; color: var(--success-color); font-weight: bold; text-align: center;"></div>

        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-save"></i> Simpan & Unggah Dokumen</button>
    </form>
</div>
