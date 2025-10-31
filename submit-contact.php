<?php
declare(strict_types=1);
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  exit('Method Not Allowed');
}

if (!empty($_POST['website'] ?? '')) {
  header('Location: /?contact=ok');
  exit;
}

$full_name = trim($_POST['full_name'] ?? '');
$email     = trim($_POST['email'] ?? '');
$mobile    = trim($_POST['mobile'] ?? '');
$subject   = trim($_POST['subject'] ?? '');
$message   = trim($_POST['message'] ?? '');

$errors = [];
if ($full_name === '') $errors[] = 'Full name is required.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
if ($mobile === '') $errors[] = 'Mobile number is required.';
elseif (!preg_match('/^[0-9+\-\s()]{7,20}$/', $mobile)) $errors[] = 'Enter a valid mobile number.';
if ($subject === '') $errors[] = 'Subject is required.';
if ($message === '') $errors[] = 'Message is required.';

if ($errors) {
  header('Location: /?contact=error');
  exit;
}

$ip = $_SERVER['REMOTE_ADDR'] ?? null;
$ua = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 500);
$ipBinary = $ip ? @inet_pton($ip) : null;

try {
  $stmt = $pdo->prepare("
    INSERT INTO contact_messages (full_name, email, mobile, subject, message, ip_address, user_agent)
    VALUES (:full_name, :email, :mobile, :subject, :message, :ip, :ua)
  ");
  $stmt->bindValue(':full_name', $full_name);
  $stmt->bindValue(':email', $email);
  $stmt->bindValue(':mobile', $mobile);
  $stmt->bindValue(':subject', $subject);
  $stmt->bindValue(':message', $message);
  $stmt->bindValue(':ip', $ipBinary, PDO::PARAM_LOB);
  $stmt->bindValue(':ua', $ua);
  $stmt->execute();

  header('Location: /?contact=ok');
  exit;
} catch (Throwable $e) {
  header('Location: /?contact=error');
  exit;
}
