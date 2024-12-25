// Get references to elements
const container1 = document.getElementById('container1');
const container2 = document.getElementById('container2');
const seats = document.querySelectorAll(".row .seat:not(.sold)");
const btn1 = document.getElementById('show1');
const btn2 = document.getElementById('show2');
const modal = document.getElementById('seatModal');
const seatInfo = document.getElementById('seatInfo');
const seatPrice = document.getElementById('seatPrice');
const confirmBtn = document.getElementById('confirmBtn');
const cancelBtn = document.getElementById('cancelBtn');
const nextButton = document.getElementById('next');
const prevButton = document.getElementById('prev');
const SelectTicketSection = document.querySelector('.selectTicket');
const paymentSection = document.querySelector('.Payment');
const printSection = document.querySelector('.printing');
const stepone = document.querySelector(".try");
const warningModal = document.getElementById('warningModal');
const closeWarningBtn = document.getElementById('closeWarningBtn');

let selectedSeat = null;  
let confirmedSeats = [];
let totalCost = 0;
let currentStep = 1;

function showWarningModal() {
    warningModal.style.display = 'block';
  }
  
  // Close the warning modal when OK is clicked
  closeWarningBtn.addEventListener('click', () => {
    warningModal.style.display = 'none';
  });
function setDefaultView() {
    container1.style.display = 'block'; // Show container 1 by default
    container2.style.display = 'none';  // Hide container 2 by default
    paymentSection.style.display = 'none'; // Hide payment section by default
}

// Function to toggle to Container 1
function SHOW1() {
    console.log('Showing container 1');
    container1.style.display = 'block';
    container2.style.display = 'none';
    btn1.classList.add('active');
    btn2.classList.remove('active');
}

// Function to toggle to Container 2
function SHOW2() {
    console.log('Showing container 2');
    container1.style.display = 'none';
    container2.style.display = 'block';
    btn2.classList.add('active');
    btn1.classList.remove('active');
}

function openSeatModal(seat, rowIndex, seatIndex) {
    selectedSeat = seat;  // Store the clicked seat

    // Get seat details from the seat's data attributes
    const seatTooltip = seat.getAttribute('data-tooltip');

    // Get the row index using the data-row attribute
    const rowNum = seat.parentElement.getAttribute('data-row');

    // Update modal content with seat row, seat number, and price
    seatInfo.textContent = `Row ${rowNum}, Seat ${seatIndex + 1}`;
    seatPrice.textContent = seatTooltip;

    // Show the modal
    modal.style.display = 'block';
}

// Function to close the modal
function closeSeatModal() {
    modal.style.display = 'none';
}

function confirmSeat() {
    // Extract seat details
    const seatText = seatInfo.textContent;
    const seatCostText = seatPrice.textContent;
    
    // Extract the seat price (assuming it always ends with LE)
    const seatCost = parseInt(seatCostText.match(/\d+/)[0]);

    // Add seat to confirmed seats array
    confirmedSeats.push(seatText);

    // Update total cost
    totalCost += seatCost;

    // Update the display of total cost
    const totalCostDisplay = document.getElementById('totalCostDisplay');
    totalCostDisplay.textContent = `Total: ${totalCost} LE`;

    // Update the display of selected seats
    const selectedSeatsDisplay = document.getElementById('selectedSeatsDisplay');
    
    // Create separate div elements for each seat
    selectedSeatsDisplay.innerHTML = confirmedSeats.map(seat => 
        `<div>${seat}</div>`
    ).join('');

    // Update the payment summary
    const paymentSeatsDisplay = document.getElementById('paymentSeatsDisplay');
    const paymentTotalDisplay = document.getElementById('paymentTotalDisplay');
    
    // Update payment summary with selected seats and total amount
    paymentSeatsDisplay.textContent = confirmedSeats.map(seat => `[${seat}]`).join(', ');
    paymentTotalDisplay.textContent = `${totalCost} LE`;

    // Mark the seat as selected and disable further selection
    selectedSeat.classList.add('selected');
    selectedSeat.classList.add('sold');

    // Close the modal
    closeSeatModal();
}

function updateSteps() {
    const circles = document.querySelectorAll('.circle');
    const progressBars = document.querySelectorAll('.progress-bar');
    
    circles.forEach((circle, idx) => {
        if (idx < currentStep) {
            circle.classList.add('active');
        } else {
            circle.classList.remove('active');
        }
    });
    
    progressBars.forEach((bar, idx) => {
        if (idx < currentStep - 1) {
            bar.classList.add('active');
        } else {
            bar.classList.remove('active');
        }
    });
}

nextButton.addEventListener('click', () => {
    if (confirmedSeats.length === 0) {
        showWarningModal(); // Show the warning modal instead of alert
        return; // Stop the function execution
    }
    // Check if there are confirmed seats before proceeding
    if (currentStep < 3 ) {
        currentStep++;
        updateSteps();
        
        if (currentStep === 2) {
            SelectTicketSection.style.display = 'none';
            paymentSection.style.display = 'block';
            nextButton.disabled = true;
            stepone.style.display = "none";
        }
        
        prevButton.disabled = false;
        if (currentStep === 3) {
            nextButton.disabled = true;
            paymentSection.style.display='none';
            printSection.style.display='flex';
            stepone.style.display = "none";
        }
    }
});

// Handle previous button click
prevButton.addEventListener('click', () => {
    if (currentStep > 1) {
        currentStep--;
        updateSteps();
        
        if (currentStep === 1) {
            SelectTicketSection.style.display = 'flex';
            paymentSection.style.display = 'none';
            printSection.style.display='none';
            stepone.style.display="block";
            prevButton.disabled = true;
        }
        else if (currentStep === 2) {
            SelectTicketSection.style.display = 'none';
            paymentSection.style.display = 'block';
            printSection.style.display='none';
            stepone.style.display = "none";
            prevButton.disabled = true;
        }
        
        nextButton.disabled = false;
    }
});

seats.forEach((seat, index) => {
    seat.addEventListener('click', (event) => {
        if (!seat.classList.contains("sold")) {
            const rowIndex = seat.parentElement.rowIndex;
            const seatIndex = Array.from(seat.parentElement.children).indexOf(seat);
            openSeatModal(seat, rowIndex, seatIndex);
        }
    });
});

// Attach events to modal buttons
confirmBtn.addEventListener('click', confirmSeat);
cancelBtn.addEventListener('click', closeSeatModal);

// Close modal if user clicks outside of it
window.onclick = function(event) {
    if (event.target == modal) {
        closeSeatModal();
    }
};

// Set default view on page load
window.onload = function() {
    console.log('Page loaded, setting default view');
    setDefaultView();
    prevButton.disabled = true;
    updateSteps();
};

// Update the display of selected seats
const selectedSeatsDisplay = document.getElementById('selectedSeatsDisplay');

// Create separate div elements for each seat
selectedSeatsDisplay.innerHTML = confirmedSeats.map(seat => 
    `<div>${seat}</div>`
).join('');