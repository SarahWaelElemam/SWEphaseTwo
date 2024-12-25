const startDate = document.getElementById('startDate');
const endDate = document.getElementById('endDate');
const overlay = document.getElementById('overlay');
const calendarModal = document.getElementById('calendarModal');
const calendar = document.getElementById('calendar');
const closeModal = document.getElementById('closeModal');
const currentMonthYear = document.getElementById('currentMonthYear');
const prevMonth = document.getElementById('prevMonth');
const nextMonth = document.getElementById('nextMonth');

document.getElementById('filterbtn').addEventListener('click', function() {
    filterEvents();
});

function filterEvents() {
    const startDateValue = document.getElementById('startDate').value;
    const endDateValue = document.getElementById('endDate').value;

    // Check if both start and end dates are selected
    if (!startDateValue || !endDateValue) {
        alert("Please select both start and end dates.");
        return;
    }

    // Format dates to YYYY-MM-DD
    const startDateFormatted = new Date(startDateValue).toISOString().split('T')[0];
    const endDateFormatted = new Date(endDateValue).toISOString().split('T')[0];

    // AJAX request to fetch filtered events
    fetch('path_to_php_script/filterEvents.php', {
        method: 'POST',
        body: JSON.stringify({ startDate: startDateFormatted, endDate: endDateFormatted }),
        headers: {
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => displayEvents(data)) // Display filtered events
    .catch(error => console.error('Error:', error));
}

function displayEvents(events) {
    const eventsContainer = document.querySelector('.row-tick');
    eventsContainer.innerHTML = ''; // Clear previous events

    events.forEach(event => {
        const eventHTML = `
            <div class="ticket">
                <div class="side front">
                    <img src="../../../public/images/${event.image}" alt="${event.Name}">
                    <div class="info bottom">
                        <h1>${event.Name}</h1>
                        <span class="title address">${event.Location}</span>
                        <p><i class="fa-solid fa-circle" style="color: #03b300;"></i> Price: ${event.price}</p>
                        <dl>
                            <dt>Date</dt>
                            <dd>${event.Date}</dd>
                            <dt>Time</dt>
                            <dd>${event.Time}</dd>
                        </dl>
                    </div>
                </div>
                <div class="side back">
                    <div class="top">
                        <div class="span">
                            <h2>Organized By</h2>
                            <span>${event.Created_By}</span>
                        </div>
                        <div class="span">
                            <h2>Location</h2>
                            <span>${event.Location}</span>
                            <p>${event.detailed_loc}</p>
                        </div>
                    </div>
                    <div class="payment bottom">
                        <button class="Book"><i class="fa-solid fa-ticket"></i> Book Now</button>
                    </div>
                </div>
            </div>
        `;
        eventsContainer.innerHTML += eventHTML;
    });
}

// Calendar Functions
let currentDate = new Date();
currentDate.setHours(0, 0, 0, 0); // Set to beginning of the day
let currentMonth = currentDate.getMonth();
let currentYear = currentDate.getFullYear();
let activeInput = null;
let startDateValue = null;

function openCalendar(input) {
    activeInput = input;
    renderCalendar();
    overlay.style.display = 'block';
    calendarModal.style.display = 'block';
}

function closeCalendar() {
    overlay.style.display = 'none';
    calendarModal.style.display = 'none';
}

function renderCalendar() {
    calendar.innerHTML = '';
    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
    const firstDay = new Date(currentYear, currentMonth, 1).getDay();

    currentMonthYear.textContent = `${new Date(currentYear, currentMonth).toLocaleString('default', { month: 'long' })} ${currentYear}`;

    const weekdays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    weekdays.forEach(day => {
        const dayElement = document.createElement('div');
        dayElement.textContent = day;
        dayElement.classList.add('calendar-weekday');
        calendar.appendChild(dayElement);
    });

    for (let i = 0; i < firstDay; i++) {
        calendar.appendChild(document.createElement('div'));
    }

    for (let day = 1; day <= daysInMonth; day++) {
        const dayElement = document.createElement('div');
        dayElement.textContent = day;
        dayElement.classList.add('calendar-day');

        const dateToCheck = new Date(currentYear, currentMonth, day);
        if (activeInput === endDate && startDateValue) {
            if (dateToCheck <= startDateValue) {
                dayElement.classList.add('past-date');
            } else {
                dayElement.addEventListener('click', () => selectDate(day));
            }
        } else {
            if (dateToCheck < currentDate) {
                dayElement.classList.add('past-date');
            } else {
                dayElement.addEventListener('click', () => selectDate(day));
            }
        }

        calendar.appendChild(dayElement);
    }
}

function selectDate(day) {
    const selectedDate = new Date(currentYear, currentMonth, day);
    const formattedDate = `${currentMonth + 1}/${day}/${currentYear}`;
    
    if (activeInput === startDate) {
        startDate.value = formattedDate;
        startDateValue = selectedDate;
        endDate.value = ''; // Clear end date when start date is changed
    } else if (activeInput === endDate) {
        if (selectedDate > startDateValue) {
            endDate.value = formattedDate;
        } else {
            alert("End date must be after the start date.");
            return;
        }
    }
    
    closeCalendar();
}

function changeMonth(delta) {
    currentMonth += delta;
    if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    } else if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    }
    renderCalendar();
}

startDate.addEventListener('click', () => openCalendar(startDate));
endDate.addEventListener('click', () => {
    if (!startDate.value) {
        alert("Please select a start date first.");
        return;
    }
    openCalendar(endDate);
});
closeModal.addEventListener('click', closeCalendar);
overlay.addEventListener('click', closeCalendar);
prevMonth.addEventListener('click', () => changeMonth(-1));
nextMonth.addEventListener('click', () => changeMonth(1));
