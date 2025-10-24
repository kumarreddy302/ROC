document.addEventListener("DOMContentLoaded", function () {
  
  console.log("DOM fully loaded and parsed"); // Add this line for debugging

  // --- Hamburger Menu Toggle ---
  const hamburger = document.getElementById("hamburger-icon");
  const navWrapper = document.getElementById("nav-wrapper"); 
  const body = document.body; 

  if (hamburger && navWrapper) {
    console.log("Hamburger and NavWrapper found"); // Debugging
    hamburger.addEventListener("click", function () {
      console.log("Hamburger clicked"); // Debugging
      navWrapper.classList.toggle("nav-active"); 
      body.classList.toggle("nav-open"); 

      if (navWrapper.classList.contains("nav-active")) {
        hamburger.innerHTML = "&times;"; 
        hamburger.style.fontSize = "2.5rem";
      } else {
        hamburger.innerHTML = "&#9776;"; 
        hamburger.style.fontSize = "2rem";
      }
    });
  } else {
     console.error("Hamburger or NavWrapper not found!"); // Error if elements missing
  }

  // --- Mobile Dropdown Toggles ---
  const dropdownButtons = document.querySelectorAll(".nav-links .drop-btn");
  console.log(`Found ${dropdownButtons.length} dropdown buttons`); // Debugging

  dropdownButtons.forEach((button) => {
    button.addEventListener("click", function (event) {
      // Check if hamburger is displayed (reliable way to check mobile)
      const hamburgerStyle = window.getComputedStyle(hamburger);
      const isMobile = hamburgerStyle.display !== 'none'; 
      console.log(`Dropdown clicked. Is mobile? ${isMobile}`); // Debugging

      if (isMobile) {
        event.preventDefault(); 
        const dropdownContent = this.nextElementSibling;

        if (dropdownContent && dropdownContent.classList.contains('dropdown-content')) {
          console.log("Toggling dropdown content"); // Debugging
          // Close other dropdowns BEFORE opening/closing the current one
          if (!dropdownContent.classList.contains("active-dropdown")) {
             closeOtherDropdowns(this);
          }
          dropdownContent.classList.toggle("active-dropdown");
        } else {
          console.log("Clicked item is not a dropdown trigger or has no content"); // Debugging
          // If it's not a dropdown button, close others
           closeOtherDropdowns(null); 
        }
      }
    });
  });

  // Helper function to close other dropdowns
  function closeOtherDropdowns(currentButton) {
    console.log("Closing other dropdowns..."); // Debugging
    dropdownButtons.forEach((button) => {
      if (button !== currentButton) { 
        const dropdownContent = button.nextElementSibling;
        if (dropdownContent && dropdownContent.classList.contains("dropdown-content") && dropdownContent.classList.contains("active-dropdown")) {
          console.log("Removing active class from a dropdown"); // Debugging
          dropdownContent.classList.remove("active-dropdown");
        }
      }
    });
  }
  
  // Close mobile nav if clicking outside of it
  document.addEventListener('click', function(event) {
    if (navWrapper && hamburger) { // Check elements exist
        const isClickInsideNav = navWrapper.contains(event.target);
        const isClickOnHamburger = hamburger.contains(event.target);

        if (!isClickInsideNav && !isClickOnHamburger && navWrapper.classList.contains('nav-active')) {
          console.log("Clicked outside nav, closing mobile menu"); // Debugging
          navWrapper.classList.remove('nav-active');
          body.classList.remove('nav-open');
          hamburger.innerHTML = "&#9776;"; 
          hamburger.style.fontSize = "2rem";
           closeOtherDropdowns(null); // Close any open dropdowns when closing nav
        }
    }
  });

  // ===== NUMBER COUNTING ANIMATION =====
  console.log("Setting up number animation..."); // Debugging

  // Function to animate counting
  function animateValue(obj, start, end, duration) {
    let startTimestamp = null;
    const step = (timestamp) => {
      if (!startTimestamp) startTimestamp = timestamp;
      const progress = Math.min((timestamp - startTimestamp) / duration, 1);
      let currentVal = Math.floor(progress * (end - start) + start);
      
      let goalString = obj.dataset.goal || end.toString(); 

      // Format large numbers with commas 
      if (end >= 1000) {
          obj.innerHTML = currentVal.toLocaleString();
      } else {
          obj.innerHTML = currentVal;
      }

      // Add '+' sign back if it was in the data-goal and animation is complete
      if (goalString.includes('+') && progress === 1) {
           obj.innerHTML += '+';
      }

      if (progress < 1) {
        window.requestAnimationFrame(step);
      } else {
         console.log(`Animation finished for element reaching: ${obj.innerHTML}`); // Debugging
      }
    };
    window.requestAnimationFrame(step);
  }

  const numbersSection = document.querySelector(".story-numbers");
  const numberElements = document.querySelectorAll(".story-numbers .number[data-goal]");
  let hasAnimated = false;

  if (!numbersSection) {
      console.error("'.story-numbers' section not found!"); // Error if section missing
  }
   if (numberElements.length === 0) {
       console.warn("No number elements with 'data-goal' found in '.story-numbers'"); // Warning if no numbers found
   } else {
        console.log(`Found ${numberElements.length} number elements to animate.`); // Debugging
   }


  // Create an Intersection Observer
  const observer = new IntersectionObserver((entries) => {
    console.log("Intersection Observer callback triggered"); // Debugging
    entries.forEach(entry => {
       console.log(`Observing entry: ${entry.target.className}, Intersecting: ${entry.isIntersecting}`); // Debugging
      if (entry.isIntersecting && !hasAnimated) {
        console.log("'.story-numbers' section is intersecting and animation not run yet. Starting animation..."); // Debugging
        numberElements.forEach(numElement => {
          const goalRaw = numElement.dataset.goal;
          const goal = parseInt(goalRaw.replace(/,/g, '').replace('+', '')); 
          console.log(`Animating element from 0 to ${goal} (raw goal: ${goalRaw})`); // Debugging
          animateValue(numElement, 0, goal, 2000); 
        });
        hasAnimated = true; 
        console.log("Setting hasAnimated to true and unobserving."); // Debugging
        observer.unobserve(numbersSection); 
      } else if (entry.isIntersecting && hasAnimated) {
          console.log("Section is intersecting, but animation already ran."); // Debugging
      } else {
          console.log("Section is not intersecting."); // Debugging
      }
    });
  }, {
    threshold: 0.1 // Trigger when 10% of the section is visible
  });

  // Start observing the section only if it exists
  if (numbersSection) {
      console.log("Starting to observe '.story-numbers' section."); // Debugging
      observer.observe(numbersSection);
  }

}); // This MUST be the very last line