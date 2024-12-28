<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Oraganizer Dashboard</title>
    <link rel="stylesheet" href="../../../public/css/organizer.css" />
    <!-- <link rel="stylesheet" href="../css/sb-admin-2.min.css" /> -->
    <script
      src="https://kit.fontawesome.com/19a37f6564.js"
      crossorigin="anonymous"
    ></script>
  </head>

  <body>
  <?php
      // Include any PHP for session handling, database connection, etc.
      // session_start();
      require_once("../../db/Dbh.php");

      require_once '../../model/Eventsdb.php';
        require_once '../../model/Organizer.php';

      // Database connection
      $dbh = new Dbh();
      $conn = $dbh->getConn();

      $eventsDb = new Eventsdb($conn);

      

$organizer = new Organizer($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['eventName'])) {
    $name = $_POST['eventName'];
    $description = $_POST['eventDescription'];
    $category = $_POST['eventType'];
    $createdBy = $_POST['createdBy'];
    $organizerName = $_POST['organizerName'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $venue = $_POST['venue'];
    $address = $_POST['address'];
    $venueMapLink = $_POST['venueMapLink'];
    $venueProfileLink = $_POST['venueProfileLink'];

    // Handle Venue Facilities Correctly
    $venueFacilitiesString = ""; // Initialize outside the if
    if (isset($_POST['venueFacilities']) && is_array($_POST['venueFacilities'])) {
    foreach ($_POST['venueFacilities'] as $facility) {
        $facility = htmlspecialchars($facility);
        $venueFacilitiesString .= $facility . ",";
    }
    $venueFacilitiesString = rtrim($venueFacilitiesString, ",");

        echo "Venue Facilities String: " . $venueFacilitiesString . "<br>"; // For debugging
    } else {
        echo "No facilities selected.<br>"; // For debugging
    }

 // Handle File uploads.
 $eventImage = isset($_FILES['eventImage']) && $_FILES['eventImage']['error'] === UPLOAD_ERR_OK ? file_get_contents($_FILES['eventImage']['tmp_name']) : null;
 $venueImage = isset($_FILES['venueImage']) && $_FILES['venueImage']['error'] === UPLOAD_ERR_OK ? file_get_contents($_FILES['venueImage']['tmp_name']) : null;
 $organizerLogo = isset($_FILES['organizerLogo']) && $_FILES['organizerLogo']['error'] === UPLOAD_ERR_OK ? file_get_contents($_FILES['organizerLogo']['tmp_name']) : null;

    if (1) {
        $createdBy = 1;
    } else {
        echo "User not logged in!"; // Handle this appropriately (redirect, error message, etc.)
        exit; // Stop execution
    }

    $success = $organizer->sendEventRequest(
        $name, $description, $category, $createdBy, $organizerName,
        $startDate, $endDate, $venue, $address, $venueMapLink,
        $venueFacilitiesString, $venueProfileLink,
        $eventImage, $venueImage, $organizerLogo
    );

    if ($success) {
        echo "Event request sent successfully!";
    } else {
        echo "Failed to send event request.";
    }

}



if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['eventId'])) { 
    // Collect form data
    $eventId = $_POST['eventId'];
    $eventName = isset($_POST['editEventName']) ? $_POST['editEventName'] : null;  
    $eventDescription = isset($_POST['editEventDescription']) ? $_POST['editEventDescription'] : null;  
    $eventType = isset($_POST['editEventType']) ? $_POST['editEventType'] : null;  
    $organizerName = isset($_POST['editOrganizerName']) ? $_POST['editOrganizerName'] : null;  
    $startDate = isset($_POST['editStartDate']) ? $_POST['editStartDate'] : null;  
    $endDate = isset($_POST['editEndDate']) ? $_POST['editEndDate'] : null;  
    $venue = isset($_POST['editVenue']) ? $_POST['editVenue'] : null;  
    $venueAddress = isset($_POST['editAddress']) ? $_POST['editAddress'] : null;  
    $venueMapLink = isset($_POST['editVenueMapLink']) ? $_POST['editVenueMapLink'] : null;  
    $venueProfileLink = isset($_POST['editVenueProfileLink']) ? $_POST['editVenueProfileLink'] : null;  

    // Initialize venueFacilitiesString
    $venueFacilitiesString = ''; 
    if (isset($_POST['editVenueFacilities']) && is_array($_POST['editVenueFacilities'])) {
        foreach ($_POST['editVenueFacilities'] as $facility) {
            $facility = htmlspecialchars($facility); // Sanitize input
            $venueFacilitiesString .= $facility . ', '; // Concatenate facilities
        }
        $venueFacilitiesString = rtrim($venueFacilitiesString, ','); // Remove trailing comma
    }
   
    
    // Check if new photos are uploaded
    $eventImage = isset($_FILES['editEventImage']) && $_FILES['editEventImage']['error'] === UPLOAD_ERR_OK 
        ? file_get_contents($_FILES['editEventImage']['tmp_name']) : null;
    $venueImage = isset($_FILES['editVenueImage']) && $_FILES['editVenueImage']['error'] === UPLOAD_ERR_OK 
        ? file_get_contents($_FILES['editVenueImage']['tmp_name']) : null;
    $organizerLogo = isset($_FILES['editOrganizerLogo']) && $_FILES['editOrganizerLogo']['error'] === UPLOAD_ERR_OK 
        ? file_get_contents($_FILES['editOrganizerLogo']['tmp_name']) : null;

    // Get the created by user (this could come from session or other mechanism)
    $createdBy = 1; // Example from session

    // Perform the update action, only update photos if new ones are uploaded
    $success = $organizer->updateEvent(
        $eventId,
        $eventName,
        $eventDescription,
        $eventType,
        $createdBy, 
        $organizerName,
        $startDate,
        $endDate,
        $venue,
        $venueAddress,
        $venueMapLink,
        $venueFacilitiesString,
        $venueProfileLink,
       
        $eventImage,  // This will be null if no new image uploaded
        $venueImage,  // This will be null if no new image uploaded
        $organizerLogo // This will be null if no new image uploaded
    );

    if ($success) {
        echo "Event updated successfully!";
    } else {
        echo "Failed to update event.";
    }
}


