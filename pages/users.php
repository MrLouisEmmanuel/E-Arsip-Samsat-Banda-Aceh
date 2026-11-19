<?php
if (($role ?? '')!=='admin') exit;
$editing = isset($_GET['id']);
$id = (int)($_GET['id'] ?? 0);
$u = $editing ? (query_rows($pdo,'SELECT * FROM users WHERE user_id=?',[$id])[0] ?? null) : ['user_nama'=>'','user_username'=>'','user_role'=>'user'];
if (!$u) { notice('Pengguna tidak ditemukan.','users','error'); }
if ($_SERVER['REQUEST_METHOD']==='POST') {
    if (isset($_POST['delete'])) {
        $target = (int)$_POST['delete'];
        $stmt = $pdo->prepare('DELETE FROM users WHERE user_id=? AND user_id<>? AND NOT EXISTS (SELECT 1 FROM arsip WHERE arsip_petugas=users.user_id OR arsip_penerima=users.user_id) AND NOT EXISTS (SELECT 1 FROM riwayat WHERE riwayat_user=users.user_id)');
        $stmt->execute([$target,$_SESSION['id']]);
        if (!$stmt->rowCount()) notice('Akun aktif Anda atau akun dengan dokumen dan riwayat tidak dapat dihapus.','users','error');
        notice('Pengguna dihapus.','users');
    }
    $u['user_nama'] = trim($_POST['nama'] ?? '');
    $u['user_username'] = trim($_POST['username'] ?? '');
    $u['user_role'] = $id==$_SESSION['id'] ? 'admin' : ($_POST['role_input'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($u['user_nama']==='' || $u['user_username']==='' || (!$editing && $password==='') || !in_array($u['user_role'],['admin','petugas','user'],true)) $error='Periksa data akun.';
    elseif (query_rows($pdo,'SELECT user_id FROM users WHERE user_username=? AND user_id<>?',[$u['user_username'],$id])) $error='Nama pengguna sudah dipakai.';
    else {
        $params = [$u['user_nama'],$u['user_username'],$u['user_role']];
        $set = 'user_nama=?,user_username=?,user_role=?';
        if ($password!=='') { $set .= ',user_password=?'; $params[] = password_hash($password,PASSWORD_DEFAULT); }
        $sql = $editing ? "UPDATE users SET $set WHERE user_id=?" : "INSERT INTO users SET $set";
        if ($editing) $params[]=$id;
        $pdo->prepare($sql)->execute($params);
        notice('Data pengguna tersimpan.','users');
    }
}
$users = query_rows($pdo,'SELECT user_id,user_nama,user_username,user_role FROM users ORDER BY user_nama');
?>
<div class="split"><form method="post" class="panel editor"><?= csrf_field() ?><h2><?= $editing?'Edit':'Tambah' ?> pengguna</h2>
<?php if (isset($error)): ?><div class="alert alert-danger"><?= h($error) ?></div><?php endif; ?>
<label>Nama lengkap<input name="nama" required maxlength="255" value="<?= h($u['user_nama']) ?>"></label>
<label>Nama pengguna<input name="username" required maxlength="255" value="<?= h($u['user_username']) ?>"></label>
<label>Peran<select name="role_input" <?= $id==$_SESSION['id']?'disabled':'' ?>><?php foreach (['user'=>'Wajib pajak','petugas'=>'Petugas','admin'=>'Admin'] as $key=>$name): ?><option value="<?= $key ?>" <?= $key===$u['user_role']?'selected':'' ?>><?= $name ?></option><?php endforeach; ?></select></label>
<label>Kata sandi <?= $editing?'<small>(isi untuk mengganti)</small>':'' ?><input type="password" name="password" autocomplete="new-password" <?= $editing?'':'required' ?>></label>
<div class="actions"><button class="btn primary">Simpan pengguna</button><?php if ($editing): ?><a href="?page=users">Batal</a><?php endif; ?></div></form>
<section class="panel"><h2>Daftar pengguna</h2><div class="scroll"><table><thead><tr><th>Pengguna</th><th>Peran</th><th>Aksi</th></tr></thead><tbody><?php foreach ($users as $row): ?><tr><td><?= h($row['user_nama']) ?><small>@<?= h($row['user_username']) ?></small></td><td><?= $row['user_role']==='user'?'Wajib pajak':ucfirst($row['user_role']) ?></td><td><div class="actions"><a href="?page=users&amp;id=<?= $row['user_id'] ?>">Edit</a><?php if ($row['user_id']!=$_SESSION['id']): ?><form method="post" action="?page=users" data-confirm="Hapus akun ini?"><?= csrf_field() ?><button class="text-danger" name="delete" value="<?= $row['user_id'] ?>">Hapus</button></form><?php endif; ?></div></td></tr><?php endforeach; ?></tbody></table></div></section></div>
