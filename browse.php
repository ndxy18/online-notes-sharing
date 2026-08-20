<?php
$base = '';
require_once __DIR__ . '/includes/functions.php';

$categories = $pdo->query("SELECT * FROM categories ORDER BY category_name")->fetchAll();

$q = clean($_GET['q'] ?? '');
$category_id = clean($_GET['category_id'] ?? '');
$semester = clean($_GET['semester'] ?? '');
$course = clean($_GET['course'] ?? '');

$sql = "SELECT n.*, u.name AS uploader FROM notes n
        JOIN users u ON u.user_id = n.uploaded_by
        WHERE n.status = 'approved'";
$params = [];

if ($q !== '') {
    $sql .= " AND (n.title LIKE ? OR n.subject LIKE ? OR n.description LIKE ?)";
    $like = "%$q%";
    $params[] = $like; $params[] = $like; $params[] = $like;
}
if ($category_id !== '') {
    $sql .= " AND n.category_id = ?";
    $params[] = $category_id;
}
if ($semester !== '') {
    $sql .= " AND n.semester = ?";
    $params[] = $semester;
}
if ($course !== '') {
    $sql .= " AND n.course LIKE ?";
    $params[] = "%$course%";
}
$sql .= " ORDER BY n.upload_date DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$notes = $stmt->fetchAll();

$page_title = "Browse Notes";
include __DIR__ . '/includes/header.php';
$tabColors = ['#FF9F5A','#2E8F86','#6C7BD9','#D95FA3','#8FB339'];
?>

<div class="container section">
    <p class="eyebrow">Search the shelf</p>
    <h2>Browse all notes</h2>

    <form method="GET" action="browse.php" class="filters">
        <input type="text" name="q" placeholder="Search title, subject, keyword..." value="<?= clean($q) ?>">
        <select name="category_id">
            <option value="">All categories</option>
            <?php foreach ($categories as $c): ?>
                <option value="<?= $c['category_id'] ?>" <?= $category_id == $c['category_id'] ? 'selected' : '' ?>><?= clean($c['category_name']) ?></option>
            <?php endforeach; ?>
        </select>
        <select name="semester">
            <option value="">All semesters</option>
            <?php for ($i = 1; $i <= 8; $i++): ?>
                <option value="<?= $i ?>" <?= $semester == $i ? 'selected' : '' ?>>Semester <?= $i ?></option>
            <?php endfor; ?>
        </select>
        <input type="text" name="course" placeholder="Course e.g. BBA" value="<?= clean($course) ?>">
        <button type="submit" class="btn btn-coral">Filter</button>
    </form>

    <p class="hint" style="margin-bottom:16px;"><?= count($notes) ?> note(s) found</p>

    <?php if (count($notes) === 0): ?>
        <div class="empty-state">
            <div class="big">🔍</div>
            <p>Koi note nahi mila. Filters change karke try karo.</p>
        </div>
    <?php else: ?>
    <div class="grid">
        <?php foreach ($notes as $i => $n): ?>
            <div class="note-card">
                <span class="note-tab" style="background:<?= $tabColors[$i % 5] ?>"><?= clean($n['subject']) ?></span>
                <div class="file-row">
                    <span class="file-icon"><?= file_icon($n['file_type']) ?></span>
                    <h3 style="margin:0;"><?= clean($n['title']) ?></h3>
                </div>
                <div class="note-meta">
                    <?php if ($n['course']): ?><span><?= clean($n['course']) ?></span><?php endif; ?>
                    <?php if ($n['semester']): ?><span>Sem <?= clean($n['semester']) ?></span><?php endif; ?>
                    <span><?= strtoupper($n['file_type']) ?></span>
                    <span>⬇ <?= $n['downloads_count'] ?></span>
                </div>
                <p class="desc"><?= clean($n['description'] ?: 'No description added.') ?></p>
                <div class="note-card-footer">
                    <span>by <?= clean($n['uploader']) ?> · <?= time_ago($n['upload_date']) ?></span>
                    <a href="view_note.php?id=<?= $n['note_id'] ?>" class="btn btn-sm btn-teal">View</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
