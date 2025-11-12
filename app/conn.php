<?php
/*
 *  Documento para conectarse a la base de datos
 *  Fecha de creación: 9 de julio del 2025
 *  Autor: Asier Larrazabal
 */

// CONEXIÓN A BASE DE DATOS EXTERNA
$hostname = "db";  // Host externo
$username = "admin";
$password = "test";
$db = "database";
$port = 3306; // Puerto de MySQL

// Configuración con manejo de errores
$conn = mysqli_connect($hostname, $username, $password, $db, $port);

// Verificar conexión
if (!$conn) {
    error_log("Error de conexión a BD externa: " . mysqli_connect_error());
    die("Error conectando a la base de datos. Por favor, intenta más tarde.");
}

// Test de conexión (opcional - quitar en producción)
// echo "✅ Conectado a BD externa: " . $hostname;
?>
