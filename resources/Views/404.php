<?php
// 404.php
$pageTitle = '404 — Not Found';
$user = $_SESSION['user'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>404 — AgriHotel Connect</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="<?= defined('BASE_URL') ? BASE_URL : '' ?>/css/app.css">
</head>
<body>
<div class="error-page">
  <div class="error-code">404</div>
  <h1 class="error-title">Page Not Found</h1>
  <p class="error-msg">The page you're looking for doesn't exist or has been moved.</p>
  <div style="display:flex;gap:1rem;justify-content:center;">
    <a href="<?= defined('BASE_URL') ? BASE_URL : '' ?>/dashboard" class="btn btn-primary">
      <i class="fas fa-home"></i> Go Home
    </a>
    <a href="javascript:history.back()" class="btn btn-outline">
      <i class="fas fa-arrow-left"></i> Go Back
    </a>
  </div>
</div>
<script src="<?= defined('BASE_URL') ? BASE_URL : '' ?>/js/app.js"></script>
</body>
</html>
