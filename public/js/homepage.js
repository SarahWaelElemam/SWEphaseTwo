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

    // Upcoming Events Slider
      let currentUpcomingPage = 0;
    const eventsPerPage = 3;
    const events = document.querySelectorAll('.upcoming-event');
    const maxPages = Math.ceil(events.length / eventsPerPage);

    function updateUpcomingEventsVisibility() {
        const startIdx = currentUpcomingPage * eventsPerPage;
        events.forEach((event, index) => {
            event.style.display = index >= startIdx && index < startIdx + eventsPerPage ? 'block' : 'none';
        });
    }

    const prevBtn = document.getElementById('new-prev-btn');
    const nextBtn = document.getElementById('new-next-btn');

    if (prevBtn && nextBtn) {
        prevBtn.addEventListener('click', () => {
            if (currentUpcomingPage > 0) {
                currentUpcomingPage--;
                updateUpcomingEventsVisibility();
            }
        });

        nextBtn.addEventListener('click', () => {
            if (currentUpcomingPage < maxPages - 1) {
                currentUpcomingPage++;
                updateUpcomingEventsVisibility();
            }
        });
    }

    // Initialize visibility
    updateUpcomingEventsVisibility();
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
