<?php

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Registrarse</title>
    <link rel="stylesheet" href="/calendario/estilos/estilos.css">
    <style>
        .register-card { max-width:480px; margin:40px auto; padding:20px; border-radius:8px; background:#fff; box-shadow:0 2px 8px rgba(0,0,0,0.08);} 
        .register-card h2 { margin-top:0; }
        .form-row { margin-bottom:12px; }
        label { display:block; margin-bottom:6px; font-weight:600; }
        input[type=text], input[type=email], input[type=password] { width:100%; padding:8px 10px; border:1px solid #cbd5e1; border-radius:6px; }
        .btn { padding:8px 12px; border-radius:6px; background:#2b6cb0; color:#fff; border:none; cursor:pointer; }
    </style>
</head>
<body>
<?php require_once __DIR__ . '/../templates/header.php'; ?>

<div class="main-content-wrapper">
    <div class="register-card">
        <h2>Crear cuenta</h2>
        <form id="registerForm">
            <div class="form-row">
                <label for="username">Usuario (username)</label>
                <input id="username" name="username" type="text" required maxlength="20">
            </div>
            <div class="form-row">
                <label for="name">Nombre</label>
                <input id="name" name="name" type="text" required maxlength="50">
            </div>
            <div class="form-row">
                <label for="surname">Apellidos</label>
                <input id="surname" name="surname" type="text" maxlength="255">
            </div>
            <div class="form-row">
                <label for="email">Correo</label>
                <input id="email" name="email" type="email" required maxlength="255">
            </div>
            <div class="form-row">
                <label for="password">Contraseña</label>
                <input id="password" name="password" type="password" required minlength="6">
            </div>
            <div class="form-row">
                <label for="password2">Confirmar contraseña</label>
                <input id="password2" name="password2" type="password" required minlength="6">
            </div>
            <div style="text-align:right;">
                <button class="btn" type="submit">Registrarse</button>
            </div>
        </form>
        <div id="message" style="margin-top:10px;"></div>
    </div>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const password2 = document.getElementById('password2').value;

    const msg = document.getElementById('message');
    msg.textContent = '';

    if (password !== password2) {
        msg.textContent = 'Las contraseñas no coinciden.';
        return;
    }

    const payload = { username: document.getElementById('username').value.trim(), name, surname: document.getElementById('surname').value.trim(), email, password };

    // comprobar si da error o no reggistro
    try {
        const candidates = ['register_action.php', 'login/register_action.php', '/app/login/register_action.php', '/login/register_action.php'];
        let lastError = null;
        for (const url of candidates) {
            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                if (res.status === 404) { lastError = `404 at ${url}`; continue; }
                const text = await res.text();
                let data = null;
                try { data = JSON.parse(text); } catch(e) { /* not json */ }
                if (!data) { msg.textContent = `Respuesta no-JSON (${res.status}) desde ${url}: ` + text.substring(0,300); lastError = 'Non-JSON'; break; }
                if (data.success) { msg.style.color='green'; msg.textContent = data.message || 'Registro correcto'; setTimeout(()=>{ window.location.href = 'login/index.php'; },1200); return; }
                else { msg.style.color='red'; msg.textContent = data.message || `Error en registro (${res.status})`; return; }
            } catch (e) { lastError = `${e.message} at ${url}`; continue; }
        }
        msg.style.color='red'; msg.textContent = 'Error de red o servidor. ' + (lastError ? ('Detalle: ' + lastError) : 'Sin respuesta');
    } catch (err) {
        msg.style.color = 'red';
        msg.textContent = 'Error de red o del servidor';
    }
});
</script>

</body>
</html>
