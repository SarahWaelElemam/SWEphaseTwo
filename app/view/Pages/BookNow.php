<?php
require_once("../../../app/controller/UserController.php");
include "../Components/NavBar.php";
require_once("../../db/Dbh.php");
require_once("../../model/Tickets.php");

$dbh = new Dbh();
$conn = $dbh->getConn();
$ticketsModel = new Tickets($conn);
// Check if we have POST data
if (!isset($_POST['event_id']) || empty($_POST['event_id'])) {
    echo "<p>Error: Event ID is missing.</p>";
    exit;
}

$eventId = intval($_POST['event_id']);

// Fetch tickets for the event
$tickets = $ticketsModel->getTicketsByEvent($eventId);

if (empty($tickets)) {
    echo "<p>No tickets available for this event.</p>";
} 
// Format date and time for display
$dateTime = date('M d | h:ia', strtotime($_POST['date'] . ' ' . $_POST['time']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../public/css/BookNow.css">
    <script src="https://kit.fontawesome.com/19a37f6564.js" crossorigin="anonymous"></script>
    <title>Event Page - <?php echo htmlspecialchars($_POST['name']); ?></title>
</head>
<body>
    <div class="event-container">
        <div class="event-image">
            <img src="../../../public/images/<?php echo htmlspecialchars($_POST['image']); ?>" alt="Event Image">
        </div>
        <div class="event-details">
            <div class="left-details">
                <h1><?php echo htmlspecialchars($_POST['name']); ?></h1>
                <p class="Time"><?php echo htmlspecialchars($dateTime); ?></p>
                <p><?php echo htmlspecialchars($_POST['location']); ?></p>
                <button class="book-btn" onclick="window.location.href='pay.php?event=<?php echo urlencode($_POST['name']); ?>'">Book Now</button>
            </div>
            <div class="right-details">
                <p>Organized by</p>
                <h3><?php echo htmlspecialchars($_POST['created_by']); ?></h3>
            </div>
        </div>
    </div>

    <div class="about-event">
        <h2>About Event</h2>
        <p><?php echo htmlspecialchars($_POST['about']); ?></p>
    </div>

    <div class="ticket-section" style='border:none'>
        <h2 style="text-align: left">Tickets</h2>
        <div class="ticket-container" style='border:none'>
    <?php
    $displayedTypes = []; // Declare the array outside the loop to track displayed ticket types

    foreach ($tickets as $ticket) {
        if ($ticket['Status'] == 'Available' && !in_array($ticket['type'], $displayedTypes)) {
            // Add the ticket type to the array
            $displayedTypes[] = $ticket['type'];

            // Render the ticket
            echo "<div class='ticket' style='border:none; background-color:transparent; box-shadow:none; '>
                    <h2 style='background-color:orange; color:white; margin-bottom:0rem; padding:1rem; border-radius:2rem;'> {$ticket['type']}</h2>
                    <p style='background-color:white; box-shadow:0 4px 8px rgba(0, 0, 0, 0.12); 
                    padding:3rem; border-radius:3rem;'>EGP {$ticket['Price']}
                    <button class='ticket-btn' style='background-color: rgba(255, 190, 70, 0.53); color: white; border: none; padding: 1rem 2rem; border-radius: 1rem; cursor: pointer; 
                     margin-top: 1rem; display: flex; align-items: center; justify-content: center; text-align: center; width: 100%;'
                     onclick=\"window.location.href='pay.php?ticket_id={$ticket['Ticket_ID']}'\" onmouseover=\"this.style.backgroundColor='orange'\" 
                     onmouseout=\"this.style.backgroundColor='rgba(255, 190, 70, 0.53)'\">
                        Book Now
                    </button>
                    </p>
                  </div>";
        }
    }
    ?>
</div>

    </div>

    <div id="ticketModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h2 id="ticketType"></h2>
            <p id="ticketPrice"></p>

            <div class="ticket-selection">
                <label for="ticketCount" class="ticket-label">Select number of tickets:</label>
                <select id="ticketCount" class="ticket-select">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </div>

            <button class="confirm-btn" onclick="confirmPurchase()">Confirm Purchase</button>
        </div>
    </div>

    <div class="venue-section">
        <h2>Venue</h2>
        <div class="venue-container">
            <div class="venue-details">
                <h3><?php echo htmlspecialchars($_POST['location']); ?></h3>
                <p><?php echo htmlspecialchars($_POST['detailed_loc']); ?></p>
                <div class="venue-links">
                    <a href="<?php echo htmlspecialchars($_POST['venue_loc']); ?>">Open In Maps</a>
                </div>
                <div class="venue-facilities">
                    <h4>FACILITIES</h4>
                    <p>🛁 Bathrooms | 🍽 Food Services</p>
                    <p>🅿 Parking | 🛡 Security</p>
                </div>
            </div>
            <div class="venue-image">
                <img src="../../../public/images/Venue.png" alt="Venue Image">
            </div>
        </div>
    </div>

    <script>
    function openModal(ticketType, ticketPrice) {
        document.getElementById('ticketModal').style.display = 'block';
        document.getElementById('ticketType').textContent = `Ticket Type: ${ticketType}`;
        document.getElementById('ticketPrice').textContent = `Price For one Ticket: EGP ${ticketPrice}`;
    }

    function closeModal() {
        document.getElementById('ticketModal').style.display = 'none';
    }

    function confirmPurchase() {
        const count = document.getElementById('ticketCount').value;
        alert(`You have selected ${count} tickets`);
        closeModal();
        window.location.href = "pay.php";
    }
    </script>
</body>
</html>
<?php include "../Components/Footer.php"?>