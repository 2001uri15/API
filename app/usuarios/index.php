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

// Importamos la clase Usuarios
require_once 'cl/Usuario.php';
$usuarioObj = new Usuarios($conn);
$usuarios = $usuarioObj->getAllUsers();
?>

    <h1>Usuarios</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Email</th>
                <th>Rol</th>
                <th>#</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($usuarios as $usuario) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($usuario['id']) . "</td>";
                echo "<td>" . htmlspecialchars($usuario['username']) . "</td>";
                echo "<td>" . htmlspecialchars($usuario['nombre']) . "</td>";
                echo "<td>" . htmlspecialchars($usuario['apellidos']) . "</td>";
                echo "<td>" . htmlspecialchars($usuario['mail']) . "</td>";
                echo "<td>" . htmlspecialchars($usuario['rol']) . "</td>";
                echo "<td><a href='edit.php?id=" . urlencode($usuario['id']) . "'>Editar</a></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

<?php
// Incluir footer al final
require_once '../templates/footer.php';
?>