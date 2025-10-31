<?php
declare(strict_types=1);
require_once 'header.php';
require_once __DIR__ . '/db.php'; // PDO connection

// Helper: format dates for Asia/Kolkata
$tz = new DateTimeZone('Asia/Kolkata');
$formatDate = function (?string $dt) use ($tz): string {
  if (!$dt) return '';
  try {
    $d = new DateTime($dt, new DateTimeZone('UTC'));
    $d->setTimezone($tz);
    return $d->format('F j, Y');
  } catch (Throwable $e) {
    return htmlspecialchars($dt, ENT_QUOTES, 'UTF-8');
  }
};

// ===== TEAM (from DB) =====
$fallbackTeamImg = 'https://via.placeholder.com/250x250/cccccc/888888?text=Photo';
$stmtTeam = $pdo->prepare("
  SELECT id, name, title,
         COALESCE(NULLIF(TRIM(photo_url), ''), :fallback) AS photo_url
  FROM team_members
  WHERE is_active = 1
  ORDER BY sort_order ASC, id ASC
");
$stmtTeam->execute([':fallback' => $fallbackTeamImg]);
$team = $stmtTeam->fetchAll();

// ===== NEWS (from DB) =====
$fallbackNewsImg = 'https://via.placeholder.com/400x250/cccccc/888888?text=News+Image';
$stmtNews = $pdo->prepare("
  SELECT id, title, slug,
         COALESCE(NULLIF(TRIM(excerpt), ''), NULL) AS excerpt,
         COALESCE(NULLIF(TRIM(image_url), ''), :fallback) AS image_url,
         published_at
  FROM news_posts
  WHERE is_published = 1 AND published_at <= NOW()
  ORDER BY published_at DESC, sort_order DESC, id DESC
  LIMIT 3
");
$stmtNews->execute([':fallback' => $fallbackNewsImg]);
$news = $stmtNews->fetchAll();

// ===== GALLERY (from DB) =====
$fallbackGalleryImg = 'https://via.placeholder.com/400x300/cccccc/888888?text=Gallery+Image';
$stmtGallery = $pdo->prepare("
  SELECT id,
         COALESCE(NULLIF(TRIM(title), ''), NULL) AS title,
         COALESCE(NULLIF(TRIM(caption), ''), NULL) AS caption,
         COALESCE(NULLIF(TRIM(image_url), ''), :fallback) AS image_url,
         COALESCE(NULLIF(TRIM(alt_text), ''), 'Gallery Image') AS alt_text
  FROM gallery_images
  WHERE is_active = 1
  ORDER BY sort_order DESC, id DESC
  LIMIT 12
");
$stmtGallery->execute([':fallback' => $fallbackGalleryImg]);
$gallery = $stmtGallery->fetchAll();
?>

<!-- ✅ Toast notification container -->
<div class="toast-stack" id="toast-stack" aria-live="polite" aria-atomic="true"></div>

<main>
  <!-- HERO SECTION -->
  <section class="hero-section">
    <div class="hero-content">
      <h1>Find Your Future Abroad</h1>
      <p class="hero-tagline">Your dream of international education starts here. Let us guide you every step of the way.</p>
      <a href="#services" class="cta-button">Explore Services</a>
    </div>
  </section>

  <!-- SERVICES -->
  <section class="services-section" id="services">
    <div class="container">
      <h2>Our Services</h2>
      <p class="section-tagline">Providing comprehensive support for your study abroad journey.</p>

      <div class="services-grid">
        <div class="service-card">
          <div class="card-header color-1"><h3>Career Counseling</h3></div>
          <div class="card-content"><p>We offer expert career guidance to help you make confident decisions about your future.</p></div>
          <div class="card-image"><img src="pexels-pavel-danilyuk-8112231.jpg" alt="Career Counseling" loading="lazy"></div>
        </div>

        <div class="service-card">
          <div class="card-header color-2"><h3>Shortlisting of Universities</h3></div>
          <div class="card-content"><p>We help you shortlist top universities that fit your goals, profile, and budget.</p></div>
          <div class="card-image"><img src="pexels-laurachouette-25186686.jpg" alt="Shortlisting Universities" loading="lazy"></div>
        </div>

        <div class="service-card">
          <div class="card-header color-3"><h3>Admission Requirements</h3></div>
          <div class="card-content"><p>We guide you through the English proficiency and entrance test requirements like GRE, GMAT, IELTS, and TOEFL.</p></div>
          <div class="card-image"><img src="pexels-keira-burton-6084455.jpg" alt="Admission Requirements" loading="lazy"></div>
        </div>

        <div class="service-card">
          <div class="card-header color-4"><h3>Application Process</h3></div>
          <div class="card-content"><p>Get complete assistance from filling applications to preparing for interviews and visa processing.</p></div>
          <div class="card-image"><img src="pexels-cytonn-955389.jpg" alt="Application Process" loading="lazy"></div>
        </div>

        <div class="service-card">
          <div class="card-header color-5"><h3>Scholarships</h3></div>
          <div class="card-content"><p>We identify the best scholarship opportunities for you to fund your overseas education.</p></div>
          <div class="card-image"><img src="ChatGPT Image Oct 24, 2025 at 08_20_15 AM.png" alt="Scholarships" loading="lazy"></div>
        </div>

        <div class="service-card">
          <div class="card-header color-6"><h3>Educational Loan</h3></div>
          <div class="card-content"><p>We help you get education loans easily through partner banks for a hassle-free experience.</p></div>
          <div class="card-image"><img src="pexels-karola-g-4968548.jpg" alt="Educational Loan" loading="lazy"></div>
        </div>
      </div>
    </div>
  </section>

  <!-- TEAM SECTION -->
  <section class="team-section" id="team">
    <div class="container">
      <h2>Meet Our Expert Team</h2>
      <p class="section-tagline">Dedicated professionals guiding you toward your goals.</p>

      <div class="team-grid">
        <?php if (empty($team)): ?>
          <p>No active team members found.</p>
        <?php else: ?>
          <?php foreach ($team as $m): ?>
            <?php
              $name  = htmlspecialchars($m['name'] ?? 'Unknown', ENT_QUOTES, 'UTF-8');
              $title = htmlspecialchars($m['title'] ?? '', ENT_QUOTES, 'UTF-8');
              $img   = htmlspecialchars($m['photo_url'] ?? $fallbackTeamImg, ENT_QUOTES, 'UTF-8');
            ?>
            <div class="team-card">
              <div class="team-image">
                <img src="<?= $img ?>" alt="<?= $name ?> — <?= $title ?>" loading="lazy"
                     onerror="this.onerror=null;this.src='<?= $fallbackTeamImg ?>';">
              </div>
              <div class="team-info">
                <h3 class="team-name"><?= $name ?></h3>
                <span class="team-title"><?= $title ?></span>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- TESTIMONIALS -->
  <section class="testimonials-section" id="testimonials">
    <div class="container">
      <h2>What Our Clients Say</h2>
      <p class="section-tagline">Hear from students who achieved their dreams with our help.</p>

      <div class="testimonials-grid">
        <div class="testimonial-card">
          <div class="stars">★★★★★</div>
          <p class="quote">"Rudra Overseas made the entire process seamless! Their guidance was invaluable, and I got into my dream university in the UK."</p>
          <div class="author">
            <span class="author-name">Priya Sharma</span>
            <span class="author-detail">MSc, University of Manchester</span>
          </div>
        </div>

        <div class="testimonial-card">
          <div class="stars">★★★★☆</div>
          <p class="quote">"Excellent counseling and support. The visa process was stress-free with their help. Highly recommended!"</p>
          <div class="author">
            <span class="author-name">Rahul Verma</span>
            <span class="author-detail">B.Eng, University of Toronto</span>
          </div>
        </div>

        <div class="testimonial-card">
          <div class="stars">★★★★★</div>
          <p class="quote">"From IELTS coaching to university applications, Rudra Overseas supported me at every step. Thank you for everything!"</p>
          <div class="author">
            <span class="author-name">Anjali Desai</span>
            <span class="author-detail">MBA, University of Melbourne</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- NEWS -->
  <section class="news-section" id="news">
    <div class="container">
      <h2>Latest News &amp; Updates</h2>
      <p class="section-tagline">Stay informed about study abroad trends and announcements.</p>

      <div class="news-grid">
        <?php if (empty($news)): ?>
          <p>No news yet. Please check back soon.</p>
        <?php else: ?>
          <?php foreach ($news as $n): ?>
            <?php
              $title = htmlspecialchars($n['title'] ?? 'Untitled', ENT_QUOTES, 'UTF-8');
              $slug  = rawurlencode($n['slug'] ?? '');
              $img   = htmlspecialchars($n['image_url'] ?? $fallbackNewsImg, ENT_QUOTES, 'UTF-8');
              $date  = $formatDate($n['published_at'] ?? '');
              $excerpt = htmlspecialchars($n['excerpt'] ?? 'Read more...', ENT_QUOTES, 'UTF-8');
            ?>
            <div class="news-card">
              <div class="news-image">
                <img src="<?= $img ?>" alt="<?= $title ?>" loading="lazy"
                     onerror="this.onerror=null;this.src='<?= $fallbackNewsImg ?>';">
              </div>
              <div class="news-content">
                <span class="news-date"><?= $date ?></span>
                <h3 class="news-title"><?= $title ?></h3>
                <p class="news-excerpt"><?= $excerpt ?></p>
                <a href="/post.php?slug=<?= $slug ?>" class="read-more-btn">Read More</a>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- GALLERY -->
  <section class="gallery-section" id="gallery">
    <div class="container">
      <h2>Gallery</h2>
      <p class="section-tagline">Glimpses of student life, events, and campus tours.</p>

      <div class="gallery-grid">
        <?php if (empty($gallery)): ?>
          <p>No images yet. Please check back soon.</p>
        <?php else: ?>
          <?php foreach ($gallery as $g): ?>
            <?php
              $title   = htmlspecialchars($g['title'] ?? '', ENT_QUOTES, 'UTF-8');
              $caption = htmlspecialchars($g['caption'] ?? '', ENT_QUOTES, 'UTF-8');
              $img     = htmlspecialchars($g['image_url'] ?? $fallbackGalleryImg, ENT_QUOTES, 'UTF-8');
              $alt     = htmlspecialchars($g['alt_text'] ?? ($title ?: 'Gallery Image'), ENT_QUOTES, 'UTF-8');
              $label   = $caption ?: ($title ?: 'Gallery Image');
            ?>
            <figure class="gallery-item">
              <img src="<?= $img ?>" alt="<?= $alt ?>" loading="lazy"
                   onerror="this.onerror=null;this.src='<?= $fallbackGalleryImg ?>';">
              <div class="overlay"><figcaption><?= $label ?></figcaption></div>
            </figure>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- CONTACT FORM -->
  <section class="contact-section" id="contact">
    <div class="container">
      <h2>Get In Touch</h2>
      <p class="section-tagline">Have a question or need more information? We'd love to hear from you.</p>

      <div class="contact-form-wrapper">
        <form action="submit-contact.php" method="POST" novalidate>
          <div class="form-row">
            <div class="form-group">
              <label for="full-name">Full Name</label>
              <input type="text" id="full-name" name="full_name" required>
            </div>
            <div class="form-group">
              <label for="email">Email Address</label>
              <input type="email" id="email" name="email" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="mobile">Mobile Number</label>
              <input type="tel" id="mobile" name="mobile" required pattern="[0-9+\-\s()]{7,20}"
                     placeholder="+91 98765 43210">
            </div>
            <div class="form-group">
              <label for="subject">Subject</label>
              <input type="text" id="subject" name="subject" required>
            </div>
          </div>

          <div class="form-group">
            <label for="message">Message</label>
            <textarea id="message" name="message" rows="6" required></textarea>
          </div>

          <input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">

          <button type="submit" class="cta-button contact-submit-btn">Send Message</button>
        </form>
      </div>
    </div>
  </section>
</main>

<!-- ✅ Toast JavaScript -->
<script>
(function () {
  const stack = document.getElementById('toast-stack');

  function closeToast(el) {
    if (!el) return;
    el.style.animation = 'toast-out .2s ease-in forwards';
    setTimeout(() => el.remove(), 180);
  }

  window.showToast = function ({ type = 'success', title = '', message = '', duration = 4000 } = {}) {
    const t = document.createElement('div');
    t.className = `toast ${type}`;
    t.setAttribute('role', type === 'error' ? 'alert' : 'status');

    const icon = document.createElement('div');
    icon.className = 'icon';
    icon.textContent = type === 'error' ? '⚠️' : '✅';

    const content = document.createElement('div');
    content.className = 'content';
    const h = document.createElement('div');
    h.className = 'title';
    h.textContent = title || (type === 'error' ? 'Something went wrong' : 'Success');
    const p = document.createElement('p');
    p.className = 'msg';
    p.textContent = message || (type === 'error' ? 'Please try again.' : 'Your action completed successfully.');
    content.appendChild(h);
    content.appendChild(p);

    const btn = document.createElement('button');
    btn.className = 'close';
    btn.innerHTML = '&times;';
    btn.setAttribute('aria-label', 'Close notification');
    btn.addEventListener('click', () => closeToast(t));

    t.appendChild(icon);
    t.appendChild(content);
    t.appendChild(btn);

    stack.appendChild(t);
    if (duration > 0) setTimeout(() => closeToast(t), duration);
  };

  const params = new URLSearchParams(window.location.search);
  const contact = params.get('contact');
  if (contact === 'ok') {
    showToast({ type: 'success', title: 'Message sent', message: 'Thank you! We’ll get back to you soon.', duration: 4500 });
  } else if (contact === 'error') {
    showToast({ type: 'error', title: 'Could not send message', message: 'Please try again later.', duration: 6000 });
  }
})();
</script>

<?php require_once 'footer.php'; ?>
</body>
</html>
