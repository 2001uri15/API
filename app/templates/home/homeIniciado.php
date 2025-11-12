<?php
// Incluir conexión a la base de datos
require_once 'conn.php';

// Incluir templates
require_once 'templates/header.php';
require_once 'templates/sidebar.php';
?>
<link rel="stylesheet" href="templates/home/estilos-home.css">
<div class="main-content">
    <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?>!</h1>
    <p>Esta es la página principal de la API.</p>
    
    <!-- Información del usuario -->
    <div class="user-info">
        <p><strong>Usuario:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['mail']); ?></p>
        <p><strong>Nombre completo:</strong> <?php echo htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellidos']); ?></p>
    </div>
    
    <!-- Grid de servicios -->
    <div class="services-grid">
        <a href="/chat/index.php" class="service-card chat">
            <span class="service-icon">💬</span>
            <h3>Chat</h3>
            <p>Comunícate en tiempo real con otros usuarios</p>
        </a>
        
        <a href="/calendario/index.php" class="service-card calendar">
            <span class="service-icon">📅</span>
            <h3>Calendario</h3>
            <p>Gestiona tus eventos y citas importantes</p>
        </a>
        
        <a href="usuarios/index.php" class="service-card users">
            <span class="service-icon">👥</span>
            <h3>Usuarios</h3>
            <p>Administra los usuarios del sistema</p>
        </a>
        
        <a href="config/index.php" class="service-card settings">
            <span class="service-icon">⚙️</span>
            <h3>Configuración</h3>
            <p>Personaliza tu experiencia y preferencias</p>
        </a>
    </div>
    
    <!-- Enlace para cerrar sesión -->
    <a href="login/logout.php" class="logout-btn">Cerrar Sesión</a>
</div>

<?php
// Incluir footer al final
require_once 'templates/footer.php';
?>