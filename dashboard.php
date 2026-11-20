<?php
require_once __DIR__ . '/app/session.php';
require 'koneksi.php';
require_once __DIR__ . '/app/portal.php';
if (!current_account($pdo)) { header('Location: login'); exit; }
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
require __DIR__ . '/app/views/dashboard.php';
