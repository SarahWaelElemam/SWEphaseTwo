<?php
require_once("../../model/Model.php");
class Ticket extends Model{
    protected $conn;
    public $ticketID;
    public $eventID;
    public $category;
    public $price;
    public $quantity;
    public $available;

    // Constructor with DB connection
    public function __construct($db) {
        $this->conn = $db;
    }
    public function getAllTickets() {
        $sql = "SELECT * FROM tickets";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $events = [];
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }

        return $events;
    }
    // Check if a ticket exists for the event and category
    public function ticketExists($eventId, $ticketCategory) {
        $count = 0;

        $sql = "SELECT COUNT(*) FROM tickets WHERE event_id = ? AND category = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("is", $eventId, $ticketCategory); // Assuming event_id is an integer and category is a string
        $stmt->execute();
    
        // Use bind_result() to fetch the result
        $stmt->bind_result($count);
        $stmt->fetch(); // Fetch the result into the $count variable
        $stmt->close();
    
        return $count > 0; // Return true if the ticket exists, false otherwise
    }

    public function getTicketsForEvent($eventId) {
        // Prepare the SQL query to retrieve tickets for the given event
        $sql = "SELECT category, price, quantity FROM tickets WHERE event_id = ?";
        $stmt = $this->conn->prepare($sql);
    
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $this->conn->error);
        }
    
        // Bind the parameter and execute the query
        $stmt->bind_param("i", $eventId);
        $stmt->execute();
    
        // Fetch the results
        $result = $stmt->get_result();
    
        // Initialize an array to store tickets
        $tickets = [];
        while ($row = $result->fetch_assoc()) {
            $tickets[] = $row; // Append each ticket as an associative array
        }
    
        // Close the statement
        $stmt->close();
    
        return $tickets; // Return the list of tickets
    }

    // Update an existing ticket
    public function updateTicket($eventId, $ticketCategory, $ticketPrice, $ticketQuantity, $availableTickets)
    {
        // SQL query to update the ticket's price, quantity, and availability
        $sql = "UPDATE tickets SET 
                    price = ?, 
                    quantity = ?, 
                    Available = ?
                WHERE Event_ID = ? AND category = ?";

        // Prepare the statement
        $stmt = $this->conn->prepare($sql);

        // Execute the statement with the bound parameters
        return $stmt->execute([
            $ticketPrice,
            $ticketQuantity,
            $availableTickets,
            $eventId,
            $ticketCategory
        ]);
    }

    public function insertTicket($eventId, $ticketType, $price, $quantity, $availableTickets) {
        // Corrected SQL query to match the existing column name `Available`
        $sql = "INSERT INTO tickets (Event_ID, Category, Price, Quantity, Available) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isiii", $eventId, $ticketType, $price, $quantity, $availableTickets);
        $stmt->execute();
    }

public function deleteTicket($eventId, $ticketCategory)
{
    $sql = "DELETE FROM tickets WHERE Event_ID = ? AND category = ?";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([$eventId, $ticketCategory]);
}

    // Method to get all tickets for an event
    public function getTicketsByEvent($eventId) {
        $sql = "SELECT * FROM tickets WHERE Event_ID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $eventId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $tickets = [];
        while ($row = $result->fetch_assoc()) {
            $tickets[] = $row;
        }

        return $tickets;
    }

    // Method to update the available tickets for an event
    public function updateAvailableTickets($ticketID, $newQuantity) {
        $sql = "UPDATE Tickets SET Available = ? WHERE Ticket_ID = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$newQuantity, $ticketID]);
    }
    public function addTicket($eventId, $price, $status, $createdAt, $type) {
        $sql = "INSERT INTO tickets (Event_ID, Price, Status, Created_At, type) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("idsss", $eventId, $price, $status, $createdAt, $type); // Adjust types as necessary
        return $stmt->execute();
    }
    public function getAvailableTicketCount($eventId, $ticketType) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as available_count FROM tickets WHERE event_id = ? AND type = ? AND status = 'Available'");
        $stmt->bind_param("is", $eventId, $ticketType);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['available_count'] ?? 0;
    }
    public function updateTicketStatus($ticketId, $newStatus) {
        $sql = "UPDATE tickets SET Status = ? WHERE Ticket_ID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $newStatus, $ticketId);
        return $stmt->execute();
    }

    // Additional methods like delete, get by ID, etc., can be added here
}

?>