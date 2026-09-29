<?php
session_start();
require_once "config/database.php";

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama      = trim($_POST['nama']);
    $kategori  = trim($_POST['kategori']);
    $deskripsi = trim($_POST['deskripsi']);
    $harga     = $_POST['harga'];
    $stok      = $_POST['stok'];
    $gambar    = '';

    if (isset($_FILES['gambar']['name']) && $_FILES['gambar']['name'] != '') {
        $filename   = time() . '_' . $_FILES['gambar']['name'];
        $target_dir = "uploads/produk/" . $filename;
        
        if (!is_dir('uploads/produk')) {
            mkdir('uploads/produk', 0777, true);
        }

        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_dir)) {
            $gambar = $filename;
        }
    }

    $stmt = mysqli_prepare($conn, "INSERT INTO produk (nama, kategori, deskripsi, harga, stok, gambar) VALUES (?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssdis", $nama, $kategori, $deskripsi, $harga, $stok, $gambar);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: admindashboard.php?pesan=Produk berhasil ditambahkan!");
        exit;
    } else {
        $error = "Gagal menambah produk.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Buah - Fresh Fruit Admin</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="container" style="max-width: 600px;">
    <div class="card">
        <h2>Tambah Produk Buah</h2>
        <br>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <label>Nama Buah</label>
            <input type="text" name="nama" required placeholder="Contoh: Apel Fuji">

            <label>Kategori</label>
            <input type="text" name="kategori" required placeholder="Contoh: Apel / Impor">

            <label>Harga (Rp)</label>
            <input type="number" step="0.01" name="harga" required placeholder="35000">

            <label>Stok (kg)</label>
            <input type="number" name="stok" required placeholder="50">

            <label>Deskripsi</label>
            <textarea name="deskripsi" rows="4" required></textarea>

            <label>Gambar Produk</label>
            <input type="file" name="gambar" accept="image/*">

            <div style="display: flex; gap: 10px; margin-top: 1rem;">
                <button type="submit" class="btn">Simpan</button>
                <a href="admindashboard.php" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>