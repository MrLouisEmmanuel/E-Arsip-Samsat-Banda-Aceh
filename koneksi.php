<?php
$host = getenv('EARSIP_DB_HOST') ?: 'localhost';
$dbname = getenv('EARSIP_DB_NAME') ?: 'arsip_kita';
$username = getenv('EARSIP_DB_USER') ?: 'root';
$password = getenv('EARSIP_DB_PASSWORD') ?: '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set default fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    error_log($e->getMessage());
    http_response_code(503);
    exit("Database sedang tidak tersedia.");
}
?>