<?php
$page_title = "Categories";
include __DIR__ . '/includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_name'])) {
    $name = clean($_POST['category_name']);
    if ($name !== '') {
        try {
            $stmt = $pdo->prepare("INSERT INTO categories (category_name) VALUES (?)");
            $stmt->execute([$name]);
            flash('success', 'Category add ho gayi.');
        } catch (PDOException $e) {
            flash('error', 'Yeh category already exist karti hai.');
        }
    }
    header("Location: categories.php"); exit;
}

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM categories WHERE category_id = ?");
    $stmt->execute([(int)$_GET['delete']]);
    flash('success', 'Category delete ho gayi.');
    header("Location: categories.php"); exit;
}

$categories = $pdo->query("SELECT c.*, (SELECT COUNT(*) FROM notes n WHERE n.category_id = c.category_id) AS notes_count
                            FROM categories c ORDER BY c.category_name")->fetchAll();
?>

<div class="section-head"><h2>Categories</h2></div>

<div class="card" style="max-width:420px; margin-bottom:26px;">
    <form method="POST" action="categories.php" style="display:flex; gap:8px;">
        <input type="text" name="category_name" placeholder="New category name" required style="flex:1;">
        <button type="submit" class="btn btn-coral">Add</button>
    </form>
</div>

<div class="table-wrap">
    <table>
        <tr><th>Category</th><th>Notes Count</th><th>Actions</th></tr>
        <?php foreach ($categories as $c): ?>
        <tr>
            <td><?= clean($c['category_name']) ?></td>
            <td><?= $c['notes_count'] ?></td>
            <td><a href="categories.php?delete=<?= $c['category_id'] ?>" class="btn btn-sm btn-danger confirm-delete">Delete</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
