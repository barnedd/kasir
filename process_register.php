<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "kasir";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password
    $role = $_POST['role'];

    $sql = "INSERT INTO user (username, password, role) VALUES ('$username', '$password', '$role')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Register berhasil!');
                window.location.href = 'login_admin.php';
              </script>";
    } else {
        echo "<script>
                alert('Register gagal: " . mysqli_error($conn) . "');
                window.history.back();
              </script>";
    }
}

mysqli_close($conn);
?>
