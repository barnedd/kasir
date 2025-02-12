<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "kasir");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Cek apakah parameter `id` ada
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Pastikan id berupa integer untuk menghindari SQL Injection
    
    // Query delete
    $sql = "DELETE FROM produk WHERE ProdukID = $id";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Menu berhasil dihapus!'); window.location.href = 'dashboard_admin.php';</script>";
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
    }
} else {
    echo "<p>Parameter ID tidak ditemukan.</p>";
}

$conn->close();
?>
