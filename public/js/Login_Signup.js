window.onerror = function (message, source, lineno, colno, error) {
  console.error(`Error: ${message} at ${source}:${lineno}:${colno}`);
};

const switchers = [...document.querySelectorAll('.switcher')]

switchers.forEach(item => {
	item.addEventListener('click', function() {
		switchers.forEach(item => item.parentElement.classList.remove('is-active'))
		this.parentElement.classList.add('is-active')
	})
})
document.getElementById('signupForm').addEventListener('submit', function (e) {
    e.preventDefault(); // Prevent form submission to handle validations

    const birthdate = new Date(document.getElementById('birthdate').value);
    const age = new Date().getFullYear() - birthdate.getFullYear();

    if (age < 13) {
      alert('You must be at least 13 years old to create an account.');
      return;
    }

    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmPassword').value;

    if (password !== confirmPassword) {
      alert('Passwords do not match. Please try again.');
      return;
    }

    alert('Account created successfully!'); // For testing purposes
    this.submit(); // Submit the form if validations pass
  });
  document.addEventListener("DOMContentLoaded", function () {
    const switchers = [...document.querySelectorAll('.switcher')];

    switchers.forEach(item => {
        item.addEventListener('click', function() {
            switchers.forEach(item => item.parentElement.classList.remove('is-active'));
            this.parentElement.classList.add('is-active');
        });
    });

    // Forgot Password Modal Logic
    const forgotPasswordLink = document.getElementById("forgot-password-link");
    const forgotPasswordModal = document.getElementById("forgot-password-modal");
    const closeForgotPasswordModal = document.getElementById("close-forgot-password-modal");
    const confirmCodeBtn = document.getElementById("confirm-code-btn");

    // Show the forgot password modal
    forgotPasswordLink.addEventListener("click", function (event) {
        event.preventDefault(); // Prevent default anchor behavior
        forgotPasswordModal.style.display = "block"; // Show the modal
    });

    // Close the modal when the close button is clicked
    closeForgotPasswordModal.addEventListener("click", function () {
        forgotPasswordModal.style.display = "none"; // Hide the modal
    });

    // Close the modal if the user clicks outside of the modal content
    window.addEventListener("click", function (event) {
        if (event.target === forgotPasswordModal) {
            forgotPasswordModal.style.display = "none"; // Hide the modal
        }
    });

    // Handle the confirmation of the verification code
    confirmCodeBtn.addEventListener("click", function () {
        const verificationCode = document.getElementById("verification-code").value;
        // Here you can add logic to verify the code
        alert("Verification code entered: " + verificationCode); // For testing purposes
        forgotPasswordModal.style.display = "none"; // Hide the modal after confirmation
    });
});
document.querySelector('.form-login').addEventListener('submit', function (e) {
  console.log("Login form submitted!");
});
