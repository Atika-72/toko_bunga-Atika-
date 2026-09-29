<?php
require_once "config/database.php";

$query = mysqli_query(
    $conn,
    "SELECT * FROM produk ORDER BY id DESC LIMIT 6"
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Fresh Fruit - Toko Buah Segar</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="navbar">
    <div class="container">
        <a href="index.php" class="brand-logo">Fresh Fruit</a>
        <div class="nav-links">
            <a href="index.php">Beranda</a>
            <a href="produk.php">Produk</a>
            <a href="tentang.php">Tentang Kami</a>
            <a href="login.php" class="btn btn-secondary">Login Admin</a>
        </div>
    </div>
</div>

<div class="container">
    <section class="hero">
        <h1>Buah Segar Setiap Hari</h1>
        <p>Dipetik langsung dari kebun pilihan untuk menjaga mutu dan nutrisi terbaik keluarga Anda.</p>
        <a href="produk.php" class="btn">Belanja Sekarang</a>
    </section>

    <h2>Produk Terbaru</h2>

    <div class="grid">
    <?php while ($row = mysqli_fetch_assoc($query)): ?>
    <div class="card">
        <?php if ($row['gambar']): ?>
            <img src="uploads/<?= htmlspecialchars($row['gambar']); ?>" class="product-image" alt="<?= htmlspecialchars($row['nama']); ?>">
        <?php endif; ?>
        <span class="product-category"><?= htmlspecialchars($row['kategori']); ?></span>
        <h3><?= htmlspecialchars($row['nama']); ?></h3>
        <div class="price">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></div>
        <a href="detail.php?id=<?= $row['id']; ?>" class="btn" style="width: 100%;">Lihat Detail</a>
    </div>
    <?php endwhile; ?>
</div>
</div>

</body>
</html>