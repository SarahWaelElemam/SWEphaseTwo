// user.js

document.addEventListener("DOMContentLoaded", function () {
    const editBtn = document.getElementById("edit-btn");
    const confirmBtn = document.getElementById("confirm-btn");
    const deleteBtn = document.getElementById("delete-btn");
    const confirmDeleteBtn = document.getElementById("confirm-delete");
    const cancelDeleteBtn = document.getElementById("cancel-delete");
    const editForm = document.getElementById("edit-form");
    const confirmationDialog = document.getElementById("confirmation-dialog");
    const overlay = document.getElementById("overlay");

    // Show the edit form and populate current values
    editBtn.addEventListener("click", function () {
        editForm.style.display = "block";

        // Get current values
        document.getElementById("edit-name").value = document.getElementById("profile-name").innerText;
        document.getElementById("edit-phone").value = document.getElementById("profile-phone").innerText;
        document.getElementById("edit-gender").value = document.getElementById("profile-gender").innerText;
        document.getElementById("edit-location").value = document.getElementById("profile-location").innerText;
        document.getElementById("edit-email").value = document.getElementById("profile-email").innerText;
    });

    // Confirm the edits and update profile details
    confirmBtn.addEventListener("click", function () {
        document.getElementById("profile-name").innerText = document.getElementById("edit-name").value;
        document.getElementById("profile-phone").innerText = document.getElementById("edit-phone").value;
        document.getElementById("profile-gender").innerText = document.getElementById("edit-gender").value;
        document.getElementById("profile-location").innerText = document.getElementById("edit-location").value;
        document.getElementById("profile-email").innerText = document.getElementById("edit-email").value;

        // Hide the edit form after confirming
        editForm.style.display = "none";
    });

    // Show the confirmation dialog for deleting the account
    deleteBtn.addEventListener("click", function () {
        confirmationDialog.style.display = "block";
        overlay.style.display = "block";
    });

    // Handle confirmation of account deletion
    confirmDeleteBtn.addEventListener("click", function () {
        // Redirect to the homepage (replace 'home.php' with your actual homepage file)
        window.location.href = 'home.php';
    });

    // Handle cancellation of account deletion
    cancelDeleteBtn.addEventListener("click", function () {
        confirmationDialog.style.display = "none";
        overlay.style.display = "none";
    });
});