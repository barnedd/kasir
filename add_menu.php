<?php
// Koneksi ke database
$host = "localhost";
$username = "root";
$password = "";
$database = "kasir";

// Buat koneksi
$conn = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Fungsi untuk membuat ProdukID random yang sesuai batas INT
function generateProdukID() {
    return rand(100000000, 2147483647); 
}

// Jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ProdukID = generateProdukID();
    $NamaProduk = $_POST['NamaProduk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    // Validasi input
    if (!empty($NamaProduk) && !empty($harga) && !empty($stok)) {
        // Query untuk memasukkan data ke tabel produk
        $sql = "INSERT INTO produk (ProdukID, NamaProduk, harga, stok) 
                VALUES ($ProdukID, '$NamaProduk', $harga, $stok)";

        if ($conn->query($sql) === TRUE) {
            echo "<script>
                alert('Menu berhasil ditambahkan!');
                window.location.href = 'dashboard_admin.php';
            </script>";
        } else {
            echo "<script>
                alert('Gagal menambahkan menu: " . $conn->error . "');
            </script>";
        }
    } else {
        echo "<script>alert('Semua field harus diisi!');</script>";
    }
}

// Tutup koneksi
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Menu</title>
    <style>
        body {
            font-family: "Poppins", sans-serif;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-container form {
            display: flex;
            flex-direction: column;
        }

        .form-container label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-container input[type="text"],
        .form-container input[type="number"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-container button {
            padding: 10px;
            background-color: #27ae60;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .form-container button:hover {
            background-color: #2ecc71;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Tambah Menu</h2>
        <form action="add_menu.php" method="POST">
            <label for="NamaProduk">Nama Produk:</label>
            <input type="text" id="NamaProduk" name="NamaProduk" required>

            <label for="harga">Harga:</label>
            <input type="number" id="harga" name="harga" required>

            <label for="stok">Stok:</label>
            <input type="number" id="stok" name="stok" required>

            <button type="submit">Tambah Menu</button>
        </form>
    </div>
</body>
</html>
