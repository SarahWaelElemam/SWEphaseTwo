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
      // require 'path/to/database/connection.php';
    ?>

    <!-- Sidebar -->
    <div class="sidebar">
      <div class="logo">
        <h2>Admin.</h2>
      </div>
      <ul>
        <li><a href="#" class="active" data-page="dashboard">Dashboard</a></li>
        <li><a href="#" data-page="chat">Chat</a></li>
        <li><a href="#" data-page="calendar">Calendar</a></li>
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
        <form id="eventForm" onsubmit="return handleAddEvent(event)">

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
                    <option value="Upcoming">Upcoming</option>
                    <option value="Ongoing">Ongoing</option>
                    <option value="Completed">Completed</option>
                </select>
                <div class="error-message" id="eventStatusError"></div>
            </div>

            <!-- Venue Facilities Section -->
            <h3>Venue Facilities</h3>
            <div class="input-group">
                <label>Facilities Available:</label>
                <label><input type="checkbox" name="venueFacilities" value="Bathrooms"> Bathrooms</label>
                <label><input type="checkbox" name="venueFacilities" value="Food Services"> Food Services</label>
                <label><input type="checkbox" name="venueFacilities" value="Parking"> Parking</label>
                <label><input type="checkbox" name="venueFacilities" value="Security"> Security</label>
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
        <form id="editEventForm" onsubmit="return handleEditEvent(event)">

            <!-- Event Details Section -->
            <h3>Event Details</h3>
            <input type="hidden" id="editEventId">
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
            <h3>Status</h3>
            <div class="input-group">
                <label for="editEventStatus">Event Status:</label>
                <select id="editEventStatus" name="editEventStatus">
                    <option value="Upcoming">Upcoming</option>
                    <option value="Ongoing">Ongoing</option>
                    <option value="Completed">Completed</option>
                </select>
                <div class="error-message" id="editEventStatusError"></div>
            </div>

            <!-- Venue Facilities Section -->
            <h3>Venue Facilities</h3>
            <div class="input-group">
                <label>Facilities Available:</label>
                <label><input type="checkbox" name="editVenueFacilities" value="Bathrooms"> Bathrooms</label>
                <label><input type="checkbox" name="editVenueFacilities" value="Food Services"> Food Services</label>
                <label><input type="checkbox" name="editVenueFacilities" value="Parking"> Parking</label>
                <label><input type="checkbox" name="editVenueFacilities" value="Security"> Security</label>
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
        <th>ID</th>
        <th>Event Name</th>
        <th>Date</th>
        <th>Address</th>
        <th>Event Type</th>
        <th>Organizer</th>
        <th>Status</th>
        <th>Event Image</th>
        <th>Organizer Logo</th>
        <th>Venue Image</th>
            
           
                <th><button class="buttonadd" id="addEventBtn">Add Event</button></th>
            </tr>
        </thead>
        <tbody id="eventsTableBody">
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
    </div>

    <script>

const events = [
    {
        id: 1,
        eventName: "Tech Conference 2024",
        eventDescription: "A conference for tech enthusiasts to share knowledge and network.",
        startDate: "2024-05-15T10:00:00Z",
        eventType: "Theatre",
        address: "Convention Center, Cityville",
        venue: "City Convention Center",
        eventImage: "../images/eventMemo.png", // Placeholder for an actual image file
        organizer: {
            name: "Tech Innovations Inc.",
            logo: "../images/organizerslogo.png" // Placeholder for an actual logo file
        },
        ticketPrice: [ // Changed to an array of ticket objects
            { type: "General Admission", price: 100, currency: "EGP" },
            { type: "VIP Admission", price: 200, currency: "EGP" }],
        eventStatus: "Upcoming",
        attendees: [],
        venueDetails: {
            facilities: ["Bathrooms", "Food Services", "Parking"],
            links: {
                venueProfile: "http://venueprofile.com",
                maps: "http://mapslink.com"
            },
            venueImage: "../images/Venue.png"// Placeholder for an actual venue image file
        }
    },
    {
        id: 2,
        eventName: "Art Exhibition 2024",
        eventDescription: "An exhibition showcasing contemporary art from local artists.",
        startDate: "2024-06-20T18:00:00Z",
        eventType: "Exhibition",
        address: "Gallery XYZ, Art District",
        venue: "Gallery XYZ",
        eventImage: "../images/eventsoundandlightjpg.jpg", // Placeholder for an actual image file
        organizer: {
            name: "Art Community",
            logo: "../images/soundandlightorglogo.png" // Placeholder for an actual logo file
        },
        ticketPrice: [ // Changed to an array of ticket objects
            { type: "General Admission", price: 100, currency: "EGP" },
            { type: "VIP Admission", price: 200, currency: "EGP" }],
        eventStatus: "Upcoming",
        attendees: [],
        venueDetails: {
            facilities: ["Restrooms", "Café", "Gift Shop"],
            links: {
                venueProfile: "http://galleryxyz.com",
                maps: "http://gallerymap.com"
            },
            venueImage: "../images/eventsoundandlightjpg.jpg", // Placeholder for an actual venue image file
        }
    }
   
];

