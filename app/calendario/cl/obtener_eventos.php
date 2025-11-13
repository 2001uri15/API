<?php
// Headers CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

session_start();
require_once '../../conn.php';

// Verificar si la conexión está establecida
if (!isset($conn) || !($conn instanceof mysqli)) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos']);
    exit;
}

// Use the unified session key set by login. If not logged in, use 0 so attendance checks return false
$userId = $_SESSION['user_id'] ?? 0;

try {
    // Obtener parámetros de filtro por fecha
    $fecha_inicio = $_GET['fecha_inicio'] ?? null;
    $fecha_fin = $_GET['fecha_fin'] ?? null;

    // Construir la consulta base
    $sql = "SELECT e.* FROM CALENDARIO_Eventos e WHERE 1=1";
    $params = [];
    $types = "";

    // Agregar filtros de fecha si están presentes
    if ($fecha_inicio && $fecha_fin) {
        $sql .= " AND e.fecha BETWEEN ? AND ?";
        $params[] = $fecha_inicio;
        $params[] = $fecha_fin;
        $types .= "ss";
    }

    $sql .= " ORDER BY e.fecha, e.horaIni";

    // Preparar y ejecutar la consulta principal
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Error preparando consulta: " . $conn->error);
    }

    // Bind parameters si hay filtros
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    if (!$stmt->execute()) {
        throw new Exception("Error ejecutando consulta: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $eventos = [];

    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // Verificar si el usuario actual está asistiendo a este evento
            $sqlAsistencia = "SELECT 1 FROM CALENDARIO_Evento_Usuario WHERE idEvento = ? AND idUsuario = ?";
            $stmtAsistencia = $conn->prepare($sqlAsistencia);
            
            if (!$stmtAsistencia) {
                throw new Exception("Error preparando consulta de asistencia: " . $conn->error);
            }

            $stmtAsistencia->bind_param("ii", $row['id'], $userId);
            
            if (!$stmtAsistencia->execute()) {
                throw new Exception("Error ejecutando consulta de asistencia: " . $stmtAsistencia->error);
            }

            $resultAsistencia = $stmtAsistencia->get_result();
            $row['asistiendo'] = ($resultAsistencia && $resultAsistencia->num_rows > 0) ? 1 : 0;
            
            // Asegurar que los tipos de datos sean correctos para JSON
            $row['id'] = (int)$row['id'];
            $row['asistiendo'] = (bool)$row['asistiendo'];
            
            $eventos[] = $row;
            
            $stmtAsistencia->close();
        }
    }

    // Si no hay eventos, devolver array vacío
    echo json_encode($eventos);
    
    $stmt->close();

} catch (Exception $e) {
    error_log("Error en obtener_eventos.php: " . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => 'Error al obtener eventos: ' . $e->getMessage(),
        'eventos' => []
    ]);
} finally {
    // Cerrar conexión si está abierta
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}
?>