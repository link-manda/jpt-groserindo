<?php
// File: pages/dashboard.php
// 1. Menghitung Total Jenis Barang
$stmt_total_barang = $pdo->query("SELECT COUNT(*) FROM barang");
$total_barang = $stmt_total_barang->fetchColumn();

// 2. Menghitung Total Stok Keseluruhan
$stmt_total_stok = $pdo->query("SELECT SUM(stok) FROM barang");
$total_stok = $stmt_total_stok->fetchColumn();

// 3. Menghitung PO yang Menunggu Penerimaan
$stmt_po_menunggu = $pdo->query("SELECT COUNT(*) FROM purchase_orders WHERE status = 'Menunggu Penerimaan'");
$po_menunggu = $stmt_po_menunggu->fetchColumn();

// 4. Mengambil data barang yang stoknya sedikit (misal, di bawah 30)
$stmt_stok_sedikit = $pdo->query("SELECT * FROM barang WHERE stok < 30 ORDER BY stok ASC");
$barang_stok_sedikit = $stmt_stok_sedikit->fetchAll(PDO::FETCH_ASSOC);
?>

<h1 class="text-3xl font-bold text-gray-800 mb-6">Dashboard</h1>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Card Total Jenis Barang -->
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center">
            <div class="bg-blue-100 text-blue-600 p-4 rounded-full">
                <i class="fa-solid fa-box fa-2x"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500">Total Jenis Barang</p>
                <p class="text-3xl font-bold text-gray-800"><?php echo $total_barang; ?></p>
            </div>
        </div>
    </div>
    <!-- Card Total Stok Keseluruhan -->
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center">
            <div class="bg-green-100 text-green-600 p-4 rounded-full">
                <i class="fa-solid fa-boxes-stacked fa-2x"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500">Total Stok Keseluruhan</p>
                <p class="text-3xl font-bold text-gray-800"><?php echo number_format($total_stok ?? 0); ?></p>
            </div>
        </div>
    </div>
    <!-- Card PO Menunggu Penerimaan -->
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center">
            <div class="bg-yellow-100 text-yellow-600 p-4 rounded-full">
                <i class="fa-solid fa-file-invoice fa-2x"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500">PO Menunggu Penerimaan</p>
                <p class="text-3xl font-bold text-gray-800"><?php echo $po_menunggu; ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Stok Barang Segera Habis -->
<div class="mt-8 bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Stok Barang Segera Habis (Stok < 30)</h2>
            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Barang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Merek</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (count($barang_stok_sedikit) > 0): ?>
                            <?php foreach ($barang_stok_sedikit as $barang): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($barang['id_barang']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($barang['nama_barang']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($barang['merek']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <span class="bg-red-100 text-red-700 font-bold py-1 px-3 rounded-full">
                                            <?php echo htmlspecialchars($barang['stok']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">Tidak ada barang dengan stok di bawah 30.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
</div>

<!-- Area untuk Grafik -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
    <!-- Grafik Tren Pembelian -->
    <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Tren Pembelian (12 Bulan Terakhir)</h2>
        <canvas id="trenPembelianChart"></canvas>
    </div>
    <!-- Grafik Supplier -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Distribusi PO per Supplier</h2>
        <div class="h-80 flex items-center justify-center">
            <canvas id="supplierChart"></canvas>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
    <!-- Grafik Stok Terbanyak -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">5 Barang dengan Stok Terbanyak</h2>
        <canvas id="stokTerbanyakChart"></canvas>
    </div>
    <!-- Grafik Stok Paling Sedikit -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">5 Barang dengan Stok Paling Sedikit</h2>
        <canvas id="stokSedikitChart"></canvas>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Grafik 1: Tren Pembelian (Line Chart)
    fetch('data_grafik.php?chart=tren_pembelian')
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById('trenPembelianChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Jumlah PO',
                        data: data.values,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
                }
            });
        });

    // Grafik 2: Distribusi Supplier (Doughnut Chart)
    fetch('data_grafik.php?chart=supplier_distribusi')
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById('supplierChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Jumlah PO',
                        data: data.values,
                        backgroundColor: [
                            'rgb(59, 130, 246)',
                            'rgb(239, 68, 68)',
                            'rgb(245, 158, 11)',
                            'rgb(16, 185, 129)',
                            'rgb(139, 92, 246)',
                        ],
                        hoverOffset: 4
                    }]
                },
                options: { 
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        });

    // Grafik 3 & 4: Stok Barang (Bar Charts)
    fetch('data_grafik.php?chart=stok_barang')
        .then(response => response.json())
        .then(data => {
            // Stok Terbanyak
            const ctxTop = document.getElementById('stokTerbanyakChart').getContext('2d');
            new Chart(ctxTop, {
                type: 'bar',
                data: {
                    labels: data.top.labels,
                    datasets: [{
                        label: 'Jumlah Stok',
                        data: data.top.values,
                        backgroundColor: 'rgba(16, 185, 129, 0.6)',
                        borderColor: 'rgb(16, 185, 129)',
                        borderWidth: 1
                    }]
                },
                options: { 
                    indexAxis: 'y', 
                    responsive: true,
                    plugins: { legend: { display: false } }
                }
            });

            // Stok Paling Sedikit
            const ctxBottom = document.getElementById('stokSedikitChart').getContext('2d');
            new Chart(ctxBottom, {
                type: 'bar',
                data: {
                    labels: data.bottom.labels,
                    datasets: [{
                        label: 'Jumlah Stok',
                        data: data.bottom.values,
                        backgroundColor: 'rgba(239, 68, 68, 0.6)',
                        borderColor: 'rgb(239, 68, 68)',
                        borderWidth: 1
                    }]
                },
                options: { 
                    indexAxis: 'y', 
                    responsive: true,
                    plugins: { legend: { display: false } }
                }
            });
        });
});
</script>