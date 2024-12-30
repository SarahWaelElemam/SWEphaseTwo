<?php
require_once __DIR__ . '../../../Controller/SessionManager.php';
use App\Controller\SessionManager;

// Debugging: Check if the user is logged in before logging out
if (SessionManager::isLoggedIn()) {
    echo "User is logged in. Proceeding with logout...";
} else {
    echo "User is not logged in.";
}

// Destroy the session and log the user out
SessionManager::logoutUser();

// Debugging: Check if the session is destroyed
if (!SessionManager::isLoggedIn()) {
    echo "Session successfully destroyed.";
} else {
    echo "Failed to destroy the session.";
}

// Redirect the user to the homepage
header("Location: /Software/SWEphaseTwo/app/view/pages/Homepage.php"); // Adjust the URL if needed
exit;
?>