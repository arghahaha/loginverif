<?php
session_start();

// Set waktu lama sesi login (15 detik)
$session_timeout = 15; // 15 detik

// Cek apakah pengguna telah login
if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
    // Perbarui waktu sesi
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $session_timeout)) {
        // Waktu sesi telah habis, logout pengguna dan redirect ke halaman login dengan notifikasi
        session_unset();
        session_destroy();
        $_SESSION['status'] = "Your session has expired. Please login again.";
        header("Location: login.php");
        exit(0);
    }

    $_SESSION['last_activity'] = time();

    // ... Lanjutkan dengan konten halaman yang diotorisasi ...
} else {
    // Pengguna belum login, redirect ke halaman login dengan notifikasi
    $_SESSION['status'] = "Please login to access user dashboard !";
    header('Location: login.php');
    exit(0);
}
?>