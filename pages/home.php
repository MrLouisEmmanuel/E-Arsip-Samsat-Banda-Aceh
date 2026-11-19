<?php
if (!isset($role)) exit;
[$scope,$params] = archive_access();
$counts = query_rows($pdo,"SELECT COUNT(*) total, COUNT(CASE WHEN arsip_status='Sedang Diverifikasi' THEN 1 END) verifikasi, COUNT(CASE WHEN arsip_status='Arsip Tersimpan' THEN 1 END) tersimpan FROM arsip a WHERE $scope",$params)[0];
$rows = query_rows($pdo,"SELECT a.*,k.kategori_nama FROM arsip a JOIN kategori k ON k.kategori_id=a.arsip_kategori WHERE $scope ORDER BY arsip_waktu_upload DESC, arsip_id DESC LIMIT 5",$params);
?>
<div class="welcome"><div><p class="eyebrow">SAMSAT BANDA ACEH</p><h2>Selamat datang, <?= h($_SESSION['nama']) ?>.</h2><p><?= $role==='petugas'?'Kumpulkan berkas dan pantau proses pengarsipan.':'Telusuri dokumen dan pantau status pengarsipan.' ?></p></div>
<a class="btn light" href="?page=<?= $role==='petugas'?'upload':'arsip' ?>"><?= icon($role==='petugas'?'plus':'folder-open-o') ?> <?= $role==='petugas'?'Unggah dokumen':'Buka data arsip' ?></a></div>
<div class="metrics"><?php foreach ([['Total dokumen','total','','archive'],['Sedang diverifikasi','verifikasi',ARCHIVE_STATUSES[1],'clock-o'],['Arsip tersimpan','tersimpan',ARCHIVE_STATUSES[3],'check-square-o']] as [$label,$key,$status,$symbol]): ?><a class="metric" href="?page=arsip&amp;status=<?= urlencode($status) ?>"><span><?= icon($symbol) ?> <?= $label ?></span><strong><?= $counts[$key] ?></strong></a><?php endforeach; ?></div>
<section class="panel"><div class="list-head"><h2>Dokumen terbaru</h2><a href="?page=arsip">Lihat semua</a></div><?php require __DIR__.'/document-list.php'; ?></section>
