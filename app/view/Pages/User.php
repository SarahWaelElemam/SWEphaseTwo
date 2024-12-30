<?php
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

if (!$userDetails) {
    die('User not found.');
}

// Fetch purchase history
$purchaseQuery = "
    SELECT p.Purchase_ID, p.Purchase_Date, t.Price, t.type, e.Name as Event_Name, e.Location, e.detailed_loc
    FROM purchase p
    JOIN tickets t ON p.Ticket_ID = t.Ticket_ID
    JOIN events e ON t.Event_ID = e.Event_ID
    WHERE p.User_ID = ?
    ORDER BY p.Purchase_Date DESC
";
$purchaseStmt = $conn->prepare($purchaseQuery);
$purchaseStmt->bind_param("i", $userId);
$purchaseStmt->execute();
$purchaseHistory = $purchaseStmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../../../public/css/User.css" />
    <script src="../../../public/js/User.js" defer></script> <!-- Link to the user.js file -->
    <title>Movie Seat Booking</title>
</head>
<body>
<?php include "../Components/NavBar.php" ?>
    <section>
    <div class="profile py-4">
            <div class="container">
                <div class="card shadow-sm">
                    <div class="card-header bg-transparent text-center">
                        <div class="profile-content">
                            <div id="avatar-display" class="avatar-container">
                                <img src="<?php echo htmlspecialchars($userDetails['image']); ?>" alt="Avatar">
                            </div>
                        </div>
                        <div class="profile-details">
                            <div class="namee">
                                <h3 id="profile-name">
                                    <?php echo htmlspecialchars($userDetails['FName'] . ' ' . $userDetails['LName']); ?>
                                </h3>
                                <p id="profile-membersince">
                                    Member since <?php echo htmlspecialchars(date('Y', strtotime($userDetails['BirthDate']))); ?>
                                </p>
                            </div>
                            <div class="table table-bordered">
                                <p>
                                    <i class="fa-regular fa-calendar" style="color: #ffab03;"></i>
                                    <span id="profile-join-date">
                                        <?php echo htmlspecialchars(date('d M Y', strtotime($userDetails['BirthDate']))); ?>
                                    </span>
                                </p>
                                <p>
                                    <i class="fa-solid fa-phone" style="color: #ffab03;"></i>
                                    <span id="profile-phone"><?php echo htmlspecialchars($userDetails['Phone']); ?></span>
                                </p>
                                <p>
                                    <i class="fa-regular fa-user" style="color: #ffab03;"></i>
                                    <span id="profile-gender"><?php echo htmlspecialchars($userDetails['Gender']); ?></span>
                                </p>
                                <p>
                                    <i class="fa-solid fa-location-dot" style="color: #ffab03;"></i>
                                    <span id="profile-location"><?php echo htmlspecialchars($userDetails['Government']); ?></span>
                                </p>
                                <p>
                                    <i class="fa-regular fa-envelope" style="color: #ffab03;"></i>
                                    <span id="profile-email"><?php echo htmlspecialchars($userDetails['Email']); ?></span>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="buttons">
                        <button type="button" class="btn" id="edit-btn">Edit Profile</button>
                        <button type="button" class="btn" id="delete-btn">Delete Account</button>
                    </div>
                </div>

                <div class="purchase-history">
                    <h3>Purchase History</h3>
                    <table class="purchase-history-table">
                        <thead>
                            <tr>
                                <th>Ticket Number</th>
                                <th>Event Name</th>
                                <th>Location</th>
                                <th>Price</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($purchaseHistory as $purchase): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($purchase['Purchase_ID']); ?></td>
                                <td><?php echo htmlspecialchars($purchase['Event_Name']); ?></td>
                                <td><?php echo htmlspecialchars($purchase['Location'] . ' - ' . $purchase['detailed_loc']); ?></td>
                                <td><?php echo htmlspecialchars($purchase['Price']); ?> EGP</td>
                                <td><?php echo htmlspecialchars(date('d M Y', strtotime($purchase['Purchase_Date']))); ?></td>
                                <td><?php echo htmlspecialchars($purchase['type']); ?></td>
                                <td>
                                    <button type="button" class="btn reorder-btn" onclick="location.href='events.php'">Reorder</button>
                                    <button type="button" class="btn contact-btn" onclick="location.href='support.php'">Contact for Help</button>
                                    <button type="button" class="btn refund-btn" onclick="location.href='support.php'">Refund</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Edit Profile Modal -->
        <div id="edit-modal" class="modal">
            <div class="modal-content">
                <span class="close-btn" id="close-modal">&times;</span>
                <h3>Edit Profile</h3>
                <form id="edit-profile-form">
                    <div class="table table-bordered">
                        <p>
                            <i class="fa-regular fa-user" style="color: #ffab03;"></i>
                            <input type="text" id="edit-fname" placeholder="First Name" value="<?php echo htmlspecialchars($userDetails['FName']); ?>"/>
                            <input type="text" id="edit-lname" placeholder="Last Name" value="<?php echo htmlspecialchars($userDetails['LName']); ?>"/>
                        </p>
                        <p>
                            <i class="fa-solid fa-phone" style="color: #ffab03;"></i>
                            <input type="text" id="edit-phone" placeholder="Phone" value="<?php echo htmlspecialchars($userDetails['Phone']); ?>"/>
                        </p>
                        <p>
                            <i class="fa-solid fa-location-dot" style="color: #ffab03;"></i>
                            <input type="text" id="edit-location" placeholder="Location" value="<?php echo htmlspecialchars($userDetails['Government']); ?>"/>
                        </p>
                        <p>
                            <i class="fa-regular fa-envelope" style="color: #ffab03;"></i>
                            <input type="email" id="edit-email" placeholder="Email" value="<?php echo htmlspecialchars($userDetails['Email']); ?>"/>
                        </p>
                    </div>
                    <button type="submit" id="confirm-btn">Confirm</button>
                </form>
            </div>
        </div>

        <!-- Confirmation Dialog for Deleting Account -->
        <div id="overlay"></div>
        <div id="confirmation-dialog">
            <p>Do you want to permanently delete your account?</p>
            <div class="dialog-buttons">
                <button id="confirm-delete">Yes</button>
                <button id="cancel-delete">No</button>
            </div>
        </div>
    </section>
    <?php include "../Components/Footer.php"?>
</body>
</html>