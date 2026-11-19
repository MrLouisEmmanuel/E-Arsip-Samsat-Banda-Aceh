<?php
// Migrasi aditif untuk instalasi yang menggunakan tabel users terpadu.
if (PHP_SAPI !== 'cli') { http_response_code(403); exit; }
require __DIR__ . '/koneksi.php';
if (!$pdo->query("SHOW COLUMNS FROM riwayat LIKE 'riwayat_aksi'")->fetch()) {
    $pdo->exec("ALTER TABLE riwayat ADD riwayat_aksi VARCHAR(100) NOT NULL DEFAULT 'Upload/Edit'");
}
if (!$pdo->query("SHOW COLUMNS FROM arsip LIKE 'arsip_penerima'")->fetch()) {
    $pdo->exec("ALTER TABLE arsip ADD arsip_penerima INT NULL, ADD CONSTRAINT fk_arsip_penerima FOREIGN KEY (arsip_penerima) REFERENCES users(user_id) ON DELETE RESTRICT");
}
echo "Skema arsip siap.\n";
