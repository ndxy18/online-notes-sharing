<?php
$base = '';
require_once __DIR__ . '/includes/functions.php';
require_login();

$categories = $pdo->query("SELECT * FROM categories ORDER BY category_name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = clean($_POST['title'] ?? '');
    $subject = clean($_POST['subject'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $course = clean($_POST['course'] ?? '');
    $semester = clean($_POST['semester'] ?? '');
    $description = clean($_POST['description'] ?? '');

    $allowed_ext = ['pdf', 'doc', 'docx', 'ppt', 'pptx'];
    $max_size = 10 * 1024 * 1024; // 10 MB

    if ($title === '' || $subject === '') {
        flash('error', 'Title aur Subject required hai.');
        header("Location: upload.php"); exit;
    }

    if (!isset($_FILES['note_file']) || $_FILES['note_file']['error'] !== UPLOAD_ERR_OK) {
        flash('error', 'File select karo upload karne ke liye.');
        header("Location: upload.php"); exit;
    }

    $file = $_FILES['note_file'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed_ext)) {
        flash('error', 'Sirf PDF, DOC, DOCX, PPT, PPTX files allowed hain.');
        header("Location: upload.php"); exit;
    }
    if ($file['size'] > $max_size) {
        flash('error', 'File size 10MB se zyada nahi honi chahiye.');
        header("Location: upload.php"); exit;
    }

    $upload_dir = __DIR__ . '/uploads/';
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

    $stored_name = uniqid('note_', true) . '.' . $ext;
    $dest = $upload_dir . $stored_name;

    if (move_uploaded_file($file['tmp_name'], $dest)) {
        $stmt = $pdo->prepare("INSERT INTO notes
            (title, subject, category_id, course, semester, description, file_name, stored_name, file_type, file_size, uploaded_by, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
        $stmt->execute([
            $title, $subject, $category_id ?: null, $course, $semester, $description,
            $file['name'], $stored_name, $ext, $file['size'], $_SESSION['user_id']
        ]);
        flash('success', 'Note upload ho gaya! Admin approval ke baad sabko dikhega.');
        header("Location: my_notes.php"); exit;
    } else {
        flash('error', 'Upload fail ho gaya, dobara try karo.');
        header("Location: upload.php"); exit;
    }
}

$page_title = "Upload Notes";
include __DIR__ . '/includes/header.php';
?>

<div class="container">
    <div class="card form-narrow" style="max-width:560px;">
        <p class="eyebrow">Share study material</p>
        <h2>Upload your notes</h2>
        <form method="POST" action="upload.php" enctype="multipart/form-data">
            <div class="form-group">
                <label>Note Title</label>
                <input type="text" name="title" required placeholder="e.g. DBMS Unit 3 - Normalization">
            </div>
            <div class="form-group">
                <label>Subject</label>
                <input type="text" name="subject" required placeholder="e.g. Database Management System">
            </div>
            <div class="form-group">
                <label>Category</label>
                <select name="category_id">
                    <option value="">Select category</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= $c['category_id'] ?>"><?= clean($c['category_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Course</label>
                <input type="text" name="course" placeholder="e.g. BBA (CA), BCA">
            </div>
            <div class="form-group">
                <label>Semester</label>
                <select name="semester">
                    <option value="">Select semester</option>
                    <?php for ($i = 1; $i <= 8; $i++): ?>
                        <option value="<?= $i ?>">Semester <?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Description (optional)</label>
                <textarea name="description" placeholder="Short note about what's covered..."></textarea>
            </div>
            <div class="form-group">
                <label>File (PDF, DOC, DOCX, PPT, PPTX — max 10MB)</label>
                <input type="file" name="note_file" required accept=".pdf,.doc,.docx,.ppt,.pptx">
            </div>
            <button type="submit" class="btn btn-coral btn-block">Upload Note</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
