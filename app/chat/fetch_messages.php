<?php
session_start();
if (!isset($_SESSION['user_id'])) exit;
require_once '../conn.php';

$chatType = $_POST['chatType'];
$userId = $_SESSION['user_id'];

if ($chatType === 'privado') {
    $otherUserId = $_POST['chatId'];
    
    // Para chat privado: mostrar mensajes en ambas direcciones
    $query = "SELECT cm.*, u.username 
              FROM Chat_Mensajes cm 
              JOIN Usuarios u ON cm.idUsuario = u.id 
              WHERE cm.tipo = 'privado' 
              AND (
                (cm.idUsuario = ?) OR 
                (cm.idUsuario = ?)
              )
              ORDER BY cm.fecha ASC";
              
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $userId, $otherUserId);
    
} else if ($chatType === 'grupo') {
    $groupId = $_POST['groupId'];
    
    $query = "SELECT cm.*, u.username 
              FROM Chat_Mensajes cm 
              JOIN Usuarios u ON cm.idUsuario = u.id 
              WHERE cm.tipo = 'grupo' AND cm.idGrupo = ?
              ORDER BY cm.fecha ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $groupId);
    
} else {
    echo "<div class='no-messages'>Selecciona un chat para empezar</div>";
    exit;
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // IMPORTANTE: Verificar si el mensaje es propio comparando el idUsuario
        $isOwn = ($row['idUsuario'] == $userId);
        $class = $isOwn ? 'own' : 'other';
        $fecha = date('H:i', strtotime($row['fecha']));
        
        // Debug en el HTML
        $debugInfo = "<!-- UserID: {$row['idUsuario']}, CurrentUser: $userId, IsOwn: " . ($isOwn ? 'YES' : 'NO') . " -->";
        
        echo $debugInfo;
        echo "<div class='message $class'>
                <div class='message-header'>
                    <span class='message-sender'>{$row['username']}</span>
                    <span class='message-time'>{$fecha}</span>
                </div>
                <div class='message-content'>{$row['mensaje']}</div>
              </div>";
    }
} else {
    echo "<div class='no-messages'>No hay mensajes aún. ¡Envía el primero!</div>";
}
?>