<?php
require_once __DIR__ . '/includes/functions.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM notes WHERE note_id = ? AND status = 'approved'");
$stmt->execute([$id]);
$note = $stmt->fetch();

if (!$note) {
    flash('error', 'Note available nahi hai.');
    header("Location: browse.php"); exit;
}

$filePath = __DIR__ . '/uploads/' . $note['stored_name'];
if (!file_exists($filePath)) {
    flash('error', 'File server pe nahi mili. Admin ko batao.');
    header("Location: browse.php"); exit;
}

// log download
$log = $pdo->prepare("INSERT INTO downloads (note_id, user_id) VALUES (?, ?)");
$log->execute([$id, $_SESSION['user_id']]);

$inc = $pdo->prepare("UPDATE notes SET downloads_count = downloads_count + 1 WHERE note_id = ?");
$inc->execute([$id]);

// send file
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($note['file_name']) . '"');
header('Content-Length: ' . filesize($filePath));
header('Cache-Control: must-revalidate');
readfile($filePath);
exit;
