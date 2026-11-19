<?php
const ARCHIVE_STATUSES = ['Diterima Petugas', 'Sedang Diverifikasi', 'Menunggu Pembayaran', 'Arsip Tersimpan'];
function h($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function csrf_field(): string {
    $_SESSION['csrf'] ??= bin2hex(random_bytes(32));
    return '<input type="hidden" name="csrf" value="' . h($_SESSION['csrf']) . '">';
}
function notice(string $message, string $page, string $type = 'success'): void {
    $_SESSION['notice'] = [$type, $message];
    header('Location: dashboard.php?page=' . $page, true, 303);
    exit;
}
function query_rows(PDO $pdo, string $sql, array $params = []): array {
    $statement = $pdo->prepare($sql);
    $statement->execute($params);
    return $statement->fetchAll();
}
function log_activity(PDO $pdo, int $archive, string $action): void {
    $pdo->prepare('INSERT INTO riwayat (riwayat_waktu, riwayat_user, riwayat_arsip, riwayat_aksi) VALUES (NOW(), ?, ?, ?)')
        ->execute([$_SESSION['id'], $archive, $action]);
}
function archive_access(): array {
    return $_SESSION['role'] === 'user'
        ? ['(a.arsip_penerima IS NULL OR a.arsip_penerima = ?)', [$_SESSION['id']]]
        : ['1=1', []];
}
function current_account(PDO $pdo): ?array {
    if (($_SESSION['status'] ?? '') !== 'login') return null;
    $rows = query_rows($pdo, 'SELECT user_nama, user_role FROM users WHERE user_id=?', [$_SESSION['id']]);
    if (!$rows) { session_unset(); return null; }
    $_SESSION['role'] = $rows[0]['user_role'];
    $_SESSION['nama'] = $rows[0]['user_nama'];
    return $rows[0];
}
function archive_path(string $file): ?string {
    if ($file === '' || basename(str_replace('\\', '/', $file)) !== $file) return null;
    $path = __DIR__ . '/arsip/' . $file;
    return is_file($path) ? $path : null;
}
function icon(string $name): string {
    return '<i class="fa fa-' . h($name) . '" aria-hidden="true"></i>';
}
function brand(): string {
    return '<a class="brand" href="index.php"><img class="brand-mark" src="gambar/depan/11.png" alt="" width="46" height="46"><span>E-Arsip<small>SAMSAT BANDA ACEH</small></span></a>';
}
function head_assets(): void { ?>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="icon" type="image/png" sizes="125x125" href="assets/img/logo/logo6.png"><link rel="stylesheet" href="assets/css/font-awesome.min.css">
<link rel="stylesheet" href="assets/css/modern-style.css"><script defer src="assets/js/portal.js"></script>
<?php }

function upload_limit(): int {
    $limits = [10 * 1024 * 1024];
    foreach (['upload_max_filesize', 'post_max_size'] as $setting) {
        $quantity = trim(ini_get($setting));
        if (preg_match('/^(\d+)\s*([kmg]?)$/i', $quantity, $parts) && (int)$parts[1] > 0) {
            $unit = ['', 'k', 'm', 'g'];
            $limits[] = (int)$parts[1] * (1024 ** array_search(strtolower($parts[2]), $unit, true));
        }
    }
    return min($limits);
}
