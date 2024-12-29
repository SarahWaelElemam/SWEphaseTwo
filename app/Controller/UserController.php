<?php
// app/controller/UserController.php
require_once __DIR__ . '/../model/UserModel.php';

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function getUserProfile() {
        session_start();
    
        // Debugging: Check if session started and user_id exists
        if (session_status() === PHP_SESSION_ACTIVE) {
            debugMessage("Session started successfully.");
        } else {
            debugMessage("Session failed to start.");
        }
    
        if (!isset($_SESSION['user_id'])) {
            debugMessage("Session active, but user_id does not exist.");
            // Redirect to login page
            header('Location: /SWEPhase2/SWEPhaseTwo/app/view/Pages/Login_Signup.php');
            exit();
        }
    
        $userId = $_SESSION['user_id'];
        debugMessage("Session active and user_id exists: $userId");
    
        $userData = $this->userModel->getUserById($userId);
    
        if ($userData) {
            debugMessage("User data retrieved successfully for user_id: $userId");
    
            // Check if all expected data fields are available
            foreach ($userData as $key => $value) {
                if (empty($value)) {
                    debugMessage("Warning: $key is missing or empty.");
                }
            }
    
            // Return user data as JSON for AJAX requests
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                echo json_encode($userData);
                exit();
            }
            
            // For normal requests, include the view
            require_once 'C:/xampp/htdocs/SWEPhase2/SWEPhaseTwo/app/view/Pages/User.php';
        } else {
            debugMessage("User not found for user_id: $userId");
            echo json_encode(['error' => 'User not found']);
        }
    }
    

    public function updateProfile() {
        session_start();
        
        // Debugging: Check session and user_id
        if (session_status() === PHP_SESSION_ACTIVE) {
            debugMessage("Session started successfully for updateProfile.");
        } else {
            debugMessage("Session failed to start in updateProfile.");
        }

        if (!isset($_SESSION['user_id'])) {
            debugMessage("Session active, but user_id does not exist in updateProfile.");
            echo json_encode(['error' => 'Not authenticated']);
            exit();
        }

        $userId = $_SESSION['user_id'];
        debugMessage("Session active and user_id exists in updateProfile: $userId");

        $data = [
            'fname' => $_POST['fname'] ?? '',
            'lname' => $_POST['lname'] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'government' => $_POST['government'] ?? '',
            'gender' => $_POST['gender'] ?? ''
        ];

        if ($this->userModel->updateUser($userId, $data)) {
            debugMessage("User profile updated successfully for user_id: $userId");
            echo json_encode(['success' => true]);
        } else {
            debugMessage("Failed to update user profile for user_id: $userId");
            echo json_encode(['error' => 'Update failed']);
        }
    }

    public function deleteProfile() {
        session_start();
        
        // Debugging: Check session and user_id
        if (session_status() === PHP_SESSION_ACTIVE) {
            debugMessage("Session started successfully for deleteProfile.");
        } else {
            debugMessage("Session failed to start in deleteProfile.");
        }

        if (!isset($_SESSION['user_id'])) {
            debugMessage("Session active, but user_id does not exist in deleteProfile.");
            echo json_encode(['error' => 'Not authenticated']);
            exit();
        }

        $userId = $_SESSION['user_id'];
        debugMessage("Session active and user_id exists in deleteProfile: $userId");

        if ($this->userModel->deleteUser($userId)) {
            session_destroy();
            debugMessage("User account deleted and session destroyed for user_id: $userId");
            echo json_encode(['success' => true]);
        } else {
            debugMessage("Failed to delete user account for user_id: $userId");
            echo json_encode(['error' => 'Deletion failed']);
        }
    }
}
?>
