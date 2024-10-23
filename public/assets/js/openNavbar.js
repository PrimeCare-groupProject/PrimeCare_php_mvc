// JavaScript to handle the menu toggle for mobile view
const menuBtn = document.getElementById('menu-btn'); // Hamburger icon
const navLinks = document.getElementById('nav-links'); // UL element

menuBtn.addEventListener('click', () => {
    console.log('clicked');
    navLinks.classList.toggle('open'); // Toggle the 'open' class to show/hide menu
});
