<?php
declare(strict_types=1);
require_once __DIR__ . '/auth.php';

// Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
  if (!csrf_check($_POST['csrf'] ?? '')) { http_response_code(400); exit('Bad CSRF'); }
  $pdo->prepare("DELETE FROM contact_messages WHERE id=:id LIMIT 1")->execute([':id'=>(int)$_POST['delete_id']]);
  header('Location: contacts-list.php'); exit;
}

$rows = $pdo->query("
  SELECT id, full_name, email, mobile, subject, message, created_at
  FROM contact_messages
  ORDER BY created_at DESC
  LIMIT 200
")->fetchAll();
?>
<div class="card">
  <h2>Contact Messages</h2>
  <table>
    <thead><tr><th>When</th><th>Name</th><th>Email</th><th>Mobile</th><th>Subject</th><th>Message</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td class="muted"><?= htmlspecialchars($r['created_at'], ENT_QUOTES) ?></td>
          <td><?= htmlspecialchars($r['full_name'], ENT_QUOTES) ?></td>
          <td><a href="mailto:<?= htmlspecialchars($r['email'], ENT_QUOTES) ?>"><?= htmlspecialchars($r['email'], ENT_QUOTES) ?></a></td>
          <td><?= htmlspecialchars($r['mobile'] ?? '', ENT_QUOTES) ?></td>
          <td class="muted"><?= htmlspecialchars($r['subject'], ENT_QUOTES) ?></td>
          <td><?= nl2br(htmlspecialchars($r['message'], ENT_QUOTES)) ?></td>
          <td>
            <form class="inline" method="post" onsubmit="return confirm('Delete this message?')">
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
