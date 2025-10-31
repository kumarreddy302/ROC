<?php
// admin/_upload.php
declare(strict_types=1);

/**
 * Ensure upload subdirectory exists; returns absolute path.
 * $subdir like: 'news/2025/10'
 */
function ensure_upload_dir(string $subdir): string {
  $root = realpath(__DIR__ . '/..'); // project root (where uploads/ should live)
  $base = $root . DIRECTORY_SEPARATOR . 'uploads';
  $path = $base . DIRECTORY_SEPARATOR . trim($subdir, '/\\');
  if (!is_dir($path) && !mkdir($path, 0775, true) && !is_dir($path)) {
    throw new RuntimeException('Failed to create upload directory: ' . $path);
  }
  return $path;
}

/**
 * Save uploaded image and return RELATIVE url "uploads/<bucket>/YYYY/MM/<unique>.<ext>"
 * - $inputName: file input name
 * - $bucket: 'news' | 'gallery' | 'team'
 * - $maxBytes: default 5MB
 */
function save_uploaded_image(string $inputName, string $bucket, int $maxBytes = 5_000_000): ?string {
  if (empty($_FILES[$inputName]) || ($_FILES[$inputName]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
    return null; // nothing uploaded
  }

  $f = $_FILES[$inputName];
  if (($f['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
    throw new RuntimeException('Upload error (code ' . (int)$f['error'] . ').');
  }
  if (($f['size'] ?? 0) <= 0 || $f['size'] > $maxBytes) {
    throw new RuntimeException('File too large. Max ' . number_format($maxBytes / 1_000_000, 1) . ' MB.');
  }

  $fi = new finfo(FILEINFO_MIME_TYPE);
  $mime = $fi->file($f['tmp_name']) ?: '';
  $allowed = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/gif'  => 'gif',
    'image/webp' => 'webp',
  ];
  if (!isset($allowed[$mime])) {
    throw new RuntimeException('Unsupported image type. Allowed: JPG, PNG, GIF, WEBP.');
  }
  $ext = $allowed[$mime];

  $dated = sprintf('%s/%s/%s', $bucket, date('Y'), date('m'));
  $targetDir = ensure_upload_dir($dated);

  $base = preg_replace('/[^a-z0-9]+/i', '-', pathinfo($f['name'], PATHINFO_FILENAME));
  $base = trim($base, '-') ?: 'img';
  $name = sprintf('%s_%s.%s', $base, bin2hex(random_bytes(6)), $ext);
  $dest = $targetDir . DIRECTORY_SEPARATOR . $name;

  if (!move_uploaded_file($f['tmp_name'], $dest)) {
    throw new RuntimeException('Failed to move uploaded file.');
  }

  // Relative URL used in <img src>
  return 'uploads/' . $dated . '/' . $name;
}
