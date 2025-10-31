<?php
declare(strict_types=1);
require_once __DIR__ . '/_init.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user  = trim($_POST['username'] ?? '');
  $pass  = trim($_POST['password'] ?? '');
  $token = $_POST['csrf'] ?? '';
  if (!csrf_check($token)) {
    $error = 'Invalid request. Please try again.';
  } else {
    $stmt = $pdo->prepare("SELECT id, username, password_hash FROM admin_users WHERE username=:u LIMIT 1");
    $stmt->execute([':u'=>$user]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row && password_verify($pass, $row['password_hash'])) {
      session_regenerate_id(true);
      $_SESSION['admin_id'] = (int)$row['id'];
      $_SESSION['admin_name'] = $row['username'];
      header('Location: index.php'); exit;
    }
    $error = 'Invalid username or password.';
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{display:grid;place-items:center;height:100vh;font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;background:#f7f7fb;margin:0}
    .card{width:360px;background:#fff;border-radius:12px;padding:20px;box-shadow:0 10px 30px rgba(0,0,0,.08)}
    h1{margin:0 0 12px}
    label{display:block;margin:10px 0 6px;color:#374151}
    input[type="text"],input[type="password"]{width:100%;padding:10px;border:1px solid #ddd;border-radius:8px}
    .btn{width:100%;margin-top:14px;background:#111827;color:#fff;padding:10px;border:0;border-radius:8px;cursor:pointer}
    .error{background:#fdecea;color:#611a15;padding:10px;border-radius:8px;margin-bottom:10px}
  </style>
</head>
<body>
  <div class="card">
    <h1>Admin Login</h1>
    <?php if ($error): ?><div class="error"><?= htmlspecialchars($error, ENT_QUOTES,'UTF-8') ?></div><?php endif; ?>
    <form method="post">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
      <label>Username</label>
      <input type="text" name="username" required autofocus autocomplete="username">
      <label>Password</label>
      <input type="password" name="password" required autocomplete="current-password">
      <button class="btn" type="submit">Sign in</button>
    </form>
  </div>
</body>
</html>
