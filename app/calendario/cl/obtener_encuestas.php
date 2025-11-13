<?php
header('Content-Type: application/json');
session_start();
require_once '../../conn.php';

$idEvento = isset($_GET['idEvento']) ? (int)$_GET['idEvento'] : 0;
$userId = $_SESSION['user_id'] ?? 0;

try {
    $sql = "SELECT * FROM Encuestas WHERE idEvento = ? ORDER BY fechaCreado DESC";
    $stmt = $conn->prepare($sql);
    if (!$stmt) throw new Exception($conn->error);
    $stmt->bind_param('i', $idEvento);
    $stmt->execute();
    $res = $stmt->get_result();

    $encuestas = [];
    while ($row = $res->fetch_assoc()) {
        $idEncuesta = (int)$row['id'];
        // obtener opciones
        $optsStmt = $conn->prepare("SELECT id, texto, orden FROM Encuesta_Opciones WHERE idEncuesta = ? ORDER BY orden ASC");
        $optsStmt->bind_param('i', $idEncuesta);
        $optsStmt->execute();
        $optsRes = $optsStmt->get_result();
        $opciones = [];
        while ($opt = $optsRes->fetch_assoc()) {
            $opciones[] = $opt;
        }
        $optsStmt->close();

        // verificar si el usuario ya votó
        $votoStmt = $conn->prepare("SELECT idOpcion FROM Encuesta_Votos WHERE idEncuesta = ? AND idUsuario = ? LIMIT 1");
        $votoStmt->bind_param('ii', $idEncuesta, $userId);
        $votoStmt->execute();
        $votoRes = $votoStmt->get_result();
        $votoSeleccionado = null;
        if ($votoRes && $votoRes->num_rows > 0) {
            $v = $votoRes->fetch_assoc();
            $votoSeleccionado = (int)$v['idOpcion'];
        }
        $votoStmt->close();

        $encuestas[] = [
            'id' => $idEncuesta,
            'titulo' => $row['titulo'],
            'descripcion' => $row['descripcion'],
            'tipo' => $row['tipo'],
            'opciones' => $opciones,
            'votoSeleccionado' => $votoSeleccionado
        ];
    }

    echo json_encode(['success' => true, 'encuestas' => $encuestas]);

} catch (Exception $e) {
    error_log('obtener_encuestas error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error al obtener encuestas']);
}

?>
