<?php
session_start();
require_once '../conn.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login/index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Borrar votos de encuestas creadas por el usuario
$conn->query("DELETE EV FROM Encuesta_Votos EV 
              JOIN Encuestas E ON EV.idEncuesta = E.id 
              WHERE E.creador = $user_id");

// Borrar opciones y encuestas creadas por el usuario
$conn->query("DELETE EO FROM Encuesta_Opciones EO 
              JOIN Encuestas E ON EO.idEncuesta = E.id 
              WHERE E.creador = $user_id");
$conn->query("DELETE FROM Encuestas WHERE creador = $user_id");

// Borrar eventos
$conn->query("DELETE FROM CALENDARIO_Evento_Usuario WHERE idUsuario = $user_id");

// Borrar eventos creados
$conn->query("DELETE FROM CALENDARIO_Eventos WHERE creador = $user_id");

// borrar usuario
$sql = "DELETE FROM Usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error en prepare(): " . $conn->error . " — SQL: " . $sql);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->close();

// Destruir sesión
session_unset();
session_destroy();

// Redirigir al login con mensaje
header("Location: ../login/index.php?msg=cuenta_eliminada");
exit;
