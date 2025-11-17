<?php
session_start();



require_once '../conn.php';
require_once 'cl/Usuario.php';

if (!isset($_GET['id'])) {
    die("ID no recibido");
}

$usuarioObj = new Usuarios($conn);

// Evitar que el admin se borre a sí mismo
if ($_GET['id'] == $_SESSION['user_id']) {
    die("No puedes eliminar tu propia cuenta.");
}

$usuarioObj->deleteUser($_GET['id']);

header("Location: index.php");
exit;
