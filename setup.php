<?php
if (PHP_SAPI !== 'cli') { http_response_code(403); exit('Jalankan setup.php melalui PHP CLI untuk instalasi baru.'); }
require __DIR__ . '/koneksi.php';
if ($pdo->query('SHOW TABLES')->fetch()) {
    fwrite(STDERR, "Instalasi awal memerlukan database kosong. Untuk skema terpadu yang sudah ada, jalankan php alter_db.php.\n");
    exit(1);
}
$pdo->exec(file_get_contents(__DIR__.'/arsip_kita.sql'));
echo "Instalasi selesai.\n";