// Function to render events table with all attributes 
function renderEvents() {
    const tbody = document.getElementById('eventsTableBody');
    tbody.innerHTML = '';

    events.forEach(event => {
        const tr = document.createElement('tr');
        


        tr.innerHTML = `
            <td>${event.id}</td>
            <td>${event.eventName}</td>
           ${new Date(event.startDate).toLocaleString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        hour12: true,
        timeZone: 'UTC'  // Set timezone to UTC
    })}</td> <!-- Formatting the date -->
            <td>${event.address}</td>
            <td>${event.eventType}</td>
            <td>${event.organizer.name}</td>
            <td>${event.eventStatus}</td>
            <td>
                <img src="${event.eventImage}" alt="${event.eventName} image" class="event-image" />
            </td>
            <td>
                <img src="${event.organizer.logo}" alt="${event.organizer.name} image" class="event-image">
            </td>
             <td>
                <img src="${event.venueDetails.venueImage}" alt=${event.eventName} image" class="event-image">
            </td>
            <td class="action-icons">
                <button class="edit-btn" onclick="openEditEventModal(${event.id})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="delete-icon" onclick="deleteEvent(${event.id})">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

function handleAddEvent(event) {
    event.preventDefault();
    console.log("here in add event");
    
    if (!validateEventForm()) return;
    console.log("here after validation event");

    // Check if elements exist before accessing their values
    const eventNameElement = document.getElementById('eventName');
    const eventDescriptionElement = document.getElementById('eventDescription');
    const startDateElement = document.getElementById('startDate');
   const addressElement = document.getElementById('address')
    const venueElement = document.getElementById('venue');
    const organizerNameElement = document.getElementById('organizerName');
    const eventImageElement = document.getElementById('eventImage');
    const organizerLogoElement = document.getElementById('organizerLogo');
    const venueImageElement = document.getElementById('venueImage');
    const eventStatusElement = document.getElementById('eventStatus');
    
    // Check for null elements and log errors
    if (!eventNameElement || !eventDescriptionElement || !startDateElement || 
         !venueElement || !organizerNameElement || 
        !eventImageElement || !organizerLogoElement || 
        !venueImageElement || !eventStatusElement) {
        
        console.error("One or more elements are null, please check your IDs");
        
        if (!eventNameElement) console.error("eventName is null");
        if (!eventDescriptionElement) console.error("eventDescription is null");
        if (!startDateElement) console.error("startDate is null");
        if (!addressElement){

            console.error("address not defined");
        }
        if (!venueElement) console.error("venue is null");
        if (!organizerNameElement) console.error("organizerName is null");
        if (!eventImageElement) console.error("eventImage is null");
        if (!organizerLogoElement) console.error("organizerLogo is null");
        if (!venueImageElement) console.error("venueImage is null");
        if (!eventStatusElement) console.error("eventStatus is null");

        return;
    }

     // Create the new event object
     const newEvent = {
        id: events.length + 1,
        eventName: eventNameElement.value,
        eventDescription: eventDescriptionElement.value,
        startDate: startDateElement.value,
        eventType: document.querySelector('input[name="eventType"]:checked').value,
        address: addressElement.value,
        venue: venueElement.value,
        eventImage: eventImageElement.files[0] ? URL.createObjectURL(eventImageElement.files[0]) : "", // Convert file to URL
        organizer: {
            name: organizerNameElement.value,
            logo: organizerLogoElement.files[0] ? URL.createObjectURL(organizerLogoElement.files[0]) : "", // Convert file to URL
        },
        ticketPrice: gatherTicketPrices(),
        eventStatus: eventStatusElement.value,
        attendees: [],
        venueDetails: {
            facilities: Array.from(document.querySelectorAll('input[name="venueFacilities"]:checked')).map(cb => cb.value),
            links: {
                venueProfile: document.getElementById('venueProfileLink').value,
                maps: document.getElementById('venueMapLink').value
            },
            venueImage: venueImageElement.files[0] ? URL.createObjectURL(venueImageElement.files[0]) : "" // Convert file to URL
        }
    };

    events.push(newEvent);
    renderEvents();
    closeModals('addEventModal');
    document.getElementById('eventForm').reset();
}

function handleEditEvent(event) {
    event.preventDefault();
    console.log("Editing event");
// Validate the form inputs
if (!validateEditEventForm()) {
        console.error("Validation failed.");
        return; // Stop execution if validation fails
    }

    // Get the event ID from the hidden input
    const eventId = parseInt(document.getElementById('editEventId').value, 10);
    console.log("Event ID to edit:", eventId);

    // Find the event to edit
    const existingEventIndex = events.findIndex(e => e.id === eventId);
    if (existingEventIndex === -1) {
        console.error("Event not found!");
        return;
    }

    const existingEvent = events[existingEventIndex];

    // Check if the existing event and its organizer are defined
    if (!existingEvent || !existingEvent.organizer) {
        console.error("Event or Organizer is not defined!");
        return;
    }

    // Proceed with editing...
    console.log("Editing event:", existingEvent);

    // Access the organizer logo element
    const editOrganizerLogoElement = document.getElementById('editOrganizerLogo');
    const updatedOrganizerLogo = editOrganizerLogoElement.files[0]
        ? URL.createObjectURL(editOrganizerLogoElement.files[0]) // New image selected
        : existingEvent.organizer.logo; // Use existingEvent instead of event

    // Similarly handle the event image
    const editEventImageElement = document.getElementById('editEventImage');
    const updatedEventImage = editEventImageElement.files[0]
        ? URL.createObjectURL(editEventImageElement.files[0]) // New image selected
        : existingEvent.eventImage; // Use existingEvent instead of event



        // Access the venue image element
        const editVenueImageElement = document.getElementById('editVenueImage'); // Use 'editVenueImage' instead of 'venueImage'
        const updatedVenueImage = editVenueImageElement.files[0]
        ? URL.createObjectURL(editVenueImageElement.files[0]) // New image selected
        : existingEvent.venueDetails.venueImage;

    // Check for null elements and log errors
    const editEventNameElement = document.getElementById('editEventName');
    const editEventDescriptionElement = document.getElementById('editEventDescription');
    const editStartDateElement = document.getElementById('editStartDate');
    const editVenueElement = document.getElementById('editVenue');
    const editAddressElement = document.getElementById('editAddress');
    const editOrganizerNameElement = document.getElementById('editOrganizerName');
    const editEventStatusElement = document.getElementById('editEventStatus');

    // Check for null elements and log errors
    if (!editEventNameElement || !editEventDescriptionElement || 
        !editStartDateElement || !editVenueElement || 
        !editOrganizerNameElement || !editAddressElement || 
        !editEventStatusElement || !updatedEventImage || !updatedOrganizerLogo) {
        
        console.error("One or more elements are null, please check your IDs");
        return;
    }

    // Gather updated event details
    const updatedEvent = {
        id: eventId, // Keep the same ID
        eventName: editEventNameElement.value,
        eventDescription: editEventDescriptionElement.value,
        startDate: editStartDateElement.value,
        eventType: document.querySelector('input[name="editEventType"]:checked').value,
        address: editAddressElement.value,
        venue: editVenueElement.value,
        eventImage: updatedEventImage,
        organizer: {
            name: editOrganizerNameElement.value,
            logo: updatedOrganizerLogo // Use updatedOrganizerLogo
        },
        ticketPrice: editgatherTicketPrices(), // Pass the ID of the ticket rows container
        eventStatus: editEventStatusElement.value,
        attendees: [], // Keep the attendees as-is or adjust as necessary
        venueDetails: {
            facilities: Array.from(document.querySelectorAll('input[name="editVenueFacilities"]:checked')).map(cb => cb.value),
            links: {
                venueProfile: document.getElementById('editVenueProfileLink').value,
                maps: document.getElementById('editVenueMapLink').value
            },
            venueImage: updatedVenueImage , // Convert file to URL
        }
    };

    // Update the event in the array
    events[existingEventIndex] = updatedEvent;
    console.log("Updated Event:", updatedEvent);

    // Refresh the event list and close modal
    renderEvents();
    closeModals('editEventModal');
    document.getElementById('editEventForm').reset();
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


function openEditEventModal(id) {
    const event = events.find(event => event.id === id);
    if (event) {
        // Populate basic event details
        resetEditInputStyles();
        document.getElementById('editEventId').value = event.id;
        
        document.getElementById('editEventName').value = event.eventName;
        document.getElementById('editEventDescription').value = event.eventDescription;

      // Set the radio button for event type
    const eventTypeRadios = document.getElementsByName('editEventType');
    eventTypeRadios.forEach(radio => {
        console.log(radio.value, event.eventType);
        if (radio.value === event.eventType) {
           
            radio.checked = true;
        }
    });

        // Set event date and time
 // Set the start date and convert to local time
    const startDateUTC = new Date(event.startDate); // Parse the UTC date
    const localStartDate = startDateUTC.toISOString().slice(0, 16); // Convert to local format
    document.getElementById('editStartDate').value = localStartDate;

        // Location details
        document.getElementById('editVenue').value = event.venue;
        document.getElementById('editAddress').value = event.address;
        document.getElementById('editVenueMapLink').value = event.venueDetails.links.maps || '';

        // Organizer details
        document.getElementById('editOrganizerName').value = event.organizer.name || '';

        // Clear and populate ticket prices
        const ticketContainer = document.getElementById('editTicketRows');
        ticketContainer.innerHTML = ''; // Clear previous entriesfdsggfdsfgd
        console.log(event.ticketPrice);
        // Check if ticketPrice is an array; if not, initialize it
      const ticketPrices = event.ticketPrice ?? [];
        console.log("ticketPrices : ",ticketPrices);
        ticketPrices.forEach(ticket => {
            console.log("hello");
            const row = document.createElement('div');
            row.className = 'ticket-row'; // Ensure we have a class for styles
            row.innerHTML = `
                <input type="text" value="${ticket.type}" placeholder="Ticket Type (e.g., VIP)" class="ticket-type" required />
                <input type="number" value="${ticket.price}" placeholder="Ticket Price" min="0" class="ticket-price" required />
                <button type="button" class="remove-ticket" onclick="removeTicketRow(this)">Remove</button>
            `;
            ticketContainer.appendChild(row);
        });

        // Set event status
        document.getElementById('editEventStatus').value = event.eventStatus;
        document.getElementById('editVenueProfileLink').value = event.venueDetails.links.venueProfile;
        // Set venue facilities checkboxes
        document.querySelectorAll("input[name='editVenueFacilities']").forEach(checkbox => {
            checkbox.checked = event.venueDetails.facilities.includes(checkbox.value);
        });

    // Store existing images for future reference
 /*        currentOrganizerLogo = event.organizer.logo || '';
        currentEventImage = event.eventImage || ''; */

        openModal('editEventModal');
    }
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


  // Initialize users array with some sample data
  let users = [
            { id: 1, username: 'john_doe', email: 'john@example.com', password: 'password123' },
            { id: 2, username: 'jane_doe', email: 'jane@example.com', password: 'password456' }
        ];

        // Function to render users table
        function renderUsers() {
            const tbody = document.getElementById('usersTableBody');
            tbody.innerHTML = '';
            
            users.forEach(user => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${user.id}</td>
                    <td>${user.username}</td>
                    <td>${user.email}</td>
                    <td class="action-icons">
                        <button class="edit-btn" onclick="openEditModal(${user.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="delete-icon" onclick="deleteUser(${user.id})">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }


        
        // Function to handle adding new user
        function handleAddUser(event) {
            event.preventDefault();
            
            const username = document.getElementById('username').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            if (validateUserForm()) {
                const newUser = {
                    id: users.length + 1,
                    username,
                    email,
                    password
                };

                users.push(newUser);
                renderUsers();
                closeModals('adduserModal');
                document.getElementById('userForm').reset();
            }
            return false;
        }




        // Setup modal events
        function setupModals() {
            const modals = ['adduserModal', 'editUserModal','addEventModal','editEventModal'];
            
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
            renderUsers();
            renderEvents();
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
    document.getElementById(modalId).classList.add('active');
    document.body.classList.add('no-scroll'); // Disable background scrolling
}

function closeModals(modalId) {
    document.getElementById(modalId).classList.remove('active');
    document.body.classList.remove('no-scroll'); // Enable background scrolling
}


 // Function to handle editing user
        function handleEditUser(event) {
            event.preventDefault();
            
            const userId = parseInt(document.getElementById('editUserId').value);
            const username = document.getElementById('editUsername').value;
            const email = document.getElementById('editEmail').value;
            const password = document.getElementById('editPassword').value;

            if (validateEditUserForm()) {
                const userIndex = users.findIndex(user => user.id === userId);
                if (userIndex !== -1) {
                    users[userIndex] = { ...users[userIndex], username, email, password };
                    renderUsers();
                    closeModal('editUserModal');
                }
            }
            return false;
        }

        // Function to delete user
        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                users = users.filter(user => user.id !== id);
                renderUsers();
            }
        }


// Function to validate user form inputs
  function validateUserForm() {
    const username = document.getElementById('username');
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    const usernameError = document.getElementById('usernameError');
    const emailError = document.getElementById('emailError');
    const passwordError = document.getElementById('passwordError');

    // Clear previous error messages and icons
    resetInputStylesuser();

    let isValid = true; // Flag to track overall form validity

    // Basic validation checks
    if (username.value.length < 3) {
        setError(username, usernameError, 'Username must be at least 3 characters long.');
        isValid = false;
    }

    const emailPattern = /^[^\s@]+@[^\s@]+\.[a-z]{2,3}$/i; // Case insensitive check
    if (!email.value.match(emailPattern)) {
        setError(email, emailError, 'Please enter a valid email address.');
        isValid = false;
    }

    if (password.value.length < 6) {
        setError(password, passwordError, 'Password must be at least 6 characters long.');
        isValid = false;
    }

    // If all checks pass, return true to allow form submission
    return isValid;
}

// Function to display error message and style the input
function setError(input, errorElement, message) {
    errorElement.textContent = message; // Set error message
    errorElement.style.display = 'block'; // Show error message
    input.classList.add('input-error'); // Add error class for styling
}

// Function to reset input styles
function resetInputStylesuser() {
    const inputs = document.querySelectorAll('#userForm input');
    const errorMessages = document.querySelectorAll('.error-message');

    inputs.forEach(input => {
        input.classList.remove('input-error'); // Remove error class
    });

    errorMessages.forEach(msg => {
        msg.style.display = 'none'; // Hide error messages
    });

    
}



function validateEditUserForm() {
        const editUsername = document.getElementById('editUsername');
        const editEmail = document.getElementById('editEmail');
        const editPassword = document.getElementById('editPassword');
        const editUsernameError = document.getElementById('editUsernameError');
        const editEmailError = document.getElementById('editEmailError');
        const editPasswordError = document.getElementById('editPasswordError');

        // Clear previous error messages and icons
        resetEditFormInputStyles();

        let isValid = true; // Flag to track overall form validity

        // Validation for username
        if (editUsername.value.length < 3) {
            setEditFormError(editUsername, editUsernameError, 'Username must be at least 3 characters long.');
            isValid = false;
        }

        // Validation for email
        const emailPattern = /^[^\s@]+@[^\s@]+\.[a-z]{2,3}$/i; // Basic email regex
        if (!editEmail.value.match(emailPattern)) {
            setEditFormError(editEmail, editEmailError, 'Please enter a valid email address.');
            isValid = false;
        }

        // Validation for password
        if (editPassword.value.length < 6) {
            setEditFormError(editPassword, editPasswordError, 'Password must be at least 6 characters long.');
            isValid = false;
        }

        // If all checks pass, return true to allow form submission
        return isValid;
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
     
    
  </body>
</html>
