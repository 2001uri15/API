<?php
/**
 * Script para inicializar tablas de encuestas si no existen
 * Ejecutar una sola vez: GET /app/calendario/cl/init_polls.php
 */
header('Content-Type: application/json');
session_start();
require_once '../../conn.php';

// Verificar autenticación (solo admin)
$isAdmin = isset($_SESSION['rol']) && $_SESSION['rol'] == 2;
if (!$isAdmin) {
    echo json_encode(['success' => false, 'message' => 'Acceso denegado - solo admins']);
    exit;
}

try {
    // Crear tabla Encuestas
    $sqlEncuestas = "CREATE TABLE IF NOT EXISTS `Encuestas` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `idEvento` INT DEFAULT NULL,
      `titulo` VARCHAR(255) NOT NULL,
      `descripcion` TEXT,
      `creador` INT DEFAULT NULL,
      `tipo` VARCHAR(20) DEFAULT 'votacion',
      `fechaCreado` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
      INDEX (`idEvento`)
    )";
    if (!$conn->query($sqlEncuestas)) {
        throw new Exception("Error creando tabla Encuestas: " . $conn->error);
    }

    // Crear tabla Encuesta_Opciones
    $sqlOpciones = "CREATE TABLE IF NOT EXISTS `Encuesta_Opciones` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `idEncuesta` INT NOT NULL,
      `texto` VARCHAR(255) NOT NULL,
      `orden` INT DEFAULT 0,
      FOREIGN KEY (`idEncuesta`) REFERENCES `Encuestas`(`id`) ON DELETE CASCADE
    )";
    if (!$conn->query($sqlOpciones)) {
        throw new Exception("Error creando tabla Encuesta_Opciones: " . $conn->error);
    }

    // Crear tabla Encuesta_Votos
    $sqlVotos = "CREATE TABLE IF NOT EXISTS `Encuesta_Votos` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `idEncuesta` INT NOT NULL,
      `idOpcion` INT NOT NULL,
      `idUsuario` INT NOT NULL,
      `fechaVoto` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (`idEncuesta`) REFERENCES `Encuestas`(`id`) ON DELETE CASCADE,
      FOREIGN KEY (`idOpcion`) REFERENCES `Encuesta_Opciones`(`id`) ON DELETE CASCADE,
      UNIQUE KEY `unique_encuesta_usuario` (`idEncuesta`, `idUsuario`)
    )";
    if (!$conn->query($sqlVotos)) {
        throw new Exception("Error creando tabla Encuesta_Votos: " . $conn->error);
    }

    echo json_encode(['success' => true, 'message' => 'Tablas de encuestas inicializadas correctamente']);

} catch (Exception $e) {
    error_log('init_polls error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

?>
