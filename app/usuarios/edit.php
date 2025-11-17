    <?php
    session_start();

    if (!isset($_SESSION['user_id']) || $_SESSION['rol'] != 2) {
        header('Location: ../index.php');
        exit;
    }

    require_once '../conn.php';
    require_once 'cl/Usuario.php';

    $usuarioObj = new Usuarios($conn);

    if (!isset($_GET['id'])) {
        die("ID no especificado");
    }

    $usuario = $usuarioObj->getUserById($_GET['id']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $usuarioObj->updateUser(
            $_POST['id'],
            $_POST['username'],
            $_POST['nombre'],
            $_POST['apellidos'],
            $_POST['mail'],
            $_POST['rol']
        );
        header('Location: index.php');
        exit;
    }
    ?>

    <h1>Editar usuario</h1>

    <form method="POST">
        <input type="hidden" name="id" value="<?= $usuario['id'] ?>">

        Username: <input type="text" name="username" value="<?= $usuario['username'] ?>"><br><br>
        Nombre: <input type="text" name="nombre" value="<?= $usuario['nombre'] ?>"><br><br>
        Apellidos: <input type="text" name="apellidos" value="<?= $usuario['apellidos'] ?>"><br><br>
        Email: <input type="email" name="mail" value="<?= $usuario['mail'] ?>"><br><br>

        Rol:
        <select name="rol">
            <option value="1" <?= $usuario['rol'] == 1 ? 'selected' : '' ?>>Usuario</option>
            <option value="2" <?= $usuario['rol'] == 2 ? 'selected' : '' ?>>Admin</option>
        </select><br><br>

        <button type="submit">Guardar</button>
    </form>
