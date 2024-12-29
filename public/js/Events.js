const removeFilterBtn = document.getElementById('removebtn');
const startDate = document.getElementById('startDate');
const endDate = document.getElementById('endDate');
const overlay = document.getElementById('overlay');
const calendarModal = document.getElementById('calendarModal');
const calendar = document.getElementById('calendar');
const closeModal = document.getElementById('closeModal');
const currentMonthYear = document.getElementById('currentMonthYear');
const prevMonth = document.getElementById('prevMonth');
const nextMonth = document.getElementById('nextMonth');

// Initialize date variables
let currentDate = new Date();
currentDate.setHours(0, 0, 0, 0);
let currentMonth = currentDate.getMonth();
let currentYear = currentDate.getFullYear();
let activeInput = null;
let startDateValue = null;

// Event Listeners
document.getElementById('filterbtn').addEventListener('click', filterEvents);
removeFilterBtn.style.display = 'none';
removeFilterBtn.addEventListener('click', removeFilter);
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

// Filter Events Function
function filterEvents() {
    const startDateValue = document.getElementById('startDate').value;
    const endDateValue = document.getElementById('endDate').value;

    if (!startDateValue || !endDateValue) {
        alert("Please select both start and end dates.");
        return;
    }

    // Create FormData object
    const formData = new FormData();
    formData.append('startDate', new Date(startDateValue).toISOString().split('T')[0]);
    formData.append('endDate', new Date(endDateValue).toISOString().split('T')[0]);

    // Send POST request
    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newEvents = doc.querySelector('.row-tick');
        
        if (newEvents) {
            document.querySelector('.row-tick').innerHTML = newEvents.innerHTML;
            // Make sure to show the remove button after successful filtering
            removeFilterBtn.style.display = 'inline-block';
        } else {
            document.querySelector('.row-tick').innerHTML = '<h2 style="margin: 2rem; font-family: italic;">No events found for the selected dates.</h2>';
            // Still show the remove button even when no events are found
            removeFilterBtn.style.display = 'inline-block';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while filtering events. Please try again.');
    });
}
function removeFilter() {
    // Clear the date inputs
    startDate.value = '';
    endDate.value = '';
    startDateValue = null;

    // Hide remove filter button before making the request
    removeFilterBtn.style.display = 'none';

    // Fetch all events
    fetch(window.location.href)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newEvents = doc.querySelector('.row-tick');
            
            if (newEvents) {
                document.querySelector('.row-tick').innerHTML = newEvents.innerHTML;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while removing the filter. Please try again.');
            // Show the remove button again if the request fails
            removeFilterBtn.style.display = 'inline-block';
        });
}
// Calendar Functions
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
        endDate.value = '';
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