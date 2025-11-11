<?php
// sidebar.php
?>
<!-- Sidebar estilizado (estilos inline para cambios rápidos) -->
<style>
    .sidebar {
        width: 220px;
        position: fixed;
        left: 0;
        top: 0;
        bottom: 0;
        background: linear-gradient(180deg, #f7fafc 0%, #edf2f7 100%);
        border-right: 1px solid #e2e8f0;
        padding: 18px;
        box-sizing: border-box;
        overflow-y: auto;
    }

    .sidebar h3 {
        margin: 0 0 12px 0;
        font-size: 1rem;
        color: #2d3748;
    }

    .sidebar ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar li {
        margin-bottom: 8px;
    }

    .sidebar a {
        display: block;
        padding: 8px 10px;
        color: #2d3748;
        text-decoration: none;
        border-radius: 6px;
        transition: background .12s, color .12s;
    }

    .sidebar a:hover {
        background: #e6f2ff;
        color: #1a365d;
    }

    /* espacio para el contenido principal a la derecha */
    .main-content-wrapper {
        margin-left: 240px;
        padding: 12px;
    }
</style>

<aside class="sidebar">
    <h3>Menú</h3>
    <ul>
        <li><a href="/calendario/index.php">Calendario</a></li>
        <li><a href="/calendario/cl/usuarios.php">Usuarios</a></li>
        <!-- Opción 'Eventos' eliminada por petición -->
        <li><a href="/calendario/cl/config.php">Configuración</a></li>
    </ul>
</aside>

<!-- Contenido principal debe estar a la derecha del sidebar -->
<div class="main-content-wrapper">