if (isset($_GET['delete_event_id'])) {
    $eventId = intval($_GET['delete_event_id']);

    try {
        $success = $organizer->deleteEvent($eventId);

        if ($success) {
            $message = "Event deleted successfully!";
        } else {
            $error = "Event not found or could not be deleted.";
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}


if (isset($_GET['eventId']) && is_numeric($_GET['eventId'])) {
    $eventId = (int)$_GET['eventId'];
    $eventToEdit = $eventsDb->getEventById($eventId);

    if (!$eventToEdit) {
        echo "Event not found.";
        exit;
    }
}



      // Fetch all events to display
$events = $eventsDb->getAllEvents();


    ?>

    <!-- Sidebar -->
    <div class="sidebar">
      <div class="logo">
        <h2>Oraganizer.</h2>
      </div>
      <ul>
        <li><a href="#" class="active" data-page="dashboard">Dashboard</a></li>
        <li><a href="#" data-page="events">Events</a></li>
        <li><a href="#" data-page="users">User Management</a></li>
        <li><a href='#'>Log Out</a><li>
      </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <!-- Dashboard page -->

<!-- Event Management Page -->
<div id="events-page" class="page hidden">
    <header>
        <h1>Event Management</h1>
    </header>

  <!-- Add Event Modal -->
<div id="addEventModal" class="modal-overlay">
    <div class="modal-content">
        <span class="modal-close" onclick="closeModals('addEventModal')">&times;</span>
        <h2>Add New Event</h2>
        <form id="eventForm" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data" onsubmit="return handleAddEvent(event)">

            <!-- Event Details Section -->
            <h3>Event Details</h3>
            <div class="input-group">
                <label for="eventName">Event Name:</label>
                <input type="text" id="eventName" name="eventName" required>
                <div class="error-message" id="eventNameError"></div>
            </div>
            <div class="input-group">
                <label for="eventDescription">Description:</label>
                <textarea id="eventDescription" name="eventDescription"></textarea>
                <div class="error-message" id="eventDescriptionError"></div>
            </div>
            <div class="input-group">
                <label>Type of Event:</label>
                <label><input type="radio" name="eventType" value="Theatre" required> Theatre</label>
                <label><input type="radio" name="eventType" value="concert"> Concert</label>
                <label><input type="radio" name="eventType" value="exhibition"> Exhibition</label>
                <div class="error-message" id="eventTypeError"></div>
            </div>
          <!-- Event Image Upload -->
            <div class="input-group">
                <label for="eventImage">Event Image:</label>
                <input type="file" id="eventImage" name="eventImage" accept="image/*">
                <div class="error-message" id="eventImageError"></div>
            </div>

            <!-- Location Section -->
            <h3>Location</h3>
            
          
            <div class="input-group">
                <label for="venue">Venue Name:</label>
                <input type="text" id="venue" name="venue">
                <div class="error-message" id="venueError"></div>
            </div>
            <div class="input-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address">
                <div class="error-message" id="addressError"></div>
            </div>
            <div class="input-group">
                <label for="venueMapLink">Google Maps Link:</label>
                <input type="text" id="venueMapLink" name="venueMapLink">
                <div class="error-message" id="venueMapLinkError"></div>
            </div>
            <!-- Date and Time Section -->
            <h3>Date and Time</h3>
            <div class="input-group">
                <label for="startDate">Start Date:</label>
                <input type="datetime-local" id="startDate" name="startDate" required>
                <div class="error-message" id="startDateError"></div>
            </div>
            <div class="input-group">
                <label for="endDate">End Date (optional):</label>
                <input type="datetime-local" id="endDate" name="endDate">
                <div class="error-message" id="endDateError"></div>
            </div>

         <!-- Event Access and Ticket Section -->
         <h3>Access & Tickets</h3>
            <div id="ticketPriceContainer">
            <h4>Ticket Prices</h4>
    <button type="button" onclick="addTicketRow()">Add Ticket Type</button>
    <div id="ticketRows"></div> 
                
            </div>

            <!-- Organizer Section -->
            <h3>Organizer Details</h3>
            <div class="input-group">
                <label for="createdBy">Organizer:</label>
                <input type="text" id="createdBy" name="createdBy" required>
                <div class="error-message" id="createdByError"></div>
            </div>
            <div class="input-group">
                <label for="organizerName">Organizer Name:</label>
                <input type="text" id="organizerName" name="organizerName">
                <div class="error-message" id="organizerNameError"></div>
            </div>
          <!-- Organizer Logo Upload -->
    <div class="input-group">
    <label for="organizerLogo">Organizer Logo:</label>
    <input type="file" id="organizerLogo" name="organizerLogo" accept="image/*">
    <div class="error-message" id="organizerLogoError"></div>
  </div>

            <!-- Event Status and Recurrence Section -->
            <h3>Status & Recurrence</h3>
            <div class="input-group">
                <label for="eventStatus">Event Status:</label>
                <select id="eventStatus" name="eventStatus">
                    
                    <option value="Accepted">Accepted</option>
                    <option value="Pending">Pending</option>
                    <option value="Rejected">Rejected</option>
                </select>
                <div class="error-message" id="eventStatusError"></div>
            </div>

            <!-- Venue Facilities Section -->
            <h3>Venue Facilities</h3>
            <div class="input-group">
                <label>Facilities Available:</label>
                <label><input type="checkbox" name="editVenueFacilities[]" value="Bathrooms"> Bathrooms</label>
    <label><input type="checkbox" name="editVenueFacilities[]" value="Food Services"> Food Services</label>
    <label><input type="checkbox" name="editVenueFacilities[]" value="Parking"> Parking</label>
    <label><input type="checkbox" name="editVenueFacilities[]" value="Security"> Security</label>
    
                <div class="error-message" id="venueFacilitiesError"></div>
            </div>
            <div class="input-group">
                <label for="venueProfileLink">Venue Profile Link:</label>
                <input type="text" id="venueProfileLink" name="venueProfileLink">
                <div class="error-message" id="venueProfileLinkError"></div>
            </div>
           
           <!-- Venue Image Upload -->
            <div class="input-group">
                <label for="venueImage">Venue Image:</label>
                <input type="file" id="venueImage" name="venueImage" accept="image/*">
                <div class="error-message" id="venueImageError"></div>
            </div>

            <button type="submit">Submit</button>
        </form>
    </div>
</div>


  <!-- Edit Event Modal -->
 
  <div id="editEventModal" class="modal-overlay">
  <div class="modal-content">
        <span class="modal-close" onclick="closeModals('editEventModal')">&times;</span>
        <h2>Edit Event</h2>
        <form id="editEventForm" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data" onsubmit="return handleEditEvent(event)">
    <!-- Hidden field for event ID -->
    <input type="hidden" name="eventId" id="editEventId">
            <!-- Event Details Section -->
            <h3>Event Details</h3>
            <div class="input-group">
                <label for="editEventName">Event Name:</label>
                <input type="text" id="editEventName" name="editEventName" required>
                <div class="error-message" id="editEventNameError"></div>
            </div>
            <div class="input-group">
                <label for="editEventDescription">Description:</label>
                <textarea id="editEventDescription" name="editEventDescription"></textarea>
                <div class="error-message" id="editEventDescriptionError"></div>
            </div>
            <div class="input-group">
                <label>Type of Event:</label>
                <label><input type="radio" name="editEventType" value="Theatre" required> Theatre</label>
                <label><input type="radio" name="editEventType" value="Concert"> Concert</label>
                <label><input type="radio" name="editEventType" value="Exhibition"> Exhibition</label>
                <div class="error-message" id="editEventTypeError"></div>
            </div>

            <!-- Event Image Upload -->
            <div class="input-group">
                <label for="editEventImage">Event Image:</label>
                <input type="file" id="editEventImage" name="editEventImage" accept="image/*">
                <div class="error-message" id="editEventImageError"></div>
            </div>

            <!-- Location Section -->
            <h3>Location</h3>
            <div class="input-group">
                <label for="editVenue">Venue Name:</label>
                <input type="text" id="editVenue" name="editVenue">
                <div class="error-message" id="editVenueError"></div>
            </div>
            <div class="input-group">
                <label for="editAddress">Address:</label>
                <input type="text" id="editAddress" name="editAddress">
                <div class="error-message" id="editAddressError"></div>
            </div>
            <div class="input-group">
                <label for="editVenueMapLink">Google Maps Link:</label>
                <input type="text" id="editVenueMapLink" name="editVenueMapLink">
                <div class="error-message" id="editVenueMapLinkError"></div>
            </div>

            <!-- Date and Time Section -->
            <h3>Date and Time</h3>
            <div class="input-group">
                <label for="editStartDate">Start Date:</label>
                <input type="datetime-local" id="editStartDate" name="editStartDate" required>
                <div class="error-message" id="editStartDateError"></div>
            </div>
            <div class="input-group">
                <label for="editEndDate">End Date (optional):</label>
                <input type="datetime-local" id="editEndDate" name="editEndDate">
                <div class="error-message" id="editEndDateError"></div>
            </div>

            <!-- Ticket Section -->
            <h3>Access & Tickets</h3>
            <div id="editTicketPriceContainer">
                <h4>Ticket Prices</h4>
                <button type="button" onclick="edit_addTicketRow()">Add Ticket Type</button>
                <div id="editTicketRows"></div>
            </div>

            <!-- Organizer Section -->
            <h3>Organizer Details</h3>
            
            <div class="input-group">
                <label for="editOrganizerName">Organizer Name:</label>
                <input type="text" id="editOrganizerName" name="editOrganizerName">
                <div class="error-message" id="editOrganizerNameError"></div>
            </div>
            <div class="input-group">
                <label for="editOrganizerLogo">Organizer Logo:</label>
                <input type="file" id="editOrganizerLogo" name="editOrganizerLogo" accept="image/*">
                <div class="error-message" id="editOrganizerLogoError"></div>
            </div>
           

            <!-- Event Status Section -->
           

            <!-- Venue Facilities Section -->
            <h3>Venue Facilities</h3>
            <div class="input-group">
            <label>Facilities Available:</label>
    <label><input type="checkbox" name="editVenueFacilities[]" value="Bathrooms"> Bathrooms</label>
    <label><input type="checkbox" name="editVenueFacilities[]" value="Food Services"> Food Services</label>
    <label><input type="checkbox" name="editVenueFacilities[]" value="Parking"> Parking</label>
    <label><input type="checkbox" name="editVenueFacilities[]" value="Security"> Security</label>
    <div class="error-message" id="editVenueFacilitiesError"></div>
            </div>
            <div class="input-group">
            <label for="editVenueProfileLink">Venue Profile Link:</label>
        <input type="text" id="editVenueProfileLink" name="editVenueProfileLink">
            <div class="error-message" id="editVenueProfileLinkError"></div>
</div>
<!-- Venue Image Upload -->
<div class="input-group">
    <label for="editVenueImage">Venue Image:</label>
    <input type="file" id="editVenueImage" name="editVenueImage" accept="image/*">
    <div class="error-message" id="editVenueImageError"></div>
</div>
<button type="submit">Save Changes</button>
        </form>
    </div>
</div>


    <!-- Events Table -->
    <h2>Events List</h2>
    <table class="common-table-style events-table">
    <thead>
        <tr>
            <th>Event ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Category</th>
            <th>Organizer</th>
            <th>Dates</th>
            <th>Venue</th>
            <th>Created</th>
            <th>Actions</th>
            <th>
                <button class="buttonadd" id="addEventBtn">Add Event</button>
            </th>
        </tr>
    </thead>
    <tbody id="eventsTableBody">
        <?php foreach ($events as $event): ?>
            <tr class="event-row">
                <td><?php echo htmlspecialchars($event['Event_ID']); ?></td>
                <td><?php echo htmlspecialchars($event['Name']); ?></td>
                <td><?php echo htmlspecialchars($event['Description']); ?></td>
                <td>
                    <span class="category-badge <?php echo strtolower($event['Category']); ?>">
                        <?php echo htmlspecialchars($event['Category']); ?>
                    </span>
                </td>
                <td><?php echo htmlspecialchars($event['Organizer_Name']); ?></td>
                <td>
                    <div class="date-info">
                        <div>Start: <?php echo date('Y-m-d H:i', strtotime($event['Start_Date'])); ?></div>
                        <?php if (!empty($event['End_Date'])): ?>
                            <div>End: <?php echo date('Y-m-d H:i', strtotime($event['End_Date'])); ?></div>
                        <?php endif; ?>
                    </div>
                </td>
                <td>
                    <div><?php echo htmlspecialchars($event['Venue']); ?></div>
                    <?php if (!empty($event['Address'])): ?>
                        <div class="address"><?php echo htmlspecialchars($event['Address']); ?></div>
                    <?php endif; ?>
                    <?php if (!empty($event['Venue_Facilities'])): ?>
                        <div class="facilities">
                            Facilities: <?php echo htmlspecialchars($event['Venue_Facilities']); ?>
                        </div>
                    <?php endif; ?>
                </td>
                <td>
                    <div>Created: <?php echo date('Y-m-d', strtotime($event['Created_At'])); ?></div>
                    <div>Updated: <?php echo date('Y-m-d', strtotime($event['Updated_At'])); ?></div>
                </td>
                <td class="actions-cell">
                    <?php if (!empty($event['Venue_Map_Link'])): ?>
                        <a href="<?php echo htmlspecialchars($event['Venue_Map_Link']); ?>" target="_blank" class="link-button">Map</a>
                    <?php endif; ?>
                    <?php if (!empty($event['Venue_Profile_Link'])): ?>
                        <a href="<?php echo htmlspecialchars($event['Venue_Profile_Link']); ?>" target="_blank" class="link-button">Venue Profile</a>
                    <?php endif; ?>
                </td>
                <td class="action-icons">
                    <?php
                        $eventData = $event;
                        unset($eventData['Image']);
                        unset($eventData['Venue_Image']);
                        unset($eventData['Organizer_Logo']);
                    ?>
                    <button class="edit-btn" onclick='openEditEventModal(<?php echo json_encode($eventData); ?>)'>
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="delete-icon" onclick="deleteEvent(<?php echo htmlspecialchars($event['Event_ID']); ?>)">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>

     
    
          </div>
        
      </div>
    </div>
    </div>

    <script>


// Function to render events table with all attributes 


// Form handling function
function handleAddEvent(event) {
    event.preventDefault();
    
    if (!validateEventForm()) return;
    
    // If validation passes, submit the form
    document.getElementById('eventForm').submit();
}

function handleEditEvent(event) {
    event.preventDefault();
    console.log("Editing event");
    // Validate the form inputs
    if (!validateEditEventForm()) {
        console.error("Validation failed.");
        return; // Stop execution if validation fails
    }

    closeModals('editEventModal'); 
       document.getElementById('editEventForm').submit();

    document.getElementById('editEventForm').reset();
}

function deleteEvent(eventId) {
        if (confirm("Are you sure you want to delete this event?")) {
            // Redirect to the same page with the event ID for deletion
            window.location.href = `?delete_event_id=${eventId}`;
        }
    }







// Adds a row by making an inactive row active and fills it
function addTicketRow() {
    const ticketRows = document.getElementById("ticketRows");
    const row = document.createElement("div");
    row.classList.add("ticket-row");

    row.innerHTML = `
        <input type="text" placeholder="Ticket Type (e.g., VIP)" class="ticket-type" required />
        <input type="number" placeholder="Ticket Price" class="ticket-price" min="0" required />
        <button type="button" class="remove-ticket" onclick="removeTicketRow(this)">Remove</button>
    `;
    ticketRows.appendChild(row);
}
function edit_addTicketRow(){

    const ticketRows = document.getElementById("editTicketRows");
    const row = document.createElement("div");
    row.classList.add("ticket-row");

    row.innerHTML = `
        <input type="text" placeholder="Ticket Type (e.g., VIP)" class="ticket-type" required />
        <input type="number" placeholder="Ticket Price" class="ticket-price" min="0" required />
        <button type="button" class="remove-ticket" onclick="removeTicketRow(this)">Remove</button>
    `;
    ticketRows.appendChild(row);

}
// Remove ticket row
function removeTicketRow(button) {
    const row = button.parentNode;
    row.remove();
}
// Gathers ticket prices and types from dynamic input fields
function gatherTicketPrices() {
    const ticketFields = document.querySelectorAll('#ticketPriceContainer .ticket-row');
    const tickets = [];
    
    ticketFields.forEach(field => {
        const type = field.querySelector('.ticket-type').value;
        const price = parseFloat(field.querySelector('.ticket-price').value);

        // Ensure both type and price are provided
        if (type && !isNaN(price)) {
            tickets.push({ type, price, currency: 'EGP' }); // Assuming EGP as currency
        }
        console.log("ticketPrices in gattherTicketPrices: ",tickets);
    });
    return tickets;
}


// Gathers ticket prices and types from dynamic input fields
function editgatherTicketPrices() {
    const ticketFields = document.querySelectorAll('#editTicketPriceContainer .ticket-row');
    const tickets = [];
    
    ticketFields.forEach(field => {
        const type = field.querySelector('.ticket-type').value;
        const price = parseFloat(field.querySelector('.ticket-price').value);

        // Ensure both type and price are provided
        if (type && !isNaN(price)) {
            tickets.push({ type, price, currency: 'EGP' }); // Assuming EGP as currency
        }
        console.log("ticketPrices in gattherTicketPrices: ",tickets);
    });
    return tickets;
}

// Modal management for adding and editing events
document.getElementById('addEventBtn').addEventListener('click', () => {
    document.getElementById('eventForm').reset();

    resetInputStylesEvent()
    openModal('addEventModal');
});



function isValidURL(url) {
    const pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
        '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.?)+[a-z]{2,}|'+ // domain name
        '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
        '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
        '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
        '(\\#[-a-z\\d_]*)?$','i');
    return !!pattern.test(url);
}

function validateEventForm() { 
    // Get form elements
    const eventName = document.getElementById('eventName');
    const eventDescription = document.getElementById('eventDescription');
    const startDate = document.getElementById('startDate');
    const endDate = document.getElementById('endDate');
    
    const address = document.getElementById('address');
    const eventType = document.querySelector('input[name="eventType"]:checked');
    const createdBy = document.getElementById('createdBy');
    const organizerName = document.getElementById('organizerName');
    const eventImage = document.getElementById('eventImage');
    const eventStatus = document.getElementById('eventStatus');
    const organizerLogo = document.getElementById('organizerLogo');
    const venueProfileLink = document.getElementById('venueProfileLink');
    const venueMapLink = document.getElementById('venueMapLink');
    const venueImage = document.getElementById('venueImage');

    // Error Elements
    const errors = {
        eventName: 'Event name must be at least 3 characters long.',
        eventDescription: 'Event description must be at least 10 characters long.',
        startDate: 'Please select a valid start date and time.',
        endDate: 'End date must be later than start date.',
       
        address: 'Address must be at least 5 characters long.',
        eventType: 'Please select an event type.',
        createdBy: 'Organizer must be specified.',
        organizerName: 'Organizer name must be at least 3 characters long.',
        eventImage: 'Please upload an event image.',
        eventStatus: 'Please select an event status.',
        organizerLogo: 'Please upload an organizer logo.',
        venueProfileLink: 'Please enter a valid URL for the venue profile.',
        venueMapLink: 'Please enter a valid URL for the venue map.',
        venueImage: 'Please upload a venue image.',
    };

    resetInputStylesEvent();

    let isValid = true;

    if (eventName.value.length < 3) {
        setError(eventName, document.getElementById('eventNameError'), errors.eventName);
        isValid = false;
    }
    if (eventDescription.value.length < 10) {
        setError(eventDescription, document.getElementById('eventDescriptionError'), errors.eventDescription);
        isValid = false;
    }
    if (!startDate.value) {
        setError(startDate, document.getElementById('startDateError'), errors.startDate);
        isValid = false;
    } else if (endDate.value && new Date(startDate.value) > new Date(endDate.value)) {
        setError(endDate, document.getElementById('endDateError'), errors.endDate);
        isValid = false;
    }
    if (!eventType) {
        setError(document.getElementById('eventTypeError'), document.getElementById('eventTypeError'), errors.eventType);
        isValid = false;
    }
    
    if (address.value.length < 5) {
        setError(address, document.getElementById('addressError'), errors.address);
        isValid = false;
    }
    if (createdBy.value.length < 3) {
        setError(createdBy, document.getElementById('createdByError'), errors.createdBy);
        isValid = false;
    }
    if (organizerName.value && organizerName.value.length < 3) {
        setError(organizerName, document.getElementById('organizerNameError'), errors.organizerName);
        isValid = false;
    }
    if (!eventImage.files.length) {
        setError(eventImage, document.getElementById('eventImageError'), errors.eventImage);
        isValid = false;
    }
    if (!organizerLogo.files.length) {
        setError(organizerLogo, document.getElementById('organizerLogoError'), errors.organizerLogo);
        isValid = false;
    }
    if (!venueProfileLink.value) {
        setError(venueProfileLink, document.getElementById('venueProfileLinkError'), errors.venueProfileLink);
        isValid = false;
    }
    if (!venueMapLink.value) {
        setError(venueMapLink, document.getElementById('venueMapLinkError'), errors.venueMapLink);
        isValid = false;
    }
    if (!venueImage.files.length) {
        setError(venueImage, document.getElementById('venueImageError'), errors.venueImage);
        isValid = false;
    }
    if (!eventStatus.value) {
        setError(eventStatus, document.getElementById('eventStatusError'), errors.eventStatus);
        isValid = false;
    }

    return isValid;
}

// Validation Function
function validateEditEventForm() {
    // Get form elements
    const eventName = document.getElementById('editEventName');
    const eventDescription = document.getElementById('editEventDescription');
    const startDate = document.getElementById('editStartDate');
    const VenueName = document.getElementById('editVenue');
    const address = document.getElementById('editAddress');
    const eventType = document.querySelector('input[name="editEventType"]:checked');
    const organizerName = document.getElementById('editOrganizerName');
    const organizerLogo = document.getElementById('editOrganizerLogo');
    const venueProfileLink = document.getElementById('editVenueProfileLink');
    const venueMapLink = document.getElementById('editVenueMapLink');
    const venueImage = document.getElementById('editVenueImage');
    const eventImage = document.getElementById('editEventImage');

    // Error Elements
    const errors = {
        eventName: 'Event name must be at least 3 characters long.',
        eventDescription: 'Event description must be at least 10 characters long.',
        startDate: 'Please select a valid start date and time.',
        venue: 'venue must be at least 5 characters long.',
        address: 'Address must be at least 5 characters long.',
        eventType: 'Please select an event type.',
        organizerName: 'Organizer name must be at least 3 characters long.',
        eventStatus: 'Please select an event status.',
        organizerLogo: 'Please upload an organizer logo.',
        venueProfileLink: 'Please enter a valid URL for the venue profile.',
        venueMapLink: 'Please enter a valid URL for the venue map.',
        venueImage: 'Please upload a venue image.',
        eventImage: 'Please upload an event image.',
        invalidImageFormat: 'Invalid image format. Please upload a JPEG, PNG, or GIF.'
    };

    resetEditInputStyles(); // Function to reset input styles

    let isValid = true;

    if (eventName.value.length < 3) {
        setError(eventName, document.getElementById('editEventNameError'), errors.eventName);
        isValid = false;
    }
    if (eventDescription.value.length < 10) {
        setError(eventDescription, document.getElementById('editEventDescriptionError'), errors.eventDescription);
        isValid = false;
    }
    if (!startDate.value) {
        setError(startDate, document.getElementById('editStartDateError'), errors.startDate);
        isValid = false;
    }

    if (VenueName.value.length < 5) {
        setError(VenueName, document.getElementById('editVenueError'), errors.venue);
        isValid = false;
    }
    if (address.value.length < 5) {
        setError(address, document.getElementById('editAddressError'), errors.address);
        isValid = false;
    }
    if (!eventType) {
        setError(document.getElementById('editEventTypeError'), document.getElementById('editEventTypeError'), errors.eventType);
        isValid = false;
    }
    if (organizerName.value.length < 3) {
        setError(organizerName, document.getElementById('editOrganizerNameError'), errors.organizerName);
        isValid = false;
    }

    // Image format validation
    const validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];

    // Event Image Validation
    if (eventImage.files.length) {
        const fileType = eventImage.files[0].type;
        if (!validImageTypes.includes(fileType)) {
            setError(eventImage, document.getElementById('editEventImageError'), errors.invalidImageFormat);
            isValid = false;
        }
    } 

    // Organizer Logo Validation
    if (organizerLogo.files.length) {
        const fileType = organizerLogo.files[0].type;
        if (!validImageTypes.includes(fileType)) {
            setError(organizerLogo, document.getElementById('editOrganizerLogoError'), errors.invalidImageFormat);
            isValid = false;
        }
    } 

    // Venue Image Validation
    if (venueImage.files.length) {
        const fileType = venueImage.files[0].type;
        if (!validImageTypes.includes(fileType)) {
            setError(venueImage, document.getElementById('editVenueImageError'), errors.invalidImageFormat);
            isValid = false;
        }
    } 

    if (!venueProfileLink.value) {
        setError(venueProfileLink, document.getElementById('editVenueProfileLinkError'), errors.venueProfileLink);
        isValid = false;
    }
    if (!venueMapLink.value) {
        setError(venueMapLink, document.getElementById('editVenueMapLinkError'), errors.venueMapLink);
        isValid = false;
    }
    

    return isValid;
}


