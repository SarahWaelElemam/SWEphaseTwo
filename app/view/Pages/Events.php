<?php
require_once("../../../app/controller/UserController.php");
include "../Components/NavBar.php";

$controller = new UsersController($model=0);
$events = $controller->getEvents();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../public/css/Events.css">
    <title>Events</title>
</head>

<body>
    <div class="filter">
        <h3>Event Filter</h3>
        <div class="filter-box">
            <input type="text" id="startDate" placeholder="Start Date" readonly>
            <input type="text" id="endDate" placeholder="End Date" readonly>
            <input type="button" id="filterbtn" value="Filter">
            <input type="button" id="removebtn" value="Remove Filter">
        </div>
    </div>

    <!-- Calendar Modal -->
    <div class="overlay" id="overlay"></div>
    <div class="calendar-modal" id="calendarModal">
        <h3>Select Date</h3>
        <div id="calendar-header">
            <button id="prevMonth">&lt;</button>
            <span id="currentMonthYear"></span>
            <button id="nextMonth">&gt;</button>
        </div>
        <div id="calendar"></div>
        <button id="closeModal">Close</button>
    </div>

    <div class="row-tick">
        <?php foreach ($events as $event): ?>
            <div class="ticket">
                <div class="side front">
                    <img src="../../../public/images/<?= htmlspecialchars($event['image']) ?>" alt="<?php echo htmlspecialchars($event['Name']); ?>">
                    <div class="info bottom">
                        <h1><?php echo htmlspecialchars($event['Name']); ?></h1>
                        <span class="title address"><?php echo htmlspecialchars($event['Location']); ?></span>
                        <p><i class="fa-solid fa-circle" style="color: #03b300; margin-top:0.5rem;"></i> Price: <?php echo htmlspecialchars($event['price_range1']); ?> - <?php echo htmlspecialchars($event['price_range2']); ?></p>
                        <dl>
                            <dt>Date</dt>
                            <dd><?php echo htmlspecialchars($event['Date']); ?></dd>
                            <dt>Time</dt>
                            <dd><?php echo htmlspecialchars($event['Time']); ?></dd>
                        </dl>
                    </div>
                </div>
                <div class="side back">
                    <div class="top">
                        <div class="span">
                            <h2>Organized By</h2>
                            <img src="../../../public/images/<?php echo htmlspecialchars($event['organizer_image']); ?>" style="width: 15rem; height: 4rem;">
                        </div>
                        <div class="span">
                            <h2>Location</h2>
                            <span><?php echo htmlspecialchars($event['Location']); ?></span>
                            <p><?php echo htmlspecialchars($event['detailed_loc']); ?></p>
                        </div>
                    </div>
                    <div class="payment bottom">
                        <button class="Book" onclick="navigateToBookNow('<?php 
                            echo htmlspecialchars(json_encode([
                            'organizer_image' =>$event['organizer_image'],
                            'Category' =>$event['Category'],
                                'event_id' => $event['Event_ID'],
                                'venue_loc' => $event['venue_loc'],
                                'name' => $event['Name'],
                                'location' => $event['Location'],
                                'date' => $event['Date'],
                                'time' => $event['Time'],
                                'price_range1' => $event['price_range1'],
                                'price_range2' => $event['price_range2'],
                                'created_by' => $event['Created_By'],
                                'detailed_loc' => $event['detailed_loc'],
                                'image' => $event['image'],
                                'about' => $event['about']
                            ]));
                        ?>')">
                            <i class="fa-solid fa-ticket"></i> Book Now
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <script src="../../../public/js/Events.js"></script>
    <script>
      function navigateToBookNow(eventData) {
    const data = JSON.parse(eventData);
    console.log(data); // Check if 'event_id' is in the data object
    
    // Create a form element
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'BookNow.php';
    
    // Create hidden input fields for each piece of data
    for (const [key, value] of Object.entries(data)) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = value;
        form.appendChild(input);
    }
    
    // Add the form to the document and submit it
    document.body.appendChild(form);
    form.submit();
}

    </script>
</body>
</html>
<?php include "../Components/Footer.php"?>