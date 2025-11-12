<?php
class LogIn {
    private $conn;
    
    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }   

    public function authenticate($username, $password) {
        $stmt = $this->conn->prepare("SELECT `id`, `username`, `nombre`, `apellidos`, `mail`, `password` FROM Usuarios WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                // Devolvemos el array de datos del usuario, no el resultado
                unset($user['password']); // Eliminamos el password por seguridad
                return $user;
            }
        }
        
        return false;
    }

    public function startSession($userData) {
        // session_start(); // No necesitas llamar session_start() aquí si ya se llamó antes
        $_SESSION['user_id'] = $userData['id'];
        $_SESSION['username'] = $userData['username'];
        $_SESSION['nombre'] = $userData['nombre'];
        $_SESSION['apellidos'] = $userData['apellidos'];
        $_SESSION['mail'] = $userData['mail'];
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
    }
}
?>