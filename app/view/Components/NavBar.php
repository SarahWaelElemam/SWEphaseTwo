<?php
require_once __DIR__ . '../../../Controller/SessionManager.php';
use App\Controller\SessionManager;

SessionManager::startSession();
$isLoggedIn = SessionManager::isLoggedIn();
?>

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
                <img src="../../../public/images/logo.png" alt="Logo">
            </div>
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" placeholder="Search...">
            </div>
            <div class="nav-items">
                <a href="Events.php" class="nav-link">Events</a>
                <a href="file.php" class="nav-link">Contact & Support</a>
                <?php if ($isLoggedIn): ?>
                    <a href="../../../app/view/pages/Logout.php" class="nav-link">Logout</a> <!-- Show logout if logged in -->
                    <?php endif; ?>
                <!-- User Profile or Login/Signup -->
                
                <a class="nav-link"   href="<?php echo $isLoggedIn ? 'User.php' : 'Login_Signup.php'; ?>">
                <i class="fa fa-user" style=' font-size: 1.5rem; overflow:hidden'></i>
                    </a>
                </div>
            

        </nav>
    </header>
    <script src="../../../public/NavBar.js"></script>
</body>
</html>     