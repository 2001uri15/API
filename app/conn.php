<?php
/*
 *  Documento para conectarse a la base de datos
 *  Fecha de creación: 9 de julio del 2025
 *  Autor: Asier Larrazabal
 */

// CONEXIÓN A BASE DE DATOS EXTERNA
$username = "admin";
$password = "test";
$db = "database";
$port = 3306; // Puerto de MySQL


$hosts = [];
if (!empty(getenv('DB_HOST'))) $hosts[] = getenv('DB_HOST');
$hosts = array_merge($hosts, ['db', '127.0.0.1', 'localhost']);

$conn = null;
foreach ($hosts as $hostname) {
    if (!$hostname) continue;
    $c = @mysqli_connect($hostname, $username, $password, $db, $port);
    if ($c) {
        $conn = $c;
        break;
    }
}

if (!$conn) {
    error_log("Error de conexión a BD en hosts: " . implode(',', $hosts) . " - " . mysqli_connect_error());
    
    $conn = null;
} else {
    // ensure UTF8 charset
    mysqli_set_charset($conn, 'utf8mb4');
}

// Test de conexión (opcional - quitar en producción)
// echo "Conectado a BD externa: " . $hostname;
?>
