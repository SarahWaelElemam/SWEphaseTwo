<<<<<<< Updated upstream
=======
<<<<<<< Updated upstream
=======
>>>>>>> Stashed changes
<?php

require_once(__ROOT__ . "controller/Controller.php");

class AdminController extends Controller{
<<<<<<< Updated upstream

}
?>
=======
    private $eventRepository;

    public function __construct() {
        $this->eventRepository = new EventRepository(); // Handles DB operations
    }

    // Create Event
    public function createEvent($eventData) {
        $event = new Event();
        // Map form data to the model
        $event->eventName = $eventData['eventName'];
        $event->eventDescription = $eventData['eventDescription'];
        $event->eventType = $eventData['eventType'];
        $event->eventImage = $eventData['eventImage']; // Save file to a folder and store path
        $event->venue = $eventData['venue'];
        $event->address = $eventData['address'];
        $event->venueMapLink = $eventData['venueMapLink'];
        $event->startDate = $eventData['startDate'];
        $event->endDate = $eventData['endDate'];
        $event->ticketPrices = json_encode($eventData['ticketPrices']); // Handle JSON encoding
        $event->createdBy = $eventData['createdBy'];
        $event->organizerName = $eventData['organizerName'];
        $event->organizerLogo = $eventData['organizerLogo'];
        $event->eventStatus = $eventData['eventStatus'];
        $event->venueFacilities = json_encode($eventData['venueFacilities']); // Handle JSON encoding
        $event->venueProfileLink = $eventData['venueProfileLink'];
        $event->venueImage = $eventData['venueImage'];

        return $this->eventRepository->save($event);
    }

    // Read Events
    public function listEvents() {
        return $this->eventRepository->findAll();
    }

    // Update Event
    public function updateEvent($eventId, $eventData) {
        $event = $this->eventRepository->findById($eventId);
        if (!$event) throw new Exception('Event not found.');

        // Update fields
        $event->eventName = $eventData['eventName'] ?? $event->eventName;
        // Map other fields similarly...

        return $this->eventRepository->save($event);
    }

    // Delete Event
    public function deleteEvent($eventId) {
        return $this->eventRepository->delete($eventId);
    }
}

?>
>>>>>>> Stashed changes
>>>>>>> Stashed changes