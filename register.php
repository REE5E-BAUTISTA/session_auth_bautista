<?php
// Its time for "REGISTER PAGE" to shine 
session_start();
require 'db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    // validation para sure
    if (strlen($username) < 3) {
        $error = "Username must be at least 3 characters.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } else {
        // check kung nageexist na
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);

        if ($stmt->fetch()) {
            $error = "Username or email already exists.";
        } else {
            // i-hash ang password bago i-save para mas secured
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $hash]);

            header("Location: index.php?registered=1");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - AuthSystem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #d69074; min-height: 100vh; display: flex; align-items: center; }
        .auth-card { max-width: 420px; width: 100%; }
        .icon-circle { width: 64px; height: 64px; border-radius: 50%; display: flex;
                       align-items: center; justify-content: center; font-size: 1.8rem; color: #fff; }
    </style>
</head>
<body>
<div class="container d-flex justify-content-center">
    <div class="auth-card">
        <div class="text-center mb-4">
            <div class="icon-circle bg-success mx-auto mb-3"><i class="bi bi-person-plus-fill"></i></div>
            <h2 class="fw-bold">Create Account</h2>
            <p class="text-muted">Sign up to get started</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <label class="form-label fw-semibold">Username</label>
            <div class="input-group mb-3">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" name="username" class="form-control" placeholder="Enter username"
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
            </div>

            <label class="form-label fw-semibold">Email Address</label>
            <div class="input-group mb-3">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control" placeholder="name@example.com"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>

            <label class="form-label fw-semibold">Password</label>
            <div class="input-group mb-4">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Create a password" required>
            </div>

            <button type="submit" class="btn btn-success w-100">
                <i class="bi bi-person-plus"></i> Register
            </button>
        </form>

        <p class="text-center mt-3 mb-0">
            Already have an account? <a href="index.php">Login</a>
        </p>
    </div>
</div>
</body>
</html>