function openEditEventModal(eventData) {
    console.log("Event data:", eventData); // Log the raw data passed to the function
    console.log("Venue_Profile_Link:", eventData.Venue_Profile_Link);
console.log("Venue_Facilities:", eventData.Venue_Facilities);
    try {

        // Ensure the eventData object is populated with the expected keys
        document.getElementById('editEventId').value = eventData.Event_ID || '';  // Handle undefined or missing data
        document.getElementById('editEventName').value = eventData.Name || '';
        document.getElementById('editEventDescription').value = eventData.Description || '';
        
        // Set radio buttons for event type (Category)
        const eventTypeRadios = document.getElementsByName('editEventType');
        eventTypeRadios.forEach((radio) => {
            if (radio.value === eventData.Category) {
                radio.checked = true;
            }
        });

        // Populate the venue fields
        document.getElementById('editVenue').value = eventData.Venue || '';
        document.getElementById('editAddress').value = eventData.Address || '';
        document.getElementById('editVenueMapLink').value = eventData.Venue_Map_Link || '';
        document.getElementById('editStartDate').value = eventData.Start_Date || '';
        document.getElementById('editEndDate').value = eventData.End_Date || '';
        
        // Organizer details
        document.getElementById('editOrganizerName').value = eventData.Organizer_Name || '';
        
        

        // Check and populate the venue facilities if they exist:
        const venueFacilities = eventData.Venue_Facilities ? eventData.Venue_Facilities.split(', ') : [];
        const facilityCheckboxes = document.getElementsByName('editVenueFacilities[]');
        facilityCheckboxes.forEach(function(checkbox) {
            checkbox.checked = venueFacilities.includes(checkbox.value);
        });

        // Set the Venue Profile Link (if available)
        document.getElementById('editVenueProfileLink').value = eventData.Venue_Profile_Link || '';

        // If there's an image, set the image preview or leave it blank
        document.getElementById('editEventImage').value = eventData.Image || '';
        if (eventData.Venue_Image) {
            // Set venue image preview logic (if needed)
            console.log("Venue Image Available:", eventData.Venue_Image);
        }

        // Show the modal if needed
        document.getElementById('editEventModal').style.display = 'block';
    } catch (error) {
        console.error("Error parsing event data:", error);
    }
    openModal('editEventModal');
}




