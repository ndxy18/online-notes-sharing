<?php
$page_title = "Manage Users";
include __DIR__ . '/includes/header.php';

if (isset($_GET['block'])) {
    $stmt = $pdo->prepare("UPDATE users SET status='blocked' WHERE user_id = ? AND role='student'");
    $stmt->execute([(int)$_GET['block']]);
    flash('success', 'User block ho gaya.');
    header("Location: users.php"); exit;
}
if (isset($_GET['unblock'])) {
    $stmt = $pdo->prepare("UPDATE users SET status='active' WHERE user_id = ? AND role='student'");
    $stmt->execute([(int)$_GET['unblock']]);
    flash('success', 'User unblock ho gaya.');
    header("Location: users.php"); exit;
}
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ? AND role='student'");
    $stmt->execute([(int)$_GET['delete']]);
    flash('success', 'User delete ho gaya (uske notes bhi delete ho gaye).');
    header("Location: users.php"); exit;
}

$users = $pdo->query("SELECT u.*,
        (SELECT COUNT(*) FROM notes n WHERE n.uploaded_by = u.user_id) AS notes_count
        FROM users u WHERE role='student' ORDER BY u.created_at DESC")->fetchAll();
?>

<div class="section-head"><h2>Manage Users</h2></div>

<?php if (count($users) === 0): ?>
    <div class="empty-state"><div class="big">👥</div><p>Abhi tak koi student register nahi hua.</p></div>
<?php else: ?>
<div class="table-wrap">
    <table>
        <tr><th>Name</th><th>Email</th><th>Course</th><th>Sem</th><th>Notes</th><th>Status</th><th>Joined</th><th>Actions</th></tr>
        <?php foreach ($users as $u): ?>
        <tr>
            <td><?= clean($u['name']) ?></td>
            <td><?= clean($u['email']) ?></td>
            <td><?= clean($u['course'] ?: '—') ?></td>
            <td><?= clean($u['semester'] ?: '—') ?></td>
            <td><?= $u['notes_count'] ?></td>
            <td><span class="badge badge-<?= $u['status'] ?>"><?= $u['status'] ?></span></td>
            <td><?= time_ago($u['created_at']) ?></td>
            <td class="row-actions">
                <?php if ($u['status'] === 'active'): ?>
                    <a href="users.php?block=<?= $u['user_id'] ?>" class="btn btn-sm btn-outline">Block</a>
                <?php else: ?>
                    <a href="users.php?unblock=<?= $u['user_id'] ?>" class="btn btn-sm btn-teal">Unblock</a>
                <?php endif; ?>
                <a href="users.php?delete=<?= $u['user_id'] ?>" class="btn btn-sm btn-danger confirm-delete">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
