<?php
session_start();

require_once '../conn.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

// Consultar rol del usuario en la base de datos
$stmt = $conn->prepare("SELECT rol FROM Usuarios WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user || $user['rol'] != 2) {
    header('Location: ../index.php');
    exit;
}


require_once '../conn.php';
require_once 'cl/Usuario.php';
require_once '../templates/header.php';
require_once '../templates/sidebar.php';

$usuarioObj = new Usuarios($conn);
$usuarios = $usuarioObj->getAllUsers();
?>

<h1>Panel de Administración - Usuarios</h1>

<table border="1" cellpadding="6">
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Email</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($u = $usuarios->fetch_assoc()) { ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= htmlspecialchars($u['username']) ?></td>
                <td><?= htmlspecialchars($u['nombre']) ?></td>
                <td><?= htmlspecialchars($u['apellidos']) ?></td>
                <td><?= htmlspecialchars($u['mail']) ?></td>
                <td><?= $u['rol'] == 2 ? 'Admin' : 'Usuario' ?></td>
                <td>
                    <a href="edit_admin.php?id=<?= $u['id'] ?>">Editar</a> |
                    <a href="delete_admin.php?id=<?= $u['id'] ?>"
                       onclick="return confirm('¿Seguro que deseas eliminar esta cuenta?');">
                       Eliminar
                    </a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php require_once '../templates/footer.php'; ?>
