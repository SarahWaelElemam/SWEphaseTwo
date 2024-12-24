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
    if (!validateCVV()) isValid = false;

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
        isFormValid = true;
    }
    if (isFormValid) {
        // If the form is valid, simulate payment success
        return true; // Indicate that payment validation was successful
    } else {
        return false; // Indicate that payment validation failed
    }
}
document.querySelector('.btn-pay').addEventListener('click', function(event) {
    if (validatePaymentForm(event)) {
        paymentSuccessful = true; // Set payment as successful
        nextButton.disabled = false; // Enable the next button
        nextButton.click();
    } else {
        paymentSuccessful = false; // Set payment as not successful
        nextButton.disabled = true; // Keep the next button disabled
    }
});
// Add event listeners for payment method change
document.querySelectorAll('input[name="paymentMethod"]').forEach(radio => {
    radio.addEventListener('change', handlePaymentMethodChange);
});

// Attach event listener to the form submit button
document.querySelector('.btn-pay').addEventListener('click', validatePaymentForm);

// Initialize on page load
window.addEventListener('load', setInitialPaymentState);
