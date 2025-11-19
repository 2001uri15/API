<?php
session_start();

require_once '../conn.php';
require_once 'cl/Usuario.php';

$usuarioObj = new Usuarios($conn);

if (!isset($_GET['id'])) {
    die("ID no especificado");
}

$usuario = $usuarioObj->getUserById($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuarioObj->updateUser(
        $_POST['id'],
        $_POST['username'],
        $_POST['nombre'],
        $_POST['apellidos'],
        $_POST['mail'],
        "",          // NO cambiamos contraseña
        1,           // Activo siempre
        $_POST['rol']
    );

    header('Location: index.php');
    exit;
}

require_once '../templates/header.php';
require_once '../templates/sidebar.php';
?>

<style>
/* Estilos para el formulario de edición */
.main-content {
    padding: 30px;
    background: #f8fafc;
    min-height: calc(100vh - 120px);
}

.edit-container {
    max-width: 800px;
    margin: 0 auto;
}

.page-header {
    margin-bottom: 30px;
}

.page-header h1 {
    color: #2d3748;
    font-size: 2.2rem;
    font-weight: 700;
    margin-bottom: 10px;
    border-bottom: 3px solid #667eea;
    padding-bottom: 10px;
    display: inline-block;
}

.page-header .subtitle {
    color: #718096;
    font-size: 1rem;
}

.edit-form {
    background: white;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.form-group {
    margin-bottom: 25px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 25px;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #2d3748;
    font-size: 0.95rem;
}

.form-input {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #fff;
}

.form-input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-select {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 1rem;
    background: #fff;
    cursor: pointer;
    transition: all 0.3s ease;
}

.form-select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 30px;
    padding-top: 25px;
    border-top: 1px solid #e2e8f0;
}

.btn {
    padding: 12px 30px;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
}

.btn-secondary {
    background: #e2e8f0;
    color: #4a5568;
}

.btn-secondary:hover {
    background: #cbd5e0;
    transform: translateY(-2px);
}

.user-info-card {
    background: #f7fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 25px;
}

.user-info-card h3 {
    margin: 0 0 15px 0;
    color: #2d3748;
    font-size: 1.1rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.info-item {
    display: flex;
    flex-direction: column;
}

.info-label {
    font-size: 0.85rem;
    color: #718096;
    margin-bottom: 4px;
}

.info-value {
    font-weight: 600;
    color: #2d3748;
}

/* Responsive */
@media (max-width: 768px) {
    .main-content {
        padding: 20px 15px;
    }
    
    .edit-form {
        padding: 25px 20px;
    }
    
    .form-row {
        grid-template-columns: 1fr;
        gap: 0;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn {
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .page-header h1 {
        font-size: 1.8rem;
    }
    
    .edit-form {
        padding: 20px 15px;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="main-content">
    <div class="edit-container">
        <div class="page-header">
            <h1>Editar Usuario</h1>
            <p class="subtitle">Panel de Administración - Modificar datos de usuario</p>
        </div>

        <div class="user-info-card">
            <h3>Información Actual del Usuario</h3>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">ID de Usuario</span>
                    <span class="info-value">#<?= $usuario['id'] ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Username</span>
                    <span class="info-value"><?= htmlspecialchars($usuario['username']) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Rol Actual</span>
                    <span class="info-value" style="color: <?= $usuario['rol'] == 2 ? '#e53e3e' : '#38a169' ?>;">
                        <?= $usuario['rol'] == 2 ? 'Administrador' : 'Usuario' ?>
                    </span>
                </div>
            </div>
        </div>

        <form method="POST" class="edit-form">
            <input type="hidden" name="id" value="<?= $usuario['id'] ?>">

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="username">
                        <i class="fas fa-user"></i> Nombre de Usuario
                    </label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           class="form-input" 
                           value="<?= htmlspecialchars($usuario['username']) ?>" 
                           required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="mail">
                        <i class="fas fa-envelope"></i> Correo Electrónico
                    </label>
                    <input type="email" 
                           id="mail" 
                           name="mail" 
                           class="form-input" 
                           value="<?= htmlspecialchars($usuario['mail']) ?>" 
                           required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="nombre">
                        <i class="fas fa-id-card"></i> Nombre
                    </label>
                    <input type="text" 
                           id="nombre" 
                           name="nombre" 
                           class="form-input" 
                           value="<?= htmlspecialchars($usuario['nombre']) ?>" 
                           required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="apellidos">
                        <i class="fas fa-id-card"></i> Apellidos
                    </label>
                    <input type="text" 
                           id="apellidos" 
                           name="apellidos" 
                           class="form-input" 
                           value="<?= htmlspecialchars($usuario['apellidos']) ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="rol">
                    <i class="fas fa-shield-alt"></i> Rol del Usuario
                </label>
                <select id="rol" name="rol" class="form-select" required>
                    <option value="1" <?= $usuario['rol'] == 1 ? 'selected' : '' ?>>Usuario Normal</option>
                    <option value="2" <?= $usuario['rol'] == 2 ? 'selected' : '' ?>>Administrador</option>
                </select>
                <small style="color: #718096; font-size: 0.85rem; margin-top: 5px; display: block;">
                    Los administradores tienen acceso completo al sistema
                </small>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver al Listado
                </a>
            </div>
        </form>
    </div>
</div>

<?php require_once '../templates/footer.php'; ?>