<?php
// admin/register.php  — open registration (no login, no access key)
declare(strict_types=1);

require_once __DIR__ . '/_init.php'; // starts session, loads $pdo, csrf_token()/csrf_check()

// Simple per-session rate limit to avoid spam (10 submits/minute)
if (!isset($_SESSION['reg_limit'])) {
  $_SESSION['reg_limit'] = ['count' => 0, 'ts' => time()];
} else {
  if (time() - $_SESSION['reg_limit']['ts'] > 60) {
    $_SESSION['reg_limit'] = ['count' => 0, 'ts' => time()];
  }
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Rate limit
  if (++$_SESSION['reg_limit']['count'] > 10) {
    $errors[] = 'Too many attempts. Please wait a minute and try again.';
  }

  // CSRF
  if (!csrf_check($_POST['csrf'] ?? '')) {
    $errors[] = 'Invalid request token. Please reload and try again.';
  }

  // Inputs
  $username = trim($_POST['username'] ?? '');
  $password = (string)($_POST['password'] ?? '');
  $confirm  = (string)($_POST['confirm'] ?? '');

  // Validation
  if ($username === '') $errors[] = 'Username is required.';
  if ($password === '') $errors[] = 'Password is required.';
  if ($password !== $confirm) $errors[] = 'Passwords do not match.';
  if ($password && strlen($password) < 8) $errors[] = 'Password must be at least 8 characters.';
  if ($password && !preg_match('/[A-Z]/', $password)) $errors[] = 'Password must include an uppercase letter.';
  if ($password && !preg_match('/[a-z]/', $password)) $errors[] = 'Password must include a lowercase letter.';
  if ($password && !preg_match('/\d/', $password))    $errors[] = 'Password must include a number.';

  // Username availability
  if (!$errors) {
    $stmt = $pdo->prepare("SELECT 1 FROM admin_users WHERE username = :u LIMIT 1");
    $stmt->execute([':u' => $username]);
    if ($stmt->fetch()) {
      $errors[] = 'That username is already taken.';
    }
  }

  // Create user
  if (!$errors) {
    $hash = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("INSERT INTO admin_users (username, password_hash) VALUES (:u, :h)");
    $stmt->execute([':u' => $username, ':h' => $hash]);
    $success = 'Admin user created successfully. You can now log in.';
    $_POST = []; // clear fields
  }
}

// Prefill after failed submit
$prefUser = htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Create Admin User</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    :root { color-scheme: light dark; }
    body{display:grid;place-items:center;min-height:100vh;font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;background:#f7f7fb;margin:0}
    .wrap{width:100%;max-width:560px;padding:16px}
    .card{background:#fff;border-radius:12px;padding:20px;box-shadow:0 10px 30px rgba(0,0,0,.08)}
    h1{margin:0 0 12px}
    p.muted{color:#6b7280;margin:0 0 16px}
    label{display:block;margin:10px 0 6px;color:#374151;font-size:14px}
    input[type="text"],input[type="password"]{width:100%;padding:10px;border:1px solid #ddd;border-radius:8px}
    .row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
    .btn{display:inline-block;background:#111827;color:#fff;padding:10px 14px;border:0;border-radius:8px;cursor:pointer}
    .btn.secondary{background:#4b5563}
    .alert-ok{background:#e8f7ed;color:#116149;padding:10px;border-radius:8px;margin-bottom:12px}
    .alert-err{background:#fdecea;color:#611a15;padding:10px;border-radius:8px;margin-bottom:12px}
    .actions{display:flex;gap:8px;flex-wrap:wrap;margin-top:10px}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <h1>Create Admin User</h1>
      <p class="muted">Open registration page. After creating the right account, delete this file for security.</p>

      <?php if ($success): ?>
        <div class="alert-ok"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></div>
      <?php endif; ?>

      <?php if ($errors): ?>
        <div class="alert-err">
          <strong>There were errors:</strong>
          <ul style="margin:8px 0 0 16px;">
            <?php foreach ($errors as $e): ?>
              <li><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <form method="post" autocomplete="off" novalidate>
        <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">

        <label for="username">Username</label>
        <input type="text" id="username" name="username" value="<?= $prefUser ?>" required autofocus autocomplete="username">

        <div class="row">
          <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required autocomplete="new-password" minlength="8" placeholder="At least 8 chars">
          </div>
          <div>
            <label for="confirm">Confirm Password</label>
            <input type="password" id="confirm" name="confirm" required autocomplete="new-password" minlength="8">
          </div>
        </div>

        <div class="actions">
          <button class="btn" type="submit">Create User</button>
          <a class="btn secondary" href="login.php">Go to Login</a>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