function resetInputStylesEvent() {
    const inputs = document.querySelectorAll('#eventForm input, #eventDescription');
    const errorMessages = document.querySelectorAll('.error-message');

    inputs.forEach(input => {
        input.classList.remove('input-error'); // Remove error class
    });

    errorMessages.forEach(msg => {
        msg.style.display = 'none'; // Hide error messages
    });
}
function resetEditInputStyles() {
    // Select all input elements within the edit event modal
    const inputs = document.querySelectorAll('#editEventForm input , #editEventDescription');
    const errorMessages = document.querySelectorAll('.error-message'); // Change class name if necessary

    // Remove error styles from inputs
    inputs.forEach(input => {
        input.classList.remove('input-error'); // Remove error class
    });

    // Hide all error messages
    errorMessages.forEach(msg => {
        msg.style.display = 'none'; // Hide error messages
    });
}


  


        
     




  // Setup modal events
 function setupModals() {
            const modals = ['addEventModal','editEventModal'];
            
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                const closeBtn = modal.querySelector('.modal-close');
                
                // Close button click
                closeBtn.addEventListener('click', () => closeModals(modalId));
                
                // Click outside modal
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) closeModals(modalId);
                });
            });

            // Add user button
            document.getElementById('addUserBtn').addEventListener('click', () => {
                document.getElementById('userForm').reset();
                openModal('adduserModal');
            });

        }
        // Initialize the page
        document.addEventListener('DOMContentLoaded', () => {
            
            
            setupModals();
        });

    // Function to open edit modal
    function openEditModal(id) {
            const user = users.find(user => user.id === id);
            if (user) {
                document.getElementById('editUserId').value = user.id;
                document.getElementById('editUsername').value = user.username;
                document.getElementById('editEmail').value = user.email;
                document.getElementById('editPassword').value = user.password;
                openModal('editUserModal');
            }
        }


   // Modal management functions
