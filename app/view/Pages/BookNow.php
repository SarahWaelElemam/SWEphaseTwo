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
                <button class="btn-book" onclick="scrollToTicketSection()">Book Now</button>
            </div>
            <div class="right-details">
                <p>Organized by</p>
                <img src="../../../public/images/<?php echo htmlspecialchars($_POST['organizer_image']); ?>">
            </div>
        </div>
    </div>

    <div class="about-event">
        <h2>About Event</h2>
        <p><?php echo htmlspecialchars($_POST['about']); ?></p>
    </div>

    <div class="ticket-section" id="ticket-section" style="border:none">
        <h2 style="text-align: left">Tickets</h2>
        <div class="ticket-container" style='border:none'>
        <?php
    $displayedTypes = []; // Declare the array outside the loop to track displayed ticket types

    foreach ($tickets as $ticket) {
        if ($ticket['Status'] == 'Available' && !in_array($ticket['type'], $displayedTypes)) {
            // Add the ticket type to the array
            $displayedTypes[] = $ticket['type'];

            // Create button onclick based on category
            $buttonOnClick = $_POST['Category'] === 'Concert' 
            ? "openModal('" . htmlspecialchars($ticket['type']) . "', '" . htmlspecialchars($ticket['Price']) . "', '" . htmlspecialchars($ticket['Ticket_ID']) . "')"
            : "confirmPurchase('" . htmlspecialchars($ticket['type']) . "', '" . htmlspecialchars($ticket['Price']) . "', '" . htmlspecialchars($ticket['Ticket_ID']) . "')";

            // Render the ticket
            echo "<div class='ticket' style='border:none; background-color:transparent; box-shadow:none; '>
                    <h2 style='background-color:orange; color:white; margin-bottom:0rem; padding:1rem; border-radius:2rem;'> {$ticket['type']}</h2>
                    <p style='background-color:white; box-shadow:0 4px 8px rgba(0, 0, 0, 0.12); 
                    padding:3rem; border-radius:3rem;'>EGP {$ticket['Price']}
                    <button class='ticket-btn' style='background-color: rgba(255, 190, 70, 0.53); color: white; border: none; padding: 1rem 2rem; border-radius: 1rem; cursor: pointer; 
                     margin-top: 1rem; display: flex; align-items: center; justify-content: center; text-align: center; width: 100%; font-size:1.2rem' 
                    onclick=\"{$buttonOnClick}\" onmouseover=\"this.style.backgroundColor='orange'\" 
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
            <div class="counter-container">
                <button type="button" id="decrementBtn" class="counter-btn" onclick="updateTicketCount(-1)">-</button>
                <input type="text" id="ticketCount" class="ticket-count" value="1" readonly />
                <button type="button" id="incrementBtn" class="counter-btn" onclick="updateTicketCount(1)">+</button>
            </div>
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
        function updateTicketCount(change) {
    const ticketCountInput = document.getElementById('ticketCount');
    let currentCount = parseInt(ticketCountInput.value);

    // Update the ticket count, ensuring it stays within the range of 1 to 5
    currentCount += change;

    if (currentCount < 1) {
        currentCount = 1; // Minimum value
    } else if (currentCount > 5) {
        currentCount = 5; // Maximum value
    }

    ticketCountInput.value = currentCount;
}
function openModal(ticketType, ticketPrice, ticketId) {
    // Store ticket info in data attributes
    const modal = document.getElementById('ticketModal');
    modal.dataset.ticketType = ticketType;
    modal.dataset.ticketPrice = ticketPrice;
    modal.dataset.ticketId = ticketId;
    
    // Show the modal
    modal.style.display = 'block';
    document.getElementById('ticketType').textContent = ` ${ticketType}`;
    document.getElementById('ticketType').style.color = 'orange';
    document.getElementById('ticketPrice').innerHTML = `Price For one Ticket: <span style="color:black">EGP ${ticketPrice}</span>`;
    document.getElementById('ticketPrice').style.color = 'gray';
}


    function closeModal() {
        document.getElementById('ticketModal').style.display = 'none';
    }

    function confirmPurchase(directTicketType = null, directTicketPrice = null, directTicketId = null) {
        let ticketType, ticketPrice, ticketId, ticketCount;
        
        const modal = document.getElementById('ticketModal');
        
        // Check if we're coming from the modal or direct button click
        if (directTicketType === null) {
            // Coming from modal
            ticketType = modal.dataset.ticketType;
            ticketPrice = modal.dataset.ticketPrice;
            ticketId = modal.dataset.ticketId;
            ticketCount = document.getElementById('ticketCount').value;
        } else {
            // Coming from direct button click
            ticketType = directTicketType;
            ticketPrice = directTicketPrice;
            ticketId = directTicketId;
            ticketCount = "0"; // Default for direct purchase
        }

        // Determine the action based on the Category
        const category = '<?php echo htmlspecialchars($_POST["Category"]); ?>';

        if (category === 'Concert') {
            // Proceed with pay.php
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'concert.php';

            // Add all the event data that was passed from Events.php
            const eventData = {
                event_id: '<?php echo htmlspecialchars($_POST["event_id"]); ?>',
                name: '<?php echo htmlspecialchars(addslashes($_POST["name"])); ?>',
                location: '<?php echo htmlspecialchars(addslashes($_POST["location"])); ?>',
                date: '<?php echo htmlspecialchars($_POST["date"]); ?>',
                time: '<?php echo htmlspecialchars($_POST["time"]); ?>',
                Category: '<?php echo htmlspecialchars($_POST["Category"]); ?>',
                organizer_image: '<?php echo htmlspecialchars($_POST["organizer_image"]); ?>',
                image: '<?php echo htmlspecialchars($_POST["image"]); ?>',
            };

            // Add event data to form
            for (const [key, value] of Object.entries(eventData)) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                form.appendChild(input);
            }

            // Add ticket data to form
            const ticketData = {
                ticket_id: ticketId,
                ticket_type: ticketType,
                ticket_price: ticketPrice,
                ticket_count: ticketCount
            };

            for (const [key, value] of Object.entries(ticketData)) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                form.appendChild(input);
            }

            // Submit the form
            document.body.appendChild(form);
            form.submit();
        } else {
            // Proceed with pay.php
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'pay.php';

            // Add all the event data that was passed from Events.php
            const eventData = {
                event_id: '<?php echo htmlspecialchars($_POST["event_id"]); ?>',
                name: '<?php echo htmlspecialchars(addslashes($_POST["name"])); ?>',
                location: '<?php echo htmlspecialchars(addslashes($_POST["location"])); ?>',
                date: '<?php echo htmlspecialchars($_POST["date"]); ?>',
                time: '<?php echo htmlspecialchars($_POST["time"]); ?>',
                Category: '<?php echo htmlspecialchars($_POST["Category"]); ?>',
                organizer_image: '<?php echo htmlspecialchars($_POST["organizer_image"]); ?>',
                image: '<?php echo htmlspecialchars($_POST["image"]); ?>',
            };

            // Add event data to form
            for (const [key, value] of Object.entries(eventData)) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                form.appendChild(input);
            }


            // Submit the form
            document.body.appendChild(form);
            form.submit();
        }
    }
    function scrollToTicketSection() {
        const ticketSection = document.getElementById('ticket-section');
        ticketSection.scrollIntoView({
            behavior: 'smooth'
        });
    }
    </script>
</body>
</html>
<?php include "../Components/Footer.php"?>