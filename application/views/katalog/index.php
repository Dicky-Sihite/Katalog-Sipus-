<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">

                <!-- Advanced Search Panel -->
                <div class="search-panel">
                    <div class="panel shadow-sm">
                        <div class="panel-heading">
                            <ul class="nav nav-tabs" id="searchTabs">
                                <li class="nav-item">
                                    <a class="nav-link <?= ($search_type == 'title') ? 'active' : '' ?>" data-bs-toggle="tab" href="#book-search">Buku</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?= ($search_type == 'author') ? 'active' : '' ?>" data-bs-toggle="tab" href="#author-search">Penulis</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?= ($search_type == 'keyword') ? 'active' : '' ?>" data-bs-toggle="tab" href="#keyword-search">Pencarian Lain</a>
                                </li>
                            </ul>
                        </div>

                        <div class="panel-body">
                            <div class="tab-content pt-0">

                                <!-- Book Search -->
                                <div class="tab-pane fade <?= ($search_type == 'title') ? 'show active' : '' ?>" id="book-search">
                                    <form method="get" action="<?php echo base_url('katalog'); ?>">
                                        <div class="input-group input-group-lg">
                                            <input
                                                type="text"
                                                name="search"
                                                class="form-control"
                                                placeholder="Cari berdasarkan judul buku..."
                                                value="<?php echo htmlspecialchars($search ?? ''); ?>">
                                            <input type="hidden" name="search_type" value="title">
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fas fa-search me-2"></i> Cari
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Author Search -->
                                <div class="tab-pane fade <?= ($search_type == 'author') ? 'show active' : '' ?>" id="author-search">
                                    <form method="get" action="<?php echo base_url('katalog'); ?>">
                                        <div class="input-group input-group-lg">
                                            <input
                                                type="text"
                                                name="search"
                                                class="form-control"
                                                placeholder="Nama penulis..."
                                                value="<?php echo htmlspecialchars($search ?? ''); ?>">
                                            <input type="hidden" name="search_type" value="author">
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fas fa-search me-2"></i> Cari
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Keyword Search -->
                                <div class="tab-pane fade <?= ($search_type == 'keyword') ? 'show active' : '' ?>" id="keyword-search">
                                    <form method="get" action="<?php echo base_url('katalog'); ?>">
                                        <div class="input-group input-group-lg">
                                            <input
                                                type="text"
                                                name="search"
                                                class="form-control"
                                                placeholder="Pencarian Lain..."
                                                value="<?php echo htmlspecialchars($search ?? ''); ?>">
                                            <input type="hidden" name="search_type" value="keyword">
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fas fa-search me-2"></i> Cari
                                            </button>
                                        </div>
                                    </form>
                                </div>

                            </div> <!-- /.tab-content -->
                        </div> <!-- /.panel-body -->
                    </div> <!-- /.panel -->
                </div> <!-- /.search-panel -->

            </div>
        </div>
    </div>
</section>

