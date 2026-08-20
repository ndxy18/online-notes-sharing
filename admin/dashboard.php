<?php
$page_title = "Dashboard";
include __DIR__ . '/includes/header.php';

$totalNotes = $pdo->query("SELECT COUNT(*) c FROM notes")->fetch()['c'];
$pendingNotes = $pdo->query("SELECT COUNT(*) c FROM notes WHERE status='pending'")->fetch()['c'];
$approvedNotes = $pdo->query("SELECT COUNT(*) c FROM notes WHERE status='approved'")->fetch()['c'];
$totalUsers = $pdo->query("SELECT COUNT(*) c FROM users WHERE role='student'")->fetch()['c'];
$totalDownloads = $pdo->query("SELECT COUNT(*) c FROM downloads")->fetch()['c'];

$pendingList = $pdo->query("SELECT n.*, u.name AS uploader FROM notes n
                             JOIN users u ON u.user_id = n.uploaded_by
                             WHERE n.status = 'pending'
                             ORDER BY n.upload_date ASC LIMIT 6")->fetchAll();
?>

<p class="eyebrow">Overview</p>
<h2>Admin Dashboard</h2>

<div class="stat-grid">
    <div class="stat-card"><div class="num"><?= $totalNotes ?></div><div class="label">Total Notes</div></div>
    <div class="stat-card"><div class="num"><?= $pendingNotes ?></div><div class="label">Pending Approval</div></div>
    <div class="stat-card"><div class="num"><?= $approvedNotes ?></div><div class="label">Approved</div></div>
    <div class="stat-card"><div class="num"><?= $totalUsers ?></div><div class="label">Students</div></div>
    <div class="stat-card"><div class="num"><?= $totalDownloads ?></div><div class="label">Total Downloads</div></div>
</div>

<div class="section-head">
    <h2 style="font-size:20px;">Waiting for your review</h2>
    <a href="notes.php?status=pending" class="btn btn-sm btn-outline">View all pending →</a>
</div>

<?php if (count($pendingList) === 0): ?>
    <div class="empty-state"><div class="big">✅</div><p>Sab caught up! Koi pending note nahi hai.</p></div>
<?php else: ?>
<div class="table-wrap">
    <table>
        <tr><th>Title</th><th>Subject</th><th>Uploader</th><th>Uploaded</th><th>Actions</th></tr>
        <?php foreach ($pendingList as $n): ?>
        <tr>
            <td><?= clean($n['title']) ?></td>
            <td><?= clean($n['subject']) ?></td>
            <td><?= clean($n['uploader']) ?></td>
            <td><?= time_ago($n['upload_date']) ?></td>
            <td class="row-actions">
                <a href="notes.php?approve=<?= $n['note_id'] ?>" class="btn btn-sm btn-teal">Approve</a>
                <a href="notes.php?reject=<?= $n['note_id'] ?>" class="btn btn-sm btn-outline">Reject</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
