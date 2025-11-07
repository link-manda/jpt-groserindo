<?php
// File: pages/dashboard.php

// KPI 1: PO Menunggu Penerimaan
$stmt_po_menunggu = $pdo->query("
    SELECT COUNT(*) 
    FROM purchase_orders 
    WHERE status = 'Menunggu Penerimaan'
");
$po_menunggu = $stmt_po_menunggu->fetchColumn();

// KPI 2: Total PO Bulan Ini
$stmt_po_bulan_ini = $pdo->query("
    SELECT COUNT(*) 
    FROM purchase_orders 
    WHERE MONTH(tanggal_po) = MONTH(CURRENT_DATE()) 
    AND YEAR(tanggal_po) = YEAR(CURRENT_DATE())
");
$po_bulan_ini = $stmt_po_bulan_ini->fetchColumn();

// KPI 3: Jumlah Item Critical Stock
$stmt_critical_stock = $pdo->query("SELECT COUNT(*) FROM barang WHERE stok < 30");
$critical_stock_count = $stmt_critical_stock->fetchColumn();

// Data barang stok sedikit
$stmt_stok_sedikit = $pdo->query("SELECT * FROM barang WHERE stok < 30 ORDER BY stok ASC LIMIT 10");
$barang_stok_sedikit = $stmt_stok_sedikit->fetchAll(PDO::FETCH_ASSOC);
?>

<h1 class="text-3xl font-bold text-gray-800 mb-6">Dashboard Eksekutif</h1>

<!-- KPI Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <!-- Card PO Menunggu Penerimaan -->
    <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition-shadow">
        <div class="flex items-center">
            <div class="bg-yellow-100 text-yellow-600 p-4 rounded-full">
                <i class="fa-solid fa-clock fa-2x"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">PO Menunggu Penerimaan</p>
                <p class="text-3xl font-bold text-gray-800"><?php echo $po_menunggu; ?></p>
                <p class="text-xs text-gray-400 mt-1">Memerlukan tindakan</p>
            </div>
        </div>
    </div>

    <!-- Card PO Bulan Ini -->
    <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition-shadow">
        <div class="flex items-center">
            <div class="bg-blue-100 text-blue-600 p-4 rounded-full">
                <i class="fa-solid fa-file-invoice fa-2x"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">PO Bulan Ini</p>
                <p class="text-3xl font-bold text-gray-800"><?php echo $po_bulan_ini; ?></p>
                <p class="text-xs text-gray-400 mt-1">Total pembelian</p>
            </div>
        </div>
    </div>

    <!-- Card Critical Stock Alert -->
    <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition-shadow">
        <div class="flex items-center">
            <div class="bg-red-100 text-red-600 p-4 rounded-full">
                <i class="fa-solid fa-triangle-exclamation fa-2x"></i>
            </div>
            <div class="ml-4">
                <p class="text-gray-500 text-sm">Item Stok Kritis</p>
                <p class="text-3xl font-bold text-gray-800"><?php echo $critical_stock_count; ?></p>
                <p class="text-xs text-gray-400 mt-1">Stok < 30 unit</p>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Stok Barang Segera Habis -->
<div class="bg-white p-6 rounded-lg shadow mb-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-semibold text-gray-800">⚠️ Alert: Stok Kritis (Stok < 30)</h2>
        <span class="bg-red-100 text-red-700 text-xs font-semibold px-3 py-1 rounded-full">
            Action Required
        </span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full table-auto">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Barang</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Merek</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (count($barang_stok_sedikit) > 0): ?>
                    <?php foreach ($barang_stok_sedikit as $barang): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm"><?php echo htmlspecialchars($barang['id_barang']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium"><?php echo htmlspecialchars($barang['nama_barang']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo htmlspecialchars($barang['merek']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <span class="<?php echo $barang['stok'] < 10 ? 'bg-red-600 text-white' : 'bg-red-100 text-red-700'; ?> font-bold py-1 px-3 rounded-full text-sm">
                                    <?php echo htmlspecialchars($barang['stok']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="text-xs <?php echo $barang['stok'] < 10 ? 'text-red-600 font-bold' : 'text-orange-600'; ?>">
                                    <?php echo $barang['stok'] < 10 ? 'URGENT' : 'Warning'; ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            <i class="fa-solid fa-check-circle text-green-500 mr-2"></i>
                            Semua stok dalam kondisi aman
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Area untuk Grafik -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Grafik Tren Pembelian -->
    <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold text-gray-800 mb-2">📈 Tren Pembelian (12 Bulan Terakhir)</h2>
        <p class="text-sm text-gray-500 mb-4">Analisis pola pembelian untuk perencanaan budget</p>
        <canvas id="trenPembelianChart" height="100"></canvas>
    </div>
    
    <!-- Grafik Top 5 Supplier -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold text-gray-800 mb-2">🏆 Top 5 Supplier</h2>
        <p class="text-sm text-gray-500 mb-4">Berdasarkan jumlah PO</p>
        <canvas id="supplierChart" height="300"></canvas>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Grafik 1: Tren Pembelian (Line Chart)
    fetch('pages/data_grafik.php?chart=tren_pembelian')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
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
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Total PO: ' + context.parsed.y;
                                }
                            }
                        }
                    },
                    scales: { 
                        y: { 
                            beginAtZero: true, 
                            ticks: { precision: 0 },
                            title: { display: true, text: 'Jumlah PO' }
                        },
                        x: {
                            title: { display: true, text: 'Bulan' }
                        }
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error loading chart data:', error);
            document.getElementById('trenPembelianChart').parentElement.innerHTML += 
                '<p class="text-red-500 text-sm mt-2">⚠️ Gagal memuat data grafik</p>';
        });

    // Grafik 2: Top 5 Supplier (Horizontal Bar Chart)
    fetch('pages/data_grafik.php?chart=supplier_distribusi')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const ctx = document.getElementById('supplierChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Jumlah PO',
                        data: data.values,
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(139, 92, 246, 0.8)',
                            'rgba(236, 72, 153, 0.8)'
                        ],
                        borderColor: [
                            'rgb(59, 130, 246)',
                            'rgb(16, 185, 129)',
                            'rgb(245, 158, 11)',
                            'rgb(139, 92, 246)',
                            'rgb(236, 72, 153)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: { 
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Total PO: ' + context.parsed.x;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: { precision: 0 },
                            title: { display: true, text: 'Jumlah PO' }
                        }
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error loading chart data:', error);
            document.getElementById('supplierChart').parentElement.innerHTML += 
                '<p class="text-red-500 text-sm mt-2">⚠️ Gagal memuat data grafik</p>';
        });
});
</script>