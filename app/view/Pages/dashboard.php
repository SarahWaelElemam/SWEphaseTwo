<!DOCTYPE html>
<html lang="en"> 
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../../../public/css/dashboard.css" />
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
        require_once '../../model/Admin.php';   

      // Database connection
      $dbh = new Dbh();
      $conn = $dbh->getConn();
      $admin = new Admin($conn);

      $eventsDb = new Eventsdb($conn);

     
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateStatus'])) {
    $eventId = $_POST['eventId'];
    $newStatus = $_POST['viewEventStatus'];

    if ($admin->updateEventStatus($eventId, $newStatus)) {
        $message = "Event status updated successfully.";
    } else {
        $message = "Failed to update event status. Please try again.";
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
        <h2>Admin.</h2>
      </div>
      <ul>
        <li><a href="dashboard.php" class="active" data-page="dashboard">Dashboard</a></li>
        <li><a href="chat.php" data-page="chat">Chat</a></li>
        <li><a href="#" data-page="events">Events</a></li>
        <li><a href="user-management.php" data-page="users">User Management</a></li>
        <li><a href='#'>Log Out</a><li>
      </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <!-- Dashboard page -->
      <?php if (isset($message)): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
<!-- Event Management Page -->
<div id="events-page" class="page hidden">
    <header>
        <h1>Event Management</h1>
    </header>

   

<!-- Edit Event Modal -->

<div id="viewEventModal" class="modal-overlay">
  <div class="modal-content">
    <span class="modal-close" onclick="closeModals('viewEventModal')">&times;</span>
    <h2>View Event</h2>
    <form id="viewEventForm" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
    <!-- Hidden field for event ID -->
      <input type="hidden" name="eventId" id="viewEventId">
    

      <!-- Event Details Section -->
      <h3>Event Details</h3>
      <div class="input-group">
        <label for="viewEventName">Event Name:</label>
        <input type="text" id="viewEventName" name="viewEventName" readonly>
      </div>
      <div class="input-group">
        <label for="viewEventDescription">Description:</label>
        <textarea id="viewEventDescription" name="viewEventDescription" readonly></textarea>
      </div>
      <!-- Event Image -->
<img id="viewEventImage" alt="Event Image" 
    style="width: 100%; max-width: 200px; height: 250px; border-radius: 8px; object-fit: cover; margin: 10px 0; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
      <div class="input-group">
        <label>Type of Event:</label>
        <label><input type="radio" name="viewEventType" value="Theatre" disabled> Theatre</label>
        <label><input type="radio" name="viewEventType" value="Concert" disabled> Concert</label>
        <label><input type="radio" name="viewEventType" value="Exhibition" disabled> Exhibition</label>
      </div>

      <div id="viewEventTickets"></div>


      <!-- Location Section -->
      <h3>Location</h3>
      <div class="input-group">
        <label for="viewVenue">Venue Name:</label>
        <input type="text" id="viewVenue" name="viewVenue" readonly>
      </div>
      <div class="input-group">
        <label for="viewAddress">Address:</label>
        <input type="text" id="viewAddress" name="viewAddress" readonly>
      </div>
      <div class="input-group">
        <label for="viewVenueMapLink">Google Maps Link:</label>
        <input type="text" id="viewVenueMapLink" name="viewVenueMapLink" readonly>
      </div>
<!-- Venue Image -->
<img id="viewVenueImage" alt="Venue Image" 
    style="width: 100%; max-width: 200px; height: 250px; border-radius: 8px; object-fit: cover; margin: 10px 0; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
      <!-- Date and Time Section -->
      <h3>Date and Time</h3>
      <div class="input-group">
        <label for="viewStartDate">Start Date:</label>
        <input type="datetime-local" id="viewStartDate" name="viewStartDate" readonly>
      </div>
      <div class="input-group">
        <label for="viewEndDate">End Date (optional):</label>
        <input type="datetime-local" id="viewEndDate" name="viewEndDate" readonly>
      </div>

      <!-- Organizer Section -->
      <h3>Organizer Details</h3>
      <div class="input-group">
        <label for="viewOrganizerName">Organizer Name:</label>
        <input type="text" id="viewOrganizerName" name="viewOrganizerName" readonly>
      </div>
      <img id="viewOrganizerLogo" alt="Organizer Logo" 
      style="width: 100%; max-width: 200px; height: 250px; border-radius: 8px; object-fit: cover; margin: 10px 0; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
      <!-- Venue Facilities -->
      <h3>Venue Facilities</h3>
      <div class="input-group">
        <label>Facilities Available:</label>
        <label><input type="checkbox" name="viewVenueFacilities[]" value="Bathrooms" disabled> Bathrooms</label>
        <label><input type="checkbox" name="viewVenueFacilities[]" value="Food Services" disabled> Food Services</label>
        <label><input type="checkbox" name="viewVenueFacilities[]" value="Parking" disabled> Parking</label>
        <label><input type="checkbox" name="viewVenueFacilities[]" value="Security" disabled> Security</label>
      </div>
      <div class="input-group">
        <label for="viewVenueProfileLink">Venue Profile Link:</label>
        <input type="text" id="viewVenueProfileLink" name="viewVenueProfileLink" readonly>
      </div>

    <!-- Event Status Section -->
<h3>Status</h3>
<div class="input-group" style="margin-top: 20px; margin-bottom: 20px;">
  <label for="viewEventStatus" 
         style="display: block; font-weight: bold; margin-bottom: 5px; font-size: 16px; color: #333;">
    Change Status:
  </label>
  <select id="viewEventStatus" name="viewEventStatus" 
          style="width: 100%; max-width: 300px; padding: 10px; font-size: 14px; border: 1px solid #ccc; 
                 border-radius: 8px; background-color: #f9f9f9; transition: all 0.3s ease; 
                 box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);"
          onmouseover="this.style.backgroundColor='#fff'; this.style.borderColor='#007bff'; 
                       this.style.boxShadow='0 4px 8px rgba(0, 123, 255, 0.2)';"
          onmouseout="this.style.backgroundColor='#f9f9f9'; this.style.borderColor='#ccc'; 
                      this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)';"
          onfocus="this.style.outline='none'; this.style.borderColor='#0056b3'; 
                   this.style.boxShadow='0 4px 10px rgba(0, 86, 179, 0.3)';">
    <option value="Pending">Pending</option>
    <option value="Accepted">Accepted</option>
    <option value="Rejected">Rejected</option>
    <!-- Add other options as necessary -->
  </select>
