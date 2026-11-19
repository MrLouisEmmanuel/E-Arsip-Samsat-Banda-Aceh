<?php
if (!isset($role)) exit;
[$scope,$params] = archive_access();
if ($role==='user') { $scope .= " AND r.riwayat_user=? AND r.riwayat_aksi='Download'"; $params[]=$_SESSION['id']; }
if ($role==='petugas') $scope .= " AND (u.user_role='petugas' OR (u.user_role='user' AND r.riwayat_aksi='Download'))";
$history = query_rows($pdo,"SELECT r.*,u.user_nama,a.arsip_nama FROM riwayat r JOIN users u ON u.user_id=r.riwayat_user JOIN arsip a ON a.arsip_id=r.riwayat_arsip WHERE $scope ORDER BY riwayat_waktu DESC,riwayat_id DESC",$params);
?>
<section class="panel"><h2><?= $role==='user'?'Unduhan saya':'Aktivitas pengarsipan' ?></h2>
<?php if (!$history): ?><p class="empty">Belum ada aktivitas yang tercatat.</p><?php else: ?><div class="scroll"><table><thead><tr><th>Waktu</th><th>Aktivitas</th><th>Dokumen</th><th>Pengguna</th></tr></thead><tbody><?php foreach ($history as $r): ?><tr><td><?= date('d/m/Y H:i',strtotime($r['riwayat_waktu'])) ?></td><td><?= h(['Download'=>'Unduh','Upload'=>'Unggah'][$r['riwayat_aksi']] ?? $r['riwayat_aksi']) ?></td><td><a href="?page=detail&amp;id=<?= $r['riwayat_arsip'] ?>"><?= h($r['arsip_nama']) ?></a></td><td><?= h($r['user_nama']) ?></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?></section>
