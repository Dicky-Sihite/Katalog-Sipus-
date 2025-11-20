<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title><?= isset($title) ? $title : 'Sistem Informasi Perpustakaan'; ?></title>

    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/catalog.css'); ?>">
</head>
<body>
    <!-- Navbar Global -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="<?= base_url(); ?>">
                <img src="https://sipus.surabaya.go.id/assets/img/logosby.png" alt="Logo Surabaya" width="35" height="40" class="me-2">
                <span class="fw-semibold text-primary">Sistem Informasi Perpustakaan</span>
            </a>
        </div>
    </nav>
    

    <!-- Konten Halaman -->
    <main class="py-4">
        <?php 
            // load view konten dinamis
            if (isset($content)) {
                $this->load->view($content);
            }
        ?>
    </main>

    
    <!-- JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
