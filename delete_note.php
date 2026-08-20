<?php
require_once __DIR__ . '/includes/functions.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM notes WHERE note_id = ?");
$stmt->execute([$id]);
$note = $stmt->fetch();

if (!$note) {
    flash('error', 'Note nahi mila.');
    header("Location: my_notes.php"); exit;
}

if ($note['uploaded_by'] != $_SESSION['user_id'] && !is_admin()) {
    flash('error', 'Aapko yeh note delete karne ki permission nahi hai.');
    header("Location: my_notes.php"); exit;
}

$filePath = __DIR__ . '/uploads/' . $note['stored_name'];
if (file_exists($filePath)) unlink($filePath);

$del = $pdo->prepare("DELETE FROM notes WHERE note_id = ?");
$del->execute([$id]);

flash('success', 'Note delete ho gaya.');
header("Location: " . (is_admin() ? 'admin/notes.php' : 'my_notes.php'));
exit;
