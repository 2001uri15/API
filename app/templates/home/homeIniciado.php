<?php
// Incluir conexión a la base de datos
require_once 'conn.php';

// Incluir templates
require_once 'templates/header.php';
require_once 'templates/sidebar.php';
?>

<div class="main-content">
    <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?>!</h1>
    <p>Esta es la página principal de la API.</p>
    
    <!-- Información del usuario -->
    <div class="user-info">
        <p><strong>Usuario:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['mail']); ?></p>
        <p><strong>Nombre completo:</strong> <?php echo htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellidos']); ?></p>
    </div>
    
    <!-- Enlace para cerrar sesión -->
    <a href="login/logout.php" class="logout-btn">Cerrar Sesión</a>
</div>

<?php
// Incluir footer al final
require_once 'templates/footer.php';
?>