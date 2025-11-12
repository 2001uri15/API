<?php
session_start();

// Verificar si NO está logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login/index.php');
    exit;
}

// Incluir conexión a la base de datos
require_once '../conn.php';

// Incluir templates
require_once '../templates/header.php';
require_once '../templates/sidebar.php';
?>

    <h1>Chat</h1>
    

<?php
// Incluir footer al final
require_once '../templates/footer.php';
?>