<?php
require_once("../../model/Model.php");

class Tickets extends Model {
    protected $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Method to get all events
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

    // Method to add a new ticket
    public function addTicket($eventId, $price, $status, $createdAt, $type) {
        $sql = "INSERT INTO tickets (Event_ID, Price, Status, Created_At, type) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("idsss", $eventId, $price, $status, $createdAt, $type); // Adjust types as necessary
        return $stmt->execute();
    }


    // Method to get tickets by event ID
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
}
?>