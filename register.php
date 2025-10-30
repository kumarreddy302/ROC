<?php
/* REGISTER.PHP
  This page includes the header and footer for UI consistency.
  It contains a data array of all universities, extracted from your
  other .php files (usa.php, uk.php, etc.) to power the 
  dependent dropdowns.
*/

// --- University Data Array ---
// This data is extracted from your provided university-list sections
$countries = [
    "Australia" => [
        "Australian Catholic University", "Australian National University", "Bond University", "Canberra Institute Of Technology", "Central Queensland University", "Charles Darwin University", "Charles Sturt University Study Centre", "Curtin University", "Deakin University", "Edith Cowan University", "Federation University", "Flinders University", "Griffith University", "International College Of Management Sydney", "James Cook University", "Kaplan Business School", "La Trobe University", "Macquarie University", "Melbourne Institute Of Technology", "Monash University", "Murdoch University", "Queensland University Of Technology", "RMIT University", "Southern Cross University", "Swinburne University Of Technology", "TAFE NSW", "TAFE Queensland", "TAFE SA", "TAFE WA", "Torrens University", "University Of Adelaide", "University Of Canberra", "University Of Melbourne", "University Of New England", "University Of New South Wales", "University Of Newcastle", "University Of Notre Dame", "University Of Queensland", "University Of South Australia", "University Of Southern Queensland", "University Of Sydney", "University Of Tasmania", "University Of Technology Sydney", "University Of The Sunshine Coast", "University Of Wollongong", "Victoria University", "Western Sydney University"
    ],
    "Asia (Partner Institutions)" => [
        "Amity Global Institute Dubai", "Curtin University Dubai", "Heriot-Watt University Dubai", "Manipal Academy Of Higher Education Dubai", "Middlesex University Dubai", "Murdoch University Dubai", "University Of Wollongong Dubai"
    ],
    "Canada" => [
        "Acadia University", "Alexander College", "Algonquin College", "Ascenda School Of Management", "Bow Valley College", "Brandon University", "British Columbia Institute Of Technology", "Brock University", "Cambrian College", "Camosun College", "Canada College", "Canadore College", "Cape Breton University", "Capilano University", "Carleton University", "Centennial College", "College Of New Caledonia", "College Of The Rockies", "Columbia College", "Concordia University", "Conestoga College", "Confederation College", "Coquitlam College", "Dalhousie University", "Douglas College", "Durham College", "Emily Carr University Of Art + Design", "Fairleigh Dickinson University", "Fanshawe College", "Fleming College", "Fraser International College", "George Brown College", "Georgian College", "Humber College", "International College Of Manitoba", "Kwantlen Polytechnic University", "Lakehead University", "Lambton College", "Langara College", "LaSalle College", "Laurentian University", "Loyalist College", "MacEwan University", "Memorial University Of Newfoundland", "Mohawk College", "Mount Allison University", "Mount Saint Vincent University", "Niagara College", "Nipissing University", "North Island College", "Northern Alberta Institute Of Technology", "Northern College", "Northern Lights College", "Okanagan College", "Ontario Tech University", "Queen's University", "Red River College", "Royal Roads University", "Ryerson University International College", "Saint Mary's University", "Saskatchewan Polytechnic", "Sault College", "Selkirk College", "Seneca College", "Sheridan College", "Simon Fraser University", "Southern Alberta Institute Of Technology", "St Clair College", "St Francis Xavier University", "St Lawrence College", "St Thomas University", "Stenberg College", "Thompson Rivers University", "Toronto Metropolitan University", "Trent University", "Trinity Western University", "University Canada West", "University Of Alberta", "University Of Calgary", "University Of Guelph", "University Of Lethbridge", "University Of Manitoba", "University Of New Brunswick", "University Of Northern British Columbia", "University Of Ottawa", "University Of Prince Edward Island", "University Of Regina", "University Of Saskatchewan", "University Of The Fraser Valley", "University Of Toronto", "University Of Victoria", "University Of Waterloo", "University Of Windsor", "Vancouver Community College", "Vancouver Film School", "Vancouver Island University", "Wilfrid Laurier International College", "Wilfrid Laurier University", "York University", "Yorkville University"
    ],
    "Europe (Partner Institutions)" => [
        "Berlin School Of Business & Innovation", "EU Business School", "GISMA Business School", "Jacobs University Bremen", "Karlsruhe Institute Of Technology", "Lancaster University Leipzig", "SRH Hochschule Berlin", "University Of Applied Sciences Europe"
    ],
    "Ireland" => [
        "Athlone Institute Of Technology", "Dublin Business School", "Dublin City University", "Dundalk Institute Of Technology", "Galway-Mayo Institute Of Technology", "Griffith College", "IBAT College Dublin", "ICD Business School", "Independent College", "Institute Of Technology Carlow", "Institute Of Technology Sligo", "Letterkenny Institute Of Technology", "Limerick Institute Of Technology", "Maynooth University", "Munster Technological University", "National College Of Ireland", "Technological University Dublin", "Trinity College Dublin", "University College Cork", "University College Dublin", "University Of Limerick", "Waterford Institute Of Technology"
    ],
    "New Zealand" => [
        "Ara Institute Of Canterbury", "Auckland Institute of Studies", "Auckland University Of Technology", "Avonmore Tertiary Institute", "Eastern Institute Of Technology", "Le Cordon Bleu", "Lincoln University", "Manukau Institute Of Technology", "Massey University", "Nelson Marlborough Institute Of Technology", "NorthTec", "Otago Polytechnic", "Pacific International Hotel Management School", "Southern Institute Of Technology", "Toi Ohomai Institute Of Technology", "Unitec Institute Of Technology", "University Of Auckland", "University Of Canterbury", "University Of Otago", "University Of Waikato", "Victoria University Of Wellington", "Waikato Institute OfTechnology", "Wellington Institute Of Technology", "Western Institute Of Technology At Taranaki", "Whitecliffe College Of Arts & Design"
    ],
    "Singapore" => [
        "Amity Global Institute", "James Cook University", "Kaplan Higher Education Academy", "London School Of Business & Finance", "Management Development Institute Of Singapore", "PSB Academy", "Singapore Institute Of Management"
    ],
    "Switzerland" => [
        "BHMS - Business & Hotel Management School", "EU Business School", "Geneva Business School", "Glion Institute of Higher Education", "Hotel & Tourism Management Institute Switzerland", "Hotel Institute Montreux", "International Management Institute", "Les Roches Global Hospitality Education", "Swiss Hotel Management School"
    ],
    "UK" => [
        "Anglia Ruskin University", "Arts University Bournemouth", "Aston University", "Bangor University", "Bath Spa University", "Birkbeck University Of London", "Birmingham City University", "Bournemouth University", "BPP University", "Brunel University London", "Canterbury Christ Church University", "Cardiff Metropolitan University", "Cardiff University", "City, University Of London", "Coventry University", "Cranfield University", "De Montfort University", "Durham University", "Edge Hill University", "Edinburgh Napier University", "Glasgow Caledonian University", "Goldsmiths, University Of London", "Heriot Watt University", "Imperial College London", "Istituto Marangoni", "Keele University", "King's College London", "Kingston University", "Lancaster University", "Leeds Beckett University", "Liverpool Hope University", "Liverpool John Moores University", "London Metropolitan University", "London School Of Economics And Political Science", "London South Bank University", "Loughborough University", "Manchester Metropolitan University", "Middlesex University", "Newcastle University", "Northumbria University", "Nottingham Trent University", "Oxford Brookes University", "Plymouth Marjon University", "Queen Mary University Of London", "Queen's University Belfast", "Ravensbourne University London", "Regent's University London", "Robert Gordon University", "Royal Holloway University Of London", "Sheffield Hallam University", "Solent University", "SOAS University Of London", "St Mary's University Twickenham London", "Swansea University", "Teesside University", "The University Of Law", "Ulster University", "University College Birmingham", "University College London", "University For The Creative Arts", "University Of Aberdeen", "University Of Bath", "University Of Bedfordshire", "University Of Birmingham", "University Of Bolton", "University Of Bradford", "University Of Brighton", "University Of Bristol", "University Of Central Lancashire", "University Of Chester", "University Of Chichester", "University Of Derby", "University Of Dundee", "University Of East Anglia", "University Of East London", "University Of Edinburgh", "University Of Essex", "University Of Exeter", "University Of Glasgow", "University Of Gloucestershire", "University Of Greenwich", "University Of Hertfordshire", "University Of Huddersfield", "University Of Hull", "University Of Kent", "University Of Leeds", "University Of Leicester", "University Of Lincoln", "University Of Liverpool", "University Of Manchester", "University Of Northampton", "University Of Nottingham", "University Of Plymouth", "University Of Portsmouth", "University Of Reading", "University Of Roehampton", "University Of Salford", "University Of Sheffield", "University Of South Wales", "University Of Southampton", "University Of Stirling", "University Of Strathclyde", "University Of Sunderland", "University Of Surrey", "University Of Sussex", "University Of The West Of England", "University Of The West Of Scotland", "University Of Warwick", "University Of West London", "University Of Westminster", "University Of Wolverhampton", "University Of Worcester", "University Of York"
    ],
    "USA" => [
        "Abilene Christian University", "Adelphi University", "American University", "Arizona State University", "Arkansas State University", "Auburn University", "Auburn University At Montgomery", "Augustana University", "Bay Path University", "Baylor University", "Berkeley College", "California State University, Dominguez Hills", "California State University, East Bay", "California State University, Fresno", "California State University, Monterey Bay", "California State University, Northridge", "California State University, San Bernardino", "California State University, San Marcos", "Clark University", "Cleary University", "Cleveland State University", "Coastal Carolina University", "College Of Staten Island - City University Of New York", "Colorado State University", "Dakota State University", "Depaul University", "Drew University", "Eastern Michigan University", "Eastern Washington University", "Felician University", "Florida Atlantic University", "Florida Institute Of Technology", "Florida International University", "Full Sail University", "George Mason University", "Georgia State University", "Golden Gate University", "Gonzaga University", "Harrisburg University Of Science And Technology", "Hofstra University", "Hult International Business School", "Illinois Institute Of Technology", "Illinois State University", "Indiana University - Purdue University Indianapolis", "Iowa State University", "Jackson State University", "Jacksonville State University", "James Madison University", "Johnson & Wales University", "Kansas State University", "Kent State University", "Lewis University", "Lipscomb University", "Long Island University Brooklyn", "Long Island University Post", "Louisiana State University", "Manhattan College", "Marshall University", "Marymount University", "Mercer University", "Miami University, Oxford Ohio", "Middle Tennessee University", "Missouri State University", "Monroe College", "Montana State University", "National Louis University", "New England College", "New Jersey Institute Of Technology", "New York Film Academy", "New York Institute Of Technology", "North Carolina State University", "North Dakota State University", "Northeastern University", "Northern Arizona University", "Northern Kentucky University", "Nova Southeastern University", "Oklahoma City University", "Oklahoma State University", "Old Dominion University", "Oregon State University", "Pace University", "Pittsburg State University", "Point Park University", "Purdue University Fort Wayne", "Purdue University Northwest", "Queens College - City University Of New York", "Quinnipiac University", "Rider University", "Rivier University", "Rochester Institute Of Technology", "Roosevelt University", "Rowan University", "Russell Sage College", "Sacred Heart University", "Saint Louis University", "Saint Mary's University Of Minnesota", "San Diego State University", "San Francisco State University", "San Jose State University", "Seattle Pacific University", "Seton Hall University", "South Dakota State University", "Southeast Missouri State University", "Southern Illinois University Edwardsville", "Southern Methodist University", "Southern New Hampshire University", "St Francis University", "St Thomas University", "St. John's University", "St. Joseph's University, New York", "Stevens Institute Of Technology", "Suffolk University", "SUNY Brockport", "SUNY Morrisville", "SUNY Oswego", "Tarleton State University", "Temple University", "Tennessee Tech University", "Texas A&M University - Corpus Christi", "Texas A&M University - Kingsville", "Texas Christian University", "Texas Tech University", "Texas Wesleyan University", "The University Of Alabama At Birmingham", "The University Of Arizona", "The University Of Illinois Chicago", "The University Of Kansas", "The University Of Memphis", "The University Of Mississippi", "The University Of Nebraska-Lincoln", "The University Of North Carolina Wilmington", "The University Of Rhode Island", "The University Of South Carolina", "The University Of Tampa", "The University Of Tennessee, Knoxville", "The University Of Texas At Arlington", "The University Of Texas At San Antonio", "The University Of Tulsa", "The University Of Utah", "Trine University", "Troy University", "Tulane University", "University Of Bridgeport", "University Of California, Irvine", "University Of California, Riverside", "University Of Central Florida", "University Of Cincinnati", "University Of Colorado Boulder", "University Of Colorado Denver", "University Of Connecticut", "University Of Dayton", "University Of Delaware", "University Of Denver", "University Of Hartford", "University Of Idaho", "University Of Kentucky", "University Of La Verne", "University Of Maine", "University Of Mary Hardin-Baylor", "University Of Massachusetts Boston", "University Of Massachusetts Dartmouth", "University Of Massachusetts Lowell", "University Of Michigan - Dearborn", "University Of Michigan - Flint", "University Of Missouri - Columbia", "University Of Missouri - Kansas City", "University Of Missouri - St. Louis", "University Of Nevada, Las Vegas", "University Of Nevada, Reno", "University Of New Hampshire", "University Of New Haven", "University Of New Mexico", "University Of North Alabama", "University Of North Dakota", "University Of North Texas", "University Of Oregon", "University Of Pittsburgh", "University Of Redlands", "University Of San Diego", "University Of South Dakota", "University Of South Florida", "University Of St. Thomas", "University Of The Pacific", "University Of Vermont", "University Of Wisconsin - Milwaukee", "University Of Wisconsin - Stout", "Utah State University", "Virginia Commonwealth University", "Virginia Tech Language And Culture Institute", "Washington State University", "Wayne State University", "West Virginia University", "Western Illinois University", "Western Kentucky University", "Western Michigan University", "Western New England University", "Wichita State University", "Widener University", "Wilkes University", "William Paterson University", "Worcester Polytechnic Institute", "Wright State University"
    ]
];

