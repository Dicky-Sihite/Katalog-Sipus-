<!-- Content -->
<section class="py-5">
    <div class="container">
        <div class="row mb-4 align-items-center d-flex justify-content-between">
            <div class="col-auto">
                <h2 class="mb-1"> Detail Buku </h2>
            </div>
            <div class="col-auto">
                <a href="<?php echo base_url('katalog'); ?>" class="btn btn-outline-primary btn-sm shadow-sm">
                    <i class="fas fa-arrow-left me-2"></i> Kembali Ke Katalog
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Book Image -->
            <div class="image-col-12 col-sm-6 col-lg-4 mb-4 d-flex flex-column align-items-center">
                <div class="book-image-container">
                    <div class="book-image-large">
                        <?php
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

                        <?php if (!empty($img_url)): ?>
                            <img src="<?php echo htmlspecialchars($img_url); ?>"
                                alt="<?php echo htmlspecialchars($book->judul_utama); ?>"
                                onerror="this.style.display='none'; this.parentElement.innerHTML += '<i class=\'fas fa-book\' style=\'font-size: 120px;\'></i>'; ">
                        <?php else: ?>
                            <i class="fas fa-book"></i>
                        <?php endif; ?>

                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="summary-cards mt-4">
                    <div class="row g-3 justify-content-center text-center">
                        <div class="col-6">
                            <div class="summary-card">
                                <div class="summary-number"><?php echo $total_copies; ?></div>
                                <div class="summary-label">Total Eksemplar</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="summary-card">
                                <div class="summary-number"><?php echo $available_copies; ?></div>
                                <div class="summary-label">Tersedia</div>
                            </div>
                        </div>
                        <div class="col-6">
                        <div class="summary-card">
                                <div class="summary-number"><?php echo $borrowed_copies; ?></div>
                                <div class="summary-label">Sedang Dipinjam</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Book Details -->
            <div class="col-lg-8">
                <div class="detail-card">
                    <h1 class="book-title-main"><?php echo strtoupper(htmlspecialchars($book->judul_utama)); ?></h1>

                    <!-- Author Info -->
                    <div class="mb-3">
                        <i class="fas fa-user text-primary me-2"></i>
                        <span class="text-muted"><?php echo htmlspecialchars($book->nama_pengarang ?? '-'); ?></span>
                    </div>

                    <!-- Tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="detail-tab" data-bs-toggle="tab"
                                data-bs-target="#detail" type="button" role="tab">
                                <i class="fas fa-info-circle me-2"></i> Detail
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="deskripsi-tab" data-bs-toggle="tab"
                                data-bs-target="#deskripsi" type="button" role="tab">
                                <i class="fas fa-file-alt me-2"></i> Deskripsi
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content">
                        <!-- Detail Tab -->
                        <div class="tab-pane fade show active" id="detail" role="tabpanel">
                            <div class="detail-row">
                                <div class="detail-label">Judul</div>
                                <div class="detail-value">: <?php echo htmlspecialchars($book->judul_utama); ?></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Pengarang</div>
                                <div class="detail-value">: <?php echo htmlspecialchars($book->nama_pengarang ?? '-'); ?></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Dewey Decimal Classification</div>
                                <div class="detail-value">: <?php echo htmlspecialchars($book->klas_ddc ?? '-'); ?></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Rak Buku</div>
                                <div class="detail-value">: <?php echo htmlspecialchars($book->rak_buku ?? '-'); ?></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Penerbit</div>
                                <div class="detail-value">: <?php echo htmlspecialchars($book->nama_penerbit ?? '-'); ?></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Tahun Terbit</div>
                                <div class="detail-value">: <?php echo htmlspecialchars($book->tahun_terbit ?? '-'); ?></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Tempat Terbit</div>
                                <div class="detail-value">: <?php echo htmlspecialchars($book->kota_terbit ?? '-'); ?></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Deskripsi Fisik</div>
                                <div class="detail-value">: <?php echo htmlspecialchars($book->jumlah_halaman ?? '-'); ?> ; <?= htmlspecialchars($book->ukuran ?? '-') ?></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Pernyataan Cetakan</div>
                                <div class="detail-value">: <?php echo htmlspecialchars($book->cetakan ?? '-'); ?></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">ISBN</div>
                                <div class="detail-value">: <?php echo htmlspecialchars($book->isbn ?? '-'); ?></div>
                            </div>
                        </div>

                        <!-- Deskripsi Tab -->
                        <div class="tab-pane fade" id="deskripsi" role="tabpanel">
                            <div class="detail-row">
                                <div class="detail-label">Judul</div>
                                <div class="detail-value">: <?php echo htmlspecialchars($book->judul_utama); ?></div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Deskripsi</div>
                                <div class="detail-value">: <?php echo !empty($book->deskripsi) ? htmlspecialchars($book->deskripsi) : htmlspecialchars($book->judul_utama); ?></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Barcode / Lokasi Buku Section -->
        <div class="row mt-4" id="barcode">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Barcode / Lokasi Buku</h5>

                        <!-- Filters -->
                        <form id="per-page-form" method="get" action="<?= current_url() ?>" class="d-flex align-items-center">
                            <?php if (!empty($barcodeSearch)): ?>
                                <input type="hidden" name="barcode_search" value="<?= htmlspecialchars($barcodeSearch) ?>">
                            <?php endif; ?>
                            <label for="show-entries" class="me-2 mb-0">Show</label>
                            <select id="show-entries" name="per_page" class="form-select form-select-sm" style="width: auto;" onchange="resetPageAndSubmit()">
                                <option value="3" <?= ($perPage == 3) ? 'selected' : '' ?>>3</option>
                                <option value="9" <?= ($perPage == 9) ? 'selected' : '' ?>>9</option>
                                <option value="25" <?= ($perPage == 25) ? 'selected' : '' ?>>25</option>
                            </select>
                            <span class="ms-2">entries</span>
                        </form>
                    </div>

                    <!-- Form Pencarian Barcode/Lokasi -->
                    <div class="card-body pb-0">
                        <form method="get" action="<?= current_url() ?>#barcode" class="d-flex gap-2 flex-nowrap">
                            <input type="hidden" name="per_page" value="<?= htmlspecialchars($perPage) ?>">

                            <!-- Input fleksibel -->
                            <input type="text"
                                name="barcode_search"
                                class="form-control flex-grow-1"
                                placeholder="Masukkan barcode atau lokasi buku..."
                                value="<?= htmlspecialchars($barcodeSearch ?? '') ?>">

                            <!-- Tombol -->
                            <button class="btn btn-primary flex-shrink-0" type="submit">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </form>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Barcode</th>
                                        <th>Data Bibliografis</th>
                                        <th>Lokasi Buku</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($barcodeData)): ?>
                                        <?php foreach ($barcodeData as $barcode): ?>
                                            <tr>
                                                <!-- Nomor urut -->
                                                <td><?= htmlspecialchars($barcode->no ?? '') ?></td>

                                                <!-- No Barcode -->
                                                <td><?= htmlspecialchars($barcode->id_barcode ?? '-') ?></td>

                                                <!-- Info buku: coba ambil dari relasi $barcode->book jika ada, kalau tidak pakai $book -->
                                                <?php
                                                    // pilih sumber data buku: prioritas $barcode->book, lalu $book (global), lalu null
                                                    $b = $barcode->book ?? ($book ?? null);
                                                ?>
                                                <td>
                                                    <strong>
                                                        <?= htmlspecialchars($b->judul_utama ?? '-') ?> /
                                                        <?= htmlspecialchars($b->nama_pengarang ?? '-') ?>
                                                    </strong><br>

                                                    <div class="text-muted mt-1">
                                                        <div class="detail-row-barcode">
                                                            <div class="detail-label-barcode">Cetakan</div>
                                                            <div class="detail-value">: <?= htmlspecialchars($b->cetakan ?? '-') ?></div>
                                                        </div>
                                                        <div class="detail-row-barcode">
                                                            <div class="detail-label-barcode">Kota Terbit</div>
                                                            <div class="detail-value">: <?= htmlspecialchars($b->kota_terbit ?? '-') ?></div>
                                                        </div>
                                                        <div class="detail-row-barcode">
                                                            <div class="detail-label-barcode">Penerbit</div>
                                                            <div class="detail-value">: <?= htmlspecialchars($b->nama_penerbit ?? '-') ?>, <?= htmlspecialchars($b->tahun_terbit ?? '-') ?></div>
                                                        </div>
                                                        <div class="detail-row-barcode">
                                                            <div class="detail-label-barcode">Halaman</div>
                                                            <div class="detail-value">: <?= htmlspecialchars($b->jumlah_halaman ?? '-') ?> ; <?= htmlspecialchars($b->ukuran ?? '-') ?></div>
                                                        </div>
                                                        <div class="detail-row-barcode">
                                                            <div class="detail-label-barcode">Bentuk Fisik</div>
                                                            <div class="detail-value">: <?= htmlspecialchars($b->bentuk_fisik ?? '-') ?></div>
                                                        </div>
                                                        <div class="detail-row-barcode">
                                                            <div class="detail-label-barcode">Kondisi Buku</div>
                                                            <div class="detail-value">: <?= htmlspecialchars($b->kondisi_buku ?? '-') ?></div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <!-- Lokasi -->
                                                <td>
                                                    <div class="detail-label-barcode"><?= htmlspecialchars($b->nama_lokasi ?? '-') ?></div>
                                                    <div class="detail-row-barcode">
                                                        <div class="detail-label-barcode">Ruangan</div>
                                                        <div class="detail-value">: <?= htmlspecialchars($b->nama_ruang ?? '-') ?></div>
                                                    </div>
                                                    <div class="detail-row-barcode">
                                                        <div class="detail-label-barcode">Rak</div>
                                                        <div class="detail-value">: <?= htmlspecialchars($b->rak_buku ?? '-') ?></div>
                                                    </div>
                                                </td>

                                                <!-- Status -->
                                                <td>
                                                    <?php if (($barcode->status ?? '') === 'Tersedia'): ?>
                                                        <span class="badge bg-success"><?= htmlspecialchars($barcode->status) ?></span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger"><?= htmlspecialchars($barcode->status ?? '-') ?></span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada data barcode</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination -->
                        <?php if (!empty($pagination)): ?>
                            <div class="mt-3">
                                <?php echo $pagination; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function resetPageAndSubmit() {
        // Reset page to 1 when changing per_page
        var form = document.getElementById('per-page-form');
        var pageInput = document.createElement('input');
        pageInput.type = 'hidden';
        pageInput.name = 'page';
        pageInput.value = '1';
        form.appendChild(pageInput);
        form.submit();
    }
</script>

