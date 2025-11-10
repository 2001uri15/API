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

$userId = $_SESSION['usuario_id'] ?? 1;

try {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['idEvento'])) {
        throw new Exception('ID de evento no proporcionado');
    }

    $idEvento = (int)$input['idEvento'];

    if ($idEvento <= 0) {
        throw new Exception('ID de evento inválido');
    }

    // Primero verificar si ya está asistiendo
    $sqlCheck = "SELECT 1 FROM CALENDARIO_Evento_Usuario WHERE idEvento = ? AND idUsuario = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    
    if (!$stmtCheck) {
        throw new Exception("Error preparando consulta de verificación: " . $conn->error);
    }

    $stmtCheck->bind_param("ii", $idEvento, $userId);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();
    $yaAsistiendo = ($resultCheck && $resultCheck->num_rows > 0);
    $stmtCheck->close();

    if ($yaAsistiendo) {
        // Eliminar asistencia
        $sqlDelete = "DELETE FROM CALENDARIO_Evento_Usuario WHERE idEvento = ? AND idUsuario = ?";
        $stmtDelete = $conn->prepare($sqlDelete);
        
        if (!$stmtDelete) {
            throw new Exception("Error preparando eliminación: " . $conn->error);
        }

        $stmtDelete->bind_param("ii", $idEvento, $userId);
        $stmtDelete->execute();
        $stmtDelete->close();
        
        echo json_encode(['success' => true, 'asistiendo' => false, 'message' => 'Has cancelado tu asistencia']);
    } else {
        // Agregar asistencia
        $sqlInsert = "INSERT INTO CALENDARIO_Evento_Usuario (idEvento, idUsuario) VALUES (?, ?)";
        $stmtInsert = $conn->prepare($sqlInsert);
        
        if (!$stmtInsert) {
            throw new Exception("Error preparando inserción: " . $conn->error);
        }

        $stmtInsert->bind_param("ii", $idEvento, $userId);
        $stmtInsert->execute();
        $stmtInsert->close();
        
        echo json_encode(['success' => true, 'asistiendo' => true, 'message' => 'Confirmada tu asistencia']);
    }

} catch (Exception $e) {
    error_log("Error en asistir_evento.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}
?>