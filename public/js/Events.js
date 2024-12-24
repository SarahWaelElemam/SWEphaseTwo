const startDate = document.getElementById('startDate');
const endDate = document.getElementById('endDate');
const overlay = document.getElementById('overlay');
const calendarModal = document.getElementById('calendarModal');
const calendar = document.getElementById('calendar');
const closeModal = document.getElementById('closeModal');
const currentMonthYear = document.getElementById('currentMonthYear');
const prevMonth = document.getElementById('prevMonth');
const nextMonth = document.getElementById('nextMonth');

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
function handleBooking() {
    window.location.href = "BookNow.php";
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