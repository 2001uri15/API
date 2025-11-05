<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

session_start();
require_once '../../conn.php';

if (!isset($conn) || !($conn instanceof mysqli)) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos']);
    exit;
}

try {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['id'])) {
        throw new Exception('ID no proporcionado');
    }

    $id = (int)$input['id'];

    if ($id <= 0) {
        throw new Exception('ID inválido');
    }

    // Primero eliminar las referencias en CALENDARIO_Evento_Usuario
    $sqlDeleteRefs = "DELETE FROM CALENDARIO_Evento_Usuario WHERE idEvento = ?";
    $stmtRefs = $conn->prepare($sqlDeleteRefs);
    
    if (!$stmtRefs) {
        throw new Exception("Error preparando eliminación de referencias: " . $conn->error);
    }

    $stmtRefs->bind_param("i", $id);
    $stmtRefs->execute();
    $stmtRefs->close();

    // Luego eliminar el evento
    $sql = "DELETE FROM CALENDARIO_Eventos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Error preparando consulta: " . $conn->error);
    }

    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Evento eliminado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se encontró el evento']);
        }
    } else {
        throw new Exception("Error ejecutando consulta: " . $stmt->error);
    }

    $stmt->close();

} catch (Exception $e) {
    error_log("Error en eliminar_evento.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}
?>