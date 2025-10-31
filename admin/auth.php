<?php
// admin/auth.php
declare(strict_types=1);
require_once __DIR__ . '/_init.php';
require_login();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin Panel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;margin:0;background:#f7f7fb}
    header{background:#111827;color:#fff;padding:12px 16px;display:flex;justify-content:space-between;align-items:center}
    a{color:inherit;text-decoration:none}
    .nav a{margin-right:16px;color:#c7d2fe}
    .wrap{max-width:1100px;margin:24px auto;padding:0 16px}
    .card{background:#fff;border-radius:12px;padding:16px;box-shadow:0 4px 16px rgba(0,0,0,.06);margin-bottom:20px}
    .row{display:flex;gap:12px;flex-wrap:wrap}
    .row .col{flex:1 1 320px}
    table{width:100%;border-collapse:collapse}
    th,td{padding:10px;border-bottom:1px solid #eee;text-align:left;font-size:14px;vertical-align:top}
    .actions a,.actions button{margin-right:8px}
    .btn{display:inline-block;background:#111827;color:#fff;padding:8px 12px;border-radius:8px;border:0;cursor:pointer}
    .btn.secondary{background:#4b5563}
    .btn.danger{background:#b91c1c}
    input[type="text"],input[type="datetime-local"],input[type="file"],textarea{width:100%;padding:8px;border:1px solid #ddd;border-radius:8px}
    label{font-size:13px;color:#374151}
    .grid{display:grid;gap:12px}
    .grid.two{grid-template-columns:1fr 1fr}
    .switch{display:flex;align-items:center;gap:8px}
    form.inline{display:inline}
    .muted{color:#6b7280}
    img.preview{max-height:120px;border-radius:8px}
    .alert-err{background:#fdecea;color:#611a15;padding:10px;border-radius:8px;margin-bottom:12px}
    .alert-ok{background:#e8f7ed;color:#116149;padding:10px;border-radius:8px;margin-bottom:12px}
  </style>
</head>
<body>
  <header>
    <div><strong>Admin</strong> Panel</div>
    <nav class="nav">
      <a href="index.php">Dashboard</a>
      <a href="news-list.php">News</a>
      <a href="gallery-list.php">Gallery</a>
      <a href="team-list.php">Team</a>
      <a href="contacts-list.php">Contacts</a>
      <a href="logout.php">Logout</a>
    </nav>
  </header>
  <div class="wrap">
