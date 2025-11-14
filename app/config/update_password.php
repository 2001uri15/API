<?php
session_start();
require_once '../conn.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login/index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$current = $_POST['current_password'];
$new = $_POST['new_password'];

$sql = "SELECT password FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error en prepare(): " . $conn->error . " — SQL: " . $sql);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($hash);
$stmt->fetch();
$stmt->close();

if (!password_verify($current, $hash)) {
    die("Contraseña incorrecta");
}

$new_hash = password_hash($new, PASSWORD_DEFAULT);

$sql = "UPDATE usuarios SET password = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $new_hash, $user_id);
$stmt->execute();

header("Location: index.php?msg=pass_actualizada");
