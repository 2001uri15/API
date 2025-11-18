<?php
session_start();
if (!isset($_SESSION['user_id'])) exit;
require_once '../conn.php';

$mensaje = trim($_POST['mensaje']);
$idUsuario = $_SESSION['user_id'];
$chatType = $_POST['chatType'];

if (empty($mensaje)) {
    echo "ERROR: Mensaje vacío";
    exit;
}

if ($chatType === 'privado') {
    $idDestinatario = $_POST['idUsuario'];
    
    // Para mensaje privado, guardamos el mensaje con el ID del usuario que lo envía
    $stmt = $conn->prepare("INSERT INTO Chat_Mensajes (idUsuario, mensaje, tipo) VALUES (?, ?, 'privado')");
    $stmt->bind_param("is", $idUsuario, $mensaje);
    
} else if ($chatType === 'grupo') {
    $idGrupo = $_POST['idGrupo'];
    
    $stmt = $conn->prepare("INSERT INTO Chat_Mensajes (idUsuario, idGrupo, mensaje, tipo) VALUES (?, ?, ?, 'grupo')");
    $stmt->bind_param("iis", $idUsuario, $idGrupo, $mensaje);
    
} else {
    echo "ERROR: Tipo de chat inválido";
    exit;
}

if ($stmt->execute()) {
    echo "OK";
} else {
    echo "ERROR: No se pudo enviar el mensaje";
}
?>