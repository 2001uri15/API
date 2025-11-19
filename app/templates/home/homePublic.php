<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Calendario API - Gestión Profesional de Eventos</title>
    <link rel="icon" type="image/png" href="icono.ico">
    <style>
        :root {
    --primary-color: #667eea;
    --primary-dark: #5a6fd8;
    --secondary-color: #764ba2;
    --accent-color: #f093fb;
    --light-color: #f7fafc;
    --text-color: #2d3748;
    --text-light: #718096;
    --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    --transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    color: var(--text-color);
    line-height: 1.7;
    overflow-x: hidden;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Header Styles Mejorado */
header {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    box-shadow: var(--shadow);
    position: sticky;
    top: 0;
    z-index: 100;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px 0;
}

.logo-container {
    display: flex;
    align-items: center;
}

.logo {
    height: 55px;
    margin-right: 18px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: var(--transition);
}

.logo:hover {
    transform: scale(1.05);
}

.logo-text h1 {
    font-size: 2rem;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-weight: 800;
    letter-spacing: -0.5px;
}

.logo-text p {
    font-size: 0.95rem;
    color: var(--text-light);
    margin-top: -3px;
    font-weight: 500;
}

nav ul {
    display: flex;
    list-style: none;
    gap: 30px;
}

nav ul li a {
    text-decoration: none;
    color: var(--text-color);
    font-weight: 600;
    transition: var(--transition);
    position: relative;
    padding: 8px 0;
}

nav ul li a::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 2px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    transition: width 0.3s ease;
}

nav ul li a:hover {
    color: var(--primary-color);
}

nav ul li a:hover::after {
    width: 100%;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    padding: 12px 28px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition);
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

.btn-outline {
    background: transparent;
    border: 2px solid var(--primary-color);
    color: var(--primary-color);
    box-shadow: none;
}

.btn-outline:hover {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    transform: translateY(-3px);
}

/* Hero Section Mejorado */
.hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 120px 0;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><pattern id="grid" width="50" height="50" patternUnits="userSpaceOnUse"><path d="M 50 0 L 0 0 0 50" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100%" height="100%" fill="url(%23grid)"/></svg>');
}

.hero h2 {
    font-size: 3.2rem;
    margin-bottom: 25px;
    font-weight: 800;
    line-height: 1.2;
    position: relative;
}

.hero p {
    font-size: 1.3rem;
    max-width: 700px;
    margin: 0 auto 40px;
    opacity: 0.95;
    font-weight: 400;
    position: relative;
}

.hero-buttons {
    display: flex;
    justify-content: center;
    gap: 20px;
    position: relative;
}

.hero-buttons .btn {
    padding: 15px 35px;
    font-size: 1.1rem;
}

.hero-buttons .btn-outline {
    background: transparent;
    border: 2px solid rgba(255, 255, 255, 0.8);
    color: white;
}

.hero-buttons .btn-outline:hover {
    background: white;
    color: var(--primary-color);
}

/* Features Section Mejorado */
.features {
    padding: 120px 0;
    background: white;
    position: relative;
}

.section-title {
    text-align: center;
    margin-bottom: 70px;
    position: relative;
}

.section-title h2 {
    font-size: 2.8rem;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 20px;
    font-weight: 800;
}

.section-title p {
    color: var(--text-light);
    max-width: 600px;
    margin: 0 auto;
    font-size: 1.2rem;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 40px;
}

.feature-card {
    background: white;
    border-radius: 20px;
    padding: 40px 30px;
    box-shadow: var(--shadow);
    transition: var(--transition);
    text-align: center;
    border: 1px solid rgba(0, 0, 0, 0.05);
    position: relative;
    overflow: hidden;
}

.feature-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
}

.feature-card:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.feature-icon {
    font-size: 3.5rem;
    margin-bottom: 25px;
    display: block;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.feature-card h3 {
    font-size: 1.5rem;
    margin-bottom: 18px;
    color: var(--text-color);
    font-weight: 700;
}

.feature-card p {
    color: var(--text-light);
    line-height: 1.7;
}

/* CTA Section Mejorado */
.cta {
    background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%);
    color: white;
    padding: 100px 0;
    text-align: center;
    position: relative;
}

