<?php

require_once("../../model/Model.php");

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
        $organizerLogo = null
        ) {
        $sql = "INSERT INTO events (
                    name, description, category, created_by, organizer_name, start_date, end_date, 
                    venue, address, venue_map_link, venue_facilities, venue_profile_link, 
                    Image, venue_image, organizer_logo, status
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending'
                )";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
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
    }
    
    public function getAllEventsByOrganizerId($organizerId)
    {
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
        $organizerLogo = null
    ) {
        // Prepare the SQL query to update the event
        // The 'IFNULL' function ensures that the existing values are kept when no new image is uploaded.
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
            venue_profile_link = ?,  -- Ensure proper naming here
                    Image = IFNULL(?, Image), 
                    venue_image = IFNULL(?, venue_image), 
                    organizer_logo = IFNULL(?, organizer_logo)
                WHERE event_id = ?";
    
        // Prepare the statement
        $stmt = $this->conn->prepare($sql);
    
        // Execute the statement with the bound parameters
        return $stmt->execute([
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
