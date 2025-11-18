<?php
session_start();
if (!isset($_SESSION['user_id'])) exit;
require_once '../conn.php';

$filter = $_GET['filter'] ?? 'todos';
$userId = $_SESSION['user_id'];

// Chats privados
if ($filter === 'todos' || $filter === 'privado') {
    $query = "SELECT DISTINCT u.id, u.username, 
              (SELECT mensaje FROM Chat_Mensajes 
               WHERE (idUsuario = u.id AND tipo = 'privado') 
               OR (idUsuario = u.id AND tipo = 'privado')
               ORDER BY id DESC LIMIT 1) as last_message
              FROM Usuarios u
              LEFT JOIN Chat_Mensajes cm ON (cm.idUsuario = u.id OR cm.idUsuario = ?)
              WHERE u.id != ? AND u.activo = 1
              ORDER BY last_message DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $userId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($user = $result->fetch_assoc()) {
        if ($user['id']) {
            echo "<div class='chat-item' data-chat-id='{$user['id']}' data-chat-type='privado'>
                    <div class='chat-item-header'>
                        <span class='chat-name'>{$user['username']}</span>
                        <span class='chat-type privado'>Privado</span>
                    </div>
                    <div class='last-message'>{$user['last_message']}</div>
                  </div>";
        }
    }
}

// Grupos
if ($filter === 'todos' || $filter === 'grupo') {
    $query = "SELECT g.id, g.nombre, g.descripcion,
              (SELECT mensaje FROM Chat_Mensajes 
               WHERE idGrupo = g.id 
               ORDER BY id DESC LIMIT 1) as last_message
              FROM Chat_Grupos g
              INNER JOIN Chat_Grupo_Miembros gm ON g.id = gm.idGrupo
              WHERE gm.idUsuario = ?
              ORDER BY last_message DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($group = $result->fetch_assoc()) {
        echo "<div class='chat-item' data-chat-id='{$group['id']}' data-chat-type='grupo' data-group-id='{$group['id']}'>
                <div class='chat-item-header'>
                    <span class='chat-name'>{$group['nombre']}</span>
                    <span class='chat-type grupo'>Grupo</span>
                </div>
                <div class='last-message'>{$group['last_message']}</div>
              </div>";
    }
}
?>