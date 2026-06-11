<?php
// pages/riwayat.php
if (!isset($_SESSION['status'])) exit();

$role = $_SESSION['role'];
$user_id = $_SESSION['id'];

// Susun query berdasarkan role
$where_clause = "";
$params = [];

if ($role == 'user') {
    // 1. Role User - Pada menu riwayat akan memperlihatkan aktifitas user mendownload dokumen per user
    $where_clause = "WHERE r.riwayat_user = ? AND r.riwayat_aksi = 'Download'";
    $params[] = $user_id;
} elseif ($role == 'petugas') {
    // 2. Role Petugas - menampilkan aktifitas seluruh akun user maupun petugas mendownload dokumen, keseluruhan aktifitas yang dilakukan oleh petugas
    $where_clause = "WHERE (r.riwayat_aksi = 'Download' AND u.user_role IN ('user', 'petugas')) OR (u.user_role = 'petugas')";
} elseif ($role == 'admin') {
    // 3. Role Admin - menampilkan aktifitas seluruh yang dilakukan oleh admin (sebagai Admin, kita tampilkan seluruh aktivitas di sistem agar bisa mengawasi penuh)
    // Jika hanya ingin aktivitas admin saja, ubah menjadi "WHERE u.user_role = 'admin'"
    $where_clause = ""; // Kosong berarti menampilkan SEMUA riwayat
}

$query = "
    SELECT r.*, u.user_nama, u.user_role, a.arsip_nama, a.arsip_kode 
    FROM riwayat r 
    JOIN users u ON r.riwayat_user = u.user_id 
    JOIN arsip a ON r.riwayat_arsip = a.arsip_id 
    $where_clause 
    ORDER BY r.riwayat_waktu DESC
";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$riwayats = $stmt->fetchAll();
?>

<div class="glass-panel" style="padding: 20px;">
    <h4 style="margin-bottom: 20px; color: var(--primary-color);">
        <i class="fas fa-history"></i> Riwayat Aktivitas Sistem
    </h4>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <thead style="background: var(--secondary-color); color: var(--text-color);">
                <tr>
                    <th style="padding: 12px; text-align: left; width: 50px;">No</th>
                    <th style="padding: 12px; text-align: left;">Waktu</th>
                    <th style="padding: 12px; text-align: left;">Pelaku (User)</th>
                    <th style="padding: 12px; text-align: left;">Role</th>
                    <th style="padding: 12px; text-align: left;">Aksi</th>
                    <th style="padding: 12px; text-align: left;">Dokumen Terkait</th>
                </tr>
            </thead>
            <tbody>
                <?php $no=1; foreach($riwayats as $r): ?>
                <tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 12px;"><?= $no++ ?></td>
                    <td style="padding: 12px;"><?= date('d M Y H:i:s', strtotime($r['riwayat_waktu'])) ?></td>
                    <td style="padding: 12px; font-weight: 500;"><?= htmlspecialchars($r['user_nama']) ?></td>
                    <td style="padding: 12px;">
                        <span style="font-size: 0.8rem; background: #e2e8f0; padding: 2px 6px; border-radius: 4px;">
                            <?= ucfirst($r['user_role']) ?>
                        </span>
                    </td>
                    <td style="padding: 12px;">
                        <?php 
                        $aksi_color = $r['riwayat_aksi'] == 'Download' ? 'var(--success-color)' : 'var(--accent-color)';
                        ?>
                        <span style="color: <?= $aksi_color ?>; font-weight: bold;">
                            <i class="fas <?= $r['riwayat_aksi'] == 'Download' ? 'fa-download' : 'fa-upload' ?>"></i> 
                            <?= htmlspecialchars($r['riwayat_aksi']) ?>
                        </span>
                    </td>
                    <td style="padding: 12px;">
                        <?= htmlspecialchars($r['arsip_kode']) ?> - <?= htmlspecialchars($r['arsip_nama']) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                
                <?php if(count($riwayats) == 0): ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px; color: var(--text-light);">
                        Belum ada riwayat aktivitas yang sesuai dengan hak akses Anda.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
