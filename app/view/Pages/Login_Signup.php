<?php
require_once __DIR__ . '../../../Controller/SessionManager.php';
require_once("../../model/LoginSignupModel.php");
require_once("../../db/Dbh.php");

// include 'forget_password .php';
session_start();
use App\Controller\SessionManager;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
// Redirect if already logged in
// if (SessionManager::isLoggedIn()) {
//     SessionManager::redirect('/SWEphaseTwo/app/view/Pages/Homepage.php');
// }

$model = new LoginSignupModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formType = $_POST['formType'] ?? '';

    if ($formType === 'login') {
        $model->handleLogin($_POST);
    } elseif ($formType === 'signup') {
        $model->handleSignup($_POST);
    }
}


if (isset($_POST['forgot_email'])) {
    $_SESSION['forgot_email'] = $_POST['forgot_email'];

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'm20930327@gmail.com';
    $mail->Password = 'mylkxlurfdfsbies';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;
    $mail->setFrom('m20930327@gmail.com');
    $mail->addAddress($_POST['forgot_email']); // Use the dynamic email here
    $mail->isHTML(true);
    $mail->Subject = "Forget Password";
    $mail->Body = " <a href='http://localhost:8080/SWEphaseTwo/app/view/Pages/forget.php?email=".$_POST['forgot_email']."'>Click here to reset your password </a>";
    $mail->send();
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
                            <a href="#" id="forgot-password">Forget Your Password?</a>
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
        <div id="forgot-password-modal" class="modal">
            <div class="modal-content">
                <span class="close-button">&times;</span>


                <form method="post">
                    <div id="email-step">
                        <p>Enter your email, and we'll send you a 4-digit code:</p>
                        <div>
                            <label for="forgot-email">Email:</label>
                            <input type="email" id="forgot-email" name="forgot_email" required>
                        </div>
                        <button type="submit" id="send-code">Send Code</button>
                    </div>
                </form>
                <!-- <form id="forgot-password-form">
            <div id="email-step">
                <p>Enter your email, and we'll send you a 4-digit code:</p>
                <div>
                    <label for="forgot-email">Email:</label>
                    <input type="email" id="forgot-email" name="email" required>
                </div>
                <button type="button" id="send-code">Send Code</button>
            </div>

            <div id="code-step" style="display: none;">
                <p>Enter the 4-digit code sent to your email:</p>
                <input type="text" id="verification-code" name="code" maxlength="4" required>
                <button type="button" id="verify-code">Verify Code</button>
            </div>

            <div id="password-step" style="display: none;">
                <p>Enter your new password:</p>
                <div>
                    <label for="new-password">New Password:</label>
                    <input type="password" id="new-password" name="password" required>
                </div>
                <button type="button" id="reset-password">Confirm</button>
            </div>
        </form> -->
            </div>
        </div>


    </section>
    <?php include "../Components/Footer.php" ?>
    <script src="../../../public/js/Login_Signup.js"></script>
</body>

</html>