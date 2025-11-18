// chat.js
class ChatSystem {
    constructor() {
        this.currentChatId = null;
        this.currentChatType = null;
        this.currentGroupId = null;
        this.init();
    }

    init() {
        this.loadChatList();
        this.setupEventListeners();
        setInterval(() => {
            if (this.currentChatId && this.currentChatType) {
                this.loadMessages();
            }
            this.loadChatList();
        }, 2000);
    }

    setupEventListeners() {
        // Botón de enviar mensaje
        document.getElementById('sendBtn').addEventListener('click', () => {
            this.sendMessage();
        });

        // Enter para enviar mensaje
        document.getElementById('messageInput').addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                this.sendMessage();
            }
        });

        // Auto-resize del textarea
        document.getElementById('messageInput').addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        // Búsqueda de chats
        document.getElementById('chatSearch').addEventListener('input', (e) => {
            this.filterChats(e.target.value);
        });

        // Botones de tipo de chat
        document.querySelectorAll('.chat-type-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                document.querySelectorAll('.chat-type-btn').forEach(b => b.classList.remove('active'));
                e.target.classList.add('active');
                this.loadChatList(e.target.dataset.type);
            });
        });

        // Nuevo grupo
        document.getElementById('newGroupBtn').addEventListener('click', () => {
            this.showGroupModal();
        });

        // Modales
        document.querySelectorAll('.close').forEach(close => {
            close.addEventListener('click', () => {
                document.querySelectorAll('.modal').forEach(modal => {
                    modal.style.display = 'none';
                });
            });
        });

        // Crear grupo
        document.getElementById('groupForm').addEventListener('submit', (e) => {
            e.preventDefault();
            this.createGroup();
        });

        // Búsqueda de usuarios para añadir
        document.getElementById('userSearch').addEventListener('input', (e) => {
            this.searchUsers(e.target.value);
        });

        // Añadir miembros
        document.getElementById('addMembersBtn').addEventListener('click', () => {
            this.addMembersToGroup();
        });

        // Cerrar modales al hacer click fuera
        window.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal')) {
                e.target.style.display = 'none';
            }
        });
    }

    async loadChatList(filter = 'todos') {
        try {
            const response = await fetch(`chat_list.php?filter=${filter}`);
            const html = await response.text();
            document.getElementById('chatItems').innerHTML = html;
            
            // Agregar event listeners a los chats
            document.querySelectorAll('.chat-item').forEach(item => {
                item.addEventListener('click', () => {
                    this.selectChat(
                        item.dataset.chatId,
                        item.dataset.chatType,
                        item.dataset.groupId
                    );
                });
            });
        } catch (error) {
            console.error('Error loading chat list:', error);
        }
    }

    async selectChat(chatId, chatType, groupId = null) {
        this.currentChatId = chatId;
        this.currentChatType = chatType;
        this.currentGroupId = groupId;

        // Actualizar UI
        document.querySelectorAll('.chat-item').forEach(item => {
            item.classList.remove('active');
        });
        event.currentTarget.classList.add('active');

        document.getElementById('chatInputArea').style.display = 'flex';
        document.getElementById('messageInput').focus();

        // Actualizar hidden fields
        document.getElementById('currentChatId').value = chatId;
        document.getElementById('currentChatType').value = chatType;
        document.getElementById('currentGroupId').value = groupId;

        // Cargar información del chat y mensajes
        await this.loadChatInfo();
        await this.loadMessages();
    }

    async loadChatInfo() {
        try {
            const formData = new FormData();
            formData.append('chatId', this.currentChatId);
            formData.append('chatType', this.currentChatType);
            formData.append('groupId', this.currentGroupId);

            const response = await fetch('chat_info.php', {
                method: 'POST',
                body: formData
            });
            const html = await response.text();
            document.getElementById('currentChatInfo').innerHTML = html;
        } catch (error) {
            console.error('Error loading chat info:', error);
        }
    }

    async loadMessages() {
        if (!this.currentChatId || !this.currentChatType) return;

        try {
            const formData = new FormData();
            formData.append('chatId', this.currentChatId);
            formData.append('chatType', this.currentChatType);
            formData.append('groupId', this.currentGroupId);

            console.log('Cargando mensajes para:', {
                chatId: this.currentChatId,
                chatType: this.currentChatType,
                groupId: this.currentGroupId
            });

            const response = await fetch('fetch_messages.php', {
                method: 'POST',
                body: formData
            });
            const html = await response.text();
            document.getElementById('chatMessages').innerHTML = html;
            document.getElementById('chatMessages').scrollTop = document.getElementById('chatMessages').scrollHeight;

            // Debug: ver las clases aplicadas
            setTimeout(() => {
                const messages = document.querySelectorAll('.message');
                console.log('Mensajes cargados:', messages.length);
                messages.forEach((msg, index) => {
                    console.log(`Mensaje ${index + 1}:`, {
                        class: msg.className,
                        text: msg.querySelector('.message-content').textContent,
                        sender: msg.querySelector('.message-sender').textContent
                    });
                });
            }, 100);

        } catch (error) {
            console.error('Error loading messages:', error);
        }
    }

    async sendMessage() {
        const messageInput = document.getElementById('messageInput');
        const message = messageInput.value.trim();

        if (!message || !this.currentChatId || !this.currentChatType) {
            console.log('No se puede enviar: mensaje vacío o chat no seleccionado');
            return;
        }

        console.log('Enviando mensaje:', {
            message: message,
            chatType: this.currentChatType,
            chatId: this.currentChatId,
            groupId: this.currentGroupId
        });

        // Deshabilitar botón temporalmente
        const sendBtn = document.getElementById('sendBtn');
        sendBtn.disabled = true;
        sendBtn.textContent = 'Enviando...';

        try {
            const formData = new FormData();
            formData.append('mensaje', message);
            formData.append('chatType', this.currentChatType);
            
            if (this.currentChatType === 'privado') {
                formData.append('idUsuario', this.currentChatId);
            } else {
                formData.append('idGrupo', this.currentGroupId);
            }

            const response = await fetch('send_message.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.text();
            console.log('Respuesta del servidor:', result);

            if (result === 'OK') {
                messageInput.value = '';
                messageInput.style.height = 'auto';
                
                // Recargar mensajes inmediatamente
                await this.loadMessages();
                
                console.log('Mensaje enviado correctamente');
            } else {
                console.error('Error al enviar mensaje:', result);
                alert('Error al enviar el mensaje: ' + result);
            }
            
        } catch (error) {
            console.error('Error sending message:', error);
            alert('Error de conexión al enviar el mensaje');
        } finally {
            // Re-habilitar botón
            sendBtn.disabled = false;
            sendBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';
        }
    }

    filterChats(searchTerm) {
        const chatItems = document.querySelectorAll('.chat-item');
        chatItems.forEach(item => {
            const chatName = item.querySelector('.chat-name').textContent.toLowerCase();
            if (chatName.includes(searchTerm.toLowerCase())) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    showGroupModal() {
        document.getElementById('groupModal').style.display = 'block';
    }

    async createGroup() {
        const name = document.getElementById('groupName').value.trim();
        const desc = document.getElementById('groupDesc').value.trim();

        if (!name) return;

        try {
            const formData = new FormData();
            formData.append('nombre', name);
            formData.append('descripcion', desc);

            const response = await fetch('create_group.php', {
                method: 'POST',
                body: formData
            });

            if (response.ok) {
                const groupId = await response.text();
                document.getElementById('groupModal').style.display = 'none';
                document.getElementById('groupForm').reset();
                this.loadChatList();
                this.showMemberModal(groupId);
            }
        } catch (error) {
            console.error('Error creating group:', error);
        }
    }

    showMemberModal(groupId = null) {
        if (groupId) {
            this.currentGroupId = groupId;
        }
        document.getElementById('memberModal').style.display = 'block';
        document.getElementById('userSearch').value = '';
        document.getElementById('searchResults').innerHTML = '';
        document.getElementById('groupMembersList').innerHTML = '';
    }

    async searchUsers(query) {
        if (query.length < 2) {
            document.getElementById('searchResults').innerHTML = '';
            return;
        }

        try {
            const formData = new FormData();
            formData.append('query', query);

            const response = await fetch('search_users.php', {
                method: 'POST',
                body: formData
            });
            const html = await response.text();
            document.getElementById('searchResults').innerHTML = html;

            // Agregar event listeners a los resultados
            document.querySelectorAll('.search-result').forEach(result => {
                result.addEventListener('click', () => {
                    this.addUserToSelection(result.dataset.userId, result.dataset.username);
                });
            });
        } catch (error) {
            console.error('Error searching users:', error);
        }
    }

    addUserToSelection(userId, username) {
        const membersList = document.getElementById('groupMembersList');
        if (membersList.querySelector(`[data-user-id="${userId}"]`)) return;

        const memberTag = document.createElement('div');
        memberTag.className = 'member-tag';
        memberTag.innerHTML = `
            ${username}
            <span class="remove-member" data-user-id="${userId}">×</span>
        `;
        memberTag.dataset.userId = userId;

        membersList.appendChild(memberTag);

        // Event listener para eliminar miembro
        memberTag.querySelector('.remove-member').addEventListener('click', (e) => {
            e.stopPropagation();
            memberTag.remove();
        });
    }

    async addMembersToGroup() {
        const members = Array.from(document.getElementById('groupMembersList').children).map(tag => tag.dataset.userId);

        if (members.length === 0) return;

        try {
            const formData = new FormData();
            formData.append('groupId', this.currentGroupId);
            formData.append('members', JSON.stringify(members));

            const response = await fetch('add_members.php', {
                method: 'POST',
                body: formData
            });

            if (response.ok) {
                document.getElementById('memberModal').style.display = 'none';
                document.getElementById('groupMembersList').innerHTML = '';
                document.getElementById('userSearch').value = '';
                this.loadChatList();
            }
        } catch (error) {
            console.error('Error adding members:', error);
        }
    }
}

// Hacer disponible globalmente para los event listeners del PHP
window.chatSystem = new ChatSystem();

// Inicializar cuando se carga la página
document.addEventListener('DOMContentLoaded', () => {
    window.chatSystem = new ChatSystem();
});