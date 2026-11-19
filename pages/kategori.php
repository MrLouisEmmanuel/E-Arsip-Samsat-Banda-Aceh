<?php
if (!isset($role) || $role === 'user') exit;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $stmt = $pdo->prepare('DELETE FROM kategori WHERE kategori_id=? AND kategori_id<>1 AND NOT EXISTS (SELECT 1 FROM arsip WHERE arsip_kategori=kategori_id)');
        $stmt->execute([$_POST['delete']]);
        if (!$stmt->rowCount()) notice('Kategori bawaan atau yang masih digunakan tidak dapat dihapus.', 'kategori','error');
        notice('Kategori dihapus.','kategori');
    }
    $name = trim($_POST['kategori_nama'] ?? '');
    $notes = trim($_POST['kategori_keterangan'] ?? '');
    if ($name !== '' && $notes !== '' && mb_strlen($name)<=255) {
        $pdo->prepare('INSERT INTO kategori (kategori_nama,kategori_keterangan) VALUES (?,?)')->execute([$name,$notes]);
        notice('Kategori ditambahkan.','kategori');
    }
    $error = 'Isi nama dan keterangan kategori.';
}
$categories = query_rows($pdo,'SELECT k.*, (SELECT COUNT(*) FROM arsip WHERE arsip_kategori=k.kategori_id) total FROM kategori k ORDER BY kategori_nama');
?>
<div class="split"><form class="panel editor" method="post"><?= csrf_field() ?><h2>Tambah kategori</h2>
<?php if (isset($error)): ?><p class="alert alert-danger"><?= h($error) ?></p><?php endif; ?>
<label>Nama kategori<input name="kategori_nama" required maxlength="255"></label><label>Keterangan<textarea name="kategori_keterangan" rows="3" required></textarea></label>
<button class="btn primary">Simpan kategori</button></form>
<section class="panel"><h2>Daftar kategori</h2><div class="scroll"><table><thead><tr><th>Kategori</th><th>Dokumen</th><th>Aksi</th></tr></thead><tbody>
<?php foreach ($categories as $k): ?><tr><td><strong><?= h($k['kategori_nama']) ?></strong><small><?= h($k['kategori_keterangan']) ?></small></td><td><?= $k['total'] ?></td><td><?php if ($k['kategori_id']!=1 && !$k['total']): ?><form method="post" data-confirm="Hapus kategori ini?"><?= csrf_field() ?><button name="delete" value="<?= $k['kategori_id'] ?>" class="btn danger">Hapus</button></form><?php else: ?><?= $k['kategori_id']==1?'Bawaan':'Digunakan' ?><?php endif; ?></td></tr><?php endforeach; ?></tbody></table></div></section></div>
