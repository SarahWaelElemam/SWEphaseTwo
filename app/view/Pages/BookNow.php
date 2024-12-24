<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Page</title>
    <link rel="stylesheet" href="../../../public/css/BookNow.css" />
    <script src="https://kit.fontawesome.com/19a37f6564.js" crossorigin="anonymous"></script>
</head>
<body>

<?php include "../Components/NavBar.php"; ?>

<div class="event-container">
    <div class="event-image">
        <img src="../public/images/eventMemo.png" alt="Event Image" />
    </div>
    <div class="event-details">
        <div class="left-details">
            <h1>MEMO</h1>
            <p class="Time">Oct 17 | 09:00pm</p>
            <p>Grand Nile Tower</p>
            <button class="book-btn" onclick="window.location.href='pay.php?event=MEMO'">Book Now</button>
        </div>
        <div class="right-details">
            <p>Organized by</p>
            <img src="../images/organizerslogo.png" alt="CraftMedia Logo" />
        </div>
    </div>
</div>

<div class="about-event">
    <h2>About Event</h2>
    <p>The iconic and forever funny, Ahmed Helmy, returns to theatre with a hit play! A satire take on society in social media times, with a huge success in KSA, now in Egypt. Alongside a line-up of top-tier comedians.</p>
</div>

<div class="ticket-section">
    <h2 style="text-align: left">Tickets</h2>
    <div class="ticket-container">
        <div class="ticket">
            <h3>Royal</h3>
            <p>EGP 2500</p>
            <button class="ticket-btn" onclick="openModal('Royal', 2500)">Buy Now</button>
        </div>
        <div class="ticket">
            <h3>VIP</h3>
            <p>EGP 2000</p>
            <button class="ticket-btn" onclick="openModal('VIP', 2000)">Buy Now</button>
        </div>
        <div class="ticket">
            <h3>Platinum</h3>
            <p>EGP 1250</p>
            <button class="ticket-btn" onclick="openModal('Royal', 2500)">Buy Now</button>
        </div>
        <div class="ticket">
            <h3>Diamond</h3>
            <p>EGP 1500</p>
            <button class="ticket-btn">Buy Now</button>
        </div>
        <div class="ticket">
            <h3>Gold</h3>
            <p>EGP 1000</p>
            <button class="ticket-btn">Buy Now</button>
        </div>
        <div class="ticket">
            <h3>Silver</h3>
            <p>EGP 750</p>
            <button class="ticket-btn">Buy Now</button>
        </div>
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
            <h3>Grand Nile Tower</h3>
            <p>Abdulaziz Al Saud, Old Cairo, Cairo</p>
            <div class="venue-links">
                <a href="#">Open Venue Profile</a>
                <a href="#">Open In Maps</a>
            </div>
            <div class="venue-facilities">
                <h4>FACILITIES</h4>
                <p>🛁 Bathrooms | 🍽 Food Services</p>
                <p>🅿 Parking | 🛡 Security</p>
            </div>
        </div>
        <div class="venue-image">
            <img src="../images/Venue.png" alt="Grand Nile Tower Venue" />
        </div>
    </div>
</div>

<?php include "../Components/Footer.php"?>

<script>
function openModal(ticketType, ticketPrice) {
    document.getElementById('ticketModal').style.display = 'block';
    document.getElementById('ticketType').textContent = Ticket Type: ${ticketType};
    document.getElementById('ticketPrice').textContent = Price For one Ticket: EGP ${ticketPrice};
}

function closeModal() {
    document.getElementById('ticketModal').style.display = 'none';
}

function confirmPurchase() {
    const count = document.getElementById('ticketCount').value;
    alert(You have selected ${count} tickets);
    closeModal();
    window.location.href = "Events.php"; // Redirect to Events.php
}

function redirectToEvents() {
    window.location.href = "Events.php"; // Redirect to Events.php on Book Now click
}
</script>

</body>
</html>