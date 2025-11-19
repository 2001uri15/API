<?php
session_start();

require_once '../conn.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

// Consultar rol del usuario en la base de datos
$stmt = $conn->prepare("SELECT rol FROM Usuarios WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user || $user['rol'] != 2) {
    header('Location: ../index.php');
    exit;
}

require_once '../conn.php';
require_once 'cl/Usuario.php';
require_once '../templates/header.php';
require_once '../templates/sidebar.php';

$usuarioObj = new Usuarios($conn);
$usuarios = $usuarioObj->getAllUsers();

// Contar usuarios por rol para las estadísticas
$stmt = $conn->prepare("SELECT rol, COUNT(*) as count FROM Usuarios GROUP BY rol");
$stmt->execute();
$roleCounts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$adminCount = 0;
$userCount = 0;

foreach ($roleCounts as $role) {
    if ($role['rol'] == 2) {
        $adminCount = $role['count'];
    } else {
        $userCount = $role['count'];
    }
}

$totalUsers = $adminCount + $userCount;
?>
<style>
/* Estilos para el Panel de Administración */
.main-content {
    padding: 30px;
    background: #f8fafc;
    min-height: calc(100vh - 120px);
}

.main-content h1 {
    color: #2d3748;
    font-size: 2.2rem;
    margin-bottom: 25px;
    font-weight: 700;
    border-bottom: 3px solid #667eea;
    padding-bottom: 10px;
    display: inline-block;
}

/* Contenedor de la tabla */
.table-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    margin-bottom: 30px;
}

/* Estilos de la tabla */
.users-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.95rem;
}

.users-table thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.users-table th {
    padding: 16px 12px;
    text-align: left;
    font-weight: 600;
    font-size: 0.9rem;
    letter-spacing: 0.5px;
    border: none;
}

.users-table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid #e2e8f0;
}

.users-table tbody tr:last-child {
    border-bottom: none;
}

.users-table tbody tr:hover {
    background-color: #f7fafc;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.users-table td {
    padding: 14px 12px;
    color: #4a5568;
    border: none;
}

/* Estilos para las celdas específicas */
.users-table td:first-child {
    font-weight: 600;
    color: #2d3748;
}

.users-table td:nth-child(6) {
    font-weight: 600;
}

.users-table td:nth-child(6):contains("Admin") {
    color: #e53e3e;
}

.users-table td:nth-child(6):contains("Usuario") {
    color: #38a169;
}

/* Estilos para los botones de acción */
.actions {
    display: flex;
    gap: 10px;
}

.action-btn {
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 500;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.edit-btn {
    background: #edf2f7;
    color: #2d3748;
    border: 1px solid #cbd5e0;
}

.edit-btn:hover {
    background: #e2e8f0;
    transform: translateY(-1px);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.delete-btn {
    background: #fed7d7;
    color: #c53030;
    border: 1px solid #feb2b2;
}

.delete-btn:hover {
    background: #feb2b2;
    transform: translateY(-1px);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Separador entre botones */
.action-separator {
    color: #cbd5e0;
    font-weight: 300;
}

/* Estado vacío */
.empty-state {
    text-align: center;
    padding: 50px 20px;
    color: #718096;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 15px;
    color: #cbd5e0;
}

.empty-state h3 {
    font-size: 1.3rem;
    margin-bottom: 10px;
    color: #4a5568;
}

/* Header de la tabla con estadísticas */
.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 15px;
}

.stats {
    display: flex;
    gap: 20px;
}

.stat-item {
    background: white;
    padding: 12px 20px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    display: flex;
    flex-direction: column;
    align-items: center;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #667eea;
}

.stat-label {
    font-size: 0.8rem;
    color: #718096;
    margin-top: 5px;
}

/* Responsive */
@media (max-width: 1024px) {
    .table-container {
        overflow-x: auto;
    }
    
    .users-table {
        min-width: 900px;
    }
}

@media (max-width: 768px) {
    .main-content {
        padding: 20px 15px;
    }
    
    .main-content h1 {
        font-size: 1.8rem;
    }
    
    .table-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .stats {
        width: 100%;
        justify-content: space-between;
    }
    
    .stat-item {
        flex: 1;
        padding: 10px 15px;
    }
    
    .actions {
        flex-direction: column;
        gap: 5px;
    }
    
    .action-btn {
        justify-content: center;
        text-align: center;
    }
}

@media (max-width: 480px) {
    .main-content h1 {
        font-size: 1.5rem;
    }
    
    .stats {
        flex-direction: column;
        gap: 10px;
    }
    
    .stat-item {
        flex-direction: row;
        justify-content: space-between;
    }
    
    .stat-value {
        font-size: 1.3rem;
    }
}
</style>
<div class="main-content">
    <h1>Panel de Administración - Usuarios</h1>
    
    <div class="table-header">
        <div class="stats">
            <div class="stat-item">
                <span class="stat-value"><?= $totalUsers ?></span>
                <span class="stat-label">Total Usuarios</span>
            </div>
            <div class="stat-item">
                <span class="stat-value"><?= $adminCount ?></span>
                <span class="stat-label">Administradores</span>
            </div>
            <div class="stat-item">
                <span class="stat-value"><?= $userCount ?></span>
                <span class="stat-label">Usuarios Normales</span>
            </div>
        </div>
    </div>

    <div class="table-container">
        <table class="users-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($usuarios->num_rows > 0) {
                    while ($u = $usuarios->fetch_assoc()) { 
                ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td><?= htmlspecialchars($u['username']) ?></td>
                        <td><?= htmlspecialchars($u['nombre']) ?></td>
                        <td><?= htmlspecialchars($u['apellidos']) ?></td>
                        <td><?= htmlspecialchars($u['mail']) ?></td>
                        <td><?= $u['rol'] == 2 ? 'Admin' : 'Usuario' ?></td>
                        <td>
                            <div class="actions">
                                <a href="edit_admin.php?id=<?= $u['id'] ?>" class="action-btn edit-btn">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="delete_admin.php?id=<?= $u['id'] ?>" 
                                   class="action-btn delete-btn"
                                   onclick="return confirm('¿Seguro que deseas eliminar esta cuenta?');">
                                    <i class="fas fa-trash"></i> Eliminar
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php 
                    }
                } else { 
                ?>
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="fas fa-users"></i>
                                <h3>No hay usuarios registrados</h3>
                                <p>No se encontraron usuarios en la base de datos.</p>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../templates/footer.php'; ?>