</div>
        <!-- Submit Button -->
  <div class="input-group">
    <button type="submit" name="updateStatus">Update Status</button>
  </div>
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
            <th>Links</th> <!-- New Status Column -->

            <th>Status</th> <!-- New Status Column -->

            <th>Actions</th>
           
        </tr>
    </thead>
    <tbody id="eventsTableBody">
        <?php foreach ($events as $event): ?>

         <?php
                // Determine the class based on the event's status
                $statusClass = '';
                $statusText = '';
                if ($event['status'] == 'Pending') {
                    $statusClass = 'pending-status';  // Yellow
                    $statusText = 'Pending';
                } elseif ($event['status'] == 'Rejected') {
                    $statusClass = 'rejected-status';  // Red
                    $statusText = 'Rejectedss';
                } elseif ($event['status'] == 'Accepted') {
                    $statusClass = 'accepted-status';  // Green
                    $statusText = 'Accepted';
                }
     ?>
           
    
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
                <td class="status-column">
                    <?php 
                        // Define inline styles for the status badge
                        $statusStyle = '';
                        $statusText = '';
                        if ($event['status'] == 'Pending') {
                            $statusStyle = 'background-color:rgb(159, 150, 71); color: white; padding: 5px 10px; border-radius: 5px;';
                            $statusText = 'Pending';
                        } elseif ($event['status'] == 'Rejected') {
                            $statusStyle = 'background-color: #f44336; color: white; padding: 5px 10px; border-radius: 5px;';
                            $statusText = 'Rejected';
                        } elseif ($event['status'] == 'Accepted') {
                            $statusStyle = 'background-color: #4caf50; color: white; padding: 5px 10px; border-radius: 5px;';
                            $statusText = 'Accepted';
                        } else {
                            $statusStyle = 'background-color: #9e9e9e; color: white; padding: 5px 10px; border-radius: 5px;';
                            $statusText = 'Unknown';
                        }
                    ?>
                    <span style="<?php echo $statusStyle; ?>"><?php echo $statusText; ?></span>
                </td>
                <td class="action-icons">
                    <?php
                        $eventData = $event;
                        unset($eventData['Image']);
                        unset($eventData['Venue_Image']);
                        unset($eventData['Organizer_Logo']);
                        $eventData['Image'] = base64_encode($event['Image']);
                        $eventData['Venue_Image'] = base64_encode($event['Venue_Image']);
                        $eventData['Organizer_Logo'] = base64_encode($event['Organizer_Logo']);

                    ?>
                    <button class="edit-btn" onclick='openViewEventModal(<?php echo json_encode($eventData); ?>)'>
                        <i class="fas fa-edit"></i>
                    </button>
                    
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>

        <!-- User Management Page -->
        <div id="users-page" class="page hidden">
            <header>
                <h1>User Management</h1>
            </header>

            <!-- User Modal -->
            <div id="adduserModal" class="modal-overlay">
            <div class="modal-content">
                <span class="modal-close">&times;</span>
                <h2>Add New User</h2>
                <form id="userForm" onsubmit="return handleAddUser(event)">
                    <div class="input-group">
                        <input type="text" id="username" name="username" placeholder="Username" required>
                        <div class="error-message" id="usernameError"></div>
                    </div>
                    <div class="input-group">
                        <input type="email" id="email" name="email" placeholder="Email" required>
                        <div class="error-message" id="emailError"></div>
                    </div>
                    <div class="input-group">
                        <input type="password" id="password" name="password" placeholder="Password" required>
                        <div class="error-message" id="passwordError"></div>
                    </div>
                    <button type="submit">Submit</button>
                </form>
            </div>
        </div>

