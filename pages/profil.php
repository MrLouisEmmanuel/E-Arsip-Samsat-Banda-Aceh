<?php
if (!isset($role)) exit;
$id = $_SESSION['id'];
$u = query_rows($pdo,'SELECT * FROM users WHERE user_id=?',[$id])[0];
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $name = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $photo = $_FILES['foto'] ?? ['error'=>UPLOAD_ERR_NO_FILE];
    $newFile = null;
    if ($name==='' || $username==='') $error = 'Isi nama dan nama pengguna.';
    elseif (query_rows($pdo,'SELECT user_id FROM users WHERE user_username=? AND user_id<>?',[$username,$id])) $error='Nama pengguna sudah dipakai.';
    elseif ($photo['error']!==UPLOAD_ERR_NO_FILE) {
        $mime = $photo['error']===UPLOAD_ERR_OK ? (new finfo(FILEINFO_MIME_TYPE))->file($photo['tmp_name']) : '';
        $ext = ['image/png'=>'png','image/jpeg'=>'jpg'][$mime] ?? null;
        if (!$ext || $photo['size']>2097152) $error='Gunakan foto PNG atau JPG hingga 2 MB.';
        else {
            $newFile = bin2hex(random_bytes(16)).'.'.$ext;
            if (!move_uploaded_file($photo['tmp_name'],'gambar/'.$newFile)) $error='Foto belum dapat disimpan.';
        }
    }
    if (!isset($error)) {
        try {
            $pdo->prepare('UPDATE users SET user_nama=?,user_username=?,user_password=?,user_foto=? WHERE user_id=?')
                ->execute([$name,$username,$password!==''?password_hash($password,PASSWORD_DEFAULT):$u['user_password'],$newFile ?? $u['user_foto'],$id]);
        } catch (PDOException $e) { if ($newFile) unlink('gambar/'.$newFile); throw $e; }
        if ($newFile && preg_match('/^[0-9a-f]{32}\.(jpg|png)$/D',$u['user_foto'] ?? '') && is_file('gambar/'.$u['user_foto'])) unlink('gambar/'.$u['user_foto']);
        $_SESSION['nama']=$name;
        $_SESSION['username']=$username;
        notice('Profil diperbarui.','profil');
    }
}
$photoName = basename($u['user_foto'] ?? '');
$photoPath = 'gambar/'.$photoName;
if (!is_file($photoPath)) $photoPath = 'gambar/'.$role.'/'.$photoName;
?>
<form class="panel editor" method="post" enctype="multipart/form-data"><?= csrf_field() ?><div class="profile-heading"><?php if ($photoName && is_file($photoPath)): ?><img class="profile-photo" src="<?= h($photoPath) ?>" alt="Foto profil"><?php else: ?><span class="avatar"><?= h(mb_substr($u['user_nama'],0,1)) ?></span><?php endif; ?><div><h2><?= h($u['user_nama']) ?></h2><small>@<?= h($u['user_username']) ?></small></div></div>
<?php if (isset($error)): ?><p class="alert alert-danger"><?= h($error) ?></p><?php endif; ?>
<label>Foto profil <small>(JPG atau PNG hingga 2 MB)</small><input type="file" name="foto" accept="image/jpeg,image/png"></label>
<div class="fields"><label>Nama lengkap<input required maxlength="255" name="nama" value="<?= h($name ?? $u['user_nama']) ?>"></label><label>Nama pengguna<input required maxlength="255" name="username" value="<?= h($username ?? $u['user_username']) ?>"></label></div>
<label>Kata sandi baru <small>(isi untuk mengganti)</small><input type="password" name="password" autocomplete="new-password"></label>
<button class="btn primary">Simpan perubahan</button></form>