// Sort the country names alphabetically
ksort($countries);

?>
<?php require_once 'header.php'; ?>

<style>
  .register-form-section {
    padding: 60px 5%;
    background-color: #f7fafc; /* Light grey/blue background */
  }

  .register-form-container {
    max-width: 800px;
    margin: 0 auto;
    background-color: #ffffff;
    padding: 40px;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
  }

  .register-form-container h2 {
    text-align: center;
    color: #0A4F8B; /* Logo Blue */
    margin-bottom: 30px;
  }

  .form-group {
    margin-bottom: 25px;
  }

  .form-group label {
    display: block;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
  }

  .form-group input,
  .form-group select {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 1rem;
    font-family: inherit;
    transition: border-color 0.3s, box-shadow 0.3s;
  }

  .form-group input:focus,
  .form-group select:focus {
    outline: none;
    border-color: #0A4F8B; /* Logo Blue */
    box-shadow: 0 0 5px rgba(10, 79, 139, 0.2);
  }

  .form-group select[disabled] {
    background-color: #f4f4f4;
    cursor: not-allowed;
  }

  .submit-btn {
    display: block;
    width: 100%;
    padding: 15px;
    background-color: #d9534f; /* Logo Red */
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 1.1rem;
    font-weight: bold;
    text-transform: uppercase;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
  }

  .submit-btn:hover {
    background-color: #c9302c; /* Darker Red */
    transform: scale(1.02);
  }
