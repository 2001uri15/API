<?php
// header.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Calendario API</title>
    
    <!-- CSS global -->
    <link rel="stylesheet" href="/calendario/estilos/estilos.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- JS global (opcional) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Header styles (inline for quick local edits) -->
    <style>
        /* Header layout */
        header {
            background: linear-gradient(90deg, #2b6cb0 0%, #63b3ed 100%);
            color: #fff;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.12);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        header .logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        header .logo img {
            height: 36px;
            width: auto;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }

        header h1 {
            margin: 0;
            font-size: 1.15rem;
            font-weight: 600;
            letter-spacing: 0.2px;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn {
            background: rgba(255,255,255,0.12);
            color: #000000ff;
            border: 1px solid rgba(0, 0, 0, 0.18);
            padding: 6px 10px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.95rem;
        }

        .btn:hover {
            background: rgba(255,255,255,0.18);
        }

        @media (max-width: 600px) {
            header { padding: 10px; }
            header h1 { font-size: 1rem; }
            .header-actions .btn { padding: 6px 8px; font-size: 0.9rem; }
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
            <button class="btn" onclick="location.href='/'"><i class="fa-solid fa-house"></i>&nbsp;Inicio</button>
            <button class="btn" onclick="window.scrollTo(0,0)"><i class="fa-solid fa-arrow-up"></i>&nbsp;Ir arriba</button>
        </div>
    </header>
