<?php
// Mulai session
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    // Redirect ke halaman login jika belum login
    header("Location: login_admin.php");
    exit();
}

// Ambil username dari session
$username = $_SESSION['username'];

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "kasir");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fungsi untuk mendapatkan produk dari database
function getProduk($conn) {
    $sql = "SELECT ProdukID, NamaProduk, harga, stok FROM produk";
    $result = $conn->query($sql);
    $produk = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $produk[] = $row;
        }
    }
    return $produk;
}

// Ambil data produk
$produkList = getProduk($conn);

// Tutup koneksi jika tidak digunakan lagi
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.4/css/boxicons.min.css">
    <style>
        /* CSS Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background-color: #f9f9f9;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: #fff;
            display: flex;
            flex-direction: column;
            padding: 20px;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: #fff;
            display: flex;
            align-items: center;
            padding: 10px 15px;
            border-radius: 5px;
            transition: 0.3s;
        }

        .sidebar ul li a i {
            margin-right: 10px;
        }

        .sidebar ul li a:hover {
            background-color: #34495e;
        }

        /* Main Content */
        .main {
            flex: 1;
            padding: 20px;
        }

        .main h1 {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #2c3e50;
            color: #fff;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        .add-menu {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
        }

        .add-menu button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #27ae60;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .add-menu button:hover {
            background-color: #2ecc71;
        }

        .btn {
            padding: 8px 12px;
            background-color: #27ae60;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #2ecc71;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Dashboard Kasir</h2>
        <ul>
            <li><a href="dashboard_admin.php"><i class="bx bx-home"></i> Dashboard</a></li>
            <li><a href="transaksi.php"><i class="bx bx-cart"></i> Transaksi</a></li> <!-- Ikon troli -->
            <li><a href="logout.php"><i class="bx bx-log-out"></i> Logout</a></li>
        </ul>
    </div>
    <div class="main">
        <h1>Halo, <?= htmlspecialchars($username); ?> - Transaksi</h1>
        <table>
            <thead>
                <tr>
                    <th>ID Produk</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($produkList)) : ?>
                    <?php foreach ($produkList as $produk) : ?>
                        <tr>
                            <td><?= htmlspecialchars($produk['ProdukID']); ?></td>
                            <td><?= htmlspecialchars($produk['NamaProduk']); ?></td>
                            <td>Rp <?= number_format($produk['harga'], 0, ',', '.'); ?></td>
                            <td><?= htmlspecialchars($produk['stok']); ?></td>
                            <td>
                                <a href="proses_transaksi.php?id=<?= $produk['ProdukID']; ?>" class="btn">Pilih</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5">Tidak ada produk tersedia.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
