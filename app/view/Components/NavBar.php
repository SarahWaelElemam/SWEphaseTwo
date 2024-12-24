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
                <a href="Login_Signup.php"><i class="fas fa-user user-icon"></i></a>
            </div>
        </nav>
    </header>
    <script src="../js/NavBar.js"></script>
</body>
</html>