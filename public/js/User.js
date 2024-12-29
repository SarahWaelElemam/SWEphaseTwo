document.addEventListener("DOMContentLoaded", function () {
    const editBtn = document.getElementById("edit-btn");
    const confirmBtn = document.getElementById("confirm-btn");
    const deleteBtn = document.getElementById("delete-btn");
    const confirmDeleteBtn = document.getElementById("confirm-delete");
    const cancelDeleteBtn = document.getElementById("cancel-delete");
    const editForm = document.getElementById("edit-form");
    const confirmationDialog = document.getElementById("confirmation-dialog");
    const overlay = document.getElementById("overlay");

    // Load user profile data
    function loadProfileData() {
        fetch('UserController.php?action=getUserProfile', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById("profile-name").innerText = `${data.FName} ${data.LName}`;
            document.getElementById("profile-phone").innerText = data.Phone;
            document.getElementById("profile-gender").innerText = data.Gender;
            document.getElementById("profile-location").innerText = data.Government;
            document.getElementById("profile-email").innerText = data.Email;
            document.getElementById("profile-join-date").innerText = data.join_date;
        })
        .catch(error => console.error('Error loading profile:', error));
    }

    // Load profile data when page loads
    loadProfileData();

    // Show the edit form and populate current values
    editBtn.addEventListener("click", function () {
        editForm.style.display = "block";
        
        // Split full name into first and last name
        const fullName = document.getElementById("profile-name").innerText.split(' ');
        document.getElementById("edit-fname").value = fullName[0];
        document.getElementById("edit-lname").value = fullName[1];
        document.getElementById("edit-phone").value = document.getElementById("profile-phone").innerText;
        document.getElementById("edit-gender").value = document.getElementById("profile-gender").innerText;
        document.getElementById("edit-government").value = document.getElementById("profile-location").innerText;
        document.getElementById("edit-email").value = document.getElementById("profile-email").innerText;
    });

    // Confirm the edits and update profile details
    confirmBtn.addEventListener("click", function () {
        const formData = new FormData();
        formData.append('fname', document.getElementById("edit-fname").value);
        formData.append('lname', document.getElementById("edit-lname").value);
        formData.append('phone', document.getElementById("edit-phone").value);
        formData.append('gender', document.getElementById("edit-gender").value);
        formData.append('government', document.getElementById("edit-government").value);
        formData.append('email', document.getElementById("edit-email").value);

        fetch('UserController.php?action=updateProfile', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadProfileData();
                editForm.style.display = "none";
            } else {
                alert('Failed to update profile: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(error => console.error('Error updating profile:', error));
    });

    // Handle account deletion
    confirmDeleteBtn.addEventListener("click", function () {
        fetch('UserController.php?action=deleteProfile', {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'home.php';
            } else {
                alert('Failed to delete account: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(error => console.error('Error deleting account:', error));
    });

    // Cancel account deletion
    cancelDeleteBtn.addEventListener("click", function () {
        confirmationDialog.style.display = "none";
        overlay.style.display = "none";
    });
});