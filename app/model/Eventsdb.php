<?php
require_once("../../model/Model.php");

class Eventsdb extends Model {
    protected $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllEventToDisplay() {
        $sql = "SELECT e.*, u.image as organizer_image 
                FROM events e 
                LEFT JOIN user u ON e.Created_By = u.User_ID 
                WHERE e.Status = 'ACCEPTED'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $events = [];
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
        
        return $events;
    }

    public function getAllEventsToDisplay($limit = 5) {
        $sql = "SELECT * FROM events WHERE Status = 'ACCEPTED' ORDER BY Created_At DESC LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $events = [];
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
    
        return $events;
    }


public function getUpcomingEvents($status) {
    $sql = "SELECT * FROM events WHERE Status = ? ORDER BY Created_At DESC LIMIT 6";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("s", $status);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}


    public function getAllEvents() {
        $sql = "SELECT * FROM events";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $events = [];
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }

        return $events;
    }

    public function getFilteredEvents($startDate, $endDate) {
        $sql = "SELECT * FROM events WHERE Status = 'ACCEPTED' AND Date BETWEEN ? AND ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ss', $startDate, $endDate);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    public function addEvent($name, $location, $category, $status, $date, $time, $createdBy, $detailedLoc, $image, $priceRange1, $priceRange2) {
        $sql = "INSERT INTO Events (Name, Location, Category, Status, Date, Time, Created_By, detailed_loc, image, price_range1, price_range2) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ssssssissdd', $name, $location, $category, $status, $date, $time, $createdBy, $detailedLoc, $image, $priceRange1, $priceRange2);
        $stmt->execute();
        return $this->conn->insert_id;
    }

    // Update an existing event with price_range1 and price_range2
    public function updateEvent($eventId, $name, $location, $category, $status, $date, $time, $detailedLoc, $image, $priceRange1, $priceRange2) {
        $sql = "UPDATE Events 
                SET Name = ?, Location = ?, Category = ?, Status = ?, 
                    Date = ?, Time = ?, detailed_loc = ?, image = ?, price_range1 = ?, price_range2 = ? 
                WHERE Event_ID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ssssssssddi', $name, $location, $category, $status, $date, $time, $detailedLoc, $image, $priceRange1, $priceRange2, $eventId);
        $stmt->execute();
        return $stmt->affected_rows;
    }

    public function deleteEvent($eventId) {
        $sql = "DELETE FROM Events WHERE Event_ID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $eventId);
        $stmt->execute();
        return $stmt->affected_rows;
    }
}
?>