<?php
session_start();
require_once "config/database.php";

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$query = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Produk - Fresh Fruit Admin</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid var(--border-color); }
        th { background-color: var(--bg-light); color: var(--primary-hover); }
        .img-thumb { width: 50px; height: 50px; object-fit: cover; border-radius: 6px; }
        .action-btns { display: flex; gap: 8px; }
        .btn-danger { background-color: #e74c3c; }
        .btn-danger:hover { background-color: #c0392b; }
        .btn-edit { background-color: #f39c12; }
        .btn-edit:hover { background-color: #d35400; }
    </style>
</head>
<body>

<div class="navbar">
    <div class="container">
        <a href="admin_dashboard.php" class="brand-logo">Fresh Fruit Admin</a>
        <div class="nav-links">
            <span>Halo, <?= htmlspecialchars($_SESSION['admin_name']); ?></span>
            <a href="logout.php" class="btn btn-secondary">Logout</a>
        </div>
    </div>
</div>

<div class="container">
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h2>Daftar Produk Buah</h2>
            <a href="produk_tambah.php" class="btn">+ Tambah Buah</a>
        </div>

        <?php if (isset($_GET['pesan'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_GET['pesan']); ?></div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Nama Buah</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok (kg)</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($query)): ?>
                <tr>
                    <td>
                        <?php if ($row['gambar']): ?>
                           <!-- BENAR: Mengarahkan langsung ke folder uploads/ -->
<img src="uploads/<?= htmlspecialchars($row['gambar']); ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;" alt="<?= htmlspecialchars($row['nama']); ?>">
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td><strong><?= htmlspecialchars($row['nama']); ?></strong></td>
                    <td><?= htmlspecialchars($row['kategori']); ?></td>
                    <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                    <td><?= $row['stok']; ?></td>
                    <td class="action-btns">
                        <a href="produk_edit.php?id=<?= $row['id']; ?>" class="btn btn-edit" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;">Edit</a>
                        <a href="produk_hapus.php?id=<?= $row['id']; ?>" class="btn btn-danger" style="padding: 0.4rem 0.8rem; font-size: 0.85rem;" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>