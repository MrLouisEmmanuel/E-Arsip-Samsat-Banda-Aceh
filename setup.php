<?php
require 'koneksi.php';

try {
    $sql = file_get_contents('arsip_kita.sql');
    
    // Matikan check foreign key agar bisa drop table
    $pdo->exec("SET FOREIGN_KEY_CHECKS=0;");
    
    // Drop tabel lama jika masih ada
    $pdo->exec("DROP TABLE IF EXISTS `admin`");
    $pdo->exec("DROP TABLE IF EXISTS `petugas`");
    $pdo->exec("DROP TABLE IF EXISTS `user`");
    $pdo->exec("DROP TABLE IF EXISTS `riwayat`");
    $pdo->exec("DROP TABLE IF EXISTS `arsip`");
    $pdo->exec("DROP TABLE IF EXISTS `kategori`");
    $pdo->exec("DROP TABLE IF EXISTS `users`");

    // Eksekusi skrip SQL yang baru
    $pdo->exec($sql);
    
    $pdo->exec("SET FOREIGN_KEY_CHECKS=1;");

    echo "SUKSES";
} catch (PDOException $e) {
    echo "GAGAL: " . $e->getMessage();
}
?>
