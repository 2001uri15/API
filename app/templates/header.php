<?php
// header.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Calendario API</title>
    <link rel="icon" type="image/png" href="/icono.ico">
    
    <!-- CSS global -->
    <link rel="stylesheet" href="/calendario/estilos/estilos.css">
    <link rel="stylesheet" href="/templates/css/estilos.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- JS global (opcional) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Header styles (inline for quick local edits) -->
     <style>
    html, body { margin: 0; padding: 0; }
    * { box-sizing: border-box; }
</style>
    <style>
        /* Header layout mejorado */
header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    padding: 15px 30px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    position: sticky;
    top: 0;
    z-index: 1000;
    margin-left: 260px;
    backdrop-filter: blur(10px);
}

header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    pointer-events: none;
}

header .logo {
    display: flex;
    align-items: center;
    gap: 15px;
}

header .logo img {
    height: 42px;
    width: auto;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    transition: transform 0.3s ease;
}

header .logo:hover img {
    transform: scale(1.05);
}

header h1 {
    margin: 0;
    font-size: 1.3rem;
    font-weight: 700;
    letter-spacing: 0.3px;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

.header-actions span {
    font-weight: 500;
    opacity: 0.95;
}

.btn {
    background: linear-gradient(135deg, rgba(255,255,255,0.2), rgba(255,255,255,0.1));
    color: white;
    border: 1px solid rgba(255,255,255,0.3);
    padding: 8px 16px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 0.95rem;
    font-weight: 500;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    gap: 6px;
}

.btn:hover {
    background: linear-gradient(135deg, rgba(255,255,255,0.3), rgba(255,255,255,0.2));
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.btn i {
    font-size: 0.9rem;
}

@media (max-width: 1024px) {
    header {
        margin-left: 240px;
        padding: 12px 25px;
    }
}

@media (max-width: 768px) {
    header {
        margin-left: 0;
        flex-direction: column;
        padding: 15px 20px;
        gap: 15px;
    }
    
    header h1 { 
        font-size: 1.1rem; 
        text-align: center;
    }
    
    .header-actions {
        width: 100%;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .header-actions .btn { 
        padding: 8px 12px; 
        font-size: 0.9rem; 
    }
    
    .header-actions span {
        width: 100%;
        text-align: center;
        margin-bottom: 5px;
    }
}
    </style>
</head>
<body>
    <!-- Header o barra de navegación -->
    <header>
        <div class="logo">
            <h1>Mi Calendario API</h1>
        </div>

        <div class="header-actions">
            <span>¡Hola, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Invitado'); ?>!</span>
            <button class="btn" onclick="location.href='/'"><i class="fa-solid fa-house"></i>&nbsp;Inicio</button>
            <button class="btn" onclick="window.scrollTo(0,0)"><i class="fa-solid fa-arrow-up"></i>&nbsp;Ir arriba</button>
        </div>
    </header>