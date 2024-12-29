<?php
class Ticket {
    private $conn;

    // Properties
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

    // Method to insert a new ticket
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

    public function getTicketsForEvent($eventId)
    {
        try {
            $sql = "SELECT category FROM tickets WHERE event_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$eventId]);
    
            // Fetch all ticket categories
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            // Check if the result is empty
            if (!$result) {
                throw new Exception('No tickets found for this event.');
            }
    
            // Extract ticket categories from the result
            return array_map(function ($ticket) {
                return $ticket['category'];
            }, $result);
    
        } catch (Exception $e) {
            // Handle the exception (log it or display an error message)
            echo "Error: " . $e->getMessage();
            return []; // Return an empty array in case of an error
        }
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
    public function getTicketsByEvent($eventID) {
        $sql = "SELECT * FROM Tickets WHERE Event_ID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$eventID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Returns an array of tickets
    }

    // Method to update the available tickets for an event
    public function updateAvailableTickets($ticketID, $newQuantity) {
        $sql = "UPDATE Tickets SET Available = ? WHERE Ticket_ID = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$newQuantity, $ticketID]);
    }

    // Additional methods like delete, get by ID, etc., can be added here
}

?>