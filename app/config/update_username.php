<?php
session_start();
require_once '../conn.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login/index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$new_username = $_POST['new_username'];

$sql = "UPDATE usuarios SET username = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $new_username, $user_id);

$stmt->execute();
header("Location: index.php?msg=nombre_actualizado");
