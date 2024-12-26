<?php
// Include the Eventsdb class
require_once("../../model/Model.php");
require_once("../../model/Eventsdb.php");

// Database connection (replace with your actual credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Project";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Instantiate the Eventsdb class
$eventsDb = new Eventsdb($conn);

// Fetch the 6 newest events
$events = $eventsDb->getAllEventsToDisplay(4);
foreach ($events as &$event) { // Use reference to modify the original array
    // Check if the image is a valid BLOB and convert to base64
    if (!empty($event['image'])) {
        $event['image'] = base64_encode($event['image']);
    } else {
        $event['image'] = ''; // Handle case where there is no image
    }
}



$upcomingEvents = $eventsDb->getUpcomingEvents('Pending'); // Assuming getUpcomingEvents is a method in Eventsdb
foreach ($upcomingEvents as &$event) {
    // Check if the image is a valid BLOB and convert to base64
    if (!empty($event['image'])) {
        $event['image'] = base64_encode($event['image']);
    } else {
        $event['image'] = ''; // Handle case where there is no image
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TixCarte - Homepage</title>
    <link rel="stylesheet" href="../../../public/css/homepage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include "../Components/NavBar.php" ?>
    <div class="ticket-container">
    <div class="ticket-slider">
    <?php if (!empty($events)): ?>
        <?php foreach ($events as $index => $event): ?>
            <div class="ticket" data-event-index="<?php echo $index; ?>">
                <div class="basic">
                    <div class="event-details">
                        <h2><?php echo htmlspecialchars($event['Name']); ?></h2>
                        <p><strong>Description:</strong> <?php echo htmlspecialchars($event['Description']); ?></p>
                        <p><strong>Category:</strong> <?php echo htmlspecialchars($event['Category']); ?></p>
                    </div>
                    <button onclick=""><i class="fa-solid fa-ticket"></i> Book Now</button>
                </div>
                <div class="event-image-box">
                    <?php if (!empty($event['image'])): ?>
                        <img src="data:image/png;base64,<?php echo $event['image']; ?>" alt="Event Image">
                    <?php else: ?>
                        <p>No image available</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No newest events available.</p>
    <?php endif; ?>
</div>





  

<div class="hot-events-slider">
    <?php if (!empty($events)): ?>
        <?php foreach ($events as $index => $event): ?>
            <div class="hot-event <?php echo $index === 0 ? 'active' : ''; ?>" 
                 data-event-index="<?php echo $index; ?>"
                 style="background-image: url('data:image/png;base64,<?php echo $event['image']; ?>');">
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hot events available.</p>
    <?php endif; ?>
</div>


<!-- Upcoming Events Section -->
<div class="upcoming-events-container">
    <div class="section-header">
        <button class="new-nav-btn" id="new-prev-btn">
            <i class="fas fa-chevron-left"></i>
        </button>
        <h2>Upcoming Events</h2>
        <button class="new-nav-btn" id="new-next-btn">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>

    <div class="upcoming-events-slider">
        <?php if (!empty($upcomingEvents)): ?>
            <?php foreach ($upcomingEvents as $event): ?>
                <div class="upcoming-event">
                    <img src="data:image/png;base64,<?php echo $event['image']; ?>" alt="Upcoming Event Image" class="event-image">
                    <div class="event-content">
                        <h2><?php echo htmlspecialchars($event['Name']); ?></h2>
                        <p><strong>Description:</strong> <?php echo htmlspecialchars($event['Description']); ?></p>
                        <p><strong>Category:</strong> <?php echo htmlspecialchars($event['Category']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No upcoming events available.</p>
        <?php endif; ?>
    </div>
</div>

    <!-- Explore Categories Section -->
    <div class="explore-categories">
        <h2>Explore Top Categories For Fun Things To Do</h2>
        <div class="category-slider">
            <div class="category-ticket">
                <div class="category-image">
                    <img src="../../../public/images/event1.png" alt="Music Events">
                </div>
                <div class="category-info">
                    <h3>Music</h3>
                </div>
            </div>
            <div class="category-ticket">
                <div class="category-image">
                    <img src="../../../public/images/event6.jpg" alt="Summits">
                </div>
                <div class="category-info">
                    <h3>Summits</h3>
                </div>
            </div>
            <div class="category-ticket">
                <div class="category-image">
                    <img src="../../../public/images/event5.png" alt="Stand-Up Comedy">
                </div>
                <div class="category-info">
                    <h3>Stand-Up Comedy</h3>
                </div>
            </div>
            <div class="category-ticket">
                <div class="category-image">
                    <img src="../../../public/images/eventMemo.png" alt="Theater">
                </div>
                <div class="category-info">
                    <h3>Theater</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Login/Signup Section -->
    <div class="login-signup-section">
        <div class="user-icon">
            <img src="../../../public/images/iconuser.png">
        </div>
        <h2>Login Or Signup To Gain Additional Benefits</h2>
        <p>Get your own personal profile, follow artists you love and more when you sign up for a TixCarte account</p>
        <button class="login-signup-btn">Login / Signup</button>
    </div>
    <?php include "../Components/Footer.php"?>
<script src="../../../public/js/homepage.js"></script>
</body>
</html>