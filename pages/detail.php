<?php
if (!isset($role)) exit;
[$scope,$params] = archive_access();
$id = (int)($_GET['id'] ?? 0);
$a = query_rows($pdo,"SELECT a.*,k.kategori_nama,u.user_nama,r.user_username penerima FROM arsip a JOIN kategori k ON k.kategori_id=a.arsip_kategori JOIN users u ON u.user_id=a.arsip_petugas LEFT JOIN users r ON r.user_id=a.arsip_penerima WHERE a.arsip_id=? AND $scope",array_merge([$id],$params))[0] ?? null;
if (!$a) { http_response_code(404); echo '<p class="panel">Dokumen tidak ditemukan.</p>'; return; }
if ($role !== 'user') {
    $categories = query_rows($pdo, 'SELECT kategori_id,kategori_nama FROM kategori ORDER BY kategori_nama');
    $recipients = query_rows($pdo, "SELECT user_id,user_nama,user_username FROM users WHERE user_role='user' OR user_id=? ORDER BY user_nama", [$a['arsip_penerima']]);
    $name = $a['arsip_nama'];
    $category = $a['arsip_kategori'];
    $recipient = $a['arsip_penerima'] ?? 'shared';
    $notes = $a['arsip_keterangan'];
}
if ($_SERVER['REQUEST_METHOD']==='POST' && $role !== 'user') {
    if (($_POST['action'] ?? '') === 'edit') {
        $name = trim($_POST['nama'] ?? '');
        $notes = trim($_POST['keterangan'] ?? '');
        $category = $_POST['kategori'] ?? '';
        $recipient = $_POST['penerima'] ?? '';
        if ($name === '' || mb_strlen($name) > 255 || !in_array($category, array_column($categories,'kategori_id'))
            || ($recipient !== 'shared' && !in_array($recipient, array_column($recipients,'user_id')))) {
            $error = 'Periksa judul, kategori, dan penerima dokumen.';
        } else {
            $pdo->beginTransaction();
            $pdo->prepare('UPDATE arsip SET arsip_nama=?,arsip_kategori=?,arsip_penerima=?,arsip_keterangan=? WHERE arsip_id=?')
                ->execute([$name,$category,$recipient === 'shared' ? null : $recipient,$notes,$id]);
            log_activity($pdo,$id,'Edit data dokumen');
            $pdo->commit();
            notice('Data dan akses dokumen diperbarui.','detail&id=' . $id);
        }
    }
    if (($_POST['action'] ?? '')==='status' && in_array($_POST['status'] ?? '',ARCHIVE_STATUSES,true)) {
        $pdo->beginTransaction();
        $pdo->prepare('UPDATE arsip SET arsip_status=? WHERE arsip_id=?')->execute([$_POST['status'],$id]);
        log_activity($pdo,$id,'Status: ' . $_POST['status']);
        $pdo->commit();
        notice('Status dokumen diperbarui.','detail&id=' . $id);
    }
    if (($_POST['action'] ?? '')==='delete') {
        $pdo->prepare('DELETE FROM arsip WHERE arsip_id=?')->execute([$id]);
        $path = archive_path($a['arsip_file']);
        if ($path && !query_rows($pdo,'SELECT arsip_id FROM arsip WHERE arsip_file=? LIMIT 1',[$a['arsip_file']])) unlink($path);
        notice('Dokumen dihapus.','arsip');
    }
}
$path = archive_path($a['arsip_file']);
$mime = $path ? (new finfo(FILEINFO_MIME_TYPE))->file($path) : '';
?>
<a href="?page=arsip" class="back">Kembali ke data arsip</a>
<section class="panel"><div class="list-head"><div><p class="eyebrow"><?= h($a['arsip_kode']) ?></p><h2><?= h($a['arsip_nama']) ?></h2></div><span class="pill"><?= h($a['arsip_status']) ?></span></div>
<dl class="metadata"><?php foreach (['Kategori'=>$a['kategori_nama'],'Petugas'=>$a['user_nama'],'Tanggal unggah'=>date('d/m/Y H:i',strtotime($a['arsip_waktu_upload'])),'Akses dokumen'=>$a['penerima'] ? '@'.$a['penerima']:'Semua pengguna terdaftar','Keterangan'=>$a['arsip_keterangan'] ?: 'Tanpa keterangan'] as $label=>$value): ?><div><dt><?= $label ?></dt><dd><?= nl2br(h($value)) ?></dd></div><?php endforeach; ?></dl>
<div class="list-head"><h3>Pratinjau berkas</h3><?php if ($path): ?><a class="btn primary" href="download.php?id=<?= $id ?>"><?= icon('download') ?> Unduh berkas</a><?php endif; ?></div>
<?php if (!$path): ?><p class="alert alert-danger">Berkas tidak tersedia di penyimpanan.</p><?php elseif ($mime==='application/pdf'): ?><object class="pdf-view" data="download.php?id=<?= $id ?>&amp;preview=1" type="application/pdf" aria-label="Pratinjau PDF"><p>Gunakan Unduh berkas untuk membuka PDF di perangkat Anda.</p></object><?php elseif (in_array($mime,['image/png','image/jpeg'],true)): ?><img class="image-view" alt="Pratinjau dokumen" src="download.php?id=<?= $id ?>&amp;preview=1"><?php else: ?><p>Unduh berkas untuk membaca format ini.</p><?php endif; ?></section>
<?php if ($role!=='user'): ?><details class="panel" <?= isset($error)?'open':'' ?>>
<summary>Edit data dan akses dokumen</summary>
<form class="editor metadata-form" method="post">
    <?= csrf_field() ?><input type="hidden" name="action" value="edit">
    <?php if (isset($error)): ?><p class="alert alert-danger"><?= h($error) ?></p><?php endif; ?>
    <?php require __DIR__ . '/document-fields.php'; ?>
    <button class="btn primary">Simpan perubahan</button>
</form></details><div class="panel manage"><form method="post"><?= csrf_field() ?><input type="hidden" name="action" value="status"><label>Status dokumen<select name="status"><?php foreach (ARCHIVE_STATUSES as $s): ?><option <?= $s===$a['arsip_status']?'selected':'' ?>><?= h($s) ?></option><?php endforeach; ?></select></label><button class="btn outline">Simpan status</button></form>
<form method="post" data-confirm="Hapus dokumen ini beserta riwayatnya secara permanen?"><?= csrf_field() ?><input type="hidden" name="action" value="delete"><button class="btn danger">Hapus dokumen</button></form></div><?php endif; ?>
