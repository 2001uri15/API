<?php
session_start();
if (!isset($_SESSION['user_id'])) exit;

require_once '../conn.php';

$query = "
    SELECT Chat_Mensajes.*, Usuarios.username 
    FROM Chat_Mensajes
    JOIN Usuarios ON Chat_Mensajes.idUsuario = Usuarios.id
    ORDER BY Chat_Mensajes.id ASC
";

$result = $conn->query($query);

while ($row = $result->fetch_assoc()) {
    echo "
        <div class='msg'>
            <span class='autor'>{$row['username']}:</span> {$row['mensaje']}<br>
            <span class='fecha'>{$row['fecha']}</span>
        </div>
    ";
}
