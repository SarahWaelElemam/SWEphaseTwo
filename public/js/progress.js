document.addEventListener('DOMContentLoaded', function() {
    const circles = document.querySelectorAll('.circle');
    const progressBars = document.querySelectorAll('.progress-bar');
    const prevButton = document.getElementById('prev');
    const nextButton = document.getElementById('next');
  
    let currentActive = 1;
    const totalSteps = circles.length;
  
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
      prevButton.disabled = currentActive === 1;
      nextButton.disabled = currentActive === totalSteps;
    }
  
    nextButton.addEventListener('click', () => {
      if (currentActive < totalSteps && confirmedSeats.length > 0) {
        currentActive++;
        updateStepper();
      } else if (confirmedSeats.length === 0) {
        showWarningModal(); // Show warning if no seats are selected
    }
    });
  
    prevButton.addEventListener('click', () => {
      if (currentActive > 1) {
        currentActive--;
        updateStepper();
      }
    });
  
    // Initialize the stepper on page load
    updateStepper();
  });
  
  window.onload = function() {
    console.log('Page loaded, setting default view');
    setDefaultView();
    prevButton.disabled = true;
  
    // Trigger the animation
    const receipts = document.querySelector('.receipts');
    receipts.classList.add('animate'); // Add a class to trigger animation
  };