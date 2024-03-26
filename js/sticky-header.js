// Sticky Header
const header = document.querySelector('header.sticky');
// Add a scroll event listener to the window
window.addEventListener('scroll', () => {
  // Check if the user has scrolled down
  if (window.scrollY > 0) {
    // Add a CSS class to the header element when scrolled
    header.classList.add('scrolled');
  } else {
    // Remove the CSS class from the header element when not scrolled
    header.classList.remove('scrolled');
  }
});
