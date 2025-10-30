<?php
$current_page = basename($_SERVER['PHP_SELF']);

// Page categories
$study_abroad_pages = [
  'usa.php','uk.php','australia.php','canada.php','ireland.php',
  'new-zealand.php','singapore.php','switzerland.php','europe.php','asia.php'
];
$training_pages = ['ielts.php','toefl.php','gre.php'];
$company_pages  = ['about.php','our_vision.php'];

$is_studyabroad_active = in_array($current_page, $study_abroad_pages, true);
$is_training_active    = in_array($current_page, $training_pages, true);
$is_company_active     = in_array($current_page, $company_pages, true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Rudra Overseas</title>

  <!-- Main site style -->
  <link rel="stylesheet" href="style.css" />

  <!-- Mobile nav styles -->
  <link rel="stylesheet" href="hamburger-mobile.css" />

  <!-- Mobile nav script -->
  <script defer src="hamburger-mobile.js"></script>
</head>
<body>

<header class="main-header">
  <div class="logo">
    <a href="index.php">
      <img src="LOGO RUDRA.png" alt="Rudra Overseas Logo" />
    </a>
  </div>

  <div class="header-right">
    <div class="nav-wrapper" id="nav-wrapper">
      <!-- Secondary (upper) menu -->
      <div class="secondary-nav">
        <div class="secondary-links">
          <a href="#">Application Process</a>
          <a href="#">Testimonials</a>
          <a href="#">FAQ's</a>
          <a href="#">News & Updates</a>
          <a href="#" class="disabled">Blogs</a>
        </div>
        <button class="register-btn">Register Now</button>
      </div>

      <!-- Primary (main) menu -->
      <nav class="main-nav" id="main-nav">
        <ul class="nav-links">
          <li><a href="index.php" class="<?= $current_page==='index.php' ? 'active' : '' ?>">Home</a></li>

          <li class="dropdown">
            <a href="#" class="drop-btn <?= $is_company_active ? 'active' : '' ?>">Company ▾</a>
            <div class="dropdown-content">
              <a href="about.php"        class="<?= $current_page==='about.php' ? 'active' : '' ?>">About Us</a>
              <a href="our_vision.php"   class="<?= $current_page==='our_vision.php' ? 'active' : '' ?>">Our Vision</a>
              <a href="#">Team</a>
              <a href="#">Contact</a>
            </div>
          </li>

          <li class="dropdown">
            <a href="#" class="drop-btn <?= $is_training_active ? 'active' : '' ?>">Training ▾</a>
            <div class="dropdown-content">
              <a href="ielts.php" class="<?= $current_page==='ielts.php' ? 'active' : '' ?>">IELTS</a>
              <a href="toefl.php" class="<?= $current_page==='toefl.php' ? 'active' : '' ?>">TOEFL</a>
              <a href="gre.php"   class="<?= $current_page==='gre.php'   ? 'active' : '' ?>">GRE</a>
            </div>
          </li>

          <li class="dropdown">
            <a href="#" class="drop-btn <?= $is_studyabroad_active ? 'active' : '' ?>">Study Abroad ▾</a>
            <div class="dropdown-content">
              <a href="usa.php"         class="<?= $current_page==='usa.php' ? 'active' : '' ?>">USA</a>
              <a href="uk.php"          class="<?= $current_page==='uk.php' ? 'active' : '' ?>">UK</a>
              <a href="australia.php"   class="<?= $current_page==='australia.php' ? 'active' : '' ?>">Australia</a>
              <a href="canada.php"      class="<?= $current_page==='canada.php' ? 'active' : '' ?>">Canada</a>
              <a href="ireland.php"     class="<?= $current_page==='ireland.php' ? 'active' : '' ?>">Ireland</a>
              <a href="new-zealand.php" class="<?= $current_page==='new-zealand.php' ? 'active' : '' ?>">New Zealand</a>
              <a href="singapore.php"   class="<?= $current_page==='singapore.php' ? 'active' : '' ?>">Singapore</a>
              <a href="switzerland.php" class="<?= $current_page==='switzerland.php' ? 'active' : '' ?>">Switzerland</a>
              <a href="europe.php"      class="<?= $current_page==='europe.php' ? 'active' : '' ?>">Europe</a>
              <a href="asia.php"        class="<?= $current_page==='asia.php' ? 'active' : '' ?>">Asia</a>
            </div>
          </li>

          <li><a href="#">Our Events</a></li>
        </ul>
      </nav>
    </div>
  </div>

  <!-- Mobile trigger + drawer + overlay -->
  <button class="hamburger" aria-label="Open menu" aria-expanded="false" aria-controls="mobile-drawer">
    <span></span><span></span><span></span>
  </button>

  <nav class="mobile-nav" id="mobile-drawer" aria-hidden="true" tabindex="-1">
    <!-- Upper (secondary) section -->
    <div class="mobile-sec">
      <ul class="mobile-secondary"></ul>
      <button class="register-btn mobile-register" type="button">Register Now</button>
    </div>
    <hr class="mobile-sep" />
    <!-- Primary (main) section -->
    <ul class="mobile-links"></ul>
  </nav>

  <div class="nav-overlay" aria-hidden="true"></div>
</header>
