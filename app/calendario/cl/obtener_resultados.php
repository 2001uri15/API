<?php
header('Content-Type: application/json');
session_start();
require_once '../../conn.php';

$idEncuesta = isset($_GET['idEncuesta']) ? (int)$_GET['idEncuesta'] : 0;

try {
    $sql = "SELECT o.id, o.texto, COUNT(v.id) as votos
            FROM Encuesta_Opciones o
            LEFT JOIN Encuesta_Votos v ON v.idOpcion = o.id
            WHERE o.idEncuesta = ?
            GROUP BY o.id, o.texto
            ORDER BY o.orden ASC";

    $stmt = $conn->prepare($sql);
    if (!$stmt) throw new Exception($conn->error);
    $stmt->bind_param('i', $idEncuesta);
    $stmt->execute();
    $res = $stmt->get_result();

    $resultados = [];
    while ($row = $res->fetch_assoc()) {
        $resultados[] = [
            'id' => (int)$row['id'],
            'texto' => $row['texto'],
            'votos' => (int)$row['votos']
        ];
    }

    echo json_encode(['success' => true, 'resultados' => $resultados]);

} catch (Exception $e) {
    error_log('obtener_resultados error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error al obtener resultados']);
}

?>
