<?php
/**
 * Script de prueba para verificar que la sesión y conexión funcionan
 * GET /app/calendario/cl/test_polls.php
 */
header('Content-Type: application/json');
session_start();
require_once '../../conn.php';

$response = [
    'session_user_id' => $_SESSION['user_id'] ?? null,
    'session_rol' => $_SESSION['rol'] ?? null,
    'db_connected' => isset($conn) && ($conn instanceof mysqli),
    'db_error' => $conn->connect_error ?? null
];

// Intentar verificar si las tablas existen
if ($response['db_connected']) {
    $result = $conn->query("SHOW TABLES LIKE 'Encuestas'");
    $response['encuestas_table_exists'] = $result && $result->num_rows > 0;
    
    $result = $conn->query("SHOW TABLES LIKE 'Encuesta_Opciones'");
    $response['opciones_table_exists'] = $result && $result->num_rows > 0;
    
    $result = $conn->query("SHOW TABLES LIKE 'Encuesta_Votos'");
    $response['votos_table_exists'] = $result && $result->num_rows > 0;
}

echo json_encode($response, JSON_PRETTY_PRINT);

?>
