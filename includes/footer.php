<?php
// File: includes/footer.php
// Bagian ini berisi penutup tag HTML dan skrip notifikasi.
?>
<!-- Memuat file JavaScript kustom -->
<script src="assets/js/script.js"></script>

<?php
// --- PEMBARUAN: Skrip untuk menampilkan notifikasi ---
if (isset($_SESSION['notification'])):
    $notification = $_SESSION['notification'];
    $type = $notification['type']; // 'success', 'error', 'warning', 'info'
    $message = $notification['message'];
?>
    <script>
        // Menunggu semua konten dimuat sebelum menampilkan notifikasi
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: '<?php echo $type; ?>',
                title: '<?php echo ucfirst($type); ?>!',
                text: '<?php echo $message; ?>',
                timer: 1500, // Notifikasi akan hilang setelah 1.5 detik
                timerProgressBar: true,
                showConfirmButton: false
            });
        });
    </script>
<?php
    // Hapus notifikasi dari session setelah ditampilkan
    unset($_SESSION['notification']);
endif;
?>
</body>

</html>