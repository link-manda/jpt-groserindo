<?php
// File: login.php
// Halaman ini menangani proses login pengguna.

// Memulai session
session_start();

// Jika pengguna sudah login, arahkan ke halaman utama (index.php)
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Memasukkan file koneksi database
require 'config/database.php';

$error_message = '';

// Memeriksa apakah form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Mencari pengguna di database berdasarkan username
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Memverifikasi pengguna dan password
    // password_verify() digunakan karena password di database di-hash
    if ($user && password_verify($password, $user['password'])) {
        // Jika berhasil, simpan informasi pengguna ke dalam session
        $_SESSION['user_id'] = $user['id_user'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
        $_SESSION['role'] = $user['role'];

        // Arahkan ke halaman utama
        header("Location: index.php?page=dashboard");
        exit();
    } else {
        // Jika gagal, tampilkan pesan error
        $error_message = 'Username atau password salah.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi JPT Groserindo</title>
    <!-- Menghubungkan ke file CSS yang dihasilkan oleh Tailwind -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="[https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap](https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap)" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="flex items-center justify-center h-screen">
        <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-lg">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-800">PT. Jaya Pratama Groserindo</h1>
                <p class="text-gray-500">Sistem Informasi Manajemen Stok & Pemesanan</p>
            </div>
            <form action="login.php" method="POST">
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" id="username" name="username" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., admin" required>
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., password" required>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition duration-300">Login</button>
                <?php if ($error_message): ?>
                    <p class="text-red-500 text-sm mt-4 text-center"><?php echo $error_message; ?></p>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>

</html>