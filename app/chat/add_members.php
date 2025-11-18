<?php
session_start();
if (!isset($_SESSION['user_id'])) exit;
require_once '../conn.php';

$groupId = $_POST['groupId'];
$members = json_decode($_POST['members']);
$userId = $_SESSION['user_id'];

// Verificar que el usuario es el creador del grupo
$query = "SELECT creador FROM Chat_Grupos WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $groupId);
$stmt->execute();
$stmt->bind_result($creador);
$stmt->fetch();
$stmt->close();

if ($creador != $userId) {
    echo "ERROR: No tienes permisos para añadir miembros";
    exit;
}

// Añadir miembros al grupo
$successCount = 0;
foreach ($members as $memberId) {
    // Verificar si el usuario ya es miembro
    $checkStmt = $conn->prepare("SELECT id FROM Chat_Grupo_Miembros WHERE idGrupo = ? AND idUsuario = ?");
    $checkStmt->bind_param("ii", $groupId, $memberId);
    $checkStmt->execute();
    $checkStmt->store_result();
    
    if ($checkStmt->num_rows == 0) {
        // Añadir miembro
        $insertStmt = $conn->prepare("INSERT INTO Chat_Grupo_Miembros (idGrupo, idUsuario) VALUES (?, ?)");
        $insertStmt->bind_param("ii", $groupId, $memberId);
        if ($insertStmt->execute()) {
            $successCount++;
        }
        $insertStmt->close();
    }
    $checkStmt->close();
}

echo "OK: Se añadieron $successCount miembros";
?>