.cta::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><circle cx="200" cy="200" r="2" fill="rgba(255,255,255,0.05)"/><circle cx="600" cy="300" r="1.5" fill="rgba(255,255,255,0.05)"/><circle cx="800" cy="150" r="1" fill="rgba(255,255,255,0.05)"/></svg>');
}

.cta h2 {
    font-size: 2.8rem;
    margin-bottom: 25px;
    font-weight: 800;
    position: relative;
}

.cta p {
    max-width: 700px;
    margin: 0 auto 40px;
    font-size: 1.2rem;
    opacity: 0.9;
    position: relative;
}

.cta .btn {
    position: relative;
    font-size: 1.1rem;
    padding: 16px 40px;
}

/* Footer Mejorado */
footer {
    background: linear-gradient(135deg, #1a202c 0%, #2d3748 100%);
    color: white;
    padding: 60px 0 30px;
    position: relative;
}

.footer-content {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 40px;
    margin-bottom: 40px;
}

.footer-column h3 {
    font-size: 1.3rem;
    margin-bottom: 25px;
    position: relative;
    padding-bottom: 12px;
    font-weight: 700;
}

.footer-column h3::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 40px;
    height: 3px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-radius: 2px;
}

.footer-column ul {
    list-style: none;
}

.footer-column ul li {
    margin-bottom: 12px;
}

.footer-column ul li a {
    color: #cbd5e0;
    text-decoration: none;
    transition: var(--transition);
    display: inline-block;
}

.footer-column ul li a:hover {
    color: white;
    transform: translateX(5px);
}

.copyright {
    text-align: center;
    padding-top: 30px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    color: #a0aec0;
    font-size: 0.95rem;
}

/* Modal de registro mejorado */
#registerModal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.7);
    align-items: center;
    justify-content: center;
    z-index: 2000;
    backdrop-filter: blur(10px);
}

#registerModal > div {
    background: white;
    border-radius: 20px;
    width: 95%;
    max-width: 500px;
    padding: 30px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    position: relative;
    animation: modalAppear 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

