<?php
session_start();
include 'koneksi.php';

if (isset($_SESSION["admin_login"])) {
    header("Location: admin/admin.php");
    exit;
}
if (isset($_SESSION["user_login"])) {
    header("Location: petugas/petugas.php");
    exit;
}

$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$password = mysqli_real_escape_string($koneksi, $_POST['password']);
$sql = "SELECT * FROM petugas WHERE username = '$username'";
$query = mysqli_query($koneksi, $sql);

if (!$query) {
    die("Query gagal: " . mysqli_error($koneksi));
}

if (mysqli_num_rows($query) > 0) {
    $data = mysqli_fetch_assoc($query);
    if ($data['password']) {
        $_SESSION['nama_petugas'] = $data['nama_petugas'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['level'] = $data['level'];

        if ($data['level'] === "admin") {
            $_SESSION["admin_login"] = true;
            header("Location: admin/admin.php");
            exit;
        } elseif ($data['level'] === "petugas") {
            $_SESSION["user_login"] = true;
            header("Location: petugas/petugas.php");
            exit;
        } else {
            echo "<script>alert('Role tidak valid!'); window.location.assign('index.php');</script>";
        }
    } else {
        echo "<script>alert('Password salah!'); window.location.assign('index.php');</script>";
    }
} else {
    echo "<script>alert('Username tidak ditemukan!'); window.location.assign('index.php');</script>";
}