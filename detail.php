<?php
require_once "config/database.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = mysqli_prepare($conn, "SELECT * FROM produk WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$produk = mysqli_fetch_assoc($result);

if (!$produk) {
    die("Produk tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($produk['nama']); ?> - Fresh Fruit</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="navbar">
    <div class="container">
        <a href="index.php" class="brand-logo">Fresh Fruit</a>
        <div class="nav-links">
            <a href="index.php">Beranda</a>
            <a href="produk.php">Produk</a>
        </div>
    </div>
</div>

<div class="container" style="max-width: 700px;">
    <div class="card">
        <?php if ($produk['gambar']): ?>
            <img src="uploads/<?= htmlspecialchars($produk['gambar']); ?>" class="product-image" style="height: 320px;" alt="<?= htmlspecialchars($produk['nama']); ?>">
        <?php endif; ?>

        <span class="product-category"><?= htmlspecialchars($produk['kategori']); ?></span>
        <h1 style="margin-top: 0.5rem;"><?= htmlspecialchars($produk['nama']); ?></h1>
        <div class="price" style="font-size: 1.8rem;">Rp <?= number_format($produk['harga'], 0, ',', '.'); ?></div>
        
        <p style="margin: 1.5rem 0;"><?= nl2br(htmlspecialchars($produk['deskripsi'])); ?></p>

        <p style="margin-bottom: 1.5rem; color: var(--text-muted);">Sisa Stok: <strong><?= $produk['stok']; ?> kg</strong></p>

        <a href="produk.php" class="btn btn-secondary">Kembali ke Katalog</a>
    </div>
</div>

</body>
</html>