<?php
define('APP_ROOT', dirname(dirname(__FILE__)));

require_once(APP_ROOT . '/controller/Controller.php');
require_once(APP_ROOT . '/model/Eventsdb.php');

class UsersController extends Controller{
    private $eventsModel;
    private $dbh;
    private $conn;

    public function __construct($model) {
        parent::__construct($model);
        $this->dbh = new Dbh();
        $this->conn = $this->dbh->getConn();
        $this->eventsModel = new Eventsdb($this->conn);
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
                $events = $this->eventsModel->getAllEventsToDisplay();
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