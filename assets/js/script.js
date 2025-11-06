// File: assets/js/script.js
// Berkas ini berisi JavaScript untuk interaktivitas, seperti modal.

document.addEventListener('DOMContentLoaded', function () {
    // --- Fungsionalitas Modal Barang ---
    const barangPage = document.querySelector('#btn-tambah-barang');
    if (barangPage) {
        const modal = document.getElementById('barang-modal');
        const btnCloseModal = document.getElementById('btn-close-modal');
        const btnCancelModal = document.getElementById('btn-cancel-modal');
        const modalTitle = document.getElementById('modal-title');
        const barangForm = document.getElementById('barang-form');
        const formAction = document.getElementById('form-action');
        const idBarangInput = document.getElementById('id_barang');
        const previewContainer = document.getElementById('list-gambar-lama');
        const previewWrapper = document.getElementById('preview-gambar-lama');

        const openModal = () => modal.classList.remove('hidden');
        const closeModal = () => modal.classList.add('hidden');

        // Event listener untuk tombol "Tambah Barang"
        document.getElementById('btn-tambah-barang').addEventListener('click', () => {
            barangForm.reset();
            modalTitle.textContent = 'Tambah Barang Baru';
            formAction.value = 'tambah';
            idBarangInput.readOnly = false;
            idBarangInput.classList.remove('bg-gray-100');
            previewWrapper.style.display = 'none'; // Sembunyikan preview saat menambah
            openModal();
        });

        // Event listener untuk tombol "Edit Barang"
        document.querySelectorAll('.btn-edit-barang').forEach(button => {
            button.addEventListener('click', () => {
                // Mengisi data teks
                const id = button.dataset.id;
                modalTitle.textContent = 'Edit Data Barang';
                formAction.value = 'edit';
                idBarangInput.value = id;
                document.getElementById('nama_barang').value = button.dataset.nama;
                document.getElementById('merek').value = button.dataset.merek;
                document.getElementById('stok').value = button.dataset.stok;
                document.getElementById('lokasi').value = button.dataset.lokasi;
                idBarangInput.readOnly = true;
                idBarangInput.classList.add('bg-gray-100');

                // Mengambil dan menampilkan gambar yang sudah ada
                previewContainer.innerHTML = ''; // Kosongkan dulu
                fetch(`api_get_images.php?id_barang=${id}`)
                    .then(response => response.json())
                    .then(images => {
                        if (images.length > 0) {
                            previewWrapper.style.display = 'block';
                            images.forEach(img => {
                                const imgHtml = `
                                    <div class="relative gambar-item">
                                        <img src="uploads/barang/${img.nama_file}" class="w-full h-24 object-cover rounded-md">
                                        <label class="absolute top-1 right-1 flex items-center bg-white bg-opacity-75 p-1 rounded-full text-xs cursor-pointer">
                                            <input type="checkbox" name="hapus_gambar[]" value="${img.id_gambar}" class="mr-1 h-4 w-4"> Hapus
                                        </label>
                                    </div>
                                `;
                                previewContainer.innerHTML += imgHtml;
                            });
                        } else {
                            previewWrapper.style.display = 'none';
                        }
                    });

                openModal();
            });
        });

        // Event listener untuk menutup modal
        btnCloseModal.addEventListener('click', closeModal);
        btnCancelModal.addEventListener('click', closeModal);
    }

    // --- Fungsionalitas Halaman Manajemen Pengguna (KODE BARU) ---
    const penggunaPage = document.querySelector('#btn-tambah-pengguna');
    if (penggunaPage) {
        const modal = document.getElementById('pengguna-modal');
        const btnCloseModal = document.getElementById('btn-close-pengguna-modal');
        const btnCancelModal = document.getElementById('btn-cancel-pengguna-modal');
        const modalTitle = document.getElementById('pengguna-modal-title');
        const penggunaForm = document.getElementById('pengguna-form');
        const formAction = document.getElementById('pengguna-form-action');
        const passwordHelp = document.getElementById('password-help');
        const passwordInput = document.getElementById('password');

        const openModal = () => modal.classList.remove('hidden');
        const closeModal = () => modal.classList.add('hidden');

        // Event listener untuk tombol "Tambah Pengguna"
        document.getElementById('btn-tambah-pengguna').addEventListener('click', () => {
            penggunaForm.reset();
            modalTitle.textContent = 'Tambah Pengguna Baru';
            formAction.value = 'tambah';
            passwordHelp.classList.add('hidden'); // Sembunyikan teks bantuan
            passwordInput.required = true; // Password wajib diisi saat menambah
            openModal();
        });

        // Event listener untuk tombol "Edit Pengguna"
        document.querySelectorAll('.btn-edit-pengguna').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.dataset.id;
                const nama = button.dataset.nama;
                const username = button.dataset.username;
                const role = button.dataset.role;

                modalTitle.textContent = 'Edit Data Pengguna';
                formAction.value = 'edit';
                document.getElementById('id_user').value = id;
                document.getElementById('nama_lengkap').value = nama;
                document.getElementById('username').value = username;
                document.getElementById('role').value = role;

                passwordHelp.classList.remove('hidden'); // Tampilkan teks bantuan
                passwordInput.required = false; // Password tidak wajib diisi saat edit

                openModal();
            });
        });

        // Event listener untuk menutup modal
        btnCloseModal.addEventListener('click', closeModal);
        btnCancelModal.addEventListener('click', closeModal);

        window.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeModal();
            }
        });
    }

    // --- PEMBARUAN: Fungsionalitas Sidebar Toggle (KODE BARU) ---
    const sidebar = document.getElementById('sidebar');
    const btnToggleSidebar = document.getElementById('btn-toggle-sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    const showSidebar = () => {
        sidebar.classList.remove('-translate-x-full');
        sidebar.classList.add('translate-x-0');
        overlay.classList.remove('hidden');
    };

    const hideSidebar = () => {
        sidebar.classList.remove('translate-x-0');
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    };

    if (btnToggleSidebar) {
        btnToggleSidebar.addEventListener('click', showSidebar);
    }

    if (overlay) {
        overlay.addEventListener('click', hideSidebar);
    }

    // Menambahkan event listener untuk menutup sidebar jika link menu di klik (berguna di mobile)
    sidebar.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 1024) { // 1024px adalah breakpoint lg dari Tailwind
                hideSidebar();
            }
        });
    });

});
