<?php
// Get the current page filename
$current_page = basename($_SERVER['PHP_SELF']);
$body_classes = ''; // Initialize body classes string

// Define Study Abroad pages
$study_abroad_pages = ['usa.php', 'uk.php', 'australia.php', 'canada.php'];
// Define Training pages
$training_pages = ['ielts.php', 'toefl.php', 'gre.php'];

// Add body classes based on page type
if (in_array($current_page, $study_abroad_pages)) {
    $body_classes .= ' page-usa page-studyabroad'; // Use 'page-usa' for consistent modern styling
    // Add specific page class if needed later e.g. ' page-australia'
} elseif (in_array($current_page, $training_pages)) {
     $body_classes .= ' page-training'; // Add a general class for training pages
     // Add specific page class if needed later e.g. ' page-ielts'
}

// Trim whitespace just in case
$body_classes = trim($body_classes);

// Determine active state for parent nav items
$is_studyabroad_active = in_array($current_page, $study_abroad_pages);
$is_training_active = in_array($current_page, $training_pages);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Rudra Overseas Education</title>
  
  <link rel="stylesheet" href="style.css" />
  
  <script src="script.js" defer></script> 
</head>
<body class="<?php echo htmlspecialchars($body_classes); ?>"> <header class="main-header">
    
    <div class="logo">
      <a href="index.php">
        <img src="LOGO RUDRA.png" alt="Rudra Overseas Logo" /> 
      </a>
    </div>

    <div class="header-right">

      <div class="hamburger" id="hamburger-icon">
        &#9776; 
      </div>

      <div class="nav-wrapper" id="nav-wrapper">

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

        <nav class="main-nav" id="main-nav">
          <ul class="nav-links">
            <li><a href="index.php" class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">Home</a></li>
            <li class="dropdown">
              <a href="#" class="drop-btn">Company ▾</a>
              <div class="dropdown-content">
                <a href="#">About Us</a>
                <a href="#">Our Vision</a>
                <a href="#">Team</a>
                <a href="#">Contact</a>
              </div>
            </li>
            <li class="dropdown">
              <a href="#" class="drop-btn">Services ▾</a>
              <div class="dropdown-content">
                <a href="#">Career Counseling</a>
                <a href="#">Visa Assistance</a>
                <a href="#">Financial Guidance</a>
              </div>
            </li>
            <li class="dropdown">
              <a href="#" class="drop-btn <?php echo $is_studyabroad_active ? 'active' : ''; ?>">Study Abroad ▾</a> 
              <div class="dropdown-content">
                 <a href="usa.php" class="<?php echo ($current_page == 'usa.php') ? 'active' : ''; ?>">USA</a>
                 <a href="uk.php" class="<?php echo ($current_page == 'uk.php') ? 'active' : ''; ?>">UK</a> 
                 <a href="australia.php" class="<?php echo ($current_page == 'australia.php') ? 'active' : ''; ?>">Australia</a> <a href="canada.php" class="<?php echo ($current_page == 'canada.php') ? 'active' : ''; ?>">Canada</a> </div>
            </li>
            <li class="dropdown">
               <a href="#" class="drop-btn <?php echo $is_training_active ? 'active' : ''; ?>">Training ▾</a> 
              <div class="dropdown-content">
                <a href="ielts.php" class="<?php echo ($current_page == 'ielts.php') ? 'active' : ''; ?>">IELTS</a>
                <a href="toefl.php" class="<?php echo ($current_page == 'toefl.php') ? 'active' : ''; ?>">TOEFL</a>
                <a href="gre.php" class="<?php echo ($current_page == 'gre.php') ? 'active' : ''; ?>">GRE</a>
              </div>
            </li>
            <li><a href="#">Our Events</a></li>
          </ul>
        </nav>

      </div> 
    </div> 
  </header>