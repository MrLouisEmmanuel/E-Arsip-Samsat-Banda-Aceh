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
    header('Location: dashboard?page=' . $page, true, 303);
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
    return match ($_SESSION['role'] ?? null) {
        'user' => ['a.arsip_penerima = ?', [$_SESSION['id']]],
        'admin', 'petugas' => ['1=1', []],
        default => ['1=0', []],
    };
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
    $path = dirname(__DIR__) . '/arsip/' . $file;
    return is_file($path) ? $path : null;
}
require_once __DIR__ . '/views/layout.php';

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
