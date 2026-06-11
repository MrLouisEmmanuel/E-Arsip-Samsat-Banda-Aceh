<?php
require 'koneksi.php';

try {
    $pdo->exec("ALTER TABLE riwayat ADD COLUMN riwayat_aksi VARCHAR(100) NOT NULL DEFAULT 'Upload/Edit'");
    echo "SUKSES";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column') !== false) {
        echo "SUKSES";
    } else {
        echo "GAGAL: " . $e->getMessage();
    }
}
?>
