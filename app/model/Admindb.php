<?php
require_once("../../model/Model.php");

class Admindb extends Model {
    protected $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Fetch all users
    public function getAllUsers() {
        $sql = "SELECT * FROM users";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        return $users;
    }

    // Fetch a specific user by ID
    public function getUserById($userId) {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Add a new user (Admin or general user)
    public function addUser($username, $password, $role) {
        $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('sss', $username, $password, $role);
        $stmt->execute();
        return $this->conn->insert_id;
    }

    // Update an existing user
    public function updateUser($userId, $username, $password = null) {
        if ($password) {
            $sql = "UPDATE users SET username = ?, password = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ssi', $username, $password, $userId);
        } else {
            $sql = "UPDATE users SET username = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('si', $username, $userId);
        }
        $stmt->execute();
        return $stmt->affected_rows;
    }

    // Delete a user
    public function deleteUser($userId) {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        return $stmt->affected_rows;
    }
}
?>
