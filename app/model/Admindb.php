<?php
require_once("../../model/Model.php");

class Admindb extends Model {
    protected $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Fetch all users
    public function getAllUsers() {
        $sql = "SELECT * FROM user";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Error preparing query: " . $this->conn->error);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            throw new Exception("Error executing query: " . $stmt->error);
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Fetch a specific user by ID
    public function getUserById($userId) {
        $sql = "SELECT * FROM user WHERE User_ID = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Error preparing query: " . $this->conn->error);
        }

        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            throw new Exception("Error fetching user: " . $stmt->error);
        }

        return $result->fetch_assoc();
    }

    // Fetch a user by email
    public function getUserByEmail($email) {
        $sql = "SELECT * FROM user WHERE Email = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error preparing query: " . $this->conn->error);
        }
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) {
            throw new Exception("Error fetching user by email: " . $stmt->error);
        }
        return $result->fetch_assoc();
    }
    // Add a new user
    public function addUser($email, $password, $role, $fname, $lname, $birthdate, $phone, $government, $gender) {
        $sql = "INSERT INTO user (Email, Password, Role, FName, LName, BirthDate, Phone, Government, Gender) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Error preparing query: " . $this->conn->error);
        }

        $stmt->bind_param('sssssssss', $email, $password, $role, $fname, $lname, $birthdate, $phone, $government, $gender);
        $stmt->execute();

        if ($stmt->error) {
            throw new Exception("Error adding user: " . $stmt->error);
        }

        return $this->conn->insert_id;
    }

    // Update an existing user
    public function updateUser($userId, $email, $password, $role, $fname, $lname, $birthdate, $phone, $government, $gender) {
        if ($password) {
            $sql = "UPDATE user 
                    SET Email = ?, Password = ?, Role = ?, FName = ?, LName = ?, BirthDate = ?, Phone = ?, Government = ?, Gender = ? 
                    WHERE User_ID = ?";
            $stmt = $this->conn->prepare($sql);
        } else {
            $sql = "UPDATE user 
                    SET Email = ?, Role = ?, FName = ?, LName = ?, BirthDate = ?, Phone = ?, Government = ?, Gender = ? 
                    WHERE User_ID = ?";
            $stmt = $this->conn->prepare($sql);
        }

        if (!$stmt) {
            throw new Exception("Error preparing query: " . $this->conn->error);
        }

        if ($password) {
            $stmt->bind_param('sssssssssi', $email, $password, $role, $fname, $lname, $birthdate, $phone, $government, $gender, $userId);
        } else {
            $stmt->bind_param('ssssssssi', $email, $role, $fname, $lname, $birthdate, $phone, $government, $gender, $userId);
        }

        $stmt->execute();

        if ($stmt->error) {
            throw new Exception("Error updating user: " . $stmt->error);
        }

        return $stmt->affected_rows;
    }

    // Delete a user
    public function deleteUser($userId) {
        $sql = "DELETE FROM user WHERE User_ID = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Error preparing query: " . $this->conn->error);
        }

        $stmt->bind_param('i', $userId);
        $stmt->execute();

        if ($stmt->error) {
            throw new Exception("Error deleting user: " . $stmt->error);
        }

        return $stmt->affected_rows;
    }
}
?>