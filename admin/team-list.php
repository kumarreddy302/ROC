<?php
declare(strict_types=1);
require_once __DIR__ . '/auth.php';

$rows = $pdo->query("
  SELECT id, name, title, photo_url, is_active, sort_order
  FROM team_members
  ORDER BY sort_order ASC, id ASC
")->fetchAll();
?>
<div class="card">
  <div style="display:flex;justify-content:space-between;align-items:center">
    <h2>Team</h2>
    <a class="btn" href="team-edit.php">+ New Member</a>
  </div>
  <table>
    <thead><tr><th>Photo</th><th>Name</th><th>Title</th><th>Active</th><th>Sort</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?php if (!empty($r['photo_url'])): ?><img class="preview" src="/<?= ltrim($r['photo_url'],'/') ?>" alt=""><?php endif; ?></td>
          <td><?= htmlspecialchars($r['name'], ENT_QUOTES) ?></td>
          <td class="muted"><?= htmlspecialchars($r['title'], ENT_QUOTES) ?></td>
          <td><?= $r['is_active'] ? 'Yes' : 'No' ?></td>
          <td><?= (int)$r['sort_order'] ?></td>
          <td class="actions">
            <a class="btn secondary" href="team-edit.php?id=<?= (int)$r['id'] ?>">Edit</a>
            <form class="inline" method="post" action="team-edit.php" onsubmit="return confirm('Delete this member?')">
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
