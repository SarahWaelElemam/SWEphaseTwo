<?php
require_once("../../Controller/SessionManager.php");
require_once("../../db/Dbh.php");
use App\Controller\SessionManager;

class LoginSignupModel {
    private $conn;
    
    public function __construct() {
        $db = new Dbh();
        $this->conn = $db->getConn();
    
        if (!$this->conn || $this->conn->connect_error) {
            die("Database connection failed: " . $this->conn->connect_error);
        }
    }
    
    private function getRedirectPath($role) {
        $baseUrl = '/Software/SWEphaseTwo/app/view/Pages/';
        switch(strtolower($role)) {
            case 'admin':
                return $baseUrl . 'dashboard.php';
            case 'organizer':
                return $baseUrl . 'organizer.php';
            case 'customer':
                return $baseUrl . 'Homepage.php';
            default:
                return $baseUrl . 'Homepage.php';
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
                    $newUserId = $this->conn->insert_id;
                    SessionManager::loginUser($newUserId);
                    SessionManager::redirect('/Software/SWEphaseTwo/app/view/Pages/Homepage.php');
                    return true;
                } else {
                    echo "<script>alert('Error saving to the database.');</script>";
                    return false;
                }
            }
        } else {
            echo "<script>alert('Please fill in all fields!');</script>";
            return false;
        }
    }
    
     public function handleLogin($data) {
        extract($data);
    
        if (!empty($Email) && !empty($Password)) {
            $stmt = $this->conn->prepare("SELECT User_ID, Password, Role FROM User WHERE Email = ?");
            $stmt->bind_param("s", $Email);
            $stmt->execute();
            $result = $stmt->get_result();
    
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if ($Password === $user['Password']) { // Note: In production, use password_verify() with hashed passwords
                    SessionManager::loginUser($user['User_ID']);
                    SessionManager::redirect($this->getRedirectPath($user['Role']));
                    return true;
                } else {
                    echo "<script>alert('Invalid credentials!');</script>";
                    return false;
                }
            } else {
                echo "<script>alert('No user found with this email!');</script>";
                return false;
            }
        } else {
            echo "<script>alert('Please fill in all fields!');</script>";
            return false;
        }
    }
}
?>
