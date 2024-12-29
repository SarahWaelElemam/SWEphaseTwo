document.addEventListener("DOMContentLoaded", function () {
    const slider = document.querySelector(".ticket-slider");
    const tickets = document.querySelectorAll(".ticket");
    let currentIndex = 0;
    
     

    if (tickets.length > 1) {
        setInterval(() => {
            tickets.forEach((ticket, index) => {
                ticket.style.transform = `translateX(-${currentIndex * 100}%)`;
            });
            currentIndex = (currentIndex + 1) % tickets.length;
        }, 3000); // Change slide every 3 seconds
    }

    const hotEvents = document.querySelectorAll('.hot-event');

    function updateTicket(index) {
        const data = eventData[index]; // Use the dynamic eventData array
        const ticket = tickets[index];

        // Add slide-out animation
        ticket.classList.add('slide-out');

        setTimeout(() => {
            const eventDetails = ticket.querySelector('.event-details');
            eventDetails.querySelector('h2').textContent = data.Name; // Using 'Name' from PHP data
            eventDetails.querySelectorAll('p')[0].innerHTML = `${data.Created_At} | ${data.Status}`;
            eventDetails.querySelectorAll('p')[1].innerHTML = `${data.Category}`;

            const eventImage = ticket.querySelector('.event-image');
            eventImage.src = data.image;

            ticket.classList.remove('slide-out');
            ticket.classList.add('slide-in');

            setTimeout(() => ticket.classList.remove('slide-in'), 50);
        }, 500);

        updateHotEvents(index);
    }


    function slideToNextEvent() {
        currentIndex = (currentIndex + 1) % eventData.length;
        updateTicket(currentIndex);
    }

    updateTicket(0);

    hotEvents.forEach((event, index) => {
        event.addEventListener('click', () => {
            if (index !== currentIndex) {
                currentIndex = index;
                updateTicket(currentIndex);
            }
        });
    });

    setInterval(slideToNextEvent, 5000);

    // Login/Signup Button
    const loginSignupBtn = document.querySelector('.login-signup-btn');
    if (loginSignupBtn) {
        loginSignupBtn.addEventListener('click', function () {
            window.location.href = 'login_Signup.php';
        });
    }

   
    // Initialize buttons state
    updateNavigationButtons();
});



const tickets = document.querySelectorAll('.ticket');
const hotEvents = document.querySelectorAll('.hot-event');

function syncHotEventsToTickets(index) {
    // Highlight the correct hot event
    hotEvents.forEach((event, i) => {
        event.classList.toggle('active', i === index);
    });

    // Optionally scroll the ticket slider to the corresponding ticket
    tickets.forEach((ticket, i) => {
        ticket.style.transform = `translateX(-${index * 100}%)`;
    });
}

// Add event listeners to hot events
hotEvents.forEach((event, index) => {
    event.addEventListener('click', () => {
        syncHotEventsToTickets(index);
    });
});

// Add an initial sync for page load
syncHotEventsToTickets(0);




let currentUpcomingIndex = 0;
const upcomingEventsSlider = document.querySelector('.upcoming-events-slider');
const upcomingEvents = document.querySelectorAll('.upcoming-event');
const eventsPerView = 3;

function updateUpcomingEvents() {
    const translation = -currentUpcomingIndex * (100 / eventsPerView);
    upcomingEventsSlider.style.transform = `translateX(${translation}%)`;
}

function nextUpcomingEvent() {
    const maxIndex = upcomingEvents.length - eventsPerView;
    if (currentUpcomingIndex < maxIndex) {
        currentUpcomingIndex++;
        updateUpcomingEvents();
    }
}

function prevUpcomingEvent() {
    if (currentUpcomingIndex > 0) {
        currentUpcomingIndex--;
        updateUpcomingEvents();
    }
}