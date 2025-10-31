<?php
declare(strict_types=1);
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/_upload.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$editing = $id > 0;
$errors = [];

// Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
  if (!csrf_check($_POST['csrf'] ?? '')) { http_response_code(400); exit('Bad CSRF'); }
  $pdo->prepare("DELETE FROM gallery_images WHERE id=:id LIMIT 1")->execute([':id'=>(int)$_POST['delete_id']]);
  header('Location: gallery-list.php'); exit;
}

// Save
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST['delete_id'])) {
  if (!csrf_check($_POST['csrf'] ?? '')) { http_response_code(400); exit('Bad CSRF'); }
  $title = trim($_POST['title'] ?? '');
  $caption = trim($_POST['caption'] ?? '');
  $image_url = trim($_POST['image_url'] ?? '');
  $alt_text = trim($_POST['alt_text'] ?? '');
  $sort_order = (int)($_POST['sort_order'] ?? 0);
  $is_active = isset($_POST['is_active']) ? 1 : 0;

  try {
    $uploaded = save_uploaded_image('image_file', 'gallery');
    if ($uploaded) $image_url = $uploaded;
  } catch (Throwable $e) { $errors[] = $e->getMessage(); }

  if (empty($errors)) {
    if ($editing) {
      $stmt = $pdo->prepare("
        UPDATE gallery_images SET title=:title, caption=:caption, image_url=:image_url,
          alt_text=:alt_text, sort_order=:sort_order, is_active=:is_active
        WHERE id=:id
      ");
      $stmt->execute(compact('title','caption','image_url','alt_text','sort_order','is_active','id'));
    } else {
      $stmt = $pdo->prepare("
        INSERT INTO gallery_images (title, caption, image_url, alt_text, sort_order, is_active)
        VALUES (:title, :caption, :image_url, :alt_text, :sort_order, :is_active)
      ");
      $stmt->execute(compact('title','caption','image_url','alt_text','sort_order','is_active'));
    }
    header('Location: gallery-list.php'); exit;
  }
}

$row = ['title'=>'','caption'=>'','image_url'=>'','alt_text'=>'','sort_order'=>0,'is_active'=>1];
if ($editing) {
  $stmt = $pdo->prepare("SELECT * FROM gallery_images WHERE id=:id LIMIT 1");
  $stmt->execute([':id'=>$id]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: $row;
}
?>
<div class="card">
  <h2><?= $editing ? 'Edit Image' : 'New Image' ?></h2>

  <?php if ($errors): ?>
    <div class="alert-err"><strong>Errors:</strong> <?= htmlspecialchars(implode('; ', $errors), ENT_QUOTES) ?></div>
  <?php endif; ?>

  <form method="post" class="grid" enctype="multipart/form-data">
    <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES) ?>">

    <div class="grid two">
      <div><label>Title</label><input type="text" name="title" value="<?= htmlspecialchars($row['title'] ?? '', ENT_QUOTES) ?>"></div>
      <div><label>Caption</label><input type="text" name="caption" value="<?= htmlspecialchars($row['caption'] ?? '', ENT_QUOTES) ?>"></div>
    </div>

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
      <div><label>Alt Text</label><input type="text" name="alt_text" value="<?= htmlspecialchars($row['alt_text'] ?? '', ENT_QUOTES) ?>"></div>
      <div><label>Sort Order</label><input type="text" name="sort_order" value="<?= htmlspecialchars((string)($row['sort_order'] ?? 0), ENT_QUOTES) ?>"></div>
    </div>

    <div class="switch"><input type="checkbox" id="is_active" name="is_active" <?= !empty($row['is_active']) ? 'checked' : '' ?>><label for="is_active">Active</label></div>

    <div><button class="btn" type="submit">Save</button> <a class="btn secondary" href="gallery-list.php">Cancel</a></div>
  </form>
</div>
</div></body></html>
