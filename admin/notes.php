<?php
$page_title = "Manage Notes";
include __DIR__ . '/includes/header.php';

// handle approve/reject actions
if (isset($_GET['approve'])) {
    $stmt = $pdo->prepare("UPDATE notes SET status='approved' WHERE note_id = ?");
    $stmt->execute([(int)$_GET['approve']]);
    flash('success', 'Note approve ho gaya.');
    header("Location: notes.php"); exit;
}
if (isset($_GET['reject'])) {
    $stmt = $pdo->prepare("UPDATE notes SET status='rejected' WHERE note_id = ?");
    $stmt->execute([(int)$_GET['reject']]);
    flash('success', 'Note reject kar diya.');
    header("Location: notes.php"); exit;
}

$statusFilter = clean($_GET['status'] ?? '');
$sql = "SELECT n.*, u.name AS uploader FROM notes n JOIN users u ON u.user_id = n.uploaded_by";
$params = [];
if ($statusFilter !== '') {
    $sql .= " WHERE n.status = ?";
    $params[] = $statusFilter;
}
$sql .= " ORDER BY n.upload_date DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$notes = $stmt->fetchAll();
?>

<div class="section-head">
    <h2>Manage Notes</h2>
    <div class="row-actions">
        <a href="notes.php" class="btn btn-sm <?= $statusFilter === '' ? 'btn-coral' : 'btn-outline' ?>">All</a>
        <a href="notes.php?status=pending" class="btn btn-sm <?= $statusFilter === 'pending' ? 'btn-coral' : 'btn-outline' ?>">Pending</a>
        <a href="notes.php?status=approved" class="btn btn-sm <?= $statusFilter === 'approved' ? 'btn-coral' : 'btn-outline' ?>">Approved</a>
        <a href="notes.php?status=rejected" class="btn btn-sm <?= $statusFilter === 'rejected' ? 'btn-coral' : 'btn-outline' ?>">Rejected</a>
    </div>
</div>

<?php if (count($notes) === 0): ?>
    <div class="empty-state"><div class="big">🗂️</div><p>Is filter mein koi note nahi hai.</p></div>
<?php else: ?>
<div class="table-wrap">
    <table>
        <tr><th>Title</th><th>Subject</th><th>Uploader</th><th>Type</th><th>Status</th><th>Downloads</th><th>Date</th><th>Actions</th></tr>
        <?php foreach ($notes as $n): ?>
        <tr>
            <td><?= clean($n['title']) ?></td>
            <td><?= clean($n['subject']) ?></td>
            <td><?= clean($n['uploader']) ?></td>
            <td><?= strtoupper($n['file_type']) ?></td>
            <td><span class="badge badge-<?= $n['status'] ?>"><?= $n['status'] ?></span></td>
            <td><?= $n['downloads_count'] ?></td>
            <td><?= time_ago($n['upload_date']) ?></td>
            <td class="row-actions">
                <?php if ($n['status'] !== 'approved'): ?>
                    <a href="notes.php?approve=<?= $n['note_id'] ?>" class="btn btn-sm btn-teal">Approve</a>
                <?php endif; ?>
                <?php if ($n['status'] !== 'rejected'): ?>
                    <a href="notes.php?reject=<?= $n['note_id'] ?>" class="btn btn-sm btn-outline">Reject</a>
                <?php endif; ?>
                <a href="../view_note.php?id=<?= $n['note_id'] ?>" class="btn btn-sm btn-outline">View</a>
                <a href="../delete_note.php?id=<?= $n['note_id'] ?>" class="btn btn-sm btn-danger confirm-delete">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
