<?php
if (($role ?? '') !== 'petugas') exit;
$categories = query_rows($pdo, 'SELECT kategori_id, kategori_nama FROM kategori ORDER BY kategori_nama');
$recipients = query_rows($pdo, "SELECT user_id, user_nama, user_username FROM users WHERE user_role='user' ORDER BY user_nama");
$name = trim($_POST['nama'] ?? '');
$category = $_POST['kategori'] ?? '';
$recipient = $_POST['penerima'] ?? '';
$notes = trim($_POST['keterangan'] ?? '');
$limit = upload_limit();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file = $_FILES['file_upload'] ?? [];
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) $file = $_FILES['file_scan'] ?? [];
    if ($name === '' || mb_strlen($name) > 255 || !in_array($category, array_column($categories,'kategori_id'))) {
        $error = 'Isi judul dokumen dan pilih kategori yang tersedia.';
    } elseif (!in_array($recipient, array_map('strval', array_column($recipients, 'user_id')), true)) {
        $error = 'Pilih akun wajib pajak yang berhak menerima dokumen ini.';
    } elseif (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK || !$file['size'] || $file['size'] > $limit) {
        $error = 'Pilih berkas sesuai batas ukuran yang tertera.';
    } else {
        $types = ['application/pdf'=>'pdf', 'image/jpeg'=>'jpg', 'image/png'=>'png'];
        $ext = $types[(new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name'])] ?? null;
        if (!$ext) { $error = 'Isi berkas harus berupa PDF, JPG, atau PNG.'; }
        else {
            $key = bin2hex(random_bytes(12));
            $filename = $key . '.' . $ext;
            $path = __DIR__ . '/../arsip/' . $filename;
            if (!move_uploaded_file($file['tmp_name'], $path)) { $error = 'Berkas belum tersimpan. Silakan pilih kembali.'; }
            else {
                try {
                    $pdo->beginTransaction();
                    $pdo->prepare('INSERT INTO arsip (arsip_waktu_upload,arsip_petugas,arsip_kode,arsip_nama,arsip_jenis,arsip_kategori,arsip_keterangan,arsip_file,arsip_penerima) VALUES (NOW(),?,?,?,?,?,?,?,?)')
                        ->execute([$_SESSION['id'],'MN-' . strtoupper(substr($key,0,10)),$name,$ext,$category,$notes,$filename,$recipient]);
                    $id = (int)$pdo->lastInsertId();
                    log_activity($pdo,$id,'Upload');
                    $pdo->commit();
                } catch (Throwable $e) {
                    if ($pdo->inTransaction()) $pdo->rollBack();
                    unlink($path);
                    throw $e;
                }
                notice('Dokumen berhasil diunggah.', 'detail&id=' . $id);
            }
        }
    }
}
?>
<form method="post" enctype="multipart/form-data" class="panel editor"><?= csrf_field() ?>
<p class="intro">Lengkapi data arsip dan pilih siapa yang dapat melihat berkas ini.</p>
<?php if (isset($error)): ?><p class="alert alert-danger" role="alert"><?= h($error) ?></p><?php endif; ?>
<?php require __DIR__ . '/document-fields.php'; ?>
<h3>Berkas dokumen</h3><p class="muted">PDF, JPG, atau PNG hingga <?= round($limit / 1048576, 1) ?> MB. Pilih satu sumber berkas.</p>
<div class="fields file-options"><label><?= icon('folder-open-o') ?> Pilih file<input data-document type="file" name="file_upload" accept="application/pdf,image/jpeg,image/png"></label>
<label><?= icon('camera') ?> Gunakan kamera<input data-document type="file" name="file_scan" accept="image/jpeg,image/png" capture="environment"></label></div>
<p id="chosen-file" aria-live="polite"></p><button class="btn primary" type="submit"><?= icon('cloud-upload') ?> Simpan dokumen</button></form>
