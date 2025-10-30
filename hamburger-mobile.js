/* ===== HAMBURGER + MOBILE NAV ===== */
document.addEventListener("DOMContentLoaded", function () {
  const body         = document.body;
  const hamburger    = document.querySelector(".hamburger");
  const drawer       = document.querySelector(".mobile-nav");
  const overlay      = document.querySelector(".nav-overlay");

  // Desktop sources
  const desktopList  = document.querySelector(".main-header .nav-links");
  const secondaryBox = document.querySelector(".main-header .secondary-nav .secondary-links");
  const desktopReg   = document.querySelector(".main-header .secondary-nav .register-btn");

  // Mobile targets
  const mobileList   = document.querySelector(".mobile-nav .mobile-links");
  const mobileSecUl  = document.querySelector(".mobile-nav .mobile-secondary");
  const mobileRegBtn = document.querySelector(".mobile-nav .mobile-register");

  if (!hamburger || !drawer || !overlay || !mobileList || !desktopList || !mobileSecUl) {
    console.warn("[mobile-nav] Missing elements. Ensure header.php includes hamburger, mobile-nav, nav-overlay, .nav-links, and .mobile-secondary.");
    return;
  }

  /* --------------------
     1) Clone SECONDARY menu (upper)
  -------------------- */
  if (secondaryBox) {
    mobileSecUl.innerHTML = "";
    secondaryBox.querySelectorAll("a[href]").forEach((a) => {
      const li = document.createElement("li");
      const link = a.cloneNode(true);
      li.appendChild(link);
      mobileSecUl.appendChild(li);
    });
  }
  // Clone Register button text/action
  if (desktopReg && mobileRegBtn) {
    mobileRegBtn.textContent = desktopReg.textContent.trim() || "Register Now";
    // If your desktop button is a link, you can set window.location on click:
    mobileRegBtn.addEventListener("click", () => {
      // Update this to your real registration page/anchor if needed:
      // window.location.href = "register.php";
      desktopReg.click(); // fallback: trigger desktop button behavior if it has a listener
    });
  } else if (mobileRegBtn) {
    mobileRegBtn.remove(); // no desktop register; remove the mobile one
  }

  // Close on click for secondary links
  mobileSecUl.addEventListener("click", (e) => {
    const t = e.target;
    if (t instanceof Element && t.matches("a[href]")) closeMenu();
  });

  /* --------------------
     2) Clone PRIMARY menu (main) and convert dropdowns into accordions
  -------------------- */
  mobileList.innerHTML = desktopList.innerHTML;

  mobileList.querySelectorAll("li.dropdown").forEach((li) => {
    const link = li.querySelector("a, button");
    const dropdown = li.querySelector(".dropdown-content");
    if (!link || !dropdown) return;

    const label = link.textContent.trim();
    const btn = document.createElement("button");
    btn.className = "linklike";
    btn.type = "button";
    btn.setAttribute("aria-expanded", "false");
    btn.innerHTML = `<span>${label}</span><span class="chev">▶</span>`;
    link.replaceWith(btn);

    const submenu = document.createElement("ul");
    submenu.className = "submenu";
    dropdown.querySelectorAll("a[href]").forEach((a) => {
      const liSub = document.createElement("li");
      liSub.appendChild(a);
      submenu.appendChild(liSub);
    });
    dropdown.remove();
    li.appendChild(submenu);

    btn.addEventListener("click", () => {
      const open = submenu.classList.toggle("open");
      btn.setAttribute("aria-expanded", String(open));
      btn.querySelector(".chev")?.classList.toggle("rot", open);
    });
  });

  // Close on link click in main section
  mobileList.addEventListener("click", (e) => {
    const t = e.target;
    if (t instanceof Element && t.matches("a[href]")) closeMenu();
  });

  /* --------------------
     3) Open/close + safety
  -------------------- */
  // Prevent inside clicks from bubbling to overlay
  drawer.addEventListener("click", (e) => e.stopPropagation());

  const openMenu = () => {
    drawer.classList.add("open");
    overlay.classList.add("show");
    hamburger.classList.add("is-active");
    body.classList.add("body-lock");
    hamburger.setAttribute("aria-expanded", "true");
    drawer.setAttribute("aria-hidden", "false");
    drawer.focus();
  };
  const closeMenu = () => {
    drawer.classList.remove("open");
    overlay.classList.remove("show");
    hamburger.classList.remove("is-active");
    body.classList.remove("body-lock");
    hamburger.setAttribute("aria-expanded", "false");
    drawer.setAttribute("aria-hidden", "true");
  };

  hamburger.addEventListener("click", () => {
    if (drawer.classList.contains("open")) closeMenu(); else openMenu();
  });

  // Only close when the backdrop itself is clicked
  overlay.addEventListener("click", (e) => { if (e.target === overlay) closeMenu(); });

  document.addEventListener("keydown", (e) => { if (e.key === "Escape") closeMenu(); });
});
