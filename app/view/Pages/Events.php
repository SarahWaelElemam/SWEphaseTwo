<?php
// Include necessary files
require_once("../../db/Dbh.php");
require_once("../../model/Eventsdb.php");
include "../Components/NavBar.php";

// Create a new database handler
$dbh = new Dbh();
$conn = $dbh->getConn();

// Create an instance of the Eventsdb model
$eventsModel = new Eventsdb($conn);

$events = [];

try {
    // Check if filter parameters are set
    if (isset($_POST['startDate']) && isset($_POST['endDate'])) {
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        // Fetch filtered events
        $events = $eventsModel->getFilteredEvents($startDate, $endDate);
    } else {
        // Fetch all events
        $events = $eventsModel->getAllEventsToDisplay();
    }

    // Check if events were retrieved
    if (empty($events)) {
        throw new Exception("No events found.");
    }
} catch (Exception $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
    exit;
}
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
                            <span><?php echo htmlspecialchars($event['Created_By']); ?></span>
                        </div>
                        <div class="span">
                            <h2>Location</h2>
                            <span><?php echo htmlspecialchars($event['Location']); ?></span>
                            <p><?php echo htmlspecialchars($event['detailed_loc']); ?></p>
                        </div>
                    </div>
                    <div class="payment bottom">
                        <button class="Book"><i class="fa-solid fa-ticket"></i> Book Now</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <script>
const removeFilterBtn = document.getElementById('removebtn');
const startDate = document.getElementById('startDate');
const endDate = document.getElementById('endDate');
const overlay = document.getElementById('overlay');
const calendarModal = document.getElementById('calendarModal');
const calendar = document.getElementById('calendar');
const closeModal = document.getElementById('closeModal');
const currentMonthYear = document.getElementById('currentMonthYear');
const prevMonth = document.getElementById('prevMonth');
const nextMonth = document.getElementById('nextMonth');

// Initialize date variables
let currentDate = new Date();
currentDate.setHours(0, 0, 0, 0);
let currentMonth = currentDate.getMonth();
let currentYear = currentDate.getFullYear();
let activeInput = null;
let startDateValue = null;

// Event Listeners
document.getElementById('filterbtn').addEventListener('click', filterEvents);
removeFilterBtn.style.display = 'none';
removeFilterBtn.addEventListener('click', removeFilter);
startDate.addEventListener('click', () => openCalendar(startDate));
endDate.addEventListener('click', () => {
    if (!startDate.value) {
        alert("Please select a start date first.");
        return;
    }
    openCalendar(endDate);
});
closeModal.addEventListener('click', closeCalendar);
overlay.addEventListener('click', closeCalendar);
prevMonth.addEventListener('click', () => changeMonth(-1));
nextMonth.addEventListener('click', () => changeMonth(1));

// Filter Events Function
function filterEvents() {
    const startDateValue = document.getElementById('startDate').value;
    const endDateValue = document.getElementById('endDate').value;

    if (!startDateValue || !endDateValue) {
        alert("Please select both start and end dates.");
        return;
    }

    // Create FormData object
    const formData = new FormData();
    formData.append('startDate', new Date(startDateValue).toISOString().split('T')[0]);
    formData.append('endDate', new Date(endDateValue).toISOString().split('T')[0]);

    // Send POST request
    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newEvents = doc.querySelector('.row-tick');
        
        if (newEvents) {
            document.querySelector('.row-tick').innerHTML = newEvents.innerHTML;
            // Make sure to show the remove button after successful filtering
            removeFilterBtn.style.display = 'inline-block';
        } else {
            document.querySelector('.row-tick').innerHTML = '<h2 style="margin: 2rem; font-family: italic;">No events found for the selected dates.</h2>';
            // Still show the remove button even when no events are found
            removeFilterBtn.style.display = 'inline-block';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while filtering events. Please try again.');
    });
}
function removeFilter() {
    // Clear the date inputs
    startDate.value = '';
    endDate.value = '';
    startDateValue = null;

    // Hide remove filter button before making the request
    removeFilterBtn.style.display = 'none';

    // Fetch all events
    fetch(window.location.href)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newEvents = doc.querySelector('.row-tick');
            
            if (newEvents) {
                document.querySelector('.row-tick').innerHTML = newEvents.innerHTML;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while removing the filter. Please try again.');
            // Show the remove button again if the request fails
            removeFilterBtn.style.display = 'inline-block';
        });
}
// Calendar Functions
function openCalendar(input) {
    activeInput = input;
    renderCalendar();
    overlay.style.display = 'block';
    calendarModal.style.display = 'block';
}

function closeCalendar() {
    overlay.style.display = 'none';
    calendarModal.style.display = 'none';
}

function renderCalendar() {
    calendar.innerHTML = '';
    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
    const firstDay = new Date(currentYear, currentMonth, 1).getDay();

    currentMonthYear.textContent = `${new Date(currentYear, currentMonth).toLocaleString('default', { month: 'long' })} ${currentYear}`;

    const weekdays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    weekdays.forEach(day => {
        const dayElement = document.createElement('div');
        dayElement.textContent = day;
        dayElement.classList.add('calendar-weekday');
        calendar.appendChild(dayElement);
    });

    for (let i = 0; i < firstDay; i++) {
        calendar.appendChild(document.createElement('div'));
    }

    for (let day = 1; day <= daysInMonth; day++) {
        const dayElement = document.createElement('div');
        dayElement.textContent = day;
        dayElement.classList.add('calendar-day');

        const dateToCheck = new Date(currentYear, currentMonth, day);
        if (activeInput === endDate && startDateValue) {
            if (dateToCheck <= startDateValue) {
                dayElement.classList.add('past-date');
            } else {
                dayElement.addEventListener('click', () => selectDate(day));
            }
        } else {
            if (dateToCheck < currentDate) {
                dayElement.classList.add('past-date');
            } else {
                dayElement.addEventListener('click', () => selectDate(day));
            }
        }

        calendar.appendChild(dayElement);
    }
}

function selectDate(day) {
    const selectedDate = new Date(currentYear, currentMonth, day);
    const formattedDate = `${currentMonth + 1}/${day}/${currentYear}`;
    
    if (activeInput === startDate) {
        startDate.value = formattedDate;
        startDateValue = selectedDate;
        endDate.value = '';
    } else if (activeInput === endDate) {
        if (selectedDate > startDateValue) {
            endDate.value = formattedDate;
        } else {
            alert("End date must be after the start date.");
            return;
        }
    }
    
    closeCalendar();
}

function changeMonth(delta) {
    currentMonth += delta;
    if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    } else if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    }
    renderCalendar();
}
</script>
</body>
</html>
<?php include "../Components/Footer.php"?>
