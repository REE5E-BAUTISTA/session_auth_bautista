<?php
// DASHBOARD (protected page)
session_start();

// kung hindi nakalogin, back to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id  = $_SESSION['user_id'];
$username = $_SESSION['username'];
$session_id = session_id();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - SessionAuthSystem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: rgb(213, 200, 200); }
        code { word-break: break-all; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="dashboard.php">SessionAuthSystem</a>
        <ul class="navbar-nav me-auto">
            <li class="nav-item"><a class="nav-link active" href="dashboard.php">Dashboard</a></li>
        </ul>
        <span class="navbar-text text-white me-3">
            <i class="bi bi-person-circle"></i> <?= htmlspecialchars($username) ?>
        </span>
        <a href="logout.php" class="btn btn-outline-danger btn-sm">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>
</nav>

<div class="container my-5">
    <!-- Welcome -->
    <div class="card shadow-sm mb-4">
        <div class="card-body p-5">
            <h1 class="text-primary fw-bold">Welcome, <?= htmlspecialchars($username) ?>!</h1>
            <p class="text-muted fs-5">This page is protected and only visible to logged-in users.</p>
            <a href="#details" class="btn btn-primary">Explore Dashboard</a>
        </div>
    </div>

    <!-- Info cards -->
    <div class="row g-4" id="details">
        <div class="col-md-4">
            <h5 class="text-secondary"><i class="bi bi-shield-check"></i> Account Status</h5>
            <p class="text-muted small mb-0">
                Your session is active and protected against Session Fixation using
                <code>session_regenerate_id(true)</code>.
            </p>
        </div>

        <div class="col-md-4">
            <h5 class="text-info"><i class="bi bi-person-badge"></i> User Details</h5>
            <p class="mb-1"><strong>User ID:</strong> <?= (int)$user_id ?></p>
            <p class="mb-0"><strong>Username:</strong> <?= htmlspecialchars($username) ?></p>
        </div>

        <div class="col-md-4">
            <h5 class="text-secondary"><i class="bi bi-key"></i> Active Session ID</h5>
            <p class="mb-1"><strong>Current Session ID:</strong></p>
            <code class="small"><?= htmlspecialchars($session_id) ?></code>
            <p class="text-muted small mt-2 mb-0">
                Generated after login via call to <code>session_regenerate_id(true)</code>.
            </p>
        </div>
    </div>

    <hr class="my-4">
    <p class="text-muted small text-end">
        <i class="bi bi-info-circle-fill"></i> This is a demo dashboard. The only thing that you can see is what your eyes can see.
    </p>
</div>

</body>
</html>
