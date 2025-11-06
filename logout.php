<?php
// File: logout.php
// Skrip ini menghancurkan session dan mengarahkan pengguna kembali ke halaman login.

// Memulai session
session_start();

// Menghapus semua variabel session
$_SESSION = array();

// Menghancurkan session
session_destroy();

// Mengarahkan ke halaman login
header("Location: login.php");
exit();
