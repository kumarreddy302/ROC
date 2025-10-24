<?php
// Get the current page filename
$current_page = basename($_SERVER['PHP_SELF']);
$body_classes = ''; // Initialize body classes string

// Add specific class based on page
// **** APPLY 'page-usa' class to BOTH usa.php AND uk.php ****
if ($current_page == 'usa.php' || $current_page == 'uk.php') {
    $body_classes .= ' page-usa page-studyabroad'; // Use the same class for styling consistency
}
// Add more classes for other specific pages if needed later
// elseif ($current_page == 'ielts.php') {
//    $body_classes .= ' page-ielts page-training';
// }

// Trim whitespace just in case
$body_classes = trim($body_classes);

// Determine active state for parent nav items
$is_studyabroad_active = ($current_page == 'usa.php' || $current_page == 'uk.php' /* || add other study abroad pages */ );
$is_training_active = ($current_page == 'ielts.php' || $current_page == 'toefl.php' || $current_page == 'gre.php');

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
                <a href="#">Australia</a>
                <a href="#">Canada</a>
              </div>
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