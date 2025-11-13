<?php
header('Content-Type: application/json');
session_start();
require_once '../../conn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    echo json_encode(['success' => false, 'message' => 'Entrada JSON inválida']);
    exit;
}

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    echo json_encode(['success' => false, 'message' => 'No autenticado']);
    exit;
}

$idEncuesta = isset($input['idEncuesta']) ? (int)$input['idEncuesta'] : 0;
$idOpcion = isset($input['idOpcion']) ? (int)$input['idOpcion'] : 0;

if ($idEncuesta <= 0 || $idOpcion <= 0) {
    echo json_encode(['success' => false, 'message' => 'Parámetros inválidos']);
    exit;
}

try {
    // Verificar si ya votó (voto único por encuesta)
    $sqlCheck = "SELECT id FROM Encuesta_Votos WHERE idEncuesta = ? AND idUsuario = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    if (!$stmtCheck) throw new Exception($conn->error);
    $stmtCheck->bind_param('ii', $idEncuesta, $userId);
    $stmtCheck->execute();
    $res = $stmtCheck->get_result();

    if ($res && $res->num_rows > 0) {
        // Ya votó -> actualizar el voto
        $row = $res->fetch_assoc();
        $idVoto = (int)$row['id'];
        $stmtUpd = $conn->prepare("UPDATE Encuesta_Votos SET idOpcion = ?, fechaVoto = NOW() WHERE id = ?");
        if (!$stmtUpd) throw new Exception($conn->error);
        $stmtUpd->bind_param('ii', $idOpcion, $idVoto);
        if (!$stmtUpd->execute()) throw new Exception($stmtUpd->error);
        $stmtUpd->close();
    } else {
        $stmtIns = $conn->prepare("INSERT INTO Encuesta_Votos (idEncuesta, idOpcion, idUsuario) VALUES (?, ?, ?)");
        if (!$stmtIns) throw new Exception($conn->error);
        $stmtIns->bind_param('iii', $idEncuesta, $idOpcion, $userId);
        if (!$stmtIns->execute()) throw new Exception($stmtIns->error);
        $stmtIns->close();
    }

    if ($stmtCheck) $stmtCheck->close();

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    error_log('votar_encuesta error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error al procesar el voto']);
}

?>
