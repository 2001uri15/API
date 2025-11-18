<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login/index.php');
    exit;
}

require_once '../conn.php';
require_once '../templates/header.php';
require_once '../templates/sidebar.php';
?>

<h1>Chat General</h1>

<style>
.chat-box {
    height: 400px;
    overflow-y: scroll;
    border: 1px solid #999;
    padding: 10px;
    margin-bottom: 10px;
    background: #f5f5f5;
}

.msg {
    margin-bottom: 8px;
    padding: 5px 8px;
    border-radius: 6px;
    background: white;
}
.msg .autor {
    font-weight: bold;
}
.msg .fecha {
    font-size: 11px;
    color: #707070;
}
</style>

<div class="chat-box" id="chatBox"></div>

<form id="chatForm">
    <input type="text" name="mensaje" id="mensaje" placeholder="Escribe un mensaje..." required class="form-control">
    <button type="submit" class="btn btn-primary" style="margin-top:5px;">Enviar</button>
</form>

<script>
// Actualizar mensajes 
setInterval(cargarMensajes, 1500);

function cargarMensajes() {
    fetch("fetch.php")
    .then(res => res.text())
    .then(html => {
        document.getElementById("chatBox").innerHTML = html;
        document.getElementById("chatBox").scrollTop = document.getElementById("chatBox").scrollHeight;
    });
}

document.getElementById("chatForm").addEventListener("submit", function(e){
    e.preventDefault();

    const formData = new FormData();
    formData.append("mensaje", document.getElementById("mensaje").value);

    fetch("send.php", {
        method: "POST",
        body: formData
    }).then(() => {
        document.getElementById("mensaje").value = "";
        cargarMensajes();
    });
});

// Cargar mensajes 
cargarMensajes();
</script>

<?php require_once '../templates/footer.php'; ?>
