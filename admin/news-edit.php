<?php
declare(strict_types=1);
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/_upload.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$editing = $id > 0;
$errors = [];
$ok = '';

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
  if (!csrf_check($_POST['csrf'] ?? '')) { http_response_code(400); exit('Bad CSRF'); }
  $pdo->prepare("DELETE FROM news_posts WHERE id = :id LIMIT 1")->execute([':id'=>(int)$_POST['delete_id']]);
  header('Location: news-list.php'); exit;
}

// Handle save
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST['delete_id'])) {
  if (!csrf_check($_POST['csrf'] ?? '')) { http_response_code(400); exit('Bad CSRF'); }

  $title = trim($_POST['title'] ?? '');
  $slug  = trim($_POST['slug'] ?? '');
  $excerpt = trim($_POST['excerpt'] ?? '');
  $body  = trim($_POST['body'] ?? '');
  $image_url = trim($_POST['image_url'] ?? '');
  $published_at = trim($_POST['published_at'] ?? '');
  $is_published = isset($_POST['is_published']) ? 1 : 0;
  $sort_order   = (int)($_POST['sort_order'] ?? 0);

  try {
    $uploaded = save_uploaded_image('image_file', 'news'); // if file present, overrides URL
    if ($uploaded) $image_url = $uploaded;
  } catch (Throwable $e) {
    $errors[] = $e->getMessage();
  }

  if (empty($errors)) {
    if ($editing) {
      $stmt = $pdo->prepare("
        UPDATE news_posts SET title=:title, slug=:slug, excerpt=:excerpt, body=:body,
          image_url=:image_url, published_at=:published_at, is_published=:is_published, sort_order=:sort_order
        WHERE id=:id
      ");
      $stmt->execute(compact('title','slug','excerpt','body','image_url','published_at','is_published','sort_order','id'));
    } else {
      $stmt = $pdo->prepare("
        INSERT INTO news_posts (title, slug, excerpt, body, image_url, published_at, is_published, sort_order)
        VALUES (:title, :slug, :excerpt, :body, :image_url, :published_at, :is_published, :sort_order)
      ");
      $stmt->execute(compact('title','slug','excerpt','body','image_url','published_at','is_published','sort_order'));
      $id = (int)$pdo->lastInsertId();
    }
    header('Location: news-list.php'); exit;
  }
}

// Load for edit
$row = [
  'title'=>'','slug'=>'','excerpt'=>'','body'=>'','image_url'=>'',
  'published_at'=>date('Y-m-d\TH:i'),'is_published'=>1,'sort_order'=>0
];
if ($editing) {
  $stmt = $pdo->prepare("SELECT * FROM news_posts WHERE id=:id LIMIT 1");
  $stmt->execute([':id'=>$id]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: $row;
}
?>
<div class="card">
  <h2><?= $editing ? 'Edit News' : 'New News' ?></h2>

  <?php if ($errors): ?>
    <div class="alert-err"><strong>Errors:</strong> <?= htmlspecialchars(implode('; ', $errors), ENT_QUOTES) ?></div>
  <?php endif; ?>

  <form method="post" class="grid" enctype="multipart/form-data">
    <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES) ?>">

    <div class="grid two">
      <div><label>Title</label><input type="text" name="title" required value="<?= htmlspecialchars($row['title'] ?? '', ENT_QUOTES) ?>"></div>
      <div><label>Slug (unique)</label><input type="text" name="slug" required value="<?= htmlspecialchars($row['slug'] ?? '', ENT_QUOTES) ?>"></div>
    </div>

    <div><label>Excerpt</label><textarea name="excerpt" rows="2"><?= htmlspecialchars($row['excerpt'] ?? '', ENT_QUOTES) ?></textarea></div>
    <div><label>Body</label><textarea name="body" rows="8"><?= htmlspecialchars($row['body'] ?? '', ENT_QUOTES) ?></textarea></div>

    <div class="grid two">
      <div>
        <label>Image URL (optional)</label>
        <input type="text" name="image_url" value="<?= htmlspecialchars($row['image_url'] ?? '', ENT_QUOTES) ?>">
        <div class="muted">If you upload a file, it will override this URL.</div>
      </div>
      <div>
        <label>Upload Image (JPG/PNG/GIF/WEBP · max 5MB)</label>
        <input type="file" name="image_file" accept="image/*">
      </div>
    </div>

    <?php if (!empty($row['image_url'])): ?>
      <div>
        <label>Current Image</label><br>
        <img class="preview" src="/<?= ltrim($row['image_url'],'/') ?>" alt="">
      </div>
    <?php endif; ?>

    <div class="grid two">
      <div><label>Published at</label><input type="datetime-local" name="published_at" value="<?= htmlspecialchars(str_replace(' ','T',$row['published_at'] ?? date('Y-m-d\TH:i')), ENT_QUOTES) ?>"></div>
      <div><label>Sort Order</label><input type="text" name="sort_order" value="<?= htmlspecialchars((string)($row['sort_order'] ?? 0), ENT_QUOTES) ?>"></div>
    </div>

    <div class="switch"><input type="checkbox" id="is_published" name="is_published" <?= !empty($row['is_published']) ? 'checked' : '' ?>><label for="is_published">Published</label></div>

    <div><button class="btn" type="submit">Save</button> <a class="btn secondary" href="news-list.php">Cancel</a></div>
  </form>
</div>
</div></body></html>
