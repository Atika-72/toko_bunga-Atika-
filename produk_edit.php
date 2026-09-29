<?php
session_start();
require_once "config/database.php";

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = mysqli_prepare($conn, "SELECT * FROM produk WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$produk = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$produk) {
    die("Produk tidak ditemukan.");
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama      = trim($_POST['nama']);
    $kategori  = trim($_POST['kategori']);
    $deskripsi = trim($_POST['deskripsi']);
    $harga     = $_POST['harga'];
    $stok      = $_POST['stok'];
    $gambar    = $produk['gambar'];

    if (isset($_FILES['gambar']['name']) && $_FILES['gambar']['name'] != '') {
        $filename   = time() . '_' . $_FILES['gambar']['name'];
        $target_dir = "uploads/produk/" . $filename;

        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_dir)) {
            if ($produk['gambar'] && file_exists("uploads/produk/" . $produk['gambar'])) {
                unlink("uploads/produk/" . $produk['gambar']);
            }
            $gambar = $filename;
        }
    }

    $stmt_update = mysqli_prepare($conn, "UPDATE produk SET nama = ?, kategori = ?, deskripsi = ?, harga = ?, stok = ?, gambar = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt_update, "sssdisi", $nama, $kategori, $deskripsi, $harga, $stok, $gambar, $id);

    if (mysqli_stmt_execute($stmt_update)) {
        header("Location: admindashboard.php?pesan=Produk berhasil diperbarui!");
        exit;
    } else {
        $error = "Gagal memperbarui produk.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Buah - Fresh Fruit Admin</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="container" style="max-width: 600px;">
    <div class="card">
        <h2>Edit Produk Buah</h2>
        <br>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <label>Nama Buah</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($produk['nama']); ?>" required>

            <label>Kategori</label>
            <input type="text" name="kategori" value="<?= htmlspecialchars($produk['kategori']); ?>" required>

            <label>Harga (Rp)</label>
            <input type="number" step="0.01" name="harga" value="<?= $produk['harga']; ?>" required>

            <label>Stok (kg)</label>
            <input type="number" name="stok" value="<?= $produk['stok']; ?>" required>

            <label>Deskripsi</label>
            <textarea name="deskripsi" rows="4" required><?= htmlspecialchars($produk['deskripsi']); ?></textarea>

            <label>Gambar Produk (Biarkan kosong jika tidak ingin diubah)</label>
            <input type="file" name="gambar" accept="image/*">

            <div style="display: flex; gap: 10px; margin-top: 1rem;">
                <button type="submit" class="btn">Update</button>
                <a href="admindashboard.php" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>