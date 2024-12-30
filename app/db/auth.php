<?php
require_once 'Dbh.php';

function checkPermission($permission_name) {
    if (!isset($_SESSION['user_id'])) {
        // User not logged in, redirect to login page
        header("Location: Login_Signup.php");
        exit;
    }

    $dbh = new Dbh();
    $conn = $dbh->connect();

    // Fetch the role of the current user
    $userId = $_SESSION['user_id'];
    
    // Prepare and execute the query to get the user's role
    $stmt = $conn->prepare("SELECT role FROM user WHERE User_ID = ?");
    $stmt->bind_param("i", $userId); // Binding parameters securely to prevent SQL injection
    $stmt->execute();
    
    // Fetch the result
    $result = $stmt->get_result();  // Get the result set from the query
    
    if ($result->num_rows === 0) {
        // No user found, redirect to login
        header("Location: Login_Signup.php");
        exit;
    }

    $user = $result->fetch_assoc();  // Fetch the row as an associative array

    // Check if role is set in the fetched data
    if (!isset($user['role'])) {
        // If role is not found in the result, redirect to login page
        header("Location: Login_Signup.php");
        exit;
    }

    $role = $user['role'];

    // Fetch the permissions for the current user's role
    $stmt = $conn->prepare("
        SELECT p.name 
        FROM permissions p
        JOIN role_permissions rp ON p.id = rp.permission_id
        JOIN roles r ON rp.role_id = r.id
        WHERE r.name = ? AND p.name = ?
    ");
    $stmt->bind_param("ss", $role, $permission_name);  // Binding both role and permission name securely
    $stmt->execute();
    
    // Get the result of the permission query
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        if($role=='Admin')
        {
            echo "<script>alert('You do not have permission to access this page.'); window.location.href = 'dashboard.php';</script>";
            exit;
        }
        else if($role=='Customer'){
            echo "<script>alert('You do not have permission to access this page.'); window.location.href = 'Homepage.php';</script>";
            exit;
        }
        else if($role=='Organizer'){
            echo "<script>alert('You do not have permission to access this page.'); window.location.href = 'organizer.php';</script>";
            exit;
        }
    }


    // User has permission, return true
    return true;
}
?>
