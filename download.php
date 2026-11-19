<?php
session_start();
require 'koneksi.php';
require_once 'portal.php';
if (!current_account($pdo)) { header('Location: login.php'); exit; }
[$scope,$params] = archive_access();
$id = (int)($_GET['id'] ?? 0);
$a = query_rows($pdo,"SELECT arsip_nama, arsip_file FROM arsip a WHERE arsip_id=? AND $scope",array_merge([$id],$params))[0] ?? null;
if (!$a || !($path = archive_path($a['arsip_file']))) { http_response_code(404); exit('Berkas tidak ditemukan.'); }
$mime = (new finfo(FILEINFO_MIME_TYPE))->file($path);
$preview = isset($_GET['preview']);
if ($preview && !in_array($mime,['application/pdf','image/jpeg','image/png'],true)) { http_response_code(415); exit; }
if (!$preview) log_activity($pdo,$id,'Download');
session_write_close();
$filename = $a['arsip_nama'] . '.' . pathinfo($path,PATHINFO_EXTENSION);
header('X-Content-Type-Options: nosniff');
header('Cache-Control: private, no-store');
header('Content-Type: ' . ($preview ? $mime : 'application/octet-stream'));
header("Content-Disposition: " . ($preview ? 'inline':'attachment') . "; filename*=UTF-8''" . rawurlencode($filename));
header('Content-Length: ' . filesize($path));
readfile($path);
