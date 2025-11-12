<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Calendario API - Gestión Profesional de Eventos</title>
    <style>
        :root {
            --primary-color: #3498db;
            --primary-dark: #2980b9;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --text-color: #333;
            --text-light: #7f8c8d;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f9f9f9;
            color: var(--text-color);
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Header Styles */
        header {
            background-color: white;
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
        }
        
        .logo-container {
            display: flex;
            align-items: center;
        }
        
        .logo {
            height: 50px;
            margin-right: 15px;
        }
        
        .logo-text h1 {
            font-size: 1.8rem;
            color: var(--primary-color);
            font-weight: 700;
        }
        
        .logo-text p {
            font-size: 0.9rem;
            color: var(--text-light);
            margin-top: -5px;
        }
        
        nav ul {
            display: flex;
            list-style: none;
        }
        
        nav ul li {
            margin-left: 25px;
        }
        
        nav ul li a {
            text-decoration: none;
            color: var(--secondary-color);
            font-weight: 500;
            transition: var(--transition);
        }
        
        nav ul li a:hover {
            color: var(--primary-color);
        }
        
        .btn {
            display: inline-block;
            background-color: var(--primary-color);
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            border: none;
            cursor: pointer;
        }
        
        .btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .btn-outline {
            background-color: transparent;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
        }
        
        .btn-outline:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 80px 0;
            text-align: center;
        }
        
        .hero h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            font-weight: 700;
        }
        
        .hero p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 30px;
            opacity: 0.9;
        }
        
        .hero-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        
        .hero-buttons .btn {
            padding: 12px 30px;
            font-size: 1.1rem;
        }
        
        .hero-buttons .btn-outline {
            background-color: transparent;
            border: 2px solid white;
            color: white;
        }
        
        .hero-buttons .btn-outline:hover {
            background-color: white;
            color: var(--primary-color);
        }
        
        /* Features Section */
        .features {
            padding: 80px 0;
            background-color: white;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .section-title h2 {
            font-size: 2.2rem;
            color: var(--secondary-color);
            margin-bottom: 15px;
        }
        
        .section-title p {
            color: var(--text-light);
            max-width: 600px;
            margin: 0 auto;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .feature-card {
            background-color: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: var(--shadow);
            transition: var(--transition);
            text-align: center;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
        }
        
        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }
        
        .feature-card h3 {
            font-size: 1.4rem;
            margin-bottom: 15px;
            color: var(--secondary-color);
        }
        
        .feature-card p {
            color: var(--text-light);
        }
        
        /* CTA Section */
        .cta {
            background-color: var(--secondary-color);
            color: white;
            padding: 80px 0;
            text-align: center;
        }
        
        .cta h2 {
            font-size: 2.2rem;
            margin-bottom: 20px;
        }
        
        .cta p {
            max-width: 700px;
            margin: 0 auto 30px;
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        /* Footer */
        footer {
            background-color: var(--secondary-color);
            color: white;
            padding: 40px 0 20px;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .footer-column h3 {
            font-size: 1.2rem;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }
        
        .footer-column h3::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 40px;
            height: 2px;
            background-color: var(--primary-color);
        }
        
        .footer-column ul {
            list-style: none;
        }
        
        .footer-column ul li {
            margin-bottom: 10px;
        }
        
        .footer-column ul li a {
            color: #bdc3c7;
            text-decoration: none;
            transition: var(--transition);
        }
        
        .footer-column ul li a:hover {
            color: white;
            padding-left: 5px;
        }
        
        .copyright {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: #bdc3c7;
            font-size: 0.9rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                text-align: center;
            }
            
            .logo-container {
                margin-bottom: 15px;
            }
            
            nav ul {
                margin-top: 15px;
                flex-wrap: wrap;
                justify-content: center;
            }
            
            nav ul li {
                margin: 5px 10px;
            }
            
            .hero h2 {
                font-size: 2rem;
            }
            
            .hero p {
                font-size: 1rem;
            }
            
            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .hero-buttons .btn {
                width: 100%;
                max-width: 250px;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo-container">
                    <img src="/calendario/estilos/logo.png" alt="Logo Mi Calendario API" class="logo">
                    <div class="logo-text">
                        <h1>Mi Calendario API</h1>
                        <p>Gestión profesional de eventos</p>
                    </div>
                </div>
                <nav>
                    <ul>
                        <li><a href="#features">Características</a></li>
                        <li><a href="#documentation">Documentación</a></li>
                        <li><a href="#pricing">Precios</a></li>
                        <li><a href="#contact">Contacto</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    
    <section class="hero">
        <div class="container">
            <h2>Gestiona tus eventos de forma profesional</h2>
            <p>Mi Calendario API te permite organizar, sincronizar y compartir tus eventos de manera eficiente con una interfaz intuitiva y potentes funciones de integración.</p>
            <div class="hero-buttons">
                <a href="/login/index.php" class="btn">Iniciar Sesión</a>
                <a href="#features" class="btn btn-outline">Conocer Más</a>
            </div>
        </div>
    </section>
    
    <section class="features" id="features">
        <div class="container">
            <div class="section-title">
                <h2>Características Principales</h2>
                <p>Descubre todas las funcionalidades que hacen de Mi Calendario API la solución perfecta para la gestión de tus eventos</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">📅</div>
                    <h3>Gestión Intuitiva</h3>
                    <p>Crea, edita y organiza tus eventos con una interfaz fácil de usar y altamente personalizable.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🔄</div>
                    <h3>Sincronización Multiplataforma</h3>
                    <p>Sincroniza tus calendarios y eventos entre todos tus dispositivos y aplicaciones favoritas.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🔒</div>
                    <h3>Seguridad Avanzada</h3>
                    <p>Tus datos están protegidos con las más avanzadas medidas de seguridad y privacidad.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3>Análisis y Reportes</h3>
                    <p>Obtén insights valiosos sobre tus eventos y patrones de uso con nuestros reportes detallados.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🔗</div>
                    <h3>API Poderosa</h3>
                    <p>Integra nuestros servicios en tus aplicaciones con nuestra API documentada y fácil de usar.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">👥</div>
                    <h3>Colaboración en Equipo</h3>
                    <p>Comparte calendarios y coordina eventos con tu equipo de trabajo de forma eficiente.</p>
                </div>
            </div>
        </div>
    </section>
    
    <section class="cta">
        <div class="container">
            <h2>¿Listo para optimizar tu gestión de eventos?</h2>
            <p>Únete a miles de usuarios que ya confían en Mi Calendario API para organizar sus eventos de manera profesional.</p>
            <a href="/login/index.php" class="btn">Comenzar Ahora</a>
        </div>
    </section>
    
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>Mi Calendario API</h3>
                    <p>La solución más completa para la gestión profesional de eventos y calendarios.</p>
                </div>
                <div class="footer-column">
                    <h3>Enlaces Rápidos</h3>
                    <ul>
                        <li><a href="#features">Características</a></li>
                        <li><a href="#documentation">Documentación</a></li>
                        <li><a href="#pricing">Planes y Precios</a></li>
                        <li><a href="#contact">Soporte</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Legal</h3>
                    <ul>
                        <li><a href="#">Términos de Servicio</a></li>
                        <li><a href="#">Política de Privacidad</a></li>
                        <li><a href="#">Cookies</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Contacto</h3>
                    <ul>
                        <li>Email: info@apicolab.eus</li>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2025 Mi Calendario API. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
</body>
</html>