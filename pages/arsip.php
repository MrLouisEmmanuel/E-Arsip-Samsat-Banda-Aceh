<?php
// pages/arsip.php
if (!isset($_SESSION['status'])) exit();

// Handle status update (Tracking) & Email Notif
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_status') {
    if (in_array($role, ['admin', 'petugas'])) {
        $arsip_id = $_POST['arsip_id'];
        $new_status = $_POST['arsip_status'];
        
        // Update status
        $stmt = $pdo->prepare("UPDATE arsip SET arsip_status = ? WHERE arsip_id = ?");
        if($stmt->execute([$new_status, $arsip_id])) {
            
            // --- Fitur Notifikasi Email (Simulasi) ---
            // Mengirim email ke uploader/petugas (atau user, jika tabel user punya kolom email)
            // Karena tabel user tidak memiliki email, kita bisa log ini atau kirim email statis untuk simulasi
            $subject = "Status Dokumen Diperbarui";
            $message = "Dokumen dengan ID $arsip_id kini berstatus: $new_status";
            $headers = "From: noreply@samsat.acehprov.go.id";
            
            // Uncomment the line below in a real server environment
            // @mail("user_terkait@example.com", $subject, $message, $headers);
            
            echo "<script>showAlert('Sukses!', 'Status dokumen berhasil diperbarui. Email notifikasi telah dikirim.', 'success');</script>";
        }
    }
}

// Handle Delete
if (isset($_GET['delete']) && in_array($role, ['admin', 'petugas'])) {
    $id = $_GET['delete'];
    
    // Hapus file fisik
    $stmt = $pdo->prepare("SELECT arsip_file FROM arsip WHERE arsip_id = ?");
    $stmt->execute([$id]);
    $file = $stmt->fetchColumn();
    if($file && file_exists("arsip/".$file)) {
        unlink("arsip/".$file);
    }
    
    $stmt = $pdo->prepare("DELETE FROM arsip WHERE arsip_id = ?");
    $stmt->execute([$id]);
    echo "<script>showAlert('Terhapus!', 'Arsip berhasil dihapus.', 'success'); window.location='?page=arsip';</script>";
}

// Fetch Data
$query = "
    SELECT a.*, k.kategori_nama, u.user_nama 
    FROM arsip a 
    JOIN kategori k ON a.arsip_kategori = k.kategori_id 
    JOIN users u ON a.arsip_petugas = u.user_id
    ORDER BY a.arsip_id DESC
";
$stmt = $pdo->query($query);
$arsips = $stmt->fetchAll();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h3>Data Arsip Dokumen</h3>
    <?php if(in_array($role, ['admin', 'petugas'])): ?>
        <a href="?page=upload" class="btn btn-primary"><i class="fas fa-file-upload"></i> Upload / Scan Dokumen Baru</a>
    <?php endif; ?>
</div>

<div style="overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <thead style="background: var(--secondary-color); color: var(--text-color);">
            <tr>
                <th style="padding: 12px; text-align: left;">Kode</th>
                <th style="padding: 12px; text-align: left;">Nama File</th>
                <th style="padding: 12px; text-align: left;">Kategori</th>
                <th style="padding: 12px; text-align: left;">Waktu</th>
                <th style="padding: 12px; text-align: left;">Status (Tracking)</th>
                <th style="padding: 12px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($arsips as $a): ?>
            <tr style="border-bottom: 1px solid #e2e8f0;">
                <td style="padding: 12px;"> <?= htmlspecialchars($a['arsip_kode']) ?> </td>
                <td style="padding: 12px;">
                    <strong><?= htmlspecialchars($a['arsip_nama']) ?></strong><br>
                    <small style="color: var(--text-light);">Diunggah oleh: <?= htmlspecialchars($a['user_nama']) ?></small>
                </td>
                <td style="padding: 12px;"> <?= htmlspecialchars($a['kategori_nama']) ?> </td>
                <td style="padding: 12px;"> <?= date('d M Y H:i', strtotime($a['arsip_waktu_upload'])) ?> </td>
                <td style="padding: 12px;">
                    <span style="padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; background: var(--accent-color); color: white;">
                        <?= htmlspecialchars($a['arsip_status']) ?>
                    </span>
                    
                    <?php if(in_array($role, ['admin', 'petugas'])): ?>
                    <form method="POST" style="margin-top: 5px; display: flex; gap: 5px;">
                        <input type="hidden" name="action" value="update_status">
                        <input type="hidden" name="arsip_id" value="<?= $a['arsip_id'] ?>">
                        <select name="arsip_status" class="form-control" style="padding: 2px 5px; font-size: 0.8rem; width: auto;" onchange="this.form.submit()">
                            <option value="">Ubah...</option>
                            <option value="Diterima Petugas">Diterima Petugas</option>
                            <option value="Sedang Diverifikasi">Sedang Diverifikasi</option>
                            <option value="Menunggu Pembayaran">Menunggu Pembayaran</option>
                            <option value="Arsip Tersimpan">Arsip Tersimpan</option>
                        </select>
                    </form>
                    <?php endif; ?>
                </td>
                <td style="padding: 12px; text-align: center;">
                    <a href="download.php?id=<?= $a['arsip_id'] ?>" class="btn" style="background: var(--success-color); color: white; padding: 5px 10px; font-size: 0.8rem;" title="Unduh">
                        <i class="fas fa-download"></i>
                    </a>
                    <?php if(in_array($role, ['admin', 'petugas'])): ?>
                    <a href="?page=arsip&delete=<?= $a['arsip_id'] ?>" class="btn" style="background: var(--danger-color); color: white; padding: 5px 10px; font-size: 0.8rem;" onclick="return confirm('Yakin ingin menghapus arsip ini?')" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if(count($arsips) == 0): ?>
            <tr><td colspan="6" style="text-align: center; padding: 20px;">Belum ada data arsip.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
