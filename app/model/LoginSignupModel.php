<?php
require_once("../../Controller/SessionManager.php");
require_once("../../db/Dbh.php");
use App\Controller\SessionManager;

class LoginSignupModel {
    private $conn;
    public function __construct() {
        $db = new Dbh();
        $this->conn = $db->getConn(); // Use getConn to ensure persistent connection
    
        if (!$this->conn || $this->conn->connect_error) {
            die("Database connection failed: " . $this->conn->connect_error);
        }
    }
    
    public function handleSignup($data) {
        extract($data);
    
        if (!empty($FName) && !empty($LName) && !empty($Email) && !empty($Password) && !empty($ConfirmPassword) &&
            !empty($Government) && !empty($PhoneNumber) && !empty($Gender) && !empty($DOB)) {
    
            if ($Password !== $ConfirmPassword) {
                echo "<script>alert('Passwords do not match!');</script>";
                return false;
            }
    
            $checkEmail = $this->conn->prepare("SELECT Email FROM User WHERE Email = ?");
            $checkEmail->bind_param("s", $Email);
            $checkEmail->execute();
            $result = $checkEmail->get_result();
    
            if ($result->num_rows > 0) {
                echo "<script>alert('Email already exists!');</script>";
                return false;
            } else {
                $stmt = $this->conn->prepare("INSERT INTO User (FName, LName, Email, Password, Government, Phone, Gender, BirthDate, Role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $defaultRole = "Customer";
                $stmt->bind_param("sssssssss", $FName, $LName, $Email, $Password, $Government, $PhoneNumber, $Gender, $DOB, $defaultRole);
                if ($stmt->execute()) {
                    SessionManager::loginUser($this->conn->insert_id);
                    SessionManager::redirect('/SWEphaseTwo/app/view/Pages/Homepage.php');
                } else {
                    echo "<script>alert('Error saving to the database.');</script>";
                }
            }
        } else {
            echo "<script>alert('Please fill in all fields!');</script>";
        }
    }
    
    public function handleLogin($data) {
        extract($data);
    
        if (!empty($Email) && !empty($Password)) {
            $stmt = $this->conn->prepare("SELECT * FROM User WHERE Email = ?");
            $stmt->bind_param("s", $Email);
            $stmt->execute();
            $result = $stmt->get_result();
    
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if ($Password === $user['Password']) {
                    SessionManager::loginUser($this->conn->insert_id);
                    SessionManager::redirect('/SWEphaseTwo/app/view/Pages/Homepage.php');
                } else {
                    echo "<script>alert('Invalid credentials!');</script>";
                }
            } else {
                echo "<script>alert('No user found with this email!');</script>";
            }
        } else {
            echo "<script>alert('Please fill in all fields!');</script>";
        }
    }
    
}

?>
