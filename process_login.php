<?php
// Koneksi ke database
$host = "localhost:81"; 
$username = "root";
$password = "";
$database = "kasir";

$conn = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Cek apakah data dikirim melalui POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST["username"]);
    $password = $conn->real_escape_string($_POST["password"]);

    // Query untuk memeriksa username dan password
    $query = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Jika login berhasil
        $row = $result->fetch_assoc();
        session_start();
        $_SESSION["username"] = $row["username"];
        $_SESSION["role"] = $row["role"];

        // Redirect berdasarkan role
        if ($row["role"] == "admin") {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: petugas_dashboard.php");
        }
    } else {
        // Jika login gagal
        echo "<script>alert('Username atau password salah!');window.location.href='login_admin.php';</script>";
    }
} else {
    echo "Akses tidak valid.";
}

$conn->close();
?>
