<?php
session_start();
if (!isset($_SESSION['user_id'])) exit;

require_once '../conn.php';

$mensaje = trim($_POST['mensaje']);
$idUsuario = $_SESSION['user_id'];

if ($mensaje === "") exit;

$stmt = $conn->prepare("INSERT INTO Chat_Mensajes (idUsuario, mensaje) VALUES (?, ?)");
$stmt->bind_param("is", $idUsuario, $mensaje);
$stmt->execute();
