<?php

require_once("../../model/Model.php");

class Admin
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Authenticate admin login
     */
   /*  public function login($username, $password)
    {
        $sql = "SELECT * FROM admins WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$username]);

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (password_verify($password, $row['password'])) {
                return $row; // Return admin details if login is successful
            }
        }
        return false; // Return false for invalid login
    }
 */
    /**
     * Update the status of an event
     */
    public function updateEventStatus($eventId, $newStatus)
    {
        $sql = "UPDATE events SET status = ? WHERE Event_ID = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$newStatus, $eventId]);
    }

    /**
     * Fetch all events with their details
     */
    public function getAllEvents()
    {
        $sql = "SELECT * FROM events ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>