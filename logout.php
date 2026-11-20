<?php
require_once __DIR__ . '/app/session.php';
session_unset();
session_destroy();
header("Location: ./");
exit();
?>
