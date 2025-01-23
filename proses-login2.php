<?php
session_start();
include 'koneksi.php';

if (isset($_SESSION["admin_login"])) {
    header("Location: admin/admin.php");
}
if (isset($_SESSION["petugas_login"])) {
    header("Location: petugas/petugas.php");
}

// Ambil input dari form
$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$password = mysqli_real_escape_string($koneksi, $_POST['password']);

// Query untuk memeriksa username di database
$sql = "SELECT * FROM petugas WHERE username = '$username'";
$query = mysqli_query($koneksi, $sql);

// Cek apakah query berhasil
if (!$query) {
    die("Query gagal: " . mysqli_error($koneksi));
}

// Cek apakah username ditemukan
if (mysqli_num_rows($query) > 0) {
    $data = mysqli_fetch_assoc($query);

    // Validasi password
    if (($data['password'])) {
        // Set session untuk pengguna
        $_SESSION['nama_petugas'] = $data['nama_petugas'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['level'] = $data['level'];

        // Arahkan pengguna sesuai level
        if ($data['level'] === "admin") {
            $loggedIn = $_SESSION["admin_login"];
            header("Location: admin/admin.php");
            exit;
        } elseif ($data['level'] === "petugas") {
            $loggedIn = $_SESSION["user_login"];
            header("Location: petugas/petugas.php");
            exit;
        } else {
            echo "<script>alert('Role tidak valid!'); window.location.assign('index.php');</script>";
        }
    } else {
        // Jika password salah
        echo "<script>alert('Password salah!'); window.location.assign('index.php');</script>";
    }
} else {
    // Jika username tidak ditemukan
    echo "<script>alert('Username tidak ditemukan!'); window.location.assign('index.php');</script>";
}
?>
