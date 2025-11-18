<?php
session_start();
if (!isset($_SESSION['user_id'])) exit;
require_once '../conn.php';

$chatType = $_POST['chatType'];
$userId = $_SESSION['user_id'];

if ($chatType === 'privado') {
    $chatId = $_POST['chatId'];
    
    // Obtener información del usuario
    $query = "SELECT id, username, nombre, apellidos 
              FROM Usuarios 
              WHERE id = ? AND activo = 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $chatId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($user = $result->fetch_assoc()) {
        echo "<h4>{$user['username']}</h4>";
        echo "<small>{$user['nombre']} {$user['apellidos']}</small>";
    } else {
        echo "<p>Usuario no encontrado</p>";
    }
    
} else if ($chatType === 'grupo') {
    $groupId = $_POST['groupId'];
    
    // Obtener información del grupo
    $query = "SELECT g.nombre, g.descripcion, u.username as creador,
              (SELECT COUNT(*) FROM Chat_Grupo_Miembros WHERE idGrupo = g.id) as total_miembros
              FROM Chat_Grupos g
              JOIN Usuarios u ON g.creador = u.id
              WHERE g.id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $groupId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($group = $result->fetch_assoc()) {
        echo "<h4>{$group['nombre']}</h4>";
        if (!empty($group['descripcion'])) {
            echo "<p>{$group['descripcion']}</p>";
        }
        echo "<small>Creado por: {$group['creador']} | Miembros: {$group['total_miembros']}</small>";
        
        // Botón para añadir miembros (solo el creador puede añadir)
        $queryCreador = "SELECT creador FROM Chat_Grupos WHERE id = ?";
        $stmtCreador = $conn->prepare($queryCreador);
        $stmtCreador->bind_param("i", $groupId);
        $stmtCreador->execute();
        $stmtCreador->bind_result($creadorId);
        $stmtCreador->fetch();
        $stmtCreador->close();
        
        if ($creadorId == $userId) {
            echo "<br><button class='btn btn-sm btn-outline-primary' onclick='chatSystem.showMemberModal()' style='margin-top: 5px;'>Añadir miembros</button>";
        }
        
    } else {
        echo "<p>Grupo no encontrado</p>";
    }
    
} else {
    echo "<p>Selecciona un chat para empezar a conversar</p>";
}
?>