<?php
session_start();
if (!isset($_SESSION['user_id'])) exit;
require_once '../conn.php';

$nombre = trim($_POST['nombre']);
$descripcion = trim($_POST['descripcion']);
$creador = $_SESSION['user_id'];

if ($nombre === "") exit;

// Crear grupo
$stmt = $conn->prepare("INSERT INTO Chat_Grupos (nombre, descripcion, creador) VALUES (?, ?, ?)");
$stmt->bind_param("ssi", $nombre, $descripcion, $creador);
$stmt->execute();

$groupId = $conn->insert_id;

// Añadir creador como miembro
$stmt = $conn->prepare("INSERT INTO Chat_Grupo_Miembros (idGrupo, idUsuario) VALUES (?, ?)");
$stmt->bind_param("ii", $groupId, $creador);
$stmt->execute();

// Retornar el ID del grupo creado
echo $groupId;
?>