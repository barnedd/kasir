<?php
session_start();

// Periksa apakah role ada dalam sesi
if (isset($_SESSION['role'])) {
    $role = $_SESSION['role'];

    // Hapus semua data sesi
    session_unset();
    session_destroy();

    // Redirect berdasarkan role
    if ($role === 'admin') {
        header("Location: login_admin.php");
    } elseif ($role === 'petugas') {
        header("Location: login_petugas.php");
    } else {
        header("Location: login_admin.php"); // Default jika role tidak terdefinisi
    }
} else {
    // Jika sesi role tidak ada, langsung ke login admin
    session_destroy();
    header("Location: login_admin.php");
}

exit;
?>
