<?php

require_once("../../model/Model.php");
require_once("../../model/Ticket.php");  // Include the Ticket model

class Organizer
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

  
    public function sendEventRequest(
        $name,
        $description,
        $category,
        $createdBy,
        $organizerName,
        $startDate,
        $endDate,
        $venue,
        $address,
        $venueMapLink,
        $venueFacilities,
        $venueProfileLink,
        $eventImage = null,
        $venueImage = null,
        $organizerLogo = null,
        $ticketCategories = [],  // Add ticket data
        $ticketPrices = [],
        $ticketQuantities = []
      ) {
        // First, insert the event
        $sql = "INSERT INTO events (
                    name, description, category, created_by, organizer_name, start_date, end_date, 
                    venue, address, venue_map_link, venue_facilities, venue_profile_link, 
                    Image, venue_image, organizer_logo, status
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending'
                )";
    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $name,
            $description,
            $category,
            $createdBy,
            $organizerName,
            $startDate,
            $endDate,
            $venue,
            $address,
            $venueMapLink,
            $venueFacilities,
            $venueProfileLink,
            $eventImage,
            $venueImage,
            $organizerLogo
        ]);
    
        // Get the event ID of the newly inserted event
        $eventID = $this->conn->insert_id;
    
        // Insert tickets related to the event if any ticket data is provided
        if (!empty($ticketCategories) && !empty($ticketPrices) && !empty($ticketQuantities)) {
            $ticketModel = new Ticket($this->conn);  // Create an instance of the Ticket model
            
            foreach ($ticketCategories as $index => $ticketCategory) {
                $ticketPrice = $ticketPrices[$index];
                $ticketQuantity = $ticketQuantities[$index];
                $availableTickets = $ticketQuantity; // Initially, all tickets are available
    
                // Insert each ticket
                $ticketModel->insertTicket($eventID, $ticketCategory, $ticketPrice, $ticketQuantity, $availableTickets);
            }
        }
    
        // Return true after both event and tickets have been inserted
        return true;  // Event and tickets inserted successfully
    }
    
 public function getAllEventsByOrganizerId($organizerId)
    {
    // First query to get events by organizer ID
    $sql = "SELECT * FROM Events WHERE created_by = ? ORDER BY created_at DESC";
    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        error_log("Prepare failed: " . $this->conn->error);
        return [];
    }

    $stmt->bind_param("i", $organizerId);
    if (!$stmt->execute()) {
        error_log("Execute failed: " . $stmt->error);
        return [];
    }

    $result = $stmt->get_result();
    $events = [];
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
    $stmt->close();

    // Second query to get tickets for each event
    $tickets = [];
    $ticketSql = "SELECT * FROM tickets";
    $ticketStmt = $this->conn->prepare($ticketSql);
    $ticketStmt->execute();
    $ticketResult = $ticketStmt->get_result();

    while ($ticketRow = $ticketResult->fetch_assoc()) {
        $tickets[$ticketRow['Event_ID']][] = $ticketRow;
    }

    // Add tickets data to events
    foreach ($events as &$event) {
        $event['tickets'] = isset($tickets[$event['Event_ID']]) ? $tickets[$event['Event_ID']] : [];
    }

    return $events;
}




    public function getEventById($eventId) {
        $sql = "SELECT * FROM Events WHERE Event_ID = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return null;
        }

        $stmt->bind_param("i", $eventId);
        if (!$stmt->execute()) {
            error_log("Execute failed: " . $stmt->error);
            return null;
        }

        $result = $stmt->get_result();
        $event = $result->fetch_assoc();
        $stmt->close();

        return $event; // Returns null if not found
    }

    public function updateEvent(
        $eventId,
        $name,
        $description,
        $category,
        $createdBy,
        $organizerName,
        $startDate,
        $endDate,
        $venue,
        $address,
        $venueMapLink,
        $venueFacilities,
        $venueProfileLink,
        $eventImage = null,
        $venueImage = null,
        $organizerLogo = null,
        $ticketCategories = [],  // Ticket data to update
        $ticketPrices = [],
        $ticketQuantities = []
    ) {
        // Prepare the SQL query to update the event
        $sql = "UPDATE events SET 
                    name = ?, 
                    description = ?, 
                    category = ?, 
                    created_by = ?, 
                    organizer_name = ?, 
                    start_date = ?, 
                    end_date = ?, 
                    venue = ?, 
                    address = ?, 
                    venue_map_link = ?, 
                    venue_facilities = ?, 
                    venue_profile_link = ?, 
                    Image = IFNULL(?, Image), 
                    venue_image = IFNULL(?, venue_image), 
                    organizer_logo = IFNULL(?, organizer_logo)
                WHERE Event_ID = ?";
    
        // Prepare the statement
        $stmt = $this->conn->prepare($sql);
    
        // Execute the statement with the bound parameters
        $stmt->execute([
            $name,
            $description,
            $category,
            $createdBy,
            $organizerName,
            $startDate,
            $endDate,
            $venue,
            $address,
            $venueMapLink,
            $venueFacilities,
            $venueProfileLink,
            $eventImage,  // Will pass null if no new image uploaded
            $venueImage,  // Will pass null if no new image uploaded
            $organizerLogo, // Will pass null if no new image uploaded
            $eventId // The ID of the event to update
        ]);
    


        
       // Handle ticket updates
       if (!empty($ticketCategories) && !empty($ticketPrices) && !empty($ticketQuantities)) {
        $ticketModel = new Ticket($this->conn); // Assuming Ticket class handles ticket-related operations

        // Fetch existing tickets for this event
        $existingTickets = $ticketModel->getTicketsForEvent($eventId);
        $existingCategories = array_column($existingTickets, 'category'); // Extract existing ticket categories

        // Determine tickets to delete
        $ticketsToDelete = array_diff($existingCategories, $ticketCategories);
        foreach ($ticketsToDelete as $categoryToDelete) {
            $ticketModel->deleteTicket($eventId, $categoryToDelete);
        }

        // Loop through the ticket data to update or insert tickets
        foreach ($ticketCategories as $index => $ticketCategory) {
            $ticketPrice = floatval($ticketPrices[$index]);
            $ticketQuantity = intval($ticketQuantities[$index]);
            $availableTickets = $ticketQuantity;

            if (in_array($ticketCategory, $existingCategories)) {
                // Update existing ticket
                $ticketModel->updateTicket($eventId, $ticketCategory, $ticketPrice, $ticketQuantity, $availableTickets);
            } else {
                // Insert new ticket
                $ticketModel->insertTicket($eventId, $ticketCategory, $ticketPrice, $ticketQuantity, $availableTickets);
            }
        }
    }

    return true; //
    }

    public function deleteEvent($eventId) {
        // SQL query to delete an event based on the Event_ID
        $sql = "DELETE FROM Events WHERE Event_ID = ?";
        $stmt = $this->conn->prepare($sql);
    
        // Check if statement preparation was successful
        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return false;
        }
    
        // Bind the parameter (event ID)
        $stmt->bind_param("i", $eventId);
    
        // Execute the statement and check for success
        if (!$stmt->execute()) {
            error_log("Execute failed: " . $stmt->error);
            $stmt->close();
            return false;
        }
    
        // Check if any rows were affected (i.e., event was deleted)
        $affectedRows = $stmt->affected_rows;
        $stmt->close();
    
        // Return true if a row was deleted, otherwise false
        return $affectedRows > 0;
    }

}
?>
