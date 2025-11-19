<?php
// sidebar.php
?>
<!-- Sidebar estilizado (estilos inline para cambios rápidos) -->
<style>
    /* Sidebar estilizado mejorado */
.sidebar {
    width: 260px;
    position: fixed;
    left: 0;
    top: 0;
    bottom: 0;
    background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
    border-right: none;
    padding: 25px 20px;
    box-sizing: border-box;
    overflow-y: auto;
    z-index: 999;
    box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
}

.sidebar::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 120px;
    background: linear-gradient(180deg, rgba(255,255,255,0.1) 0%, transparent 100%);
    pointer-events: none;
}

.sidebar h3 {
    margin: 0 0 25px 0;
    font-size: 1.1rem;
    color: white;
    font-weight: 600;
    text-align: center;
    padding-bottom: 15px;
    border-bottom: 1px solid rgba(255,255,255,0.2);
}

.sidebar ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar li {
    margin-bottom: 8px;
    position: relative;
}

.sidebar a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 15px;
    color: rgba(255,255,255,0.9);
    text-decoration: none;
    border-radius: 10px;
    transition: all 0.3s ease;
    font-weight: 500;
    position: relative;
    overflow: hidden;
}

.sidebar a::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 3px;
    background: #ffd700;
    transform: scaleY(0);
    transition: transform 0.3s ease;
}

.sidebar a:hover {
    background: rgba(255,255,255,0.15);
    color: white;
    transform: translateX(5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.sidebar a:hover::before {
    transform: scaleY(1);
}

.sidebar a i {
    width: 20px;
    text-align: center;
    font-size: 1.1rem;
}

/* espacio para el contenido principal a la derecha */
.main-content-wrapper {
    margin-left: 280px;
    padding: 30px;
    min-height: calc(100vh - 120px);
    background: #f8fafc;
}

@media (max-width: 1024px) {
    .sidebar {
        width: 240px;
    }
    
    .main-content-wrapper {
        margin-left: 260px;
    }
}

@media (max-width: 768px) {
    .sidebar {
        width: 100%;
        position: relative;
        height: auto;
        padding: 20px;
    }
    
    .main-content-wrapper {
        margin-left: 0;
        padding: 20px;
    }
    
    .sidebar ul {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 10px;
    }
    
    .sidebar a {
        justify-content: center;
        text-align: center;
        flex-direction: column;
        gap: 5px;
    }
}
</style>

<aside class="sidebar">
    <ul>
        <li><a href="/index.php">Inicio</a></li>
        <li><a href="/chat/index.php">Chat</a></li>
        <li><a href="/calendario/index.php">Calendario</a></li>
        <?php if($_SESSION['rol'] === 2): ?>
            <li><a href="/usuarios/index.php">Usuarios</a></li>
        <?php endif; ?>
        <li><a href="/config/index.php">Configuración</a></li>
        <li><a href="/login/logout.php">Cerrar sesión</a></li>
    </ul>
</aside>

<!-- Contenido principal debe estar a la derecha del sidebar -->
<div class="main-content-wrapper">