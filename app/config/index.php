<?php
session_start();

// Verificar sino esta logueado
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login/index.php');
    exit;
}


require_once '../conn.php';
// Conseguir datos del usuario actual
$user_id = $_SESSION['user_id'];

// Obtener el nombre de usuario 
$sql = "SELECT username FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error en prepare(): " . $conn->error . " — SQL: " . $sql);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username);
$stmt->fetch();
$stmt->close();

// Incluir templates
require_once '../templates/header.php';
require_once '../templates/sidebar.php';
?>

<h1>Configuración</h1>

<style>
.config-section {
    background: #fff;
    padding: 20px;
    margin: 20px 0;
    border-radius: 10px;
    box-shadow: 0 3px 6px rgba(0,0,0,0.08);
}
.config-section h2 {
    margin-top: 0;
}
.config-section form input, 
.config-section select {
    width: 280px;
    padding: 8px;
    margin-top: 6px;
    margin-bottom: 12px;
}
.config-section button {
    padding: 10px 15px;
    border: none;
    background: #3182ce;
    color: white;
    border-radius: 6px;
    cursor: pointer;
}
.config-section button:hover {
    background: #225ea8;
}
</style>

<!-- Cambiar nombre de usuario -->
<div class="config-section">
    <h2>Cambiar nombre de usuario</h2>
    <form action="update_username.php" method="POST">
        <label>Nombre actual:</label><br>
        <input type="text" value="<?php echo htmlspecialchars($username); ?>" disabled><br>

        <label>Nuevo nombre de usuario:</label><br>
        <input type="text" name="new_username" required><br>

        <button type="submit">Guardar cambios</button>
    </form>
</div>

<!-- Cambiar contraseña -->
<div class="config-section">
    <h2>Cambiar contraseña</h2>
    <form action="update_password.php" method="POST">
        <label>Contraseña actual:</label><br>
        <input type="password" name="current_password" required><br>

        <label>Nueva contraseña:</label><br>
        <input type="password" name="new_password" required><br>

        <button type="submit">Actualizar contraseña</button>
    </form>
</div>

<!-- Eliminar cuenta -->
<div class="config-section" style="border: 1px solid #f44336;">
    <h2>Eliminar cuenta</h2>
    <form action="delete_account.php" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar tu cuenta? Esta acción no se puede deshacer.');">
        <button type="submit" style="background: #f44336;">Eliminar mi cuenta</button>
    </form>
</div>


<?php
require_once '../templates/footer.php';
?>
