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

$userId = $_SESSION['user_id'] ?? 1;

try {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['nombre']) || !isset($input['fecha']) || !isset($input['horaIni']) || !isset($input['horaFin'])) {
        throw new Exception('Datos incompletos');
    }

    $nombre = trim($input['nombre']);
    $descripcion = trim($input['descripcion'] ?? '');
    $fecha = $input['fecha'];
    $horaIni = $input['horaIni'];
    $horaFin = $input['horaFin'];

    // Validaciones
    if (empty($nombre) || empty($fecha) || empty($horaIni) || empty($horaFin)) {
        throw new Exception('Todos los campos obligatorios deben ser completados');
    }

    $sql = "INSERT INTO CALENDARIO_Eventos (nombre, descripcion, fecha, horaIni, horaFin) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Error preparando consulta: " . $conn->error);
    }

    $stmt->bind_param("sssss", $nombre, $descripcion, $fecha, $horaIni, $horaFin);
    
    if ($stmt->execute()) {
        $nuevoId = $stmt->insert_id;
        echo json_encode(['success' => true, 'id' => $nuevoId, 'message' => 'Evento agregado correctamente']);
    } else {
        throw new Exception("Error ejecutando consulta: " . $stmt->error);
    }

    $stmt->close();

} catch (Exception $e) {
    error_log("Error en agregar_evento.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}
?>