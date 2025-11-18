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
<link rel="stylesheet" href="chat.css">

<div class="chat-container">
    <!-- Lista de chats -->
    <div class="chat-list">
        <div class="chat-list-header">
            <input type="text" class="chat-search" id="chatSearch" placeholder="Buscar chats...">
            <button class="new-group-btn" id="newGroupBtn">Nuevo Grupo</button>
        </div>
        <div class="chat-items" id="chatItems">
            <!-- Los chats se cargarán aquí -->
        </div>
    </div>

    <!-- Área de chat -->
    <div class="chat-area">
        <div class="chat-header" id="chatHeader">
            <div id="currentChatInfo">
                <p>Selecciona un chat para empezar a conversar</p>
            </div>
        </div>
        <div class="chat-messages" id="chatMessages">
            <!-- Los mensajes se cargarán aquí -->
        </div>
        <div class="chat-input-area" id="chatInputArea" style="display: none;">
            <form id="messageForm" class="message-input-container">
                <input type="hidden" id="currentChatId">
                <input type="hidden" id="currentChatType">
                <input type="hidden" id="currentGroupId">
                <textarea class="message-input" id="messageInput" placeholder="Escribe un mensaje..." rows="1"></textarea>
            </form>
            <button class="send-btn" id="sendBtn" type="button">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>

<!-- Modal para nuevo grupo -->
<div id="groupModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Crear Nuevo Grupo</h3>
            <span class="close">&times;</span>
        </div>
        <form id="groupForm">
            <div class="form-group">
                <label>Nombre del grupo:</label>
                <input type="text" id="groupName" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Descripción (opcional):</label>
                <textarea id="groupDesc" class="form-control" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Crear Grupo</button>
        </form>
    </div>
</div>

<!-- Modal para añadir miembros -->
<div id="memberModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Añadir Miembros</h3>
            <span class="close">&times;</span>
        </div>
        <div class="form-group">
            <label>Buscar usuario:</label>
            <input type="text" id="userSearch" class="form-control" placeholder="Escribe el username...">
            <div class="search-results" id="searchResults"></div>
        </div>
        <div class="group-members" id="groupMembersList"></div>
        <button type="button" class="btn btn-success" id="addMembersBtn">Añadir Miembros</button>
    </div>
</div>

<script src="chat.js"></script>

<?php require_once '../templates/footer.php'; ?>