<!-- Edit User Modal -->
<div id="editUserModal" class="modal-overlay">
            <div class="modal-content">
                <span class="modal-close">&times;</span>
                <h2>Edit User</h2>
                <form id="editUserForm" onsubmit="return handleEditUser(event)">
                    <input type="hidden" id="editUserId">
                    <div class="input-group">
                        <input type="text" id="editUsername" name="username" placeholder="Username" required>
                        <div class="error-message" id="editUsernameError"></div>
                    </div>
                    <div class="input-group">
                        <input type="email" id="editEmail" name="email" placeholder="Email" required>
                        <div class="error-message" id="editEmailError"></div>
                    </div>
                    <div class="input-group">
                        <input type="password" id="editPassword" name="password" placeholder="Password" required>
                        <div class="error-message" id="editPasswordError"></div>
                    </div>
                    <button type="submit">Submit</button>
                </form>
            </div>
        </div>

            <!-- Users Table -->
            <h2>Users List</h2>
             <table class="common-table-style users-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                   
                    <th> <button class="buttonadd" id="addUserBtn">Add User</button></th>
                </tr>
            </thead>
            <tbody id="usersTableBody">
            </tbody>
        </table>
    </div>
     <!-- Chat Page -->
 <div id="chat-page" class="page hidden">
        <header>
          <h1>Customer Support Tickets</h1>
        </header>

        <!-- Ticket Controls -->
        <div class="ticket-controls">
          <input type="text" placeholder="Search tickets..." />
          <select id="ticket-status-filter" onchange="filterTicketsByStatus()">
            <option value="all">All</option>
            <option value="open">Open</option>
            <option value="pending">Pending</option>
            <option value="resolved">Resolved</option>
          </select>
        </div>

        <!-- Ticket List -->
        <div class="ticket-list" id="ticketList">
          <!-- Dynamically filled ticket items -->
        </div>
      </div>

      <!-- Modal for Chat/Ticket -->
      <div id="ticketModal" class="modal-overlay">
        <div class="modal-content">
          <!-- Exit Button to close modal -->
          <button class="exit-button" onclick="closeModal()">Exit</button>

          <div id="ticket-info">
            <!-- Ticket Information goes here -->
          </div>

          <!-- Chat Box -->
          <div class="chat-box" id="chatBox"></div>

          <!-- Chat Input -->
          <div class="chat-input-container">
            <textarea
              id="adminMessage"
              placeholder="Type your message here..."
            ></textarea>
            <button onclick="sendMessage()">Send</button>
          </div>

          <!-- Ticket Status -->
          <div class="ticket-status-container">
            <label for="ticketStatus">Update Status:</label>
            <select id="ticketStatus" onchange="onStatusChange()">
              <option value="open">Open</option>
              <option value="pending">Pending</option>
              <option value="resolved">Resolved</option>
            </select>
            <button id="confirmButton" onclick="confirmStatusUpdate()" disabled>
              Confirm
            </button>
          </div>
        </div>
</div>
    </div>
   

    <script>







