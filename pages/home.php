<?php
// pages/home.php
if (!isset($_SESSION['status'])) exit();

// Get counts
$count_arsip = $pdo->query("SELECT COUNT(*) FROM arsip")->fetchColumn();
$count_kategori = $pdo->query("SELECT COUNT(*) FROM kategori")->fetchColumn();
$count_user = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$count_riwayat = $pdo->query("SELECT COUNT(*) FROM riwayat")->fetchColumn();
?>
<div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 2rem;">
    <div style="background: var(--bg-gradient); padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0; text-align: center;">
        <i class="fas fa-folder-open" style="font-size: 2.5rem; color: var(--accent-color); margin-bottom: 10px;"></i>
        <h3><?= $count_arsip ?></h3>
        <p style="color: var(--text-light);">Total Arsip</p>
    </div>
    <div style="background: var(--bg-gradient); padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0; text-align: center;">
        <i class="fas fa-tags" style="font-size: 2.5rem; color: var(--success-color); margin-bottom: 10px;"></i>
        <h3><?= $count_kategori ?></h3>
        <p style="color: var(--text-light);">Kategori</p>
    </div>
    <div style="background: var(--bg-gradient); padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0; text-align: center;">
        <i class="fas fa-users" style="font-size: 2.5rem; color: #f59e0b; margin-bottom: 10px;"></i>
        <h3><?= $count_user ?></h3>
        <p style="color: var(--text-light);">Pengguna Sistem</p>
    </div>
    <div style="background: var(--bg-gradient); padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0; text-align: center;">
        <i class="fas fa-history" style="font-size: 2.5rem; color: #8b5cf6; margin-bottom: 10px;"></i>
        <h3><?= $count_riwayat ?></h3>
        <p style="color: var(--text-light);">Riwayat Unduh</p>
    </div>
</div>

<div class="welcome-box" style="padding: 20px; border-left: 4px solid var(--accent-color); background: #f0fdfa;">
    <h3 style="color: var(--primary-color);">Sistem E-Arsip Digital</h3>
    <p style="margin-top: 10px; line-height: 1.6;">
        Sistem Manajemen Arsip Digital Samsat Banda Aceh. Gunakan menu di sidebar untuk melakukan aktivitas pengarsipan, pencarian dokumen, maupun pemantauan status proses (tracking).
    </p>
</div>
