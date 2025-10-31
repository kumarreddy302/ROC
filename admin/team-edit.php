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
  $pdo->prepare("DELETE FROM team_members WHERE id=:id LIMIT 1")->execute([':id'=>(int)$_POST['delete_id']]);
  header('Location: team-list.php'); exit;
}

// Save
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST['delete_id'])) {
  if (!csrf_check($_POST['csrf'] ?? '')) { http_response_code(400); exit('Bad CSRF'); }
  $name = trim($_POST['name'] ?? '');
  $title = trim($_POST['title'] ?? '');
  $photo_url = trim($_POST['photo_url'] ?? '');
  $sort_order = (int)($_POST['sort_order'] ?? 0);
  $is_active = isset($_POST['is_active']) ? 1 : 0;

  try {
    $uploaded = save_uploaded_image('photo_file', 'team');
    if ($uploaded) $photo_url = $uploaded;
  } catch (Throwable $e) { $errors[] = $e->getMessage(); }

  if (empty($errors)) {
    if ($editing) {
      $stmt = $pdo->prepare("
        UPDATE team_members SET name=:name, title=:title, photo_url=:photo_url,
          sort_order=:sort_order, is_active=:is_active
        WHERE id=:id
      ");
      $stmt->execute(compact('name','title','photo_url','sort_order','is_active','id'));
    } else {
      $stmt = $pdo->prepare("
        INSERT INTO team_members (name, title, photo_url, sort_order, is_active)
        VALUES (:name, :title, :photo_url, :sort_order, :is_active)
      ");
      $stmt->execute(compact('name','title','photo_url','sort_order','is_active'));
    }
    header('Location: team-list.php'); exit;
  }
}

$row = ['name'=>'','title'=>'','photo_url'=>'','sort_order'=>0,'is_active'=>1];
if ($editing) {
  $stmt = $pdo->prepare("SELECT * FROM team_members WHERE id=:id LIMIT 1");
  $stmt->execute([':id'=>$id]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: $row;
}
?>
<div class="card">
  <h2><?= $editing ? 'Edit Member' : 'New Member' ?></h2>

  <?php if ($errors): ?>
    <div class="alert-err"><strong>Errors:</strong> <?= htmlspecialchars(implode('; ', $errors), ENT_QUOTES) ?></div>
  <?php endif; ?>

  <form method="post" class="grid" enctype="multipart/form-data">
    <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES) ?>">

    <div class="grid two">
      <div><label>Name</label><input type="text" name="name" required value="<?= htmlspecialchars($row['name'] ?? '', ENT_QUOTES) ?>"></div>
      <div><label>Title</label><input type="text" name="title" required value="<?= htmlspecialchars($row['title'] ?? '', ENT_QUOTES) ?>"></div>
    </div>

    <div class="grid two">
      <div>
        <label>Photo URL (optional)</label>
        <input type="text" name="photo_url" value="<?= htmlspecialchars($row['photo_url'] ?? '', ENT_QUOTES) ?>">
        <div class="muted">If you upload a file, it will override this URL.</div>
      </div>
      <div>
        <label>Upload Photo (JPG/PNG/GIF/WEBP · max 5MB)</label>
        <input type="file" name="photo_file" accept="image/*">
      </div>
    </div>

    <?php if (!empty($row['photo_url'])): ?>
      <div>
        <label>Current Photo</label><br>
        <img class="preview" src="/<?= ltrim($row['photo_url'],'/') ?>" alt="">
      </div>
    <?php endif; ?>

    <div class="grid two">
      <div><label>Sort Order</label><input type="text" name="sort_order" value="<?= htmlspecialchars((string)($row['sort_order'] ?? 0), ENT_QUOTES) ?>"></div>
      <div class="switch"><input type="checkbox" id="is_active" name="is_active" <?= !empty($row['is_active']) ? 'checked' : '' ?>><label for="is_active">Active</label></div>
    </div>

    <div><button class="btn" type="submit">Save</button> <a class="btn secondary" href="team-list.php">Cancel</a></div>
  </form>
</div>
</div></body></html>