function openViewEventModal(eventData) {
    console.log("Event data:", eventData); // Log the raw data passed to the function
    console.log("Status from event data:", eventData.status); // Debugging


    try {
        // Ensure the eventData object is populated with the expected keys
        document.getElementById('viewEventId').value = eventData.Event_ID || '';
        document.getElementById('viewEventName').value = eventData.Name || '';
        document.getElementById('viewEventDescription').value = eventData.Description || '';

        // Set radio buttons for event type (Category)
        const eventTypeRadios = document.getElementsByName('viewEventType');
        eventTypeRadios.forEach((radio) => {
            if (radio.value === eventData.Category) {
                radio.checked = true;
            }
        });

        // Populate the venue fields
        document.getElementById('viewVenue').value = eventData.Venue || '';
        document.getElementById('viewAddress').value = eventData.Address || '';
        document.getElementById('viewVenueMapLink').value = eventData.Venue_Map_Link || '';
        document.getElementById('viewStartDate').value = eventData.Start_Date || '';
        document.getElementById('viewEndDate').value = eventData.End_Date || '';
        
        // Organizer details
        document.getElementById('viewOrganizerName').value = eventData.Organizer_Name || '';

        // Check and populate the venue facilities if they exist
        const venueFacilities = eventData.Venue_Facilities ? eventData.Venue_Facilities.split(', ') : [];
        const facilityCheckboxes = document.getElementsByName('viewVenueFacilities[]');
        facilityCheckboxes.forEach(function(checkbox) {
            checkbox.checked = venueFacilities.includes(checkbox.value);
        });

        // Set the Venue Profile Link (if available)
        document.getElementById('viewVenueProfileLink').value = eventData.Venue_Profile_Link || '';
        const statusDropdown = document.getElementById('viewEventStatus');
        if (statusDropdown) {
            statusDropdown.value = eventData.status || 'Pending'; // Default to 'Pending' if no status is found
        }
        // Display event image from blob
        
        const eventImageBase64 = eventData.Image;  // Base64-encoded image
        const venueImageBase64 = eventData.Venue_Image;  // Base64-encoded image
        const organizerLogoBase64 = eventData.Organizer_Logo;  // Base64-encoded organizer logo

const eventImage = document.getElementById('viewEventImage');
if (eventImageBase64) {
    eventImage.src = `data:image/jpeg;base64,${eventImageBase64}`;
} else {
    eventImage.src = 'placeholder.jpg';  // Fallback to a placeholder
}

const venueImage = document.getElementById('viewVenueImage');
if (venueImageBase64) {
    venueImage.src = `data:image/jpeg;base64,${venueImageBase64}`;
} else {
    venueImage.src = 'placeholder.jpg';  // Fallback to a placeholder
}

const organizerLogo = document.getElementById('viewOrganizerLogo');
if (organizerLogoBase64) {
    organizerLogo.src = `data:image/jpeg;base64,${organizerLogoBase64}`;
} else {
    organizerLogo.src = 'placeholder.jpg';  // Fallback to a placeholder
}
   
   

       // Show available tickets (if any)
       const ticketsSection = document.getElementById('viewEventTickets');
        if (eventData.tickets && eventData.tickets.length > 0) {
            // Clear any previous ticket data
            ticketsSection.innerHTML = '';

            // Loop through the tickets array and create HTML for each ticket
            eventData.tickets.forEach(ticket => {
                console.log("Ticket Data:", ticket); // Debugging each ticket's structure
                const ticketElement = document.createElement('div');
                ticketElement.classList.add('ticket-item');

                // Check if properties exist and display them
                const ticketType = ticket.Category || "N/A";
                const ticketPrice = ticket.Price !== undefined ? `$${ticket.Price}` : "N/A";
                const ticketAvailableQuantity = ticket.Available !== undefined ? ticket.Available : "N/A";

                ticketElement.innerHTML = `
                    <p><strong>Ticket Type:</strong> ${ticketType}</p>
                    <p><strong>Price:</strong> ${ticketPrice}</p>
                    <p><strong>Available Quantity:</strong> ${ticketAvailableQuantity}</p>
                `;
                ticketsSection.appendChild(ticketElement);
            });
        } else {
            ticketsSection.innerHTML = '<p>No tickets available.</p>';
        }
        // Show the modal if needed
        document.getElementById('viewEventModal').style.display = 'block';
    } catch (error) {
        console.error("Error parsing event data:", error);
    }
    openModal('viewEventModal');
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
    const eventStatus = document.getElementById('editEventStatus');
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
    if (!eventStatus.value) {
        setError(eventStatus, document.getElementById('editEventStatusError'), errors.eventStatus);
        isValid = false;
    }

    return isValid;
}





function deleteEvent(eventId) {
    // Confirm the deletion
    if (confirm("Are you sure you want to delete this event?")) {
        // Remove the event from the events array (assuming you have an array called `events`)
        const eventIndex = events.findIndex(event => event.id === eventId);
        if (eventIndex !== -1) {
            events.splice(eventIndex, 1); // Remove the event

            // Update the UI
            renderEvents(); // Assuming you have a function that re-renders the event list
            
        } else {
            alert("Event not found.");
        }
    }
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







        // Setup modal events
        function setupModals() {
            const modals = ['adduserModal', 'editUserModal','changeEventStatusModal','viewEventModal'];
            
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
    


        function openModal(modalId) {
    
    const modal = document.getElementById(modalId);
    modal.classList.add('active');
    modal.style.display = 'flex'; // Ensure the modal is displayed as flex for centering
    document.body.classList.add('no-scroll'); // Disable background scrolling
}

function closeModals(modalId) {
 
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
     
    
  </body>
</html>