@keyframes modalAppear {
    from {
        opacity: 0;
        transform: scale(0.8) translateY(-20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        text-align: center;
        gap: 20px;
    }
    
    .logo-container {
        margin-bottom: 0;
    }
    
    nav ul {
        margin-top: 15px;
        flex-wrap: wrap;
        justify-content: center;
        gap: 15px;
    }
    
    .hero h2 {
        font-size: 2.3rem;
    }
    
    .hero p {
        font-size: 1.1rem;
    }
    
    .hero-buttons {
        flex-direction: column;
        align-items: center;
        gap: 15px;
    }
    
    .hero-buttons .btn {
        width: 100%;
        max-width: 280px;
        justify-content: center;
    }
    
    .section-title h2 {
        font-size: 2.2rem;
    }
    
    .features-grid {
        grid-template-columns: 1fr;
        gap: 30px;
    }
}
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo-container">
                    <img src="logotipo.svg" alt="Logo Mi Calendario API" class="logo">
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
                <a href="login/index.php" class="btn">Iniciar Sesión</a>
                <button id="openRegisterBtn" class="btn btn-outline">Registrarse</button>
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
            <a href="login/index.php" class="btn">Comenzar Ahora</a>
        </div>
    </section>
    
    <!-- Registration Modal -->
    <div id="registerModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); align-items:center; justify-content:center; z-index:2000;">
        <div style="background:white; border-radius:8px; width:95%; max-width:520px; padding:20px; box-shadow:0 8px 30px rgba(0,0,0,0.2); position:relative;">
            <button id="closeRegister" style="position:absolute; right:12px; top:12px; background:transparent; border:none; font-size:18px; cursor:pointer;">✕</button>
            <h2 style="margin-top:0;">Crear cuenta</h2>
            <div id="regMsg" style="margin-bottom:10px;color:red;"></div>
            <form id="homeRegisterForm">
                <div style="margin-bottom:10px;">
                    <label for="r_username">Usuario</label>
                    <input id="r_username" name="username" type="text" required maxlength="50" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px;">
                </div>
                <div style="margin-bottom:10px;display:flex;gap:10px;">
                    <div style="flex:1;">
                        <label for="r_name">Nombre</label>
                        <input id="r_name" name="name" type="text" required style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px;">
                    </div>
                    <div style="flex:1;">
                        <label for="r_surname">Apellidos</label>
                        <input id="r_surname" name="surname" type="text" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px;">
                    </div>
                </div>
                <div style="margin-bottom:10px;">
                    <label for="r_email">Correo</label>
                    <input id="r_email" name="email" type="email" required style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px;">
                </div>
                <div style="margin-bottom:10px;display:flex;gap:10px;">
                    <div style="flex:1;">
                        <label for="r_password">Contraseña</label>
                        <input id="r_password" name="password" type="password" required minlength="6" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px;">
                    </div>
                    <div style="flex:1;">
                        <label for="r_password2">Confirmar</label>
                        <input id="r_password2" name="password2" type="password" required minlength="6" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px;">
                    </div>
                </div>
                <div style="text-align:right;margin-top:10px;">
                    <button type="button" id="cancelReg" class="btn btn-outline" style="margin-right:8px;">Cancelar</button>
                    <button type="submit" class="btn">Registrarse</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    (function(){
        const openBtn = document.getElementById('openRegisterBtn');
        const modal = document.getElementById('registerModal');
        const closeBtn = document.getElementById('closeRegister');
        const cancelBtn = document.getElementById('cancelReg');
        const form = document.getElementById('homeRegisterForm');
        const msg = document.getElementById('regMsg');

        function openModal(){ msg.textContent=''; modal.style.display = 'flex'; }
        function closeModal(){ modal.style.display = 'none'; }

        openBtn && openBtn.addEventListener('click', openModal);
        closeBtn && closeBtn.addEventListener('click', closeModal);
        cancelBtn && cancelBtn.addEventListener('click', closeModal);
        modal && modal.addEventListener('click', function(e){ if(e.target === modal) closeModal(); });

        form && form.addEventListener('submit', async function(e){
            e.preventDefault();
            msg.style.color = 'red';
            msg.textContent = '';
            const username = document.getElementById('r_username').value.trim();
            const name = document.getElementById('r_name').value.trim();
            const surname = document.getElementById('r_surname').value.trim();
            const email = document.getElementById('r_email').value.trim();
            const password = document.getElementById('r_password').value;
            const password2 = document.getElementById('r_password2').value;

            if(!username || !name || !email || !password){ msg.textContent = 'Rellena los campos obligatorios'; return; }
            if(password !== password2){ msg.textContent = 'Las contraseñas no coinciden'; return; }

            const payload = { username, name, surname, email, password };

            // Try candidate endpoints in order until one returns a JSON response
            const candidates = [
                'login/register_action.php',
                '/app/login/register_action.php',
                'app/login/register_action.php',
                '/login/register_action.php'
            ];

            let lastError = null;
            for (const url of candidates) {
                try {
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload)
                    });

                    // If we get a 404, try next candidate
                    if (res.status === 404) {
                        lastError = `404 Not Found at ${url}`;
                        continue;
                    }

                    const text = await res.text();
                    let data = null;
                    try { data = JSON.parse(text); } catch (e) { /* not json */ }

                    if (!data) {
                        msg.textContent = `Respuesta no-JSON (${res.status}) desde ${url}: ` + text.substring(0, 300);
                        lastError = `Non-JSON response (${res.status})`;
                        break;
                    }

                    if (data.success) {
                        msg.style.color = 'green';
                        msg.textContent = data.message || 'Registro correcto';
                        setTimeout(()=>{ window.location.href = 'login/index.php'; }, 900);
                        return;
                    } else {
                        msg.style.color = 'red';
                        msg.textContent = data.message || `Error en registro (${res.status})`;
                        return;
                    }

                } catch (err) {
                    lastError = `${err.message} at ${url}`;
                    // try next candidate
                    continue;
                }
            }

            // If we reach here nothing succeeded
            msg.style.color = 'red';
            msg.textContent = 'Error de red o servidor. ' + (lastError ? ('Detalle: ' + lastError) : 'Sin respuesta');
        });
    })();
    </script>

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