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

$titulo = trim($input['titulo'] ?? '');
$descripcion = trim($input['descripcion'] ?? '');
$opciones = $input['opciones'] ?? [];
$idEvento = isset($input['idEvento']) ? (int)$input['idEvento'] : null;

if ($titulo === '' || !is_array($opciones) || count($opciones) === 0) {
    echo json_encode(['success' => false, 'message' => 'Titulo y al menos una opción son requeridos']);
    exit;
}

try {
    $conn->begin_transaction();

    $sql = "INSERT INTO Encuestas (idEvento, titulo, descripcion, creador) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) throw new Exception($conn->error);
    $stmt->bind_param('issi', $idEvento, $titulo, $descripcion, $userId);
    if (!$stmt->execute()) throw new Exception($stmt->error);
    $idEncuesta = $stmt->insert_id;
    $stmt->close();

    $sqlOpt = "INSERT INTO Encuesta_Opciones (idEncuesta, texto, orden) VALUES (?, ?, ?)";
    $stmtOpt = $conn->prepare($sqlOpt);
    if (!$stmtOpt) throw new Exception($conn->error);

    $orden = 0;
    foreach ($opciones as $opt) {
        $texto = trim((string)$opt);
        if ($texto === '') continue;
        $stmtOpt->bind_param('isi', $idEncuesta, $texto, $orden);
        if (!$stmtOpt->execute()) throw new Exception($stmtOpt->error);
        $orden++;
    }
    $stmtOpt->close();

    $conn->commit();

    echo json_encode(['success' => true, 'idEncuesta' => $idEncuesta]);

} catch (Exception $e) {
    if ($conn->in_transaction) $conn->rollback();
    error_log('crear_encuesta error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error al crear la encuesta']);
}

?>