</style>

<main>

  <section class="page-banner">
    <div class="container">
      <h1>Register Now</h1>
      <p>Take the first step towards your global education journey.</p>
    </div>
  </section>

  <section class="register-form-section">
    <div class="register-form-container">
      <h2>Student Registration</h2>
      
      <form id="registrationForm" action="submit_registration.php" method="POST">

        <div class="form-group">
          <label for="name">Name:</label>
          <input type="text" id="name" name="name" required>
        </div>

        <div class="form-group">
          <label for="contact">Contact Number:</label>
          <input type="tel" id="contact" name="contact" required>
        </div>

        <div class="form-group">
          <label for="gmail">Gmail:</label>
          <input type="email" id="gmail" name="gmail" required>
        </div>

        <div class="form-group">
          <label for="country">Country you wanted to go:</label>
          <select id="country" name="country" required>
            <option value="">-- Select a Country --</option>
            <?php foreach (array_keys($countries) as $country): ?>
              <option value="<?php echo htmlspecialchars($country); ?>">
                <?php echo htmlspecialchars($country); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label for="college">College you wanted to join:</label>
          <select id="college" name="college" required disabled>
            <option value="">-- Select a country first --</option>
          </select>
        </div>

        <button type="submit" class="submit-btn">Register</button>

      </form>
    </div>
  </section>

