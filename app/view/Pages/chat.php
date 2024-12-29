<?php  
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

require_once("../../db/Dbh.php");
$dbh = new Dbh();
$conn = $dbh->getConn();

// Retrieve tickets from the database
$sql = "SELECT * FROM chat";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

// Handle status update if requested
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ticket_id'], $_POST['status'])) {
    $ticketId = $_POST['ticket_id'];
    $status = $_POST['status'];
    $adminReply = $_POST['admin_reply'] ?? '';
    $recipientEmail = $_POST['email']; // Get the email dynamically

    // Update ticket status
    $updateSql = "UPDATE chat SET status = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'm20930327@gmail.com';
    $mail->Password = 'mylkxlurfdfsbies';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;
    $mail->setFrom('m20930327@gmail.com');
    $mail->addAddress($recipientEmail); // Use the dynamic email here
    $mail->isHTML(true);
    $mail->Subject = "Admin Reply";
    $mail->Body = $adminReply;
    $mail->send();

    if ($updateStmt) {
        $updateStmt->bind_param("si", $status, $ticketId);
        $updateStmt->execute();
        $updateStmt->close();

        echo "<script>alert('Reply Sent !! Status Updated Successfully!'); window.location.href = window.location.href;</script>";
    } else {
        echo "<script>alert('Failed to update status. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Support Tickets</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../../public/css/adminchat.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../../public/css/core.css">
    <link rel="stylesheet" href="../../../public/css/style.css">
    <link rel="stylesheet" href="../../../public/css/sidebar.css">
</head>

<body>
<div class="sidebars">
  <div class="logo">
    <h2>Admin.</h2>
  </div>
  <ul>
    <li><a href="dashboard.php" class="active" id="back-to-dashboard">Back to Dashboard</a></li>
    <li><a href="#">Log Out</a></li>
  </ul>
</div>
    <div class="main-container">
        <div class="bg-white p-4">
            <h1>Customer Support Tickets</h1>
            <div class="filter-section pt-4">
                <input id="searchBar" type="text" placeholder="Search tickets..." class="form-control">
                <select id="statusFilter" class="form-select">
                    <option value="all">All</option>
                    <option value="open">Open</option>
                    <option value="pending">Pending</option>
                    <option value="resolved">Resolved</option>
                </select>
                <select id="issueTypeFilter" class="form-select">
                    <option value="all">All Issues</option>
                    <option value="Issue with payment">Issue with Payment</option>
                    <option value="Issue with account">Issue with Account</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div id="ticketsContainer">
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <div class="ticket" 
                        data-ticket-id="<?php echo $row['id']; ?>"
                        data-status="<?php echo $row['status']; ?>"
                        data-issue="<?php echo htmlspecialchars($row['subject']); ?>"
                        data-message="<?php echo htmlspecialchars($row['message']); ?>"
                        data-name="<?php echo htmlspecialchars($row['fname'] . ' ' . $row['lname']); ?>"
                        data-email="<?php echo htmlspecialchars($row['email']); ?>"
                        onclick="openDialog(this)">
                        <h5><?php echo htmlspecialchars($row['subject']); ?></h5>
                        <p class="name">Name: <?php echo htmlspecialchars($row['fname'] . ' ' . $row['lname']); ?></p>
                        <p>Status: <span class="status <?php echo $row['status']; ?>"><?php echo $row['status']; ?></span></p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="dialogBox" tabindex="-1" aria-labelledby="dialogTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dialogTitle">Ticket Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="chat-section">
                        <p id="chatMessage" class="p-2 border rounded" style="background-color: #f8f9fa;"></p>
                    </div>
                    <form method="POST">
                        <input type="hidden" name="ticket_id" id="ticketId">
                        <input type="hidden" name="email" id="emailInput"> <!-- Add this hidden field -->
                        <div class="mb-3">
                            <label for="updateStatus" class="form-label">Update Status:</label>
                            <select class="form-select" id="updateStatus" name="status">
                                <option value="open">Open</option>
                                <option value="pending">Pending</option>
                                <option value="resolved">Resolved</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="adminReply" class="form-label">Admin Reply:</label>
                            <textarea class="form-control" id="adminReply" name="admin_reply" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success mt-2">Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openDialog(ticket) {
            const title = ticket.querySelector('h5').textContent;
            const message = ticket.dataset.message;
            const name = ticket.dataset.name;
            const email = ticket.dataset.email; // Get the email
            const ticketId = ticket.dataset.ticketId;

            document.getElementById('dialogTitle').textContent = `Subject: ${title}`;
            document.getElementById('chatMessage').textContent = `Customer (${name}): ${message}`;
            document.getElementById('ticketId').value = ticketId;

            // Set the email input hidden field or directly to the form if needed
            const emailInput = document.getElementById('emailInput');
            if (emailInput) {
                emailInput.value = email;
            }

            const dialogBox = new bootstrap.Modal(document.getElementById('dialogBox'));
            dialogBox.show();
        }

        // Ticket filtering logic
        const searchBar = document.getElementById('searchBar');
        const statusFilter = document.getElementById('statusFilter');
        const issueTypeFilter = document.getElementById('issueTypeFilter');
        const tickets = document.querySelectorAll('.ticket');

        searchBar.addEventListener('input', filterTickets);
        statusFilter.addEventListener('change', filterTickets);
        issueTypeFilter.addEventListener('change', filterTickets);

        function filterTickets() {
            const searchValue = searchBar.value.toLowerCase();
            const statusValue = statusFilter.value;
            const issueValue = issueTypeFilter.value;

            tickets.forEach(ticket => {
                const ticketName = ticket.querySelector('.name').textContent.toLowerCase();
                const ticketStatus = ticket.dataset.status;
                const ticketIssue = ticket.dataset.issue;

                const matchesSearch = ticketName.includes(searchValue);
                const matchesStatus = statusValue === 'all' || ticketStatus === statusValue;
                const matchesIssue = issueValue === 'all' || ticketIssue === issueValue;

                ticket.style.display = matchesSearch && matchesStatus && matchesIssue ? 'block' : 'none';
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
