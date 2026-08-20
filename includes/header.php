<?php
require_once __DIR__ . '/functions.php';
$success = flash('success');
$error = flash('error');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="theme-color" content="#22304A">
<meta name="description" content="Upload, search and download student notes by subject, semester and course.">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="NotesHub">
<title><?= isset($page_title) ? clean($page_title) . ' — NotesHub' : 'NotesHub — Student Notes Sharing' ?></title>
<link rel="manifest" href="<?= isset($base) ? $base : '' ?>manifest.json">
<link rel="icon" type="image/png" sizes="32x32" href="<?= isset($base) ? $base : '' ?>assets/icons/favicon-32.png">
<link rel="icon" type="image/png" sizes="16x16" href="<?= isset($base) ? $base : '' ?>assets/icons/favicon-16.png">
<link rel="apple-touch-icon" href="<?= isset($base) ? $base : '' ?>assets/icons/apple-touch-icon.png">
<link rel="stylesheet" href="<?= isset($base) ? $base : '' ?>assets/css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="container">
        <a href="<?= isset($base) ? $base : '' ?>index.php" class="brand">📚 Notes<span class="dot">Hub</span></a>
        <button class="nav-toggle" id="navToggle">☰</button>
        <div class="nav-links" id="navLinks">
            <a href="<?= isset($base) ? $base : '' ?>index.php">Home</a>
            <a href="<?= isset($base) ? $base : '' ?>browse.php">Browse Notes</a>
            <?php if (is_logged_in()): ?>
                <a href="<?= isset($base) ? $base : '' ?>dashboard.php">Dashboard</a>
                <a href="<?= isset($base) ? $base : '' ?>upload.php">Upload</a>
                <a href="<?= isset($base) ? $base : '' ?>my_notes.php">My Notes</a>
                <span class="nav-user-pill">👤 <?= clean($_SESSION['name']) ?></span>
                <a href="<?= isset($base) ? $base : '' ?>logout.php" class="btn btn-outline btn-sm">Logout</a>
            <?php else: ?>
                <a href="<?= isset($base) ? $base : '' ?>login.php">Login</a>
                <a href="<?= isset($base) ? $base : '' ?>register.php" class="btn btn-coral btn-sm">Sign up free</a>
            <?php endif; ?>
            <button id="installBtn" class="btn btn-outline btn-sm" style="display:none;">📲 Install App</button>
        </div>
    </div>
</nav>

<?php if ($success): ?>
    <div class="container" style="margin-top:18px;">
        <div class="alert alert-success">✅ <?= clean($success) ?></div>
    </div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="container" style="margin-top:18px;">
        <div class="alert alert-error">⚠️ <?= clean($error) ?></div>
    </div>
<?php endif; ?>
