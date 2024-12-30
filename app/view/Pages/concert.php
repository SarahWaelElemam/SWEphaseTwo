<?php 
include "../Components/NavBar.php";
require_once __DIR__ . '../../../Controller/SessionManager.php';
require_once(__DIR__ . '/../../db/Dbh.php');
require_once __DIR__ . '../../../model/user.php';

use App\Controller\SessionManager; // Add this line to use the correct namespace

/// Check if the user is logged in
SessionManager::requireLogin();
// Get logged-in user ID
$userId = SessionManager::getUserId();
if (!$userId) {
    die('User ID is not available.'); // Check if the user ID is correct
}
// Fetch user details from the database
$dbh = new Dbh();
$conn = $dbh->getConn();
// Fetch user details from the database
$stmt = $conn->prepare("SELECT * FROM user WHERE User_ID = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$userDetails = $result->fetch_assoc();
$dateTime = date('M d | h:ia', strtotime($_POST['date'] . ' ' . $_POST['time']));
require_once("../../../app/db/Dbh.php");
require_once("../../../app/model/Ticket.php");

// Create an instance of the DBh class and establish the database connection
$dbh = new Dbh();
$conn = $dbh->getConn();

// Initialize the Tickets class with the database connection
$tickets = new Ticket($conn);

// Handle POST request for ticket purchase
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate required POST fields
    if (!isset($_POST['ticket_ids']) || empty($_POST['ticket_ids'])) {
        echo "Error: Ticket IDs are required.";
        exit;
    }

    // Decode the ticket IDs from the POST request (assume it's a comma-separated string)
    $ticketIds = explode(',', $_POST['ticket_ids']);

    // Simulate payment validation (replace with actual payment logic)
    $paymentSuccessful = true; // This should be set based on your payment gateway response

    if ($paymentSuccessful) {
        $newStatus = "Sold";
        $successCount = 0;
        $totalTickets = count($ticketIds);

        // Begin transaction
        $conn->begin_transaction();

        try {
            // Update ticket status for each ticket ID
            foreach ($ticketIds as $ticketId) {
                $ticketId = trim($ticketId);
                if ($tickets->updateTicketStatus($ticketId, $newStatus)) {
                    $successCount++;
                }
            }

            // If all tickets were updated successfully, commit the transaction
            if ($successCount === $totalTickets) {
                $conn->commit();
                // You might want to store this in a session variable to show success message
                $_SESSION['purchase_success'] = true;
            } else {
                // If any update failed, rollback all changes
                $conn->rollback();
                throw new Exception("Failed to update all ticket statuses");
            }
        } catch (Exception $e) {
            // Rollback the transaction on any error
            $conn->rollback();
            echo "<p>Error: " . $e->getMessage() . ". Please contact support.</p>";
            exit;
        }
    } else {
        echo "<p>Payment failed. Please try again.</p>";
        exit;
    }
}
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Create a new PHPMailer instance
$mail = new PHPMailer(true);

