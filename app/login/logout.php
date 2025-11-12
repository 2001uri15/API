<?php
session_start();
require_once 'cl.php';
require_once '../conn.php';

$login = new LogIn($conn);
$login->logout();

// Redirigir al login con mensaje de éxito
header('Location: /index.php?logout=success');
exit();
?>