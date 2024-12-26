<?php
require_once("../../db/Dbh.php");
$dbh = new Dbh();
$conn = $dbh->getConn();

$sql = "SELECT * from chat";
$stmt = $conn->prepare($sql);

$stmt->execute();

$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Support Tickets</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../../public/css/adminchat.css" rel="stylesheet">
    <link href="../../../public/css/sidebar.css" rel="stylesheet">
    <style>
        /* General Styles */
      

        .main-content {
            margin-left: 270px;
            padding: 20px;
        }

        .filter-section {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .ticket {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
        }

        .ticket .status {
            font-weight: bold;
            padding: 2px 8px;
            border-radius: 12px;
            color: white;
        }

        .ticket .status.pending {
            background-color: orange;
        }

        .ticket .status.open {
            background-color: blue;
        }

        .ticket .status.resolved {
            background-color: green;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <ul>
            <li><a href="#">Dashboard</a></li>
            <li><a href="chat.php" class="active">Chat</a></li>
            <li><a href="#">Calendar</a></li>
            <li><a href="#">Events</a></li>
            <li><a href="dashboard.php">User Management</a></li>
        </ul>
    </div>
    <div class="main-content">

        <h1>Customer Support Tickets</h1>
        <div class="filter-section">
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
                data-status="<?php echo $row['status']; ?>" 
                data-issue="<?php echo htmlspecialchars($row['subject']); ?>" 
                data-message="<?php echo htmlspecialchars($row['message']); ?>" 
                data-name="<?php echo htmlspecialchars($row['fname'] . ' ' . $row['lname']); ?>" 
                onclick="openDialog(this)">
                <h5><?php echo htmlspecialchars($row['subject']); ?></h5>
                <p class="name">Name: <?php echo htmlspecialchars($row['fname'] . ' ' . $row['lname']); ?></p>
                <p>Status: <span class="status <?php echo $row['status']; ?>"><?php echo $row['status']; ?></span></p>
            </div>
            <?php } ?>
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
                    <form>
                        <div class="mb-3">
                            <textarea class="form-control" id="adminReply" rows="3" placeholder="Type your message here..."></textarea>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="sendReply()">Send</button>
                    </form>
                    <div class="mt-3">
                        <label for="updateStatus" class="form-label">Update Status:</label>
                        <select class="form-select" id="updateStatus">
                            <option value="open">Open</option>
                            <option value="pending">Pending</option>
                            <option value="resolved">Resolved</option>
                        </select>
                        <button type="button" class="btn btn-success mt-2" onclick="confirmStatusUpdate()">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to open the dialog box with dynamic content
        function openDialog(ticket) {
            const title = ticket.querySelector('h5').textContent;
            const message = ticket.dataset.message;
            const name = ticket.dataset.name;

            document.getElementById('dialogTitle').textContent = `Subject: ${title}`;
            document.getElementById('chatMessage').textContent = `Customer (${name}): ${message}`;

            const dialogBox = new bootstrap.Modal(document.getElementById('dialogBox'));
            dialogBox.show();
        }

        // Send reply function (to be implemented with backend)
        function sendReply() {
            const reply = document.getElementById('adminReply').value;
            if (reply.trim() === '') {
                alert('Reply cannot be empty!');
                return;
            }
            console.log('Reply sent:', reply);
            document.getElementById('adminReply').value = '';
        }

        // Update status function (to be implemented with backend)
        function confirmStatusUpdate() {
            const status = document.getElementById('updateStatus').value;
            console.log('Status updated to:', status);
        }

        // Filters for tickets
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
</body>
</html>
