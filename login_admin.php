<?php
// Mulai sesi
session_start();

// Panggil file koneksi database
include 'db_connection.php';

// Cek apakah form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk mengecek username
    $sql = "SELECT * FROM user WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verifikasi password (dengan hash)
        if (password_verify($password, $user['password'])) {
            // Login berhasil
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect berdasarkan role
            if ($user['role'] === 'admin') {
                header("Location: dashboard_admin.php");
            } else {
                header("Location: dashboard_user.php"); // Halaman untuk pengguna non-admin
            }
            exit();
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Username tidak ditemukan.";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        /* Salin CSS dari desain Anda sebelumnya */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: url('https://fastly.4sqi.net/img/general/500x500/DCJUWGGT3JUR12ELCZBNEH5RATEAT25Y3WAXHK4W0N3C5T43.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .wrapper {
            width: 400px;
            padding: 40px;
            background: rgba(255, 255, 255, 0.3);
            color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            backdrop-filter: blur(20px);
        }

        .wrapper h1 {
            margin-bottom: 20px;
        }

        .wrapper .input-box {
            width: 100%;
            margin: 20px 0;
            position: relative;
        }

        .input-box input {
            width: 100%;
            padding: 10px 20px;
            background: transparent;
            border: 2px solid #fff;
            border-radius: 40px;
            outline: none;
            color: #fff;
        }

        .input-box input::placeholder {
            color: #fff;
        }

        .input-box i {
            position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            color: #fff;
            cursor: pointer;
        }

        .btn {
            width: 100%;
            padding: 10px 20px;
            background: #fff;
            color: black;
            border: none;
            border-radius: 40px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #ddd;
        }

        p {
            text-align: center;
            margin-top: 20px;
        }

        p a {
            color: #fff;
            text-decoration: none;
            position: relative;
            transition: all 0.3s ease;
        }

        p a::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: #fff;
            transform: scaleX(0);
            transform-origin: bottom right;
            transition: transform 0.3s ease;
        }

        p a:hover::before {
            transform: scaleX(1);
            transform-origin: bottom left;
        }

        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <form action="" method="POST">
            <h1>Login Admin</h1>
            <div class="input-box">
                <input type="text" name="username" placeholder="Username" required>
                <i class='bx bx-user'></i>
            </div>
            <div class="input-box">
                <input type="password" name="password" id="password" placeholder="Password" required oninput="togglePasswordIcon()">
                <i id="password-icon" class='bx bx-lock'></i>
            </div>
            <button type="submit" class="btn">Login</button>
            <p>Belum punya akun? <a href="register_admin.php">Register</a></p>
            <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        </form>
    </div>

    <script>
        function togglePasswordIcon() {
            var passwordInput = document.getElementById("password");
            var passwordIcon = document.getElementById("password-icon");

            if (passwordInput.value.length > 0) {
                passwordIcon.style.display = "none";
            } else {
                passwordIcon.style.display = "block";
            }
        }
    </script>
</body>
</html>
