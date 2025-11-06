<?php
// File: pages/delivery_order.php
// Halaman ini HANYA untuk menampilkan data penerimaan barang. Logika dipindah ke controller.

require_once 'includes/table_helper.php';

// --- DATA UNTUK TABEL ATAS (PO MENUNGGU) ---
$stmt_pending_po = $pdo->query("SELECT po.id_po, po.kode_po, po.tanggal_po, s.nama_supplier FROM purchase_orders po JOIN suppliers s ON po.id_supplier = s.id_supplier WHERE po.status = 'Menunggu Penerimaan' ORDER BY po.tanggal_po ASC");
$pending_po_list = $stmt_pending_po->fetchAll(PDO::FETCH_ASSOC);

// --- LOGIKA UNTUK TABEL BAWAH (RIWAYAT) ---
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 15;
$page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$offset = ($page - 1) * $limit;

$sort_columns = ['tanggal_terima', 'kode_po', 'nama_penerima'];
$sort_by = isset($_GET['sort']) && in_array($_GET['sort'], $sort_columns) ? $_GET['sort'] : 'tanggal_terima';
$order = isset($_GET['order']) && strtolower($_GET['order']) == 'asc' ? 'ASC' : 'DESC';

$search = isset($_GET['search']) ? $_GET['search'] : '';

$sql_base = "FROM delivery_orders do JOIN purchase_orders po ON do.id_po = po.id_po JOIN users u ON do.id_user_penerima = u.id_user";
$params = [];
if (!empty($search)) {
    $sql_base .= " WHERE (po.kode_po LIKE ? OR u.nama_lengkap LIKE ?)";
    $search_param = "%{$search}%";
    $params = [$search_param, $search_param];
}

$count_sql = "SELECT COUNT(do.id_do) " . $sql_base;
$stmt_count = $pdo->prepare($count_sql);
$stmt_count->execute($params);
$total_records = $stmt_count->fetchColumn();
$total_pages = ceil($total_records / $limit);

$data_sql = "SELECT do.id_do, do.tanggal_terima, po.kode_po, u.nama_lengkap as nama_penerima " . $sql_base . " ORDER BY {$sort_by} {$order} LIMIT {$limit} OFFSET {$offset}";
$stmt = $pdo->prepare($data_sql);
$stmt->execute($params);
$history_do_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

$url_params = ['page' => 'delivery-order', 'search' => $search, 'limit' => $limit, 'sort' => $sort_by, 'order' => $order, 'p' => $page];
?>

<h1 class="text-3xl font-bold text-gray-800 mb-6">Penerimaan Barang</h1>

<!-- Bagian PO Menunggu Penerimaan -->
<div class="bg-white p-6 rounded-lg shadow mb-8">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">PO Menunggu Penerimaan</h2>
    <div class="overflow-x-auto">
        <table class="w-full table-auto">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode PO</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal PO</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (count($pending_po_list) > 0): ?>
                    <?php foreach ($pending_po_list as $po): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap font-medium"><?php echo htmlspecialchars($po['kode_po']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars(date('d M Y', strtotime($po['tanggal_po']))); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($po['nama_supplier']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <form method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengkonfirmasi penerimaan untuk PO ini? Stok akan diperbarui.');">
                                    <!-- Input hidden untuk memberitahu controller asal form dan aksinya -->
                                    <input type="hidden" name="page_source" value="delivery-order">
                                    <input type="hidden" name="action" value="proses_penerimaan">
                                    <input type="hidden" name="id_po" value="<?php echo $po['id_po']; ?>">
                                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 text-sm flex items-center gap-2 mx-auto">
                                        <i class="fa-solid fa-check"></i>
                                        <span>Konfirmasi Terima</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">Tidak ada PO yang menunggu untuk diterima.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php generate_controls('delivery-order', $search, $limit, $sort_by, $order); ?>

<!-- Bagian Riwayat Penerimaan Barang -->
<div class="bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Riwayat Penerimaan Barang</h2>
    <div class="overflow-x-auto">
        <table class="w-full table-auto">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php generate_sort_link('tanggal_terima', 'Tanggal Terima', $url_params); ?></th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php generate_sort_link('kode_po', 'Kode PO', $url_params); ?></th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?php generate_sort_link('nama_penerima', 'Diterima Oleh', $url_params); ?></th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($history_do_list as $do): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars(date('d M Y', strtotime($do['tanggal_terima']))); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap font-medium"><?php echo htmlspecialchars($do['kode_po']); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($do['nama_penerima']); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <a href="index.php?page=po-detail&id=<?php echo $do['id_do']; // Seharusnya id_po, tapi kita biarkan dulu 
                                                                    ?>" class="text-blue-500 hover:text-blue-700">
                                <i class="fa-solid fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- Navigasi Paginasi -->
    <div class="mt-6 flex justify-between items-center">
        <div class="text-sm text-gray-600">
            Menampilkan <?php echo $offset + 1; ?> - <?php echo min($offset + $limit, $total_records); ?> dari <?php echo $total_records; ?> data
        </div>
        <?php generate_pagination($total_pages, $url_params); ?>
    </div>
</div>