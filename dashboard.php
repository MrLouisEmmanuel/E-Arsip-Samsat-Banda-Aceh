<?php
session_start();
require 'koneksi.php';
require_once 'portal.php';
if (!current_account($pdo)) { header('Location: login.php'); exit; }
$role = $_SESSION['role'];
$menu = ['home'=>['Ringkasan','th-large'], 'arsip'=>['Data arsip','folder-open-o']];
if ($role === 'petugas') $menu['upload'] = ['Unggah dokumen','cloud-upload'];
if ($role !== 'user') $menu['kategori'] = ['Kategori arsip','tags'];
if ($role === 'admin') $menu['users'] = ['Pengguna','users'];
$menu += ['riwayat'=>['Riwayat aktivitas','history'], 'profil'=>['Profil saya','user-o']];
$page = $_GET['page'] ?? 'home';
if ($page === 'scan' && $role === 'petugas') { header('Location: ?page=upload'); exit; }
$allowed = is_string($page) && (isset($menu[$page]) || $page === 'detail');
$title = $allowed ? ($menu[$page][0] ?? 'Detail dokumen') : 'Halaman tidak tersedia';
if (!$allowed) http_response_code(403);
$flash = $_SESSION['notice'] ?? null;
unset($_SESSION['notice']);
ob_start();
if (!$allowed) { echo '<p class="panel">Halaman ini tidak tersedia untuk akun Anda.</p>'; }
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && (!isset($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], (string)($_POST['csrf'] ?? '')))) {
    http_response_code(400);
    echo '<p class="alert alert-danger">Formulir kedaluwarsa atau berkas terlalu besar. Muat ulang halaman lalu coba kembali.</p>';
} else {
    try { require __DIR__ . '/pages/' . $page . '.php'; }
    catch (PDOException $error) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        error_log($error->getMessage());
        $flash = ['error','Data belum dapat diproses. Periksa isian atau coba kembali.'];
    }
}
$content = ob_get_clean();
?>
<!doctype html><html lang="id"><head><?php head_assets(); ?><title><?= h($title) ?> | E-Arsip</title></head>
<body class="desk"><a class="skip" href="#utama">Ke konten utama</a>
<aside class="side"><div class="side-brand"><?= brand() ?><button type="button" data-menu aria-expanded="false" aria-controls="side-nav" class="mobile-menu"><?= icon('bars') ?> Menu</button></div>
<nav id="side-nav" aria-label="Ruang kerja"><p class="nav-label">RUANG KERJA</p>
<?php foreach ($menu as $key=>[$label,$symbol]): $active = $page === $key || ($page === 'detail' && $key === 'arsip'); ?>
<a href="?page=<?= $key ?>" <?= $active ? 'aria-current="page"' : '' ?>><?= icon($symbol) ?> <?= h($label) ?></a>
<?php endforeach; ?><a class="signout" href="logout.php"><?= icon('sign-out') ?> Keluar</a></nav></aside>
<main id="utama" class="workspace"><header class="desk-head"><div><p class="eyebrow">E-ARSIP &nbsp;/&nbsp; RUANG KERJA</p><h1><?= h($title) ?></h1></div><a class="account" href="?page=profil"><span class="avatar"><?= h(mb_substr($_SESSION['nama'],0,1)) ?></span><span><?= h($_SESSION['nama']) ?><small><?= $role === 'user' ? 'Wajib pajak' : ucfirst($role) ?></small></span></a></header>
<?php if ($flash): ?><div role="status" class="alert <?= $flash[0] === 'error' ? 'alert-danger':'alert-success' ?>"><?= h($flash[1]) ?></div><?php endif; ?>
<div class="page-content"><?= $content ?></div></main></body></html>
