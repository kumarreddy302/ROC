<?php
declare(strict_types=1);
require_once __DIR__ . '/db.php';
$tz = new DateTimeZone('Asia/Kolkata');

$slug = $_GET['slug'] ?? '';
if ($slug === '') {
  http_response_code(404);
  exit('Post not found.');
}

$stmt = $pdo->prepare("
  SELECT id, title, slug, body, image_url, published_at
  FROM news_posts
  WHERE slug = :slug AND is_published = 1 AND published_at <= NOW()
  LIMIT 1
");
$stmt->execute([':slug' => $slug]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
  http_response_code(404);
  exit('Post not found.');
}

$title = htmlspecialchars($post['title'] ?? 'Untitled', ENT_QUOTES, 'UTF-8');
$img   = htmlspecialchars($post['image_url'] ?? '', ENT_QUOTES, 'UTF-8');
$date  = (function($dt) use ($tz) {
  $d = new DateTime($dt, new DateTimeZone('UTC'));
  $d->setTimezone($tz);
  return $d->format('F j, Y');
})($post['published_at']);

$body = $post['body'] ?? '';
// If body contains HTML you trust, echo as-is; otherwise sanitize.
// For demo, allow basic formatting and line breaks:
$bodySafe = nl2br(htmlspecialchars($body, ENT_QUOTES, 'UTF-8'));
?>
<?php require_once 'header.php'; ?>
<main class="post-page">
  <div class="container">
    <article class="news-article">
      <header class="article-header">
        <h1><?= $title ?></h1>
        <p class="article-date"><?= $date ?></p>
      </header>
      <?php if (!empty($img)): ?>
        <figure class="article-image">
          <img src="<?= $img ?>" alt="<?= $title ?>" loading="lazy">
        </figure>
      <?php endif; ?>
      <div class="article-body">
        <?= $bodySafe ?>
      </div>
      <p><a href="/" class="back-link">← Back to Home</a></p>
    </article>
  </div>
</main>
<?php require_once 'footer.php'; ?>
