<?php
// Mulai session
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: login_admin.php");
    exit();
}

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "kasir");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil semua produk dari database
$sql_produk = "SELECT ProdukID, NamaProduk, Stok, Harga FROM produk";
$result_produk = $conn->query($sql_produk);

if (!$result_produk) {
    die("Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses Transaksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        form {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1 style="text-align:center;">Proses Transaksi</h1>
    <form action="simpan_transaksi.php" method="POST">
        <label for="NamaPelanggan">Nama Pelanggan:</label>
        <input type="text" id="NamaPelanggan" name="NamaPelanggan" placeholder="Masukkan Nama Pelanggan" required>

        <label for="Alamat">Alamat:</label>
        <input type="text" id="Alamat" name="Alamat" placeholder="Masukkan Alamat Pelanggan" required>

        <label for="NomorTelepon">Nomor Telepon:</label>
        <input type="text" id="NomorTelepon" name="NomorTelepon" placeholder="Masukkan Nomor Telepon" required>

        <label for="produkID">Pilih Produk:</label>
        <select id="produkID" name="produkID" required>
            <option value="">-- Pilih Produk --</option>
            <?php
            while ($row = $result_produk->fetch_assoc()) {
                $stok = isset($row['Stok']) ? (int)$row['Stok'] : 0;
                $harga = isset($row['Harga']) ? (float)$row['Harga'] : 0.0;

                // Hanya tampilkan produk dengan stok > 0
                if ($stok > 0) { ?>
                    <option value="<?php echo $row['ProdukID']; ?>">
                        <?php echo $row['NamaProduk'] . " (Stok: " . $stok . ", Harga: Rp" . number_format($harga, 0, ',', '.') . ")"; ?>
                    </option>
                <?php }
            }
            ?>
        </select>

        <label for="jumlah">Jumlah:</label>
        <input type="number" id="jumlah" name="jumlah" min="1" placeholder="Masukkan Jumlah Produk" required>

        <button type="submit">Simpan Transaksi</button>
    </form>
</body>
</html>
