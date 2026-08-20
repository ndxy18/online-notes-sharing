<?php
$base = '';
require_once __DIR__ . '/includes/functions.php';

if (is_logged_in()) { header("Location: dashboard.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = clean($_POST['name'] ?? '');
    $email = clean($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    $course = clean($_POST['course'] ?? '');
    $semester = clean($_POST['semester'] ?? '');

    if ($name === '' || $email === '' || $password === '') {
        flash('error', 'Sab required fields bharo.');
    } elseif (strlen($password) < 6) {
        flash('error', 'Password kam se kam 6 characters ka hona chahiye.');
    } elseif ($password !== $confirm) {
        flash('error', 'Password aur Confirm Password match nahi kar rahe.');
    } else {
        $check = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
        $check->execute([$email]);
        if ($check->fetch()) {
            flash('error', 'Yeh email already registered hai. Login karo.');
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, course, semester, role) VALUES (?, ?, ?, ?, ?, 'student')");
            $stmt->execute([$name, $email, $hash, $course, $semester]);
            flash('success', 'Account ban gaya! Ab login karo.');
            header("Location: login.php");
            exit;
        }
    }
    header("Location: register.php");
    exit;
}

$page_title = "Sign Up";
include __DIR__ . '/includes/header.php';
?>

<div class="container">
    <div class="card form-narrow">
        <p class="eyebrow">Join NotesHub</p>
        <h2>Create your student account</h2>
        <form method="POST" action="register.php">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" required placeholder="e.g. Naveed Shaikh">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required placeholder="you@example.com">
            </div>
            <div class="form-group">
                <label>Course</label>
                <input type="text" name="course" placeholder="e.g. BBA (CA), BCA, B.Com">
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
                <label>Password</label>
                <input type="password" name="password" required placeholder="At least 6 characters">
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-coral btn-block">Create Account</button>
        </form>
        <p style="margin-top:16px; font-size:13.5px; text-align:center;">Already have an account? <a href="login.php">Login here</a></p>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
