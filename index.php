<?php
// LOGIN PAGE to
session_start();
require 'db.php';

// kung nakalogin na, pwede nang diretso sa dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";
$success = isset($_GET['registered']) ? "Account created! You can now log in." : "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['username']);   // pwedeng alinman username o email
    $password = $_POST['password'];

    // prepared statement para iwas sa SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1");
    $stmt->execute([$login, $login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        // iwas session fixation
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - AuthSystem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #d4bbaa; min-height: 100vh; display: flex; align-items: center; }
        .auth-card { max-width: 420px; width: 100%; }
        .icon-circle { width: 64px; height: 64px; border-radius: 50%; display: flex;
                       align-items: center; justify-content: center; font-size: 1.8rem; color: #fff; }
    </style>
</head>
<body>
<div class="container d-flex justify-content-center">
    <div class="auth-card">
        <div class="text-center mb-4">
            <div class="icon-circle bg-primary mx-auto mb-3"><i class="bi bi-person-fill"></i></div>
            <h2 class="fw-bold">Welcome Back</h2>
            <p class="text-muted">Sign in to your account</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success py-2"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST">
            <label class="form-label fw-semibold">Username or Email</label>
            <div class="input-group mb-3">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" name="username" class="form-control" placeholder="Enter username or email" required>
            </div>

            <label class="form-label fw-semibold">Password</label>
            <div class="input-group mb-4">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-box-arrow-in-right"></i> Login
            </button>
        </form>

        <p class="text-center mt-3 mb-0">
            Don't have an account? <a href="register.php">Create one</a>
        </p>
    </div>
</div>
</body>
</html>
