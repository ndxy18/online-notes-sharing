<?php
$base = '';
require_once __DIR__ . '/includes/functions.php';

$stmt = $pdo->query("SELECT n.*, u.name AS uploader FROM notes n
                      JOIN users u ON u.user_id = n.uploaded_by
                      WHERE n.status = 'approved'
                      ORDER BY n.upload_date DESC LIMIT 8");
$latest = $stmt->fetchAll();

$totalNotes = $pdo->query("SELECT COUNT(*) c FROM notes WHERE status='approved'")->fetch()['c'];
$totalStudents = $pdo->query("SELECT COUNT(*) c FROM users WHERE role='student'")->fetch()['c'];
$totalDownloads = $pdo->query("SELECT COUNT(*) c FROM downloads")->fetch()['c'];

$page_title = "Home";
include __DIR__ . '/includes/header.php';
$tabColors = ['#FF9F5A','#2E8F86','#6C7BD9','#D95FA3','#8FB339'];
?>

<section class="hero">
    <div class="hero-stamp">EST. <?= date('Y') ?><br>NO MORE LOST NOTES</div>
    <div class="container">
        <p class="eyebrow">— for students, by students —</p>
        <h1>Stop hunting for notes across five WhatsApp groups.</h1>
        <p class="lead">One shelf for every subject, semester and course. Upload once, everyone finds it. Search, download, done.</p>
        <form action="browse.php" method="GET" class="search-box">
            <input type="text" name="q" placeholder="Search by subject, title or keyword… e.g. DBMS Unit 3">
            <button type="submit" class="btn btn-coral">🔍 Search Notes</button>
        </form>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="stat-grid">
            <div class="stat-card"><div class="num"><?= $totalNotes ?>+</div><div class="label">Notes Available</div></div>
            <div class="stat-card"><div class="num"><?= $totalStudents ?>+</div><div class="label">Students Joined</div></div>
            <div class="stat-card"><div class="num"><?= $totalDownloads ?>+</div><div class="label">Downloads So Far</div></div>
        </div>

        <div class="section-head">
            <h2>Freshly uploaded 📥</h2>
            <a href="browse.php" class="btn btn-outline btn-sm">View all →</a>
        </div>

        <?php if (count($latest) === 0): ?>
            <div class="empty-state">
                <div class="big">🗂️</div>
                <p>Abhi tak koi note approved nahi hua. Sabse pehle upload karne wale bano!</p>
                <a href="register.php" class="btn btn-coral">Sign up &amp; Upload</a>
            </div>
        <?php else: ?>
        <div class="grid">
            <?php foreach ($latest as $i => $n): ?>
                <div class="note-card">
                    <span class="note-tab" style="background:<?= $tabColors[$i % 5] ?>"><?= clean($n['subject']) ?></span>
                    <div class="file-row">
                        <span class="file-icon"><?= file_icon($n['file_type']) ?></span>
                        <div>
                            <h3 style="margin:0;"><?= clean($n['title']) ?></h3>
                        </div>
                    </div>
                    <div class="note-meta">
                        <?php if ($n['course']): ?><span><?= clean($n['course']) ?></span><?php endif; ?>
                        <?php if ($n['semester']): ?><span>Sem <?= clean($n['semester']) ?></span><?php endif; ?>
                        <span><?= strtoupper($n['file_type']) ?></span>
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
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
