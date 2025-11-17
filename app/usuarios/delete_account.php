<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 2) {
    header("Location: ../index.php");
    exit;
}

require_once '../conn.php';
require_once 'cl/Usuario.php';

if (!isset($_GET['id'])) {
    die("ID no recibido");
}

$usuarioObj = new Usuarios($conn);

// Impedir que un admin se borre a sí mismo
if ($_GET['id'] == $_SESSION['user_id']) {
    die("Error: No puedes eliminar tu propia cuenta.");
}

$usuarioObj->deleteUser($_GET['id']);

header("Location: index.php");
exit;
