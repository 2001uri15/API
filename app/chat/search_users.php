<?php
session_start();
if (!isset($_SESSION['user_id'])) exit;
require_once '../conn.php';

$query = $_POST['query'] ?? '';
$userId = $_SESSION['user_id'];

if (strlen($query) < 2) {
    echo "<div class='search-result'>Escribe al menos 2 caracteres</div>";
    exit;
}

$searchQuery = "%$query%";
$stmt = $conn->prepare("SELECT id, username, nombre, apellidos 
                       FROM Usuarios 
                       WHERE (username LIKE ? OR nombre LIKE ? OR apellidos LIKE ?) 
                       AND id != ? AND activo = 1
                       LIMIT 10");
$stmt->bind_param("sssi", $searchQuery, $searchQuery, $searchQuery, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($user = $result->fetch_assoc()) {
        echo "<div class='search-result' data-user-id='{$user['id']}' data-username='{$user['username']}'>
                <span>{$user['username']} - {$user['nombre']} {$user['apellidos']}</span>
                <button class='add-member-btn' onclick='event.stopPropagation(); chatSystem.addUserToSelection(\"{$user['id']}\", \"{$user['username']}\")'>Añadir</button>
              </div>";
    }
} else {
    echo "<div class='search-result'>No se encontraron usuarios</div>";
}
?>