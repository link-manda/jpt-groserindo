<?php
// File: includes/table_helper.php
// Berisi fungsi-fungsi untuk menghasilkan komponen tabel yang interaktif.

/**
 * Menampilkan form kontrol untuk pencarian dan limit.
 */
function generate_controls($page_name, $search_val, $limit_val, $sort_by, $order)
{
    echo '
    <div class="bg-white p-4 rounded-lg shadow mb-6 flex justify-between items-center flex-wrap gap-4">
        <form action="index.php" method="GET" class="flex items-center gap-2">
            <input type="hidden" name="page" value="' . $page_name . '">
            <input type="hidden" name="sort" value="' . htmlspecialchars($sort_by) . '">
            <input type="hidden" name="order" value="' . htmlspecialchars($order) . '">
            <label for="search" class="sr-only">Cari</label>
            <input type="search" name="search" id="search" placeholder="Cari data..." class="w-64 px-3 py-2 border border-gray-300 rounded-md" value="' . htmlspecialchars($search_val) . '">
            <button type="submit" class="bg-gray-700 text-white px-4 py-2 rounded-md"><i class="fa-solid fa-search"></i></button>
        </form>
        <form action="index.php" method="GET" class="flex items-center gap-2">
            <input type="hidden" name="page" value="' . $page_name . '">
            <input type="hidden" name="sort" value="' . htmlspecialchars($sort_by) . '">
            <input type="hidden" name="order" value="' . htmlspecialchars($order) . '">
            <input type="hidden" name="search" value="' . htmlspecialchars($search_val) . '">
            <label for="limit" class="text-sm text-gray-600">Tampilkan:</label>
            <select name="limit" id="limit" class="px-3 py-2 border border-gray-300 rounded-md" onchange="this.form.submit()">
                <option value="15" ' . ($limit_val == 15 ? 'selected' : '') . '>15</option>
                <option value="25" ' . ($limit_val == 25 ? 'selected' : '') . '>25</option>
                <option value="50" ' . ($limit_val == 50 ? 'selected' : '') . '>50</option>
                <option value="100" ' . ($limit_val == 100 ? 'selected' : '') . '>100</option>
            </select>
        </form>
    </div>';
}

/**
 * Membuat link untuk sorting pada header tabel.
 */
function generate_sort_link($column_name, $display_text, $params)
{
    $order = ($params['sort'] == $column_name && $params['order'] == 'asc') ? 'desc' : 'asc';
    $icon = ($params['sort'] == $column_name) ? ($params['order'] == 'asc' ? '<i class="fa-solid fa-arrow-up ml-1"></i>' : '<i class="fa-solid fa-arrow-down ml-1"></i>') : '';

    $query_params = http_build_query(array_merge($params, ['sort' => $column_name, 'order' => $order, 'p' => 1]));

    echo '<a href="index.php?' . $query_params . '" class="hover:text-gray-900">' . $display_text . $icon . '</a>';
}

/**
 * Menampilkan navigasi paginasi.
 */
function generate_pagination($total_pages, $params)
{
    if ($total_pages <= 1) {
        return;
    }

    echo '<nav class="flex items-center gap-1">';

    // Tombol Previous
    $prev_params = http_build_query(array_merge($params, ['p' => $params['p'] - 1]));
    $prev_class = ($params['p'] <= 1) ? 'pointer-events-none text-gray-400' : 'hover:bg-gray-200';
    echo '<a href="index.php?' . $prev_params . '" class="' . $prev_class . ' px-3 py-2 rounded-md">&laquo; Sebelumnya</a>';

    // Nomor Halaman
    for ($i = 1; $i <= $total_pages; $i++) {
        $page_params = http_build_query(array_merge($params, ['p' => $i]));
        $page_class = ($i == $params['p']) ? 'bg-blue-600 text-white' : 'hover:bg-gray-200';
        echo '<a href="index.php?' . $page_params . '" class="' . $page_class . ' px-3 py-2 rounded-md">' . $i . '</a>';
    }

    // Tombol Next
    $next_params = http_build_query(array_merge($params, ['p' => $params['p'] + 1]));
    $next_class = ($params['p'] >= $total_pages) ? 'pointer-events-none text-gray-400' : 'hover:bg-gray-200';
    echo '<a href="index.php?' . $next_params . '" class="' . $next_class . ' px-3 py-2 rounded-md">Berikutnya &raquo;</a>';

    echo '</nav>';
}