function openModal(modalId) {
    
    const modal = document.getElementById(modalId);
    modal.classList.add('active');
    modal.style.display = 'flex'; // Ensure the modal is displayed as flex for centering
    document.body.classList.add('no-scroll'); // Disable background scrolling
}

function closeModals(modalId) {
    resetEditInputStyles();
    const modal = document.getElementById(modalId);
    modal.classList.remove('active');
    modal.style.display = 'none'; // Hide the modal
    document.body.classList.remove('no-scroll'); // Enable background scrolling
}




// Function to display error message and style the input
function setError(input, errorElement, message) {
    errorElement.textContent = message; // Set error message
    errorElement.style.display = 'block'; // Show error message
    input.classList.add('input-error'); // Add error class for styling
}







    // Function to display error message and style the input in the edit form
    function setEditFormError(input, errorElement, message) {
        errorElement.textContent = message; // Set error message
        errorElement.style.display = 'block'; // Show error message
        input.classList.add('input-error'); // Add error class for styling
    }

    // Function to reset input styles in the edit form
    function resetEditFormInputStyles() {
        const inputs = document.querySelectorAll('#editUserForm input');
        const errorMessages = document.querySelectorAll('#editUserForm .error-message');

        inputs.forEach(input => {
            input.classList.remove('input-error'); // Remove error class
        });

        errorMessages.forEach(msg => {
            msg.style.display = 'none'; // Hide error messages
        });
    }


         // Sidebar navigation
        const sidebarLinks = document.querySelectorAll('.sidebar ul li a');
        const pages = document.querySelectorAll('.page');

        sidebarLinks.forEach(link => {
            link.addEventListener('click', function (event) {
                event.preventDefault();

                // Remove 'active' class from all links
                sidebarLinks.forEach(link => link.classList.remove('active'));

                // Hide all pages
                pages.forEach(page => page.classList.add('hidden'));

                // Add 'active' class to the clicked link
                this.classList.add('active');

                // Show the corresponding page
                const pageId = this.getAttribute('data-page') + '-page';
                document.getElementById(pageId).classList.remove('hidden');
            });
        });
    </script>
     
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>
    <script src="dashboard.js"></script>
  </body>
</html>
