document.addEventListener('DOMContentLoaded', () => {
    const toggleSwitch = document.getElementById('theme-toggle');
    const body = document.body;
    const searchIcon = document.querySelector('.search-icon');
    const menuToggle = document.querySelector('.menu-toggle');
    const navItems = document.querySelector('.nav-items');

    toggleSwitch.addEventListener('change', () => {
        if (toggleSwitch.checked) {
            body.style.backgroundColor = '#333';
            body.style.color = '#fff';
            searchIcon.style.color = '#fff';
        } else {
            body.style.backgroundColor = '#f5f5f5';
            body.style.color = '#000';
            searchIcon.style.color = '#000';
        }
    });

    menuToggle.addEventListener('click', () => {
        navItems.classList.toggle('active');
        menuToggle.classList.toggle('active');

        menuToggle.querySelectorAll('span').forEach((span, index) => {
            if (menuToggle.classList.contains('active')) {
                if (index === 0) span.style.transform = 'rotate(45deg) translate(5px, 5px)';
                if (index === 1) span.style.opacity = '0';
                if (index === 2) span.style.transform = 'rotate(-45deg) translate(7px, -7px)';
            } else {
                span.style.transform = 'none';
                span.style.opacity = '1';
            }
        });
    });

    document.addEventListener('click', (event) => {
        if (!navItems.contains(event.target) && !menuToggle.contains(event.target) && navItems.classList.contains('active')) {
            navItems.classList.remove('active');
            menuToggle.classList.remove('active');
            menuToggle.querySelectorAll('span').forEach(span => {
                span.style.transform = 'none';
                span.style.opacity = '1';
            });
        }
    });
});