<?php
$base = '';
require_once __DIR__ . '/includes/functions.php';

if (is_logged_in()) { header("Location: " . (is_admin() ? 'admin/dashboard.php' : 'dashboard.php')); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = clean($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        if ($user['status'] === 'blocked') {
            flash('error', 'Aapka account block kar diya gaya hai. Admin se contact karo.');
            header("Location: login.php");
            exit;
        }
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        flash('success', 'Welcome back, ' . $user['name'] . '!');
        header("Location: " . ($user['role'] === 'admin' ? 'admin/dashboard.php' : 'dashboard.php'));
        exit;
    } else {
        flash('error', 'Email ya password galat hai.');
        header("Location: login.php");
        exit;
    }
}

$page_title = "Login";
include __DIR__ . '/includes/header.php';
?>

<div class="container">
    <div class="card form-narrow">
        <p class="eyebrow">Welcome back</p>
        <h2>Login to NotesHub</h2>
        <form method="POST" action="login.php">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required placeholder="you@example.com">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Your password">
            </div>
            <button type="submit" class="btn btn-coral btn-block">Login</button>
        </form>
        <p style="margin-top:16px; font-size:13.5px; text-align:center;">New here? <a href="register.php">Create an account</a></p>
        <p class="hint" style="text-align:center; margin-top:6px;">Admin? Same form — you'll be redirected to the admin panel automatically.</p>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
