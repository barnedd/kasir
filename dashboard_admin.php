<?php
// Mulai session
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    // Redirect ke halaman login jika belum login
    header("Location: login_admin.php");
    exit();
}

// Ambil username dan role dari session
$username = $_SESSION['username'];
$role = ucfirst($_SESSION['role']); // Ubah huruf pertama role menjadi kapital
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kasir</title>
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

        .menu-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .menu-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s;
        }

        .menu-card:hover {
            transform: translateY(-5px);
        }

        .menu-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .menu-card .content {
            padding: 15px;
            text-align: center;
        }

        .menu-card .content h3 {
            margin: 10px 0;
            font-size: 18px;
            color: #2c3e50;
        }

        .menu-card .content p {
            font-size: 16px;
            color: #7f8c8d;
        }

        .menu-card .content .price {
            margin: 10px 0;
            font-size: 18px;
            font-weight: bold;
            color: #27ae60;
        }

        .menu-card .content .btn-group {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .btn {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: #fff;
            background-color: #2c3e50;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #34495e;
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
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Dashboard Kasir</h2>
        <ul>
            <li><a href="#"><i class="bx bx-home"></i> Dashboard</a></li>
            <li><a href="transaksi.php"><i class="bx bx-cart"></i> Transaksi</a></li>
            <li><a href="logout.php"><i class="bx bx-log-out"></i> Logout</a></li>
        </ul>
    </div>
    <div class="main">
        <!-- Ubah dari 'Daftar Menu' ke 'Halo, username - role' -->
        <h1>Halo, <?= htmlspecialchars($username); ?> - <?= htmlspecialchars($role); ?></h1>
        <div class="add-menu">
            <button onclick="addMenu()">Tambah Menu</button>
        </div>
        <div class="menu-container" id="menu-container">
            <?php
            $conn = new mysqli("localhost", "root", "", "kasir");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT ProdukID, NamaProduk, harga, stok FROM produk";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='menu-card'>";
                    echo "<div class='content'>";
                    echo "<h3>{$row['NamaProduk']}</h3>";
                    echo "<p>Stok: {$row['stok']}</p>";
                    echo "<p class='price'>Rp.{$row['harga']}</p>";
                    echo "<div class='btn-group'>";
                    echo "<a href='edit_menu.php?id={$row['ProdukID']}' class='btn'>Edit</a>";
                    echo "<button class='btn' onclick='deleteMenu({$row['ProdukID']})'>Hapus</button>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p>Tidak ada menu tersedia.</p>";
            }

            $conn->close();
            ?>
        </div>
    </div>

    <script>
        function addMenu() {
            window.location.href = 'add_menu.php';
        }

        function deleteMenu(id) {
            if (confirm('Apakah Anda yakin ingin menghapus menu ini?')) {
                window.location.href = `delete_menu.php?id=${id}`;
            }
        }
    </script>
</body>
</html>
