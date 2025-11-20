<?php if (!empty($pagination_data)): ?>
    <?php
    $current_page = $pagination_data['current_page'];
    $total_pages = $pagination_data['total_pages'];
    $start_page = isset($pagination_data['start_page']) ? $pagination_data['start_page'] : max(1, $current_page - 2);
    $end_page = isset($pagination_data['end_page']) ? $pagination_data['end_page'] : min($total_pages, $current_page + 2);
    $base_url = $pagination_data['base_url'];
    $query_params = $pagination_data['query_params'] ?? [];

    // Pastikan tidak ada 'page' ganda di query string
    unset($query_params['page']);

    // Bangun query string dasar
    $query_string = http_build_query($query_params);
    $separator = $query_string ? '&' : '?';
    $query_prefix = $query_string ? '?' . $query_string : '';
    ?>

    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">

            <!-- Tombol First -->
            <li class="page-item<?= ($current_page <= 1) ? ' disabled' : ''; ?>">
                <a class="page-link" href="<?= $base_url . $query_prefix . $separator . 'page=1'; ?>">«</a>
            </li>

            <!-- Tombol Prev -->
            <li class="page-item<?= ($current_page <= 1) ? ' disabled' : ''; ?>">
                <a class="page-link" href="<?= $base_url . $query_prefix . $separator . 'page=' . max(1, $current_page - 1); ?>">‹</a>
            </li>

            <!-- Nomor Halaman -->
            <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                <li class="page-item<?= ($i == $current_page) ? ' active' : ''; ?>">
                    <?php if ($i == $current_page): ?>
                        <span class="page-link"><?= $i; ?></span>
                    <?php else: ?>
                        <a class="page-link" href="<?= $base_url . $query_prefix . $separator . 'page=' . $i; ?>"><?= $i; ?></a>
                    <?php endif; ?>
                </li>
            <?php endfor; ?>

            <!-- Tombol Next -->
            <li class="page-item<?= ($current_page >= $total_pages) ? ' disabled' : ''; ?>">
                <a class="page-link" href="<?= $base_url . $query_prefix . $separator . 'page=' . min($total_pages, $current_page + 1); ?>">›</a>
            </li>

            <!-- Tombol Last -->
            <li class="page-item<?= ($current_page >= $total_pages) ? ' disabled' : ''; ?>">
                <a class="page-link" href="<?= $base_url . $query_prefix . $separator . 'page=' . $total_pages; ?>">»</a>
            </li>

        </ul>
    </nav>
<?php endif; ?>
