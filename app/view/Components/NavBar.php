<?php
require_once __DIR__ . '../../../Controller/SessionManager.php';
use App\Controller\SessionManager;

SessionManager::startSession();
$isLoggedIn = SessionManager::isLoggedIn();
?>
<nav>
    <?php if ($isLoggedIn): ?>
        <a href="/app/view/pages/User.php">Profile</a>
        <a href="/app/view/pages/Logout.php">Logout</a>
    <?php else: ?>
        <a href="/app/view/pages/Login_Signup.php">Login / Signup</a>
    <?php endif; ?>
</nav>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TixCarte - Responsive Navigation</title>
    <link rel="stylesheet" href="../../../public/css/NavBar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <img src="../../../public/images/logo.png">
            </div>
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" placeholder="Search...">
            </div>
            <button class="menu-toggle" aria-label="Toggle menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <div class="nav-items">
                <a href="Events.php" class="nav-link">Events</a>
                <a href="file.php" class="nav-link">Contact & Support</a>
                <label class="toggle-switch">
                    <input type="checkbox" id="theme-toggle">
                    <span class="slider"></span>
                </label>
                <!-- User Profile or Login/Signup -->
                <div class="user-icon">
    <a href="<?php echo $isLoggedIn ? '/SWEphaseTwo/app/view/pages/User.php' : '/SWEphaseTwo/app/view/pages/Login_Signup.php'; ?>">
        <i class="fa fa-user"></i>
    </a>
</div>
          </nav>
    </header>
    <script src="../js/NavBar.js"></script>
</body>
</html>