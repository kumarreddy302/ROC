document.addEventListener("DOMContentLoaded", function () {
  
  // --- Hamburger Menu Toggle ---
  const hamburger = document.getElementById("hamburger-icon");
  const navWrapper = document.getElementById("nav-wrapper"); // Changed target

  if (hamburger && navWrapper) {
    hamburger.addEventListener("click", function () {
      navWrapper.classList.toggle("nav-active"); // Changed target
      
      // Toggle icon to an 'X'
      if (navWrapper.classList.contains("nav-active")) {
        hamburger.innerHTML = "&times;"; // 'X' icon
        hamburger.style.fontSize = "2.5rem";
      } else {
        hamburger.innerHTML = "&#9776;"; // '☰' icon
        hamburger.style.fontSize = "2rem";
      }
    });
  }

  // --- Mobile Dropdown Toggles (No changes needed here) ---
  const dropdownButtons = document.querySelectorAll(".nav-links .drop-btn");

  dropdownButtons.forEach((button) => {
    button.addEventListener("click", function (event) {
      // Check if we are in mobile view
      const isMobile = window.getComputedStyle(hamburger).display === "block";

      if (isMobile) {
        event.preventDefault(); 
        const dropdownContent = this.nextElementSibling;

        if (dropdownContent) {
          dropdownContent.classList.toggle("active-dropdown");
        }
        
        closeOtherDropdowns(this);
      }
    });
  });

  // Helper function to close other dropdowns
  function closeOtherDropdowns(currentButton) {
    dropdownButtons.forEach((button) => {
      if (button !== currentButton) {
        const dropdownContent = button.nextElementSibling;
        if (dropdownContent) {
          dropdownContent.classList.remove("active-dropdown");
        }
      }
    });
  }
  
});