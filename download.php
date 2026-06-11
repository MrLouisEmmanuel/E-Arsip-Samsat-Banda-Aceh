<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['status'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Fetch file info
    $stmt = $pdo->prepare("SELECT arsip_file, arsip_nama FROM arsip WHERE arsip_id = ?");
    $stmt->execute([$id]);
    $arsip = $stmt->fetch();
    
    if ($arsip) {
        $filepath = 'arsip/' . $arsip['arsip_file'];
        
        if (file_exists($filepath)) {
            // Log to riwayat
            $user_id = $_SESSION['id'];
            $waktu = date('Y-m-d H:i:s');
            $aksi = 'Download';
            
            $log_stmt = $pdo->prepare("INSERT INTO riwayat (riwayat_waktu, riwayat_user, riwayat_arsip, riwayat_aksi) VALUES (?, ?, ?, ?)");
            $log_stmt->execute([$waktu, $user_id, $id, $aksi]);
            
            // Force download
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filepath));
            readfile($filepath);
            exit();
        } else {
            echo "File tidak ditemukan di server.";
        }
    } else {
        echo "Data arsip tidak ditemukan.";
    }
}
?>
