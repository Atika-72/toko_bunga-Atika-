<?php
session_start();
require_once "config/database.php";

if (isset($_SESSION['admin_logged_in'])) {
    header("Location: admindashboard.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Query cek admin
    $query = mysqli_query($conn, "SELECT * FROM admin WHERE username = '$username'");
    $admin = mysqli_fetch_assoc($query);

    // Cek jika username ada DAN password cocok (teks biasa/MD5/password_verify)
    if ($admin && ($password === $admin['password'] || password_verify($password, $admin['password']))) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_name'] = $admin['nama'];
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin - Fresh Fruit</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="container" style="max-width: 400px; margin-top: 5rem;">
    <div class="card">
        <h2 style="text-align: center; margin-bottom: 1.5rem;">Login Admin</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Username</label>
            <input type="text" name="username" required placeholder="Masukkan username">

            <label>Password</label>
            <input type="password" name="password" required placeholder="Masukkan password">

            <button type="submit" class="btn" style="width: 100%;">Masuk</button>
        </form>
    </div>
</div>

</body>
</html>