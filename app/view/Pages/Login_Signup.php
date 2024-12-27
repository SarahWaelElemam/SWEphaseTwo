<?php
require_once __DIR__ . '../../../Controller/SessionManager.php';
require_once("../../model/LoginSignupModel.php");
use App\Controller\SessionManager;

// Redirect if already logged in
if (SessionManager::isLoggedIn()) {
    SessionManager::redirect('/SWEphaseTwo/app/view/Pages/Homepage.php');
}

$model = new LoginSignupModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formType = $_POST['formType'] ?? '';

    if ($formType === 'login') {
        $model->handleLogin($_POST);
    } elseif ($formType === 'signup') {
        $model->handleSignup($_POST);
    }
}
?>

<?php include "../Components/NavBar.php" ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TixCarte Login/Signup</title>
    <link rel="stylesheet" href="../../../public/css/Login_Signup.css">
    <link rel="stylesheet" href="../../../public/css/Footer.css">
</head>
<body>
    <section class="forms-section">
        <h1 class="section-title">Welcome To TickCarte</h1>
        <div class="forms">
            <div class="form-wrapper is-active">
                <button type="button" class="switcher switcher-login">
                    Login
                    <span class="underline"></span>
                </button>
                <form class="form form-login" method="POST" action="login_signup.php">
                <fieldset>
                        <legend>Please, enter your email and password for login.</legend>
                        <div class="input-block">
                            <label for="login-email">E-mail</label>
                            <input id="login-email" type="email" name="Email" required>
                        </div>
                        <div class="input-block">
                            <label for="login-password">Password</label>
                            <input id="login-password" type="password" name="Password" required>
                       <!-- Add the Forgot Password link here -->
<div class="forgot-password" style="margin-top: 15px; text-align: right;">
    <a href="#" id="forgot-password-link">Forgot Password?</a>
</div>
                        </div>
                    </fieldset>
                    <input type="hidden" name="formType" value="login">
                    <button type="submit" class="btn-login">Login</button>
               
                </form>
            </div>
            
            <div class="form-wrapper">
                <button type="button" class="switcher switcher-signup">
                    Sign Up
                    <span class="underline"></span>
                </button>
                <div class="form-container">
                    <form class="form form-signup" method="POST" action="">
                        <fieldset>
                            <legend>Please, enter your details to sign up.</legend>
                            <div class="input-block">
                                <label for="signup-FName">First Name</label>
                                <input id="signup-FName" type="text" name="FName" required>
                            </div>
                            <div class="input-block">
                                <label for="signup-LName">Last Name</label>
                                <input id="signup-LName" type="text" name="LName" required>
                            </div>
                            <div class="input-block">
                                <label for="signup-email">E-mail</label>
                                <input id="signup-email" type="email" name="Email" required>
                            </div>
                            <div class="input-block">
                                <label for="signup-password">Password</label>
                                <input id="signup-password" type="password" name="Password" required>
                            </div>
                            <div class="input-block">
                                <label for="signup-password-confirm">Confirm Password</label>
                                <input id="signup-password-confirm" type="password" name="ConfirmPassword" required>
                            </div>
                            <div class="input-block">
                                <label for="signup-number">Phone Number</label>
                                <input id="signup-number" type="PhoneNumber" name="PhoneNumber" required>
                            </div>
                            <div class="input-block">
                                <label for="signup-government">Choose Government</label>
                                <select id="signup-government" name="Government" required>
                                    <option value="" disabled selected>Select your Government</option>
                                    <option value="Cairo">Cairo</option>
                                    <option value="Alexandria">Alexandria</option>
                                    <!-- Additional options omitted for brevity -->
                                </select>
                            </div>
                            <div class="input-block">
                                <label for="signup-dob">Date of Birth</label>
                                <input id="signup-dob" type="date" name="DOB" required>
                            </div>
                            <div class="input-block">
                                <label for="signup-gender">Gender</label>
                                <select id="signup-gender" name="Gender" required>
                                    <option value="" disabled selected>Select your Gender</option>
                                    <option value="Female">Female</option>
                                    <option value="Male">Male</option>
                                </select>
                            </div>
                        </fieldset>
                        <input type="hidden" name="formType" value="signup">
                        <button type="submit" class="btn-signup">Continue</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <?php include "../Components/Footer.php"?>
    <script src="../../../public/js/Login_Signup.js"></script>
</body>
</html>
