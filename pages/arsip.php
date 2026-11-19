<?php
if (!isset($role)) exit;
[$scope,$params] = archive_access();
$terms = [$scope];
$q = trim($_GET['q'] ?? '');
$category = $_GET['kategori'] ?? '';
$status = $_GET['status'] ?? '';
if ($q !== '') {
    $terms[] = "(a.arsip_nama LIKE ? ESCAPE '!' OR a.arsip_kode LIKE ? ESCAPE '!')";
    $search = '%' . strtr($q,['!'=>'!!','%'=>'!%','_'=>'!_']) . '%';
    array_push($params,$search,$search);
}
if ($category !== '') { $terms[] = 'a.arsip_kategori=?'; $params[] = $category; }
if ($status !== '') { $terms[] = 'a.arsip_status=?'; $params[] = $status; }
$where = implode(' AND ', $terms);
$total = (int)query_rows($pdo,"SELECT COUNT(*) AS n FROM arsip a WHERE $where",$params)[0]['n'];
$last = max(1,(int)ceil($total/20));
$number = min($last,max(1,(int)($_GET['hal'] ?? 1)));
$offset = ($number-1)*20;
$rows = query_rows($pdo,"SELECT a.*,k.kategori_nama FROM arsip a JOIN kategori k ON k.kategori_id=a.arsip_kategori WHERE $where ORDER BY a.arsip_waktu_upload DESC,a.arsip_id DESC LIMIT 20 OFFSET $offset",$params);
$categories = query_rows($pdo,'SELECT kategori_id,kategori_nama FROM kategori ORDER BY kategori_nama');
?>
<form class="panel filters" method="get"><input type="hidden" name="page" value="arsip">
<label>Cari dokumen<input type="search" name="q" placeholder="Judul atau kode arsip" value="<?= h($q) ?>"></label>
<label>Kategori<select name="kategori"><option value="">Semua kategori</option><?php foreach ($categories as $k): ?><option value="<?= $k['kategori_id'] ?>" <?= $category == $k['kategori_id']?'selected':'' ?>><?= h($k['kategori_nama']) ?></option><?php endforeach; ?></select></label>
<label>Status<select name="status"><option value="">Semua status</option><?php foreach (ARCHIVE_STATUSES as $s): ?><option <?= $s===$status?'selected':'' ?>><?= h($s) ?></option><?php endforeach; ?></select></label>
<button class="btn primary"><?= icon('search') ?> Cari</button><?php if ($q!=='' || $category!=='' || $status!==''): ?><a href="?page=arsip">Reset</a><?php endif; ?></form>
<div class="panel"><div class="list-head"><h2>Daftar dokumen <small><?= $total ?></small></h2><?php if ($role==='petugas'): ?><a class="btn outline" href="?page=upload"><?= icon('plus') ?> Unggah dokumen</a><?php endif; ?></div>
<?php require __DIR__ . '/document-list.php'; ?>
<?php if ($last>1): ?><nav class="pager" aria-label="Halaman hasil"><?php foreach ([$number-1=>'Sebelumnya',$number+1=>'Berikutnya'] as $n=>$label): if ($n>=1 && $n<=$last): ?><a class="btn outline" href="?<?= h(http_build_query(array_merge($_GET,['hal'=>$n]))) ?>"><?= $label ?></a><?php endif; endforeach; ?><span>Halaman <?= $number ?> dari <?= $last ?></span></nav><?php endif; ?></div>
