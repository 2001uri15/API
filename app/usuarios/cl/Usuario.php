<?php
class Usuarios{
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }   

    public function getAllUsers() {
        $stmt = $this->conn->prepare("SELECT id, username, nombre, apellidos, mail, activo, rol FROM Usuarios");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

    public function getUserById($id) {
        $stmt = $this->conn->prepare("SELECT id, username, nombre, apellidos, mail, activo, rol FROM Usuarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function createUser($username, $nombre, $apellidos, $mail, $password, $activo, $rol) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->conn->prepare("INSERT INTO Usuarios (username, nombre, apellidos, mail, password, activo, rol) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssis", $username, $nombre, $apellidos, $mail, $hashedPassword, $activo, $rol);
        return $stmt->execute();
    }

    public function updateUser($id, $username, $nombre, $apellidos, $mail, $password, $activo, $rol) {
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $this->conn->prepare("UPDATE Usuarios SET username = ?, nombre = ?, apellidos = ?, mail = ?, password = ?, activo = ?, rol = ? WHERE id = ?");
            $stmt->bind_param("sssssisi", $username, $nombre, $apellidos, $mail, $hashedPassword, $activo, $rol, $id);
        } else {
            $stmt = $this->conn->prepare("UPDATE Usuarios SET username = ?, nombre = ?, apellidos = ?, mail = ?, activo = ?, rol = ? WHERE id = ?");
            $stmt->bind_param("sssssii", $username, $nombre, $apellidos, $mail, $activo, $rol, $id);
        }
        return $stmt->execute();
    }

    public function deleteUser($id) {
        $stmt = $this->conn->prepare("DELETE FROM Usuarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}