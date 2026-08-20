<?php
$base = '';
require_once __DIR__ . '/includes/functions.php';
require_login();

$stmt = $pdo->prepare("SELECT * FROM notes WHERE uploaded_by = ? ORDER BY upload_date DESC");
$stmt->execute([$_SESSION['user_id']]);
$notes = $stmt->fetchAll();

$page_title = "My Notes";
include __DIR__ . '/includes/header.php';
?>

<div class="container section">
    <div class="section-head">
        <h2>My uploaded notes</h2>
        <a href="upload.php" class="btn btn-coral btn-sm">+ Upload new</a>
    </div>

    <?php if (count($notes) === 0): ?>
        <div class="empty-state">
            <div class="big">🗂️</div>
            <p>Aapne abhi tak koi note upload nahi kiya.</p>
            <a href="upload.php" class="btn btn-coral">Upload your first note</a>
        </div>
    <?php else: ?>
    <div class="table-wrap">
        <table>
            <tr><th>Title</th><th>Subject</th><th>Type</th><th>Status</th><th>Downloads</th><th>Uploaded</th><th>Actions</th></tr>
            <?php foreach ($notes as $n): ?>
            <tr>
                <td><?= clean($n['title']) ?></td>
                <td><?= clean($n['subject']) ?></td>
                <td><?= strtoupper($n['file_type']) ?></td>
                <td><span class="badge badge-<?= $n['status'] ?>"><?= $n['status'] ?></span></td>
                <td><?= $n['downloads_count'] ?></td>
                <td><?= time_ago($n['upload_date']) ?></td>
                <td class="row-actions">
                    <a href="view_note.php?id=<?= $n['note_id'] ?>" class="btn btn-sm btn-outline">View</a>
                    <a href="delete_note.php?id=<?= $n['note_id'] ?>" class="btn btn-sm btn-danger confirm-delete">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