</main>

<script>
  // 1. Pass the PHP data to JavaScript
  // We use json_encode to safely convert the PHP array to a JavaScript object
  const collegeData = <?php echo json_encode($countries); ?>;

  // 2. Get references to the dropdown elements
  const countrySelect = document.getElementById('country');
  const collegeSelect = document.getElementById('college');

  // 3. Add an event listener to the country dropdown
  countrySelect.addEventListener('change', function() {
    // Get the selected country value
    const selectedCountry = this.value;

    // Clear the college dropdown
    collegeSelect.innerHTML = '';

    if (selectedCountry) {
      // If a country is selected:
      // 1. Enable the college dropdown
      collegeSelect.disabled = false;
      
      // 2. Add a default "select" option
      let defaultOption = new Option('-- Select a College --', '');
      collegeSelect.add(defaultOption);

      // 3. Get the list of colleges for the selected country
      const colleges = collegeData[selectedCountry];
      
      // 4. Sort colleges alphabetically
      colleges.sort();

      // 5. Populate the dropdown with the new list of colleges
      colleges.forEach(function(collegeName) {
        let option = new Option(collegeName, collegeName);
        collegeSelect.add(option);
      });

    } else {
      // If no country is selected:
      // 1. Disable the college dropdown
      collegeSelect.disabled = true;
      
      // 2. Add a placeholder option
      let placeholderOption = new Option('-- Select a country first --', '');
      collegeSelect.add(placeholderOption);
    }
  });
</script>

<?php require_once 'footer.php'; ?>