let nextButton;
// concert.js
document.addEventListener('DOMContentLoaded', function() {
    // Get all necessary elements
    const cardRadio = document.querySelector('input[value="card"]');
    const walletRadio = document.querySelector('input[value="wallet"]');
    const cardContent = document.querySelector('.creditcard');
    const walletContent = document.querySelector('.value');
    const paymentSection = document.querySelector('.Payment');
    const printSection = document.querySelector('.printing');
    const stepone = document.querySelector(".try");
    
    // Progress bar elements
    const circles = document.querySelectorAll('.circle');
    const progressBars = document.querySelectorAll('.progress-bar');
    const prevButton = document.getElementById('prev');
    nextButton = document.getElementById('next');
    
    let currentActive = 2; // Start from step 2
    const totalSteps = circles.length;

    function setDefaultView() {
        paymentSection.style.display = 'block';
        printSection.style.display = 'none';
    }

    function updateStepper() {
        // Update circles
        circles.forEach((circle, index) => {
            if (index < currentActive) {
                circle.classList.add('active');
            } else {
                circle.classList.remove('active');
            }
        });

        // Update progress bars
        progressBars.forEach((bar, index) => {
            const indicator = bar.querySelector('.indicator');
            if (index < currentActive - 1) {
                indicator.style.height = '100%'; // Fully fill the previous progress bars
                bar.style.height = '1rem'; // Set height for filled bars
            } else if (index === currentActive - 1) {
                indicator.style.height = '100%'; // Fill the current progress bar
                bar.style.height = '7rem'; // Set height for the active bar
            } else {
                indicator.style.height = '0%'; // Hide the future progress bars
                bar.style.height = '1rem'; // Set default height for future bars
            }
        });

        // Update buttons
        prevButton.disabled = currentActive === 2; // Disabled at step 2
        nextButton.disabled = currentActive === totalSteps || !paymentSuccessful; // Disable if at step 3 or payment not successful

        // Update sections visibility
        if (currentActive === 2) {
            paymentSection.style.display = 'block';
            printSection.style.display = 'none';
            stepone.style.display = "none";
        } else if (currentActive === 3) {
            paymentSection.style.display = 'none';
            printSection.style.display = 'flex';
            stepone.style.display = "none";
        }
    }

    nextButton.addEventListener('click', () => {
        if (currentActive < totalSteps) {
            currentActive++;
            updateStepper();
        }
    });

    prevButton.addEventListener('click', () => {
        if (currentActive > 2) { // Can't go below step 2
            currentActive--;
            updateStepper();
        }
    });

    function togglePaymentMethod() {
        if (cardRadio.checked) {
            cardContent.style.display = 'block';
            walletContent.style.display = 'none';
        } else {
            cardContent.style.display = 'none';
            walletContent.style.display = 'block';
        }
    }

    // Payment method event listeners
    cardRadio.addEventListener('change', togglePaymentMethod);
    walletRadio.addEventListener('change', togglePaymentMethod);
    togglePaymentMethod();

    // Handle payment form submission
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const cardNumber = document.getElementById('cardNumber');
        if (cardNumber && cardNumber.value.length !== 12) {
            document.getElementById('cardNumberError').style.display = 'block';
            return;
        }
        // Move to step 3 after successful validation
        currentActive = 3;
        updateStepper();
        
        // Trigger the receipt animation
        const receipts = document.querySelector('. receipts');
        receipts.classList.add('animate');
    });

    // Initialize on page load
    window.onload = function() {
        console.log('Page loaded, setting default view');
        setDefaultView();
        prevButton.disabled = true;
        nextButton.disabled = true; // Disable the next button initially
        currentActive = 2; // Start at step 2
        updateStepper();

        // Initialize receipt animation
        const receipts = document.querySelector('.receipts');
        receipts.classList.add('animate');
    };

    // Initial setup
    updateStepper();
});

// paymentCheck.js
// Get references to elements
const creditCardRadio = document.querySelector('input[value="card"]');
const walletRadio = document.querySelector('input[value="wallet"]');
const creditCardDiv = document.querySelector('.creditcard');
const valueDiv = document.querySelector('.value');
const cardNumberInput = document.getElementById('cardNumber');
const cardNumberError = document.getElementById('cardNumberError');
const cardHolderName = document.querySelector('.creditcard input[placeholder="Name on card"]');
const cardHolderNameError = document.createElement('span');
const expiryInput = document.querySelector('.creditcard input[placeholder="MM/YY"]');
const expiryError = document.createElement('span');
const cvvInput = document.querySelector('.creditcard input[placeholder="123"]');
const cvvError = document.createElement('span');
const walletCardholderName = document.querySelector('.value input[placeholder="Your Full Name"]');
const walletMobileNumber = document.querySelector('.value input[placeholder="Mobile Number"]');
cardHolderName.parentElement.appendChild(cardHolderNameError);
expiryInput.parentElement.appendChild(expiryError);
cvvInput.parentElement.appendChild(cvvError);
let paymentSuccessful = false;

function setErrorStyles(errorSpan) {
    errorSpan.style.color = 'red';
    errorSpan.style.display = 'none';  // Hide by default
}

// Set default error span styles
setErrorStyles(cardHolderNameError);
setErrorStyles(expiryError);
setErrorStyles(cvvError);

// Set initial state
function setInitialPaymentState() {
    creditCardDiv.style.display = 'block';
    valueDiv.style.display = 'none';
    creditCardRadio.checked = true;
}

// Handle payment method change
function handlePaymentMethodChange(event) {
    if (event.target.value === 'card') {
        creditCardDiv.style.display = 'block';
        valueDiv.style.display = 'none';
    } else if (event.target.value === 'wallet') {
        creditCardDiv.style.display = 'none';
        valueDiv.style.display = 'block';
    }
}

// Validate the card number
function validateCardNumber() {
    const cardNumber = cardNumberInput.value.trim();
    if (cardNumber.length !== 12 || isNaN(cardNumber)) {
        cardNumberError.style.display = 'block'; // Show error if invalid
        cardNumberError.textContent = 'Card number must be exactly 12 digits.';
        return false;
    } else {
        cardNumberError.style.display = 'none'; // Hide error if valid
        return true;
    }
}

// Validate cardholder name
function validateCardHolderName() {
    if (cardHolderName.value.trim() === "") {
        cardHolderNameError.style.display = 'block';
        cardHolderNameError.textContent = 'Cardholder name cannot be empty.';
        return false;
    } else {
        cardHolderNameError.style.display = 'none';
        return true;
    }
}

// Validate expiry date
function validateExpiryDate() {
    const expiryRegex = /^(0[1-9]|1[0-2])\/\d{2}$/;
    if (!expiryRegex.test(expiryInput.value.trim())) {
        expiryError.style.display = 'block';
        expiryError.textContent = 'Expiry date must be in MM/YY format.';
        return false;
    } else {
        expiryError.style.display = 'none';
        return true;
    }
}

// Validate CVV
function validateCVV() {
    if (cvvInput.value.trim().length !== 3 || isNaN(cvvInput.value.trim())) {
        cvvError.style.display = 'block';
        cvvError.textContent = 'CVV must be exactly 3 digits.';
        return false;
    } else {
        cvvError.style.display = 'none';
        return true;
    }
}

// Validate card details (for credit card payment)
function validateCreditCardDetails() {
    let isValid = true;
    
    // Validate all fields one by one
    if (!validateCardHolderName()) isValid = false;
    if (!validateCardNumber()) isValid = false;
    if (!validateExpiryDate()) isValid = false;
    if (!validateCVV()) isValid = false 
    return isValid;
}

// Validate the form before submission
function validatePaymentForm(event) {
    event.preventDefault(); // Prevent the form from being submitted by default

    let isFormValid = false;

    // Check which payment method is selected and validate accordingly
    if (creditCardRadio.checked) {
        isFormValid = validateCreditCardDetails();
    } else if (walletRadio.checked) {
        isFormValid = true; // Assume wallet payment is valid for simplicity
    }
    if (isFormValid) {
        paymentSuccessful = true; // Set payment as successful
        nextButton.disabled = false; // Enable the next button
        console.log('Next button enabled');
        nextButton.click(); // Move to the next step
    } else {
        paymentSuccessful = false; // Set payment as not successful
        nextButton.disabled = true; // Keep the next button disabled
    }
}

document.querySelector('.btn-pay').addEventListener('click', validatePaymentForm);

// Add event listeners for payment method change
document.querySelectorAll('input[name="paymentMethod"]').forEach(radio => {
    radio.addEventListener('change', handlePaymentMethodChange);
});

// Ensure that the reset button properly resets the fields
document.getElementById('cancelPayment').addEventListener('click', function() {
    // Reset text input fields
    document.querySelectorAll('input[type="text"], input[type="number"], input[type="tel"], input[type="email"]').forEach(input => {
        input.value = ''; // Clear the value
    });

    // Optionally reset the payment method to the default one (e.g., card)
    document.querySelector('input[name="paymentMethod"][value="card"]').checked = true;
    
    // Reset any error messages
    document.querySelectorAll('.error').forEach(error => {
        error.style.display = 'none'; // Hide error messages
    });

    // If you have a form or a progress bar, reset those as well
    // Example: Reset the progress to step 1
    currentActive = 2;
    updateStepper();  // Assuming updateStepper updates the UI based on the current step
});


// Initialize on page load
window.addEventListener('load', setInitialPaymentState);
