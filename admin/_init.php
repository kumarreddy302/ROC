<?php
// admin/_init.php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../db.php'; // provides $pdo (PDO)

// --- CSRF helpers ---
function csrf_token(): string {
  if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
  return $_SESSION['csrf'];
}
function csrf_check(string $token): bool {
  return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
}

// --- Auth helpers ---
function is_logged_in(): bool {
  return !empty($_SESSION['admin_id']);
}
function require_login(): void {
  if (!is_logged_in()) {
    header('Location: login.php');
    exit;
  }
}

// (Optional) site base for images if your app is in a subfolder:
// define('SITE_BASE', '/<your-project>/'); // example