try {
    // SMTP Configuration
    $mail->isSMTP();                                            // Use SMTP
    $mail->Host = 'smtp.gmail.com';                     // SMTP server
    $mail->SMTPAuth = true;                                   // Enable SMTP authentication
    $mail->Username = 'm20930327@gmail.com';               // SMTP username
    $mail->Password = 'mylkxlurfdfsbies';                  // SMTP password
    $mail->SMTPSecure = 'ssl';            // Enable SSL
    $mail->Port = 465;                                    // Port (use 587 for TLS)

    // Sender and Recipient
    $mail->setFrom('m20930327@gmail.com', 'Your Ticket');

    /// here replace yasser gmail  with session 
    $mail->addAddress($userDetails['Email'], $userDetails['FName']);

    // Email Subject and Body
    $mail->isHTML(true);
    $mail->Subject = 'Here is your QR code';
    // $mail->Body    = '<p>Please find your Ticket info below:<br>'.htmlspecialchars($_POST["name"]).'<br>'.htmlspecialchars($_POST["Category"]).'<br>'.htmlspecialchars($_POST["ticket_type"]).'<br>'.htmlspecialchars($_POST["ticket_price"]).'<br></p><img src="cid:qrcode">';
    $mail->Body = '<p><br>
    <h3>
    Thank you for your purchase! 🎉<br>
    Below are the details of your ticket:
<br>
    Name: ' . htmlspecialchars($_POST["name"]) . '<br>
        Ticket-type: ' . htmlspecialchars($_POST["ticket_type"]) . '<br>
        Category: ' . htmlspecialchars($_POST["Category"]) . '<br>
        Price: $' . htmlspecialchars($_POST["ticket_price"]) . '<br>
</h3>
Please find the attached QR code for quick access at the venue. If you have any questions or need further assistance, feel free to reach out.
<br><img src="cid:qrcode"><br>
Enjoy your experience!<br>

Best regards,<br>
The TixCarte Team<br>

    </p> ';


    $imageData = file_get_contents('../../../public/images/qr.jpg');
    $mail->addStringEmbeddedImage($imageData, 'qrcode', 'qrcode.svg', PHPMailer::ENCODING_BASE64, 'image/svg+xml');

    // Send the email
    $mail->send();
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../public/css/pay.css" />
    <title>Document</title>
</head>
<body>

<div class="checkout">
<div class="Payment" >
    <div class="payment-header">
        <h2>Payment Details</h2>
    </div>
    
    <div class="payment-summary">
    <h3>Order Summary</h3>
    <p><?php echo htmlspecialchars($_POST["Category"]); ?>: <span><?php echo htmlspecialchars($_POST["name"]); ?></span></p>
    <p>Date: <span><?php echo htmlspecialchars($dateTime); ?></span></p>
    <p>Selected Tickets: <span id="paymentSeatsDisplay"><?php echo htmlspecialchars($_POST["ticket_count"]); ?> <?php echo htmlspecialchars($_POST["ticket_type"]); ?> </span></p>
    <p>Total Amount: <span id="paymentTotalDisplay"><?php echo htmlspecialchars($_POST["ticket_count"] * $_POST["ticket_price"]); ?> EGP</span></p>

</div>

    <form class="payment-form" id="paymentForm">
        <div class="payment-method">
            <label>
                <input type="radio" name="paymentMethod" value="card" checked>
                <i class="fa-brands fa-cc-mastercard"></i>Credit/Debit Card
            </label>
            <label>
                <input type="radio" name="paymentMethod" value="wallet">
                <img src="../../../public/images/valU.png">
            </label>
        </div>
    <div class="creditcard">
        <div class="form-group">
            <label>Cardholder Name</label>
            <input type="text" required placeholder="Name on card" style="text-transform: uppercase;">
            
        </div>

        <div class="card-details">
            <div class="form-group">
                <label>Card Number</label>
                <input id="cardNumber" type="text" required placeholder="1234 5678 9012 3456" maxlength="12">
                <span id="cardNumberError" style="color: red; display: none;">Card number must be exactly 12 digits.</span>
            </div>
            <div class="form-group">
                <label>Expiry</label>
                <input type="text" required placeholder="MM/YY">
            </div>
            <div class="form-group">
                <label>CVV</label>
                <input type="text" required placeholder="123">
            </div>
        </div>

        <div class="payment-buttons">
            <button type="button" class="btn-cancel" id="cancelPayment">Reset</button>
            <button type="submit" class="btn-pay" onclick="validateCardNumber(event)">Pay Now</button>
        </div>
    </div>
    <div class="value">
    <div class="form-group">
            <label>Cardholder Name</label>
            <input type="text" required placeholder="Your Full Name">
    </div>
    <div class="form-group">
            <label>Mobile Number</label>
            <input type="text" required placeholder="Mobile Number">
    </div>
    <div class="TotalPay">
        <p></p>
    </div>
    <div class="payment-buttons">
            <button type="button" class="btn-cancel" id="cancelPayment">Cancel</button>
            <button type="submit" class="btn-pay" onclick="validateCardNumber(event)">Pay Now</button>

        </div>
    </div>

    </form>
</div>

<div class="printing" style="display:none">
   <div class="top">
   <p class="title">Wait a second, your ticket is sending to your email</p>
   <div class="printer" >
   </div>
   <div class="receipts-wrapper">
      <div class="receipts">
         <div class="receipt">
            <img src="../../../public/images/logo.png">
            <div class="route">
               <h4> Thank you for using TickCarte</h4>
            </div>
            <div class="details">
            <div class="item">
                  <span>Ticket id</span>
                  <p><?php echo htmlspecialchars($_POST["ticket_ids"]); ?></p>
               </div>
               <div class="item">
                  <span>Event</span>
                  <p><?php echo htmlspecialchars($_POST["name"]); ?></p>
               </div>
               <div class="item">
                  <span>Seats</span>
                  <p><?php echo htmlspecialchars($_POST["ticket_count"]); ?> <?php echo htmlspecialchars($_POST["ticket_type"]); ?></p>
               </div>
               <div class="item">
                  <span>Day | Time</span>
                  <p><?php echo htmlspecialchars($dateTime); ?></p>
               </div>
            </div>
         </div>
         <div class="receipt qr-code">
            <svg class="qr" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 29.938 29.938">
               <path d="M7.129 15.683h1.427v1.427h1.426v1.426H2.853V17.11h1.426v-2.853h2.853v1.426h-.003zm18.535 12.83h1.424v-1.426h-1.424v1.426zM8.555 15.683h1.426v-1.426H8.555v1.426zm19.957 12.83h1.427v-1.426h-1.427v1.426zm-17.104 1.425h2.85v-1.426h-2.85v1.426zm12.829 0v-1.426H22.81v1.426h1.427zm-5.702 0h1.426v-2.852h-1.426v2.852zM7.129 11.406v1.426h4.277v-1.426H7.129zm-1.424 1.425v-1.426H2.852v2.852h1.426v-1.426h1.427zm4.276-2.852H.002V.001h9.979v9.978zM8.555 1.427H1.426v7.127h7.129V1.427zm-5.703 25.66h4.276V22.81H2.852v4.277zm14.256-1.427v1.427h1.428V25.66h-1.428zM7.129 2.853H2.853v4.275h4.276V2.853zM29.938.001V9.98h-9.979V.001h9.979zm-1.426 1.426h-7.127v7.127h7.127V1.427zM0 19.957h9.98v9.979H0v-9.979zm1.427 8.556h7.129v-7.129H1.427v7.129zm0-17.107H0v7.129h1.427v-7.129zm18.532 7.127v1.424h1.426v-1.424h-1.426zm-4.277 5.703V22.81h-1.425v1.427h-2.85v2.853h2.85v1.426h1.425v-2.853h1.427v-1.426h-1.427v-.001zM11.408 5.704h2.85V4.276h-2.85v1.428zm11.403 11.405h2.854v1.426h1.425v-4.276h-1.425v-2.853h-1.428v4.277h-4.274v1.427h1.426v1.426h1.426V17.11h-.004zm1.426 4.275H22.81v-1.427h-1.426v2.853h-4.276v1.427h2.854v2.853h1.426v1.426h1.426v-2.853h5.701v-1.426h-4.276v-2.853h-.002zm0 0h1.428v-2.851h-1.428v2.851zm-11.405 0v-1.427h1.424v-1.424h1.425v-1.426h1.427v-2.853h4.276v-2.853h-1.426v1.426h-1.426V7.125h-1.426V4.272h1.426V0h-1.426v2.852H15.68V0h-4.276v2.852h1.426V1.426h1.424v2.85h1.426v4.277h1.426v1.426H15.68v2.852h-1.426V9.979H12.83V8.554h-1.426v2.852h1.426v1.426h-1.426v4.278h1.426v-2.853h1.424v2.853H12.83v1.426h-1.426v4.274h2.85v-1.426h-1.422zm15.68 1.426v-1.426h-2.85v1.426h2.85zM27.086 2.853h-4.275v4.275h4.275V2.853zM15.682 21.384h2.854v-1.427h-1.428v-1.424h-1.427v2.851zm2.853-2.851v-1.426h-1.428v1.426h1.428zm8.551-5.702h2.853v-1.426h-2.853v1.426zm1.426 11.405h1.427V22.81h-1.427v1.426zm0-8.553h1.427v-1.426h-1.427v1.426zm-12.83-7.129h-1.425V9.98h1.425V8.554z"/>
            </svg>
            <div class="description">
               <p>Your QR code is generated</p>
               <p>check Your Email</p>
            </div>
         </div>
      </div>
   </div>
</div>
            </div>
<div class="info">
    <img src="../../../public/images/<?php echo htmlspecialchars($_POST["image"]); ?>" alt="Memo">
    <div class="details">
        <p class="name"><?php echo htmlspecialchars($_POST["name"]); ?></p>
        <p class="date"><?php echo htmlspecialchars($dateTime); ?></p>
    </div>
    <div class="containerr">
    <div class="steps">
        <div class="circle-wrapper">
            <div class="circle active">1</div><span>Select Ticket</span>
        </div>
        <div class="progress-bar active">
            <div class=try>
            <span class="indicator"></span>
            <span id="selectedSeatsDisplay" class="selected-seats-display"></span>
            </div>
        </div>
        <div class="circle-wrapper">
            <div class="circle">2</div><span>Review and Checkout</span>
        </div>
        <div class="progress-bar"><span class="indicator"></span></div>
        <div class="circle-wrapper">
            <div class="circle">3</div><span>Send Ticket</span>
        </div>
    </div>
    
    <div class="buttons">
        <button id="prev" disabled>Previous</button>
        <button id="next">Next</button>
    </div>
    
    <div id="totalCostDisplay" class="total-cost-display">
    Total <?php echo htmlspecialchars($_POST["ticket_count"])*($_POST['ticket_price']); ?> EGP
    </div>
    
</div>
</div>
</div>
<?php include "../Components/Footer.php"?>
<script src="../../../public/js/concert.js"></script>

</body>
</html>