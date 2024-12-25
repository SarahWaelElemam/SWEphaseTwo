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
            <div class="ticket">
                <div class="basic">
                    <div class="event-details">
                        <h2>INTIFADET ILMOND</h2>
                        <p><strong>Date:</strong> Nov 15</p>
                        <p><strong>Venue:</strong> Zed Park - El Sheikh Zayed</p>
                        <p><img src="../images/images.png"></p>
                    </div>
                    <button onclick=""><i class="fa-solid fa-ticket"></i> Book Now</button>
                </div>
                <div class="airline">
                    <img src="../images/event1.png" alt="Event Image" class="event-image">
                </div>
            </div>
        </div>
    </div>

    <!-- Hot Events Section -->
    <div class="hot-events-container">
        <h2>Hot Events</h2>
        <div class="hot-events-slider">
            <div class="hot-event active" id="event1"></div>
            <div class="hot-event" id="event2"></div>
            <div class="hot-event" id="event3"></div>
        </div>
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
            <div class="upcoming-event" id="upcoming1">
                <img src="../images/event4.jpg" alt="Upcoming Event 1" class="event-image">
            </div>
            <div class="upcoming-event" id="upcoming2">
                <img src="../images/event6.jpg" alt="Upcoming Event 2" class="event-image">
            </div>
            <div class="upcoming-event" id="upcoming3">
                <img src="../images/event5.png" alt="Upcoming Event 3" class="event-image">
            </div>
            <div class="upcoming-event" id="upcoming4">
                <img src="../images/event1.png" alt="Upcoming Event 4" class="event-image">
            </div>
            <div class="upcoming-event" id="upcoming5">
                <img src="../images/event3.png" alt="Upcoming Event 5" class="event-image">
            </div>
            <div class="upcoming-event" id="upcoming6">
                <img src="../images/eventMemo.png" alt="Upcoming Event 6" class="event-image">
            </div>
        </div>
    </div>

    <!-- Explore Categories Section -->
    <div class="explore-categories">
        <h2>Explore Top Categories For Fun Things To Do</h2>
        <div class="category-slider">
            <div class="category-ticket">
                <div class="category-image">
                    <img src="../images/event1.png" alt="Music Events">
                </div>
                <div class="category-info">
                    <h3>Music</h3>
                </div>
            </div>
            <div class="category-ticket">
                <div class="category-image">
                    <img src="../images/event6.jpg" alt="Summits">
                </div>
                <div class="category-info">
                    <h3>Summits</h3>
                </div>
            </div>
            <div class="category-ticket">
                <div class="category-image">
                    <img src="../images/event5.png" alt="Stand-Up Comedy">
                </div>
                <div class="category-info">
                    <h3>Stand-Up Comedy</h3>
                </div>
            </div>
            <div class="category-ticket">
                <div class="category-image">
                    <img src="../images/eventMemo.png" alt="Theater">
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
            <img src="../images/iconuser.png">
        </div>
        <h2>Login Or Signup To Gain Additional Benefits</h2>
        <p>Get your own personal profile, follow artists you love and more when you sign up for a TixCarte account</p>
        <button class="login-signup-btn">Login / Signup</button>
    </div>
    <?php include "../Components/Footer.php"?>
<script src="../../../public/js/homepage.js"></script>
</body>
</html>
