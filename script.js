document.addEventListener("DOMContentLoaded", function () {

  console.log("DOM fully loaded and parsed");

  // --- Hamburger Menu Code REMOVED ---
  // --- Mobile Dropdown Toggle Code REMOVED ---
  // --- Click Outside Logic REMOVED ---
  // --- Regular Link Click Logic REMOVED ---
  // --- Helper functions for menu REMOVED ---


  // ===== NUMBER COUNTING ANIMATION =====
  console.log("Setting up number animation...");
  function animateValue(obj, start, end, duration) {
    let startTimestamp = null;
    const step = (timestamp) => {
      if (!startTimestamp) startTimestamp = timestamp;
      const progress = Math.min((timestamp - startTimestamp) / duration, 1);
      let currentVal = Math.floor(progress * (end - start) + start);
      let goalString = obj.dataset.goal || end.toString();
      if (end >= 1000) { obj.innerHTML = currentVal.toLocaleString(); } else { obj.innerHTML = currentVal; }
      if (goalString.includes('+') && progress === 1) { obj.innerHTML += '+'; }
      if (progress < 1) { window.requestAnimationFrame(step); } else { console.log(`Animation finished for element reaching: ${obj.innerHTML}`); }
    };
    window.requestAnimationFrame(step);
  }
  const numbersSection = document.querySelector(".story-numbers");
  const numberElements = document.querySelectorAll(".story-numbers .number[data-goal]");
  let hasAnimated = false;
  if (!numbersSection) { console.error("'.story-numbers' section not found!"); }
  if (numberElements.length === 0) { console.warn("No number elements with 'data-goal' found in '.story-numbers'"); } else { console.log(`Found ${numberElements.length} number elements to animate.`); }
  const observer = new IntersectionObserver((entries) => { console.log("Intersection Observer callback triggered"); entries.forEach(entry => { console.log(`Observing entry: ${entry.target.className}, Intersecting: ${entry.isIntersecting}`); if (entry.isIntersecting && !hasAnimated) { console.log("'.story-numbers' section is intersecting and animation not run yet. Starting animation..."); numberElements.forEach(numElement => { const goalRaw = numElement.dataset.goal; const goal = parseInt(goalRaw.replace(/,/g, '').replace('+', '')); console.log(`Animating element from 0 to ${goal} (raw goal: ${goalRaw})`); animateValue(numElement, 0, goal, 2000); }); hasAnimated = true; console.log("Setting hasAnimated to true and unobserving."); observer.unobserve(numbersSection); } else if (entry.isIntersecting && hasAnimated) { console.log("Section is intersecting, but animation already ran."); } else { console.log("Section is not intersecting."); } }); }, { threshold: 0.1 });
  if (numbersSection) { console.log("Starting to observe '.story-numbers' section."); observer.observe(numbersSection); }

   // ===== SECTION SCROLL ANIMATION (CODE REMOVED as per previous request) =====

}); // This MUST be the very last line