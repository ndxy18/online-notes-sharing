<?php
$base = '';
require_once __DIR__ . '/includes/functions.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT n.*, u.name AS uploader, u.email AS uploader_email, c.category_name
                        FROM notes n
                        JOIN users u ON u.user_id = n.uploaded_by
                        LEFT JOIN categories c ON c.category_id = n.category_id
                        WHERE n.note_id = ?");
$stmt->execute([$id]);
$note = $stmt->fetch();

if (!$note) {
    flash('error', 'Note nahi mila.');
    header("Location: browse.php"); exit;
}

// Only owner or admin can view a pending/rejected note
$isOwner = is_logged_in() && $_SESSION['user_id'] == $note['uploaded_by'];
if ($note['status'] !== 'approved' && !$isOwner && !is_admin()) {
    flash('error', 'Yeh note abhi approved nahi hai.');
    header("Location: browse.php"); exit;
}

$page_title = $note['title'];
include __DIR__ . '/includes/header.php';
?>

<div class="container section">
    <div class="card" style="max-width:700px; margin:0 auto;">
        <span class="badge badge-<?= $note['status'] ?>" style="margin-bottom:10px;"><?= $note['status'] ?></span>
        <h2 style="margin-top:6px;"><?= file_icon($note['file_type']) ?> <?= clean($note['title']) ?></h2>
        <div class="note-meta" style="margin-bottom:18px;">
            <span>Subject: <?= clean($note['subject']) ?></span>
            <?php if ($note['category_name']): ?><span><?= clean($note['category_name']) ?></span><?php endif; ?>
            <?php if ($note['course']): ?><span><?= clean($note['course']) ?></span><?php endif; ?>
            <?php if ($note['semester']): ?><span>Sem <?= clean($note['semester']) ?></span><?php endif; ?>
            <span><?= strtoupper($note['file_type']) ?> · <?= round($note['file_size'] / 1024) ?> KB</span>
        </div>

        <p style="color:var(--ink-soft);"><?= nl2br(clean($note['description'] ?: 'No description added.')) ?></p>

        <hr style="border:none; border-top:1px dashed var(--line); margin:20px 0;">

        <p style="font-size:13.5px; color:var(--ink-soft);">
            Uploaded by <strong><?= clean($note['uploader']) ?></strong> · <?= time_ago($note['upload_date']) ?><br>
            ⬇ <?= $note['downloads_count'] ?> download(s)
        </p>

        <div style="margin-top:20px; display:flex; gap:10px; flex-wrap:wrap;">
            <?php if ($note['status'] === 'approved'): ?>
                <?php if (is_logged_in()): ?>
                    <a href="download.php?id=<?= $note['note_id'] ?>" class="btn btn-coral">⬇ Download Note</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-coral">Login to download</a>
                <?php endif; ?>
            <?php endif; ?>
            <a href="browse.php" class="btn btn-outline">← Back to browse</a>
            <?php if ($isOwner): ?>
                <a href="delete_note.php?id=<?= $note['note_id'] ?>" class="btn btn-danger confirm-delete">Delete</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
