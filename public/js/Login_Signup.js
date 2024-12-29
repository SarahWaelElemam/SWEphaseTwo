window.onerror = function (message, source, lineno, colno, error) {
    console.error(`Error: ${message} at ${source}:${lineno}:${colno}`);
};

document.addEventListener("DOMContentLoaded", function () {
    // Switcher logic
    const switchers = [...document.querySelectorAll('.switcher')];
    switchers.forEach(item => {
        item.addEventListener('click', function () {
            switchers.forEach(item => item.parentElement.classList.remove('is-active'));
            this.parentElement.classList.add('is-active');
        });
    });

    // Signup form validation
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

});

document.addEventListener("DOMContentLoaded", function () {
    const forgotPasswordLink = document.getElementById("forgot-password");
    const forgotPasswordModal = document.getElementById("forgot-password-modal");
    const closeButton = forgotPasswordModal.querySelector(".close-button");
    const sendCodeButton = document.getElementById("send-code");
    const verifyCodeButton = document.getElementById("verify-code");
    const codeVerificationSection = document.getElementById("code-verification");

    // Show the modal when the "Forgot Password" link is clicked
    forgotPasswordLink.addEventListener("click", (event) => {
        event.preventDefault(); // Prevent default link behavior
        forgotPasswordModal.style.display = "block";
    });

    // Close the modal when the close button is clicked
    closeButton.addEventListener("click", () => {
        forgotPasswordModal.style.display = "none";
        resetModal();
    });

    // Close the modal when clicking outside the modal content
    window.addEventListener("click", (event) => {
        if (event.target === forgotPasswordModal) {
            forgotPasswordModal.style.display = "none";
            resetModal();
        }
    });

    // Handle the "Send Code" button click
    sendCodeButton.addEventListener("click", () => {
        const email = document.getElementById("forgot-email").value;

        if (!email) {
            alert("Please enter a valid email address.");
            return;
        }

        fetch('forget_password.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ action: 'send_code', email: email })
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);
                    codeVerificationSection.style.display = "block";
                } else {
                    alert(data.message);
                }
            });
    });

    // Handle the "Verify Code" button click
    verifyCodeButton.addEventListener("click", () => {
        const code = document.getElementById("verification-code").value;

        if (!code || code.length !== 4) {
            alert("Please enter a valid 4-digit code.");
            return;
        }

        fetch('forget_password.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ action: 'verify_code', code: code })
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);
                    promptPasswordReset();
                } else {
                    alert(data.message);
                }
            });
    });

    // Function to show password reset fields
    function promptPasswordReset() {
        const resetPasswordSection = document.createElement("div");
        resetPasswordSection.innerHTML = `
            <p>Enter your new password:</p>
            <input type="password" id="new-password" placeholder="New Password" required>
            <input type="password" id="confirm-new-password" placeholder="Confirm New Password" required>
            <button id="confirm-password-reset">Confirm</button>
        `;
        codeVerificationSection.parentNode.appendChild(resetPasswordSection);

        const confirmPasswordResetButton = document.getElementById("confirm-password-reset");
        confirmPasswordResetButton.addEventListener("click", () => {
            const newPassword = document.getElementById("new-password").value;
            const confirmNewPassword = document.getElementById("confirm-new-password").value;

            if (newPassword !== confirmNewPassword) {
                alert("Passwords do not match. Please try again.");
                return;
            }

            fetch('forget_password.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ action: 'reset_password', password: newPassword })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        forgotPasswordModal.style.display = "none";
                        resetModal();
                    } else {
                        alert(data.message);
                    }
                });
        });
    }

    // Reset the modal content
    function resetModal() {
        document.getElementById("forgot-email").value = "";
        document.getElementById("verification-code").value = "";
        codeVerificationSection.style.display = "none";
    }
});