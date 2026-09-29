<?php
require_once "config/database.php";

$keyword = $_GET['keyword'] ?? '';

if (!empty($keyword)) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM produk WHERE nama LIKE ? ORDER BY id DESC");
    $searchTerm = "%" . $keyword . "%";
    mysqli_stmt_bind_param($stmt, "s", $searchTerm);
    mysqli_stmt_execute($stmt);
    $query = mysqli_stmt_get_result($stmt);
} else {
    $query = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Produk - Fresh Fruit</title>
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
        </div>
    </div>
</div>

<div class="container">
    <h1>Daftar Buah Segar</h1>

    <div class="card" style="margin-bottom: 2rem;">
        <form method="GET" style="display: flex; gap: 10px;">
            <input type="text" name="keyword" placeholder="Cari buah..." value="<?= htmlspecialchars($keyword); ?>" style="margin: 0;">
            <button type="submit" class="btn">Cari</button>
        </form>
    </div>

    <div class="grid">
        <?php if (mysqli_num_rows($query) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($query)): ?>
            <div class="card">
                <?php if ($row['gambar']): ?>
                   <img src="uploads/<?= htmlspecialchars($row['gambar']); ?>" class="product-image" alt="<?= htmlspecialchars($row['nama']); ?>">
                <?php endif; ?>
                <h3><?= htmlspecialchars($row['nama']); ?></h3>
                <p style="color: var(--text-muted); font-size: 0.9rem;"><?= htmlspecialchars($row['deskripsi']); ?></p>
                <div class="price">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></div>
                <a href="detail.php?id=<?= $row['id']; ?>" class="btn" style="width: 100%;">Lihat Detail</a>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Buah tidak ditemukan.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>