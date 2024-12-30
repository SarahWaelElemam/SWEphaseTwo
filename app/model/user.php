<?php
require_once("../../model/Model.php");
require_once(__DIR__ . '../../db/Dbh.php');

abstract class User extends Model {
    protected $conn;
    protected $fname;
    protected $lname;
    protected $email;
    protected $password;
    protected $birthDate;
    protected $phone;
    protected $government;
    protected $gender;
    protected $image;
    protected $role;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function create($userData) {
        $query = "INSERT INTO user (fname, lname, email, password, role, birth_date, phone, government, gender, image) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            $userData['fname'],
            $userData['lname'],
            $userData['email'],
            password_hash($userData['password'], PASSWORD_DEFAULT),
            $this->role,
            $userData['birthDate'],
            $userData['phone'],
            $userData['government'],
            $userData['gender'],
            $userData['image']
        ]);
    }
    
    public function read($id) {
        $query = "SELECT * FROM user WHERE id = ? AND role = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id, $this->role]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function update($id, $userData) {
        $query = "UPDATE user SET fname = ?, lname = ?, email = ?, birth_date = ?, 
                 phone = ?, government = ?, gender = ?, image = ? WHERE id = ? AND role = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            $userData['fname'],
            $userData['lname'],
            $userData['email'],
            $userData['birthDate'],
            $userData['phone'],
            $userData['government'],
            $userData['gender'],
            $userData['image'],
            $id,
            $this->role
        ]);
    }
    
    public function delete($id) {
        $query = "DELETE FROM users WHERE id = ? AND role = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id, $this->role]);
    }
}

class Admin extends User {
    protected $role = 'admin';
}

class Customer extends User {
    protected $role = 'customer';
}

class Organizer extends User {
    protected $role = 'organizer';
}
?>