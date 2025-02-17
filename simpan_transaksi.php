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

// Ambil data dari form
$NamaPelanggan = $_POST['NamaPelanggan'];
$Alamat = $_POST['Alamat'];
$NomorTelepon = $_POST['NomorTelepon'];
$ProdukID = $_POST['produkID'];
$Jumlah = (int)$_POST['jumlah'];

// Validasi input
if (empty($NamaPelanggan) || empty($Alamat) || empty($NomorTelepon) || empty($ProdukID) || $Jumlah <= 0) {
    die("Transaksi gagal: Input tidak valid.");
}

// Generate PelangganID dan PenjualanID secara random
$PelangganID = rand(10000, 99999);
$PenjualanID = rand(100000, 999999);

// Mulai transaksi
$conn->begin_transaction();

try {
    // Masukkan data pelanggan ke tabel `pelanggan`
    $sql_pelanggan = "INSERT INTO pelanggan (PelangganID, NamaPelanggan, Alamat, NomorTelepon) VALUES (?, ?, ?, ?)";
    $stmt_pelanggan = $conn->prepare($sql_pelanggan);
    $stmt_pelanggan->bind_param("isss", $PelangganID, $NamaPelanggan, $Alamat, $NomorTelepon);
    $stmt_pelanggan->execute();

    // Ambil harga produk
    $sql_harga = "SELECT Harga, Stok FROM produk WHERE ProdukID = ?";
    $stmt_harga = $conn->prepare($sql_harga);
    $stmt_harga->bind_param("i", $ProdukID);
    $stmt_harga->execute();
    $result_harga = $stmt_harga->get_result();

    if ($result_harga->num_rows === 0) {
        throw new Exception("Produk tidak ditemukan.");
    }

    $row = $result_harga->fetch_assoc();
    $Harga = $row['Harga'];
    $Stok = $row['Stok'];

    // Cek apakah stok mencukupi
    if ($Jumlah > $Stok) {
        throw new Exception("Stok tidak mencukupi untuk transaksi ini.");
    }

    // Hitung subtotal
    $Subtotal = $Harga * $Jumlah;

    // Masukkan data ke tabel `detailpenjualan` dengan IGNORE untuk menghindari error duplicate entry
    $DetailID = rand(1000000, 9999999);
    $sql_detail = "INSERT IGNORE INTO detailpenjualan (DetailID, PenjualanID, ProdukID, JumlahProduk, Subtotal) VALUES (?, ?, ?, ?, ?)";
    $stmt_detail = $conn->prepare($sql_detail);
    $stmt_detail->bind_param("iiiii", $DetailID, $PenjualanID, $ProdukID, $Jumlah, $Subtotal);
    $stmt_detail->execute();

    // Kurangi stok produk
    $stok_baru = $Stok - $Jumlah;
    $sql_update_stok = "UPDATE produk SET Stok = ? WHERE ProdukID = ?";
    $stmt_update_stok = $conn->prepare($sql_update_stok);
    $stmt_update_stok->bind_param("ii", $stok_baru, $ProdukID);
    $stmt_update_stok->execute();

    // Commit transaksi
    $conn->commit();

    echo "
        <script>
            alert('Transaksi berhasil disimpan.');
            window.location.href = 'dashboard_admin.php';
        </script>
    ";
} catch (Exception $e) {
    // Rollback transaksi jika terjadi error
    $conn->rollback();
    echo "
        <script>
            alert('Transaksi gagal: " . $e->getMessage() . "');
            window.location.href = 'dashboard_admin.php';
        </script>
    ";
}

// Tutup koneksi
$conn->close();
?>
