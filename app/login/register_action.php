<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');

session_start();
require_once __DIR__ . '/../conn.php';

// Read JSON
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['success' => false, 'message' => 'Payload inválido']);
    exit;
}

$username = trim($input['username'] ?? '');
$name = trim($input['name'] ?? '');
$surname = trim($input['surname'] ?? '');
$email = trim($input['email'] ?? '');
$password = $input['password'] ?? '';

if (!$username || !$name || !$email || !$password) {
    echo json_encode(['success' => false, 'message' => 'Faltan campos obligatorios']);
    exit;
}

// Basic validation
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Email inválido']);
    exit;
}

try {
    // Logs
    error_log('REGISTER: username=' . $username . ', name=' . $name . ', email=' . $email);
    
    // ver conexion
    if (!isset($conn) || !($conn instanceof mysqli)) {
        throw new Exception('No hay conexión a la base de datos');
    }
    error_log('REGISTER: Conexión BD OK');

    // Comprobar email si existe
    $sql = "SELECT id FROM Usuarios WHERE mail = ? OR username = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    if (!$stmt) throw new Exception('Error preparando consulta: ' . $conn->error);
    $stmt->bind_param('ss', $email, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        error_log('REGISTER: Usuario/email ya existe');
        echo json_encode(['success' => false, 'message' => 'El email o usuario ya está registrado']);
        exit;
    }
    $stmt->close();
    error_log('REGISTER: Verificación de duplicados OK');

    // añadir usuario`
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $activo = 1;
    $rol = 0;

    error_log('REGISTER: Hash password, activo=1, rol=0. Hash=' . substr($hash, 0, 20) . '...');
    $sql = "INSERT INTO Usuarios (username, nombre, apellidos, mail, password, activo, rol) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) throw new Exception('Error preparando insert: ' . $conn->error);
    $stmt->bind_param('sssssii', $username, $name, $surname, $email, $hash, $activo, $rol);
    error_log('REGISTER: Ejecutando INSERT...');
    if ($stmt->execute()) {
        error_log('REGISTER: INSERT exitoso para user=' . $username);
        echo json_encode(['success' => true, 'message' => 'Usuario registrado correctamente']);
    } else {
        throw new Exception('Error ejecutando insert: ' . $stmt->error);
    }
    $stmt->close();

} catch (Exception $e) {
    error_log('REGISTER ERROR: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error interno: ' . $e->getMessage()]);
}

?>