<!-- Content Section -->
<section class="content-section">
    <div class="container">

        <!-- Section Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
            <div class="col-md-6">
                <h2 class="mb-1">Katalog Online</h2>
            </div>
            <div class="col-md-6 text-end">
                <?php 
                    // Hanya tampilkan tombol "Kembali" jika ada parameter pencarian aktif
                    $hasSearch = $this->input->get('search');
                    if (!empty($hasSearch)) : 
                ?>
                <a href="<?php echo base_url('katalog'); ?>" class="btn btn-outline-primary btn-sm shadow-sm">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
                <?php endif; ?>
            </div>
        </div>


        <!-- Books Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">

                <!-- Controls Bar -->
                <div class="controls-bar m-4">
                    <div class="row align-items-center">

                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <label class="me-2 mb-0">Show</label>
                                <select
                                    class="form-select form-select-sm"
                                    style="width: auto;"
                                    id="perPageSelect">
                                    <option value="3" <?php echo ($per_page == 3) ? 'selected' : ''; ?>>3</option>
                                    <option value="9" <?php echo ($per_page == 9) ? 'selected' : ''; ?>>9</option>
                                    <option value="25" <?php echo ($per_page == 25) ? 'selected' : ''; ?>>25</option>
                                </select>
                                <span class="ms-2 page-info">entries</span>
                            </div>
                        </div>


                    </div>
                </div>

                <!-- Books Grid -->
                <div class="px-4 pb-4">
                    <?php if (!empty($books)): ?>
                        <div class="row g-4">
                            <?php foreach ($books as $book): ?>

                                <?php
                                // Tentukan URL gambar
                                if (!empty($book->file_fotobuku)) {
                                    if (strpos($book->file_fotobuku, './assets') === 0) {
                                        $img_url = str_replace('./', 'https://sipus.surabaya.go.id/', $book->file_fotobuku);
                                    } else {
                                        $img_url = $book->file_fotobuku;
                                    }
                                } else {
                                    $img_url = null;
                                }
                                ?>
                                <div class="col-6 col-md-4 mb-3 justify-content-center">
                                    <div class="card card-book h-100 d-flex flex-column">
                                        <div class="book-image">
                                            <?php if (!empty($img_url)): ?>
                                                <img
                                                    src="<?php echo htmlspecialchars($img_url); ?>"
                                                    alt="<?php echo htmlspecialchars($book->judul_utama); ?>"
                                                    loading="lazy"
                                                    class="book-cover-img"
                                                    onerror="this.style.display='none'; this.parentElement.innerHTML += '<i class=\'fas fa-book\'></i>'; ">
                                            <?php else: ?>
                                                <i class="fas fa-book"></i>
                                            <?php endif; ?>

                                            <?php if (($book->status_global ?? '') === 'Tersedia'): ?>
                                                <span class="status-badge badge bg-success position-absolute"><?= htmlspecialchars($book->status_global) ?></span>
                                            <?php else: ?>
                                                <span class="status-badge badge bg-danger position-absolute"><?= htmlspecialchars($book->status_global ?? '-') ?></span>
                                            <?php endif; ?>
                                        </div>


                                        <div class="card-body d-flex flex-column">
                                            <h5 class="book-title mb-2">
                                                <?php echo htmlspecialchars($book->judul_utama); ?>
                                            </h5>

                                            <div class="book-meta">
                                                <i class="fas fa-user"></i>
                                                <?php echo htmlspecialchars($book->nama_pengarang ?? '-'); ?>
                                            </div>
                                                <!-- Tahun Terbit
                                                <div class="book-meta">
                                                    <i class="fa-solid fa-city"></i>
                                                    ?php echo htmlspecialchars($book->tahun_terbit ?? '-'); ?>
                                                </div>
                                                -->
                                            <div class="book-meta">
                                                <i class=" fas fa-building "></i>
                                                <?php echo htmlspecialchars($book->nama_penerbit ?? '-'); ?>
                                            </div>

                                            <!-- Deskripsi singkat (maksimal 3 baris) -->
                                            <div class="deskripsi book-meta mt-3 ">
                                                <i class="fas fa-file-alt"></i>
                                                <?php
                                                if (!empty($book->deskripsi)) {
                                                    echo htmlspecialchars($book->deskripsi);
                                                } else {
                                                    echo "Deskripsi tidak tersedia";
                                                }
                                                ?>
                                            </div>

                                            <div class="mt-auto pt-3 center text-center">
                                                <a
                                                    href="<?php echo base_url('katalog/detail/' . $book->id_buku); ?>"
                                                    class="btn btn-outline-primary btn-sm shadow-sm w-">
                                                    <i class="fas fa-eye me-1"></i> Lihat Detail
                                                </a>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-center text-muted">Tidak ada buku yang ditemukan.</p>
                    <?php endif; ?>
                </div>


                <!-- Pagination -->
                <?php if (!empty($pagination)): ?>
                    <?php echo $pagination; ?>
                <?php endif; ?>
                <div class="col-md-6 px-4 pb-4">
                    <span class="page-info">
                        Menampilkan
                        <?php echo (($current_page - 1) * $per_page) + 1; ?>
                        hingga
                        <?php echo min($current_page * $per_page, $total_rows); ?>
                        dari
                        <?php echo $total_rows; ?> data
                    </span>
                </div>

            </div> <!-- /.px-4 -->
        </div> <!-- /.card-body -->
    </div> <!-- /.card -->
    </div> <!-- /.container -->
</section>

<!-- Script -->
<script>
    // Handle per page change
    document.getElementById('perPageSelect').addEventListener('change', function() {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', this.value);
        url.searchParams.delete('page');
        window.location.href = url.toString();
    });

    // Handle image loading
    document.addEventListener('DOMContentLoaded', function() {
        const images = document.querySelectorAll('.book-cover-img');
        images.forEach(img => {
            img.addEventListener('load', function() {
                this.classList.add('loaded');
            });
            if (img.complete) {
                img.classList.add('loaded');
            }
        });
    });
</script>