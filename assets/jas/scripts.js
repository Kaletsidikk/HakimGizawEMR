// scripts.js

document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menu-toggle');
    const navMenu = document.querySelector('.nav-menu.toggle-menu');

    // When the checkbox is clicked, toggle the menu
    menuToggle.addEventListener('change', function() {
        if (menuToggle.checked) {
            navMenu.style.transform = 'translateX(0)';
            document.body.style.overflow = 'hidden'; // Prevent scrolling while menu is open
        } else {
            navMenu.style.transform = 'translateX(100%)';
            document.body.style.overflow = ''; // Restore scrolling
        }
    });

    // Close the menu when clicking outside of it
    document.addEventListener('click', function(event) {
        if (!navMenu.contains(event.target) && !menuToggle.contains(event.target) && menuToggle.checked) {
            menuToggle.checked = false;  // Uncheck the menu toggle
            navMenu.style.transform = 'translateX(100%)'; // Hide menu
        }
    });

    // Close the menu when pressing ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && menuToggle.checked) {
            menuToggle.checked = false;  // Uncheck the menu toggle
            navMenu.style.transform = 'translateX(100%)'; // Hide menu
        }
    });
});
