<?php
declare(strict_types=1);
require_once __DIR__ . '/auth.php';

$rows = $pdo->query("
  SELECT id, title, caption, image_url, is_active, sort_order
  FROM gallery_images
  ORDER BY sort_order DESC, id DESC
")->fetchAll();
?>
<div class="card">
  <div style="display:flex;justify-content:space-between;align-items:center">
    <h2>Gallery</h2>
    <a class="btn" href="gallery-edit.php">+ New Image</a>
  </div>
  <table>
    <thead><tr><th>Preview</th><th>Title</th><th>Caption</th><th>Active</th><th>Sort</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?php if (!empty($r['image_url'])): ?><img class="preview" src="/<?= ltrim($r['image_url'],'/') ?>" alt=""><?php endif; ?></td>
          <td><?= htmlspecialchars($r['title'] ?? '', ENT_QUOTES) ?></td>
          <td class="muted"><?= htmlspecialchars($r['caption'] ?? '', ENT_QUOTES) ?></td>
          <td><?= $r['is_active'] ? 'Yes' : 'No' ?></td>
          <td><?= (int)$r['sort_order'] ?></td>
          <td class="actions">
            <a class="btn secondary" href="gallery-edit.php?id=<?= (int)$r['id'] ?>">Edit</a>
            <form class="inline" method="post" action="gallery-edit.php" onsubmit="return confirm('Delete this image?')">
              <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES) ?>">
              <input type="hidden" name="delete_id" value="<?= (int)$r['id'] ?>">
              <button class="btn danger" type="submit">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
</div></body></html>
