<?php
declare(strict_types=1);
require_once __DIR__ . '/auth.php';
?>
<div class="card">
  <h2>Welcome, <?= htmlspecialchars($_SESSION['admin_name'] ?? 'Owner', ENT_QUOTES,'UTF-8') ?></h2>
  <p class="muted">Use the navigation to manage content.</p>
</div>

<div class="row">
  <div class="col"><div class="card"><h3>News</h3><p>Publish updates and articles.</p><a class="btn" href="news-list.php">Manage News</a></div></div>
  <div class="col"><div class="card"><h3>Gallery</h3><p>Add or edit gallery images.</p><a class="btn" href="gallery-list.php">Manage Gallery</a></div></div>
  <div class="col"><div class="card"><h3>Team</h3><p>Update team members and roles.</p><a class="btn" href="team-list.php">Manage Team</a></div></div>
  <div class="col"><div class="card"><h3>Contacts</h3><p>View messages from the site.</p><a class="btn" href="contacts-list.php">View Messages</a></div></div>
</div>
</div></body></html>
