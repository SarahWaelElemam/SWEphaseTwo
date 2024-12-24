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