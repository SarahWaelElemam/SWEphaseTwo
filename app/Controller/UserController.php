<?php
define('APP_ROOT', dirname(dirname(__FILE__)));
require_once(APP_ROOT . '/controller/Controller.php');
require_once(APP_ROOT . '/model/Eventsdb.php');

class UserController extends Controller{

    private $dbh;
    private $eventsModel;
    private $conn;

    public function __construct() {
        $this->dbh = new DBh(); 
        $this->conn = $this->dbh->getConn();
        $this->eventsModel = new Eventsdb($this->conn);
    }

    // Fetch user data from the database
    public function getUserDetails($userId) {
        $sql = "SELECT * FROM user WHERE UserId = ?";
        $stmt = $this->dbh->getConn()->prepare($sql);
        $stmt->bind_param("i", $userId); // Bind the userId parameter
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc(); // Return user details
    }

    // Update user details
    public function updateUserDetails($userId, $fname, $lname, $email, $phone, $gender, $governamnte) {
        $sql = "UPDATE user SET Fname = ?, Lname = ?, email = ?, phone = ?, gender = ?, governamnte = ? WHERE UserId = ?";
        $stmt = $this->dbh->getConn()->prepare($sql);
        $stmt->bind_param("ssssssi", $fname, $lname, $email, $phone, $gender, $governamnte, $userId);
        return $stmt->execute(); // Execute the update query
    }

    // Delete user account
    public function deleteUserAccount($userId) {
        $sql = "DELETE FROM user WHERE UserId = ?";
        $stmt = $this->dbh->getConn()->prepare($sql);
        $stmt->bind_param("i", $userId);
        return $stmt->execute(); // Execute the delete query
    }

    // Close the connection
    public function __destruct() {
        $this->dbh = null;
    }

    public function getEvents() {
        try {
            $events = [];
            
            if (isset($_POST['startDate']) && isset($_POST['endDate'])) {
                $events = $this->eventsModel->getFilteredEvents(
                    $_POST['startDate'],
                    $_POST['endDate']
                );
            } else {
                $events = $this->eventsModel->getAllEventToDisplay();
            }

            if (empty($events)) {
                throw new Exception("No events found.");
            }

            return $events;

        } catch (Exception $e) {
            echo "<p>Error: " . $e->getMessage() . "</p>";
            exit;
        }
    }
    public function getUserEvents($userId) {
        return $this->eventsModel->getAllEventsToDisplay();
    }
    public function createEvent($eventData) {
        return $this->eventsModel->addEvent(
            $eventData['name'],
            $eventData['location'],
            $eventData['category'],
            'PENDING',
            $eventData['date'],
            $eventData['time'],
            $eventData['created_by'],
            $eventData['detailed_loc'],
            $eventData['image'],
            $eventData['price_range1'],
            $eventData['price_range2']
        );
    }
    public function updateUserEvent($eventId, $eventData) {
        return $this->eventsModel->updateEvent(
            $eventId,
            $eventData['name'],
            $eventData['location'],
            $eventData['category'],
            $eventData['status'],
            $eventData['date'],
            $eventData['time'],
            $eventData['detailed_loc'],
            $eventData['image'],
            $eventData['price_range1'],
            $eventData['price_range2']
        );
    }

    public function deleteUserEvent($eventId) {
        return $this->eventsModel->deleteEvent($eventId);
    }
}
?>
