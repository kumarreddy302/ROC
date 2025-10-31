<?php
declare(strict_types=1);
require_once __DIR__ . '/auth.php';

$rows = $pdo->query("
  SELECT id, title, slug, is_published, published_at, image_url
  FROM news_posts
  ORDER BY published_at DESC, sort_order DESC, id DESC
")->fetchAll();
?>
<div class="card">
  <div style="display:flex;justify-content:space-between;align-items:center">
    <h2>News</h2>
    <a class="btn" href="news-edit.php">+ New Post</a>
  </div>
  <table>
    <thead><tr><th>Image</th><th>Title</th><th>Slug</th><th>Published</th><th>Date</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?php if (!empty($r['image_url'])): ?><img class="preview" src="/<?= ltrim($r['image_url'],'/') ?>" alt=""><?php endif; ?></td>
          <td><?= htmlspecialchars($r['title'], ENT_QUOTES) ?></td>
          <td class="muted"><?= htmlspecialchars($r['slug'], ENT_QUOTES) ?></td>
          <td><?= $r['is_published'] ? 'Yes' : 'No' ?></td>
          <td><?= htmlspecialchars($r['published_at'], ENT_QUOTES) ?></td>
          <td class="actions">
            <a class="btn secondary" href="news-edit.php?id=<?= (int)$r['id'] ?>">Edit</a>
            <form class="inline" method="post" action="news-edit.php" onsubmit="return confirm('Delete this post?')">
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
