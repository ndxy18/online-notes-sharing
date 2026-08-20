<?php
require_once __DIR__ . '/../../includes/functions.php';
require_admin();
$success = flash('success');
$error = flash('error');
$current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="theme-color" content="#22304A">
<link rel="icon" type="image/png" sizes="32x32" href="../assets/icons/favicon-32.png">
<link rel="apple-touch-icon" href="../assets/icons/apple-touch-icon.png">
<title><?= isset($page_title) ? clean($page_title) . ' — Admin' : 'Admin Panel' ?> · NotesHub</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="container">
        <a href="dashboard.php" class="brand">📚 Notes<span class="dot">Hub</span> <span style="font-size:11px; background:var(--ink); color:#fff; padding:2px 8px; border-radius:10px; margin-left:6px;">ADMIN</span></a>
        <div class="nav-links" style="display:flex;">
            <span class="nav-user-pill">👤 <?= clean($_SESSION['name']) ?></span>
            <a href="../index.php" class="btn btn-outline btn-sm">View Site</a>
            <a href="logout.php" class="btn btn-sm btn-danger">Logout</a>
        </div>
    </div>
</nav>

<?php if ($success): ?>
<div class="container" style="margin-top:16px;"><div class="alert alert-success">✅ <?= clean($success) ?></div></div>
<?php endif; ?>
<?php if ($error): ?>
<div class="container" style="margin-top:16px;"><div class="alert alert-error">⚠️ <?= clean($error) ?></div></div>
<?php endif; ?>

<div class="admin-shell">
    <aside class="admin-sidebar">
        <a href="dashboard.php" class="<?= $current === 'dashboard.php' ? 'active' : '' ?>">📊 Dashboard</a>
        <a href="notes.php" class="<?= $current === 'notes.php' ? 'active' : '' ?>">📄 Manage Notes</a>
        <a href="users.php" class="<?= $current === 'users.php' ? 'active' : '' ?>">👥 Manage Users</a>
        <a href="categories.php" class="<?= $current === 'categories.php' ? 'active' : '' ?>">🏷️ Categories</a>
    </aside>
    <main class="admin-main">
