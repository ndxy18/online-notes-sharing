<?php
$base = '';
require_once __DIR__ . '/includes/functions.php';
require_login();
if (is_admin()) { header("Location: admin/dashboard.php"); exit; }

$uid = $_SESSION['user_id'];

$myNotesCount = $pdo->prepare("SELECT COUNT(*) c FROM notes WHERE uploaded_by = ?");
$myNotesCount->execute([$uid]);
$myNotesCount = $myNotesCount->fetch()['c'];

$myApproved = $pdo->prepare("SELECT COUNT(*) c FROM notes WHERE uploaded_by = ? AND status='approved'");
$myApproved->execute([$uid]);
$myApproved = $myApproved->fetch()['c'];

$myDownloads = $pdo->prepare("SELECT COUNT(*) c FROM downloads WHERE user_id = ?");
$myDownloads->execute([$uid]);
$myDownloads = $myDownloads->fetch()['c'];

$myDownloadsGiven = $pdo->prepare("SELECT COALESCE(SUM(downloads_count),0) c FROM notes WHERE uploaded_by = ?");
$myDownloadsGiven->execute([$uid]);
$myDownloadsGiven = $myDownloadsGiven->fetch()['c'];

$recent = $pdo->prepare("SELECT * FROM notes WHERE uploaded_by = ? ORDER BY upload_date DESC LIMIT 5");
$recent->execute([$uid]);
$recent = $recent->fetchAll();

$page_title = "Dashboard";
include __DIR__ . '/includes/header.php';
?>

<div class="container section">
    <p class="eyebrow">Your space</p>
    <h2>Hi, <?= clean($_SESSION['name']) ?> 👋</h2>

    <div class="stat-grid">
        <div class="stat-card"><div class="num"><?= $myNotesCount ?></div><div class="label">Notes Uploaded</div></div>
        <div class="stat-card"><div class="num"><?= $myApproved ?></div><div class="label">Approved</div></div>
        <div class="stat-card"><div class="num"><?= $myDownloadsGiven ?></div><div class="label">Times Others Downloaded Yours</div></div>
        <div class="stat-card"><div class="num"><?= $myDownloads ?></div><div class="label">Notes You Downloaded</div></div>
    </div>

    <div class="section-head">
        <h2 style="font-size:20px;">Quick actions</h2>
    </div>
    <div class="grid" style="margin-bottom:34px;">
        <div class="note-card">
            <h3>📤 Upload new notes</h3>
            <p class="desc">Share a PDF/DOC/PPT with your batch. Admin approves before it goes live.</p>
            <a href="upload.php" class="btn btn-coral btn-sm">Upload now</a>
        </div>
        <div class="note-card">
            <h3>🔍 Browse notes</h3>
            <p class="desc">Search by subject, semester, course or category.</p>
            <a href="browse.php" class="btn btn-teal btn-sm">Browse</a>
        </div>
        <div class="note-card">
            <h3>🗂️ Manage my notes</h3>
            <p class="desc">See status of your uploads or delete old ones.</p>
            <a href="my_notes.php" class="btn btn-outline btn-sm">My notes</a>
        </div>
    </div>

    <div class="section-head"><h2 style="font-size:20px;">Recent uploads</h2></div>
    <?php if (count($recent) === 0): ?>
        <div class="empty-state"><div class="big">📭</div><p>Abhi tak koi upload nahi kiya.</p></div>
    <?php else: ?>
    <div class="table-wrap">
        <table>
            <tr><th>Title</th><th>Subject</th><th>Status</th><th>Uploaded</th><th></th></tr>
            <?php foreach ($recent as $n): ?>
            <tr>
                <td><?= clean($n['title']) ?></td>
                <td><?= clean($n['subject']) ?></td>
                <td><span class="badge badge-<?= $n['status'] ?>"><?= $n['status'] ?></span></td>
                <td><?= time_ago($n['upload_date']) ?></td>
                <td><a href="view_note.php?id=<?= $n['note_id'] ?>" class="btn btn-sm btn-outline">View</a></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
