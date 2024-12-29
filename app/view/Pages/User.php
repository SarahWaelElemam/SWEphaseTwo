<?php
require_once __DIR__ . '/../../Controller/UserController.php';
function debugMessage($message) {
    echo "<div style='position: fixed; top: 0; left: 0; width: 100%; z-index: 9999; background-color: black; color: white; padding: 10px; font-family: monospace; text-align: center;'>$message</div>";
}

debugMessage("profile.php started execution.");

$controller = new UserController();

$action = $_GET['action'] ?? 'getUserProfile';
debugMessage("Action requested: $action");

switch ($action) {
    case 'updateProfile':
        $controller->updateProfile();
        break;
    case 'deleteProfile':
        $controller->deleteProfile();
        break;
    default:
        $controller->getUserProfile();
        break;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../../../public/css/User.css" />
    <script src="../../../public/js/User.js" defer></script>
    <title>Movie Seat Booking</title>
</head>
<body>
<?php include "C:/xampp/htdocs/SWEPhase2/SWEPhaseTwo/app/view/Components/NavBar.php"; ?>
    <section>
        <div class="profile py-4">
            <div class="container">
                <div class="row">
                    <div class="card shadow-sm">
                        <div class="card-header bg-transparent text-center">
                            <div class="profile-content">
                                <div id="avatar-display" class="avatar-container">
                                    <!-- Avatar image or content here -->
                                </div>
                            </div>
                            <div class="profile-details">
                                <div class="namee">
                                <h3 id="profile-name"><?php echo !empty($userData['FName']) ? $userData['FName'] : 'User Name Not Available'; ?></h3>
<p id="profile-membersince">Member since <?php echo !empty($userData['join_date']) ? $userData['join_date'] : 'Join Date Not Available'; ?></p>

<!-- Display other profile details -->
<p>
    <i class="fa-regular fa-calendar" style="color: #ffab03;"></i>
    <span id="profile-join-date"><?php echo !empty($userData['join_date']) ? $userData['join_date'] : 'Join Date Not Available'; ?></span>
</p>
<p>
    <i class="fa-solid fa-phone" style="color: #ffab03;"></i>
    <span id="profile-phone"><?php echo !empty($userData['Phone']) ? $userData['Phone'] : 'Phone Number Not Available'; ?></span>
</p>
<p>
    <i class="fa-regular fa-user" style="color: #ffab03;"></i>
    <span id="profile-gender"><?php echo !empty($userData['Gender']) ? $userData['Gender'] : 'Gender Not Available'; ?></span>
</p>
<p>
    <i class="fa-solid fa-location-dot" style="color: #ffab03;"></i>
    <span id="profile-location"><?php echo !empty($userData['Government']) ? $userData['Government'] : 'Government Not Available'; ?></span>
</p>
<p>
    <i class="fa-regular fa-envelope" style="color: #ffab03;"></i>
    <span id="profile-email"><?php echo !empty($userData['Email']) ? $userData['Email'] : 'Email Not Available'; ?></span>
</p>


                                </div>
                            </div>
                            <div style="height: 1em"></div>
                        </div>
                        
                        <div class="buttons">
                            <button type="button" class="btn" id="edit-btn">Edit Profile</button>
                            <button type="button" class="btn" id="delete-btn">Delete Account</button>
                        </div>
                    </div>

                    <!-- Purchase History -->
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
                                    <th>Actions</th> 
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Example static data, replace with dynamic fetching -->
                                <tr>
                                    <td>123456</td>
                                    <td>Concert A</td>
                                    <td>Cairo Stadium</td>
                                    <td>$50</td>
                                    <td>15 Oct 2024</td>
                                    <td>
                                        <button type="button" class="btn reorder-btn" onclick="location.href='events.php'">Reorder</button>
                                        <button type="button" class="btn contact-btn" onclick="location.href='support.php'">Contact for Help</button>
                                        <button type="button" class="btn refund-btn" onclick="location.href='support.php'">Refund</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>789012</td>
                                    <td>Festival B</td>
                                    <td>Giza Arena</td>
                                    <td>$75</td>
                                    <td>20 Oct 2024</td>
                                    <td>
                                        <button type="button" class="btn reorder-btn" onclick="location.href='events.php'">Reorder</button>
                                        <button type="button" class="btn contact-btn" onclick="location.href='support.php'">Contact for Help</button>
                                        <button type="button" class="btn refund-btn" onclick="location.href='support.php'">Refund</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Edit Profile Form (initially hidden) -->
                <div id="edit-form" style="display: none;">
                    <h3>Edit Profile</h3>
                    <div class="table table-bordered">
                        <p>
                            <i class="fa-regular fa-user" style="color: #ffab03;"></i>
                            <input type="text" id="edit-fname" placeholder="First Name" />
                            <input type="text" id="edit-lname" placeholder="Last Name" />
                        </p>
                        <p>
                            <i class="fa-solid fa-phone" style="color: #ffab03;"></i>
                            <input type="text" id="edit-phone" placeholder="Phone" />
                        </p>
                        <p>
                            <i class="fa-regular fa-user" style="color: #ffab03;"></i>
                            <select id="edit-gender">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </p>
                        <p>
                            <i class="fa-solid fa-location-dot" style="color: #ffab03;"></i>
                            <input type="text" id="edit-government" placeholder="Government" />
                        </p>
                        <p>
                            <i class="fa-regular fa-envelope" style="color: #ffab03;"></i>
                            <input type="email" id="edit-email" placeholder="Email" />
                        </p>
                    </div>
                    <button type="button" id="confirm-btn">Confirm</button>
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
            </div>
        </div>
    </section>
</body>
</html>
