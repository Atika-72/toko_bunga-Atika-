<?php
session_start();
require_once "config/database.php";

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = mysqli_prepare($conn, "SELECT gambar FROM produk WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$produk = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if ($produk) {
    if ($produk['gambar'] && file_exists("uploads/produk/" . $produk['gambar'])) {
        unlink("uploads/produk/" . $produk['gambar']);
    }

    $del_stmt = mysqli_prepare($conn, "DELETE FROM produk WHERE id = ?");
    mysqli_stmt_bind_param($del_stmt, "i", $id);
    mysqli_stmt_execute($del_stmt);
}

header("Location: admindashboard.php?pesan=Produk berhasil dihapus!");
exit;
?>