<?php
session_start();
?>

<?php include "../Components/NavBar.php";
require_once("../../db/Dbh.php");
?>
<?php
$email= $_GET['email'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Dbh();
    $password = $_POST['password'];
    // $email = $_SESSION['forgot_email'];
    $conn = $db->getConn();
    $stmt = $conn->prepare("UPDATE user SET Password= ? where Email=?");
    $stmt->bind_param("ss", $password, $email);
    $stmt->execute();
   
    header("location:Login_Signup.php");
}
?>
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
        <h1 class="section-title">Welcome To TixCarte</h1>
        <div class="forms" style="margin:center;">
            <div class="form-wrapper is-active">
                <form class="form form-login" method="POST">
                    <fieldset>
                        <legend>Please, enter your password and confirm password to proceed.</legend>
                        <div class="input-block">
                            <label for="password">Password</label>
                            <input id="password" type="password" name="password" required>
                        </div>
                        <div class="input-block">
                            <label for="confirm-password">Confirm Password</label>
                            <input id="confirm-password" type="password" name="confirmpassword" required>
                        </div>
                    </fieldset>
                    <input type="hidden" name="formType" value="login">
                    <button type="submit" id="submit-button" class="btn-login">Confirm</button>
                    <p id="error-message" style="color: red; display: none;">Passwords do not match!</p>
                </form>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const password = document.getElementById("password");
            const confirmPassword = document.getElementById("confirm-password");
            const errorMessage = document.getElementById("error-message");
            const submitButton = document.getElementById("submit-button");

            // Event listener for form submission
            submitButton.addEventListener("click", function (e) {
                // Prevent form submission if passwords do not match
                if (password.value !== confirmPassword.value) {
                    e.preventDefault();
                    errorMessage.style.display = "block"; // Show error message
                    errorMessage.textContent = "Passwords do not match!";
                } else {
                    errorMessage.style.display = "none"; // Hide error message if passwords match
                    alert("Passwords match! Form submitted successfully.");
                }
            });
        });
    </script>
</body>
</html>