<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model untuk mengelola semua operasi database yang berhubungan dengan buku perpustakaan
 */
class Buku_model extends CI_Model
{
    /**
     * Menginisialisasi koneksi database saat model dibuat
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Menghitung total jumlah buku unik berdasarkan kriteria pencarian yang diberikan
     */
    public function count_all_books($search = '', $search_type = 'title')
    {
        if (!empty($search)) {
            // Use a simpler approach: get all matching records first, then count distinct titles
            $this->db->select('b.judul_utama');
            $this->db->from('buku b');

            switch ($search_type) {
                case 'title':
                    $this->db->like('LOWER(b.judul_utama)', strtolower($search));
                    break;
                case 'author':
                    $this->db->like('LOWER(b.nama_pengarang)', strtolower($search));
                    break;
                case 'keyword':
                    $this->db->group_start();
                    $this->db->like('LOWER(b.judul_utama)', strtolower($search));
                    $this->db->or_like('LOWER(b.nama_pengarang)', strtolower($search));
                    $this->db->or_like('LOWER(b.deskripsi)', strtolower($search));
                    $this->db->or_like('LOWER(b.nama_penerbit)', strtolower($search));
                    $this->db->group_end();
                    break;
                default:
                    $this->db->like('LOWER(b.judul_utama)', strtolower($search));
                    break;
            }

            $query = $this->db->get();
            $results = $query->result();

            // Count unique titles
            $unique_titles = array();
            foreach ($results as $row) {
                $unique_titles[$row->judul_utama] = true;
            }

            return count($unique_titles);
        } else {
            // If no search, count all unique books
            $this->db->select('COUNT(DISTINCT judul_utama) as total');
            $this->db->from('buku');
            $query = $this->db->get();
            $result = $query->row();
            return $result->total;
        }
    }

    /**
     * Mengambil daftar buku dengan paginasi dan fitur pencarian berdasarkan judul, penulis, atau kata kunci
     */
    public function get_books($limit, $offset, $search = '', $search_type = 'title')
    {
        if (!empty($search)) {
            // Get all matching records first
            $this->db->select('judul_utama, nama_pengarang, deskripsi, tahun_terbit, file_fotobuku, id_buku, nama_penerbit');
            $this->db->from('buku');

            switch ($search_type) {
                case 'title':
                    $this->db->like('LOWER(judul_utama)', strtolower($search));
                    break;
                case 'author':
                    $this->db->like('LOWER(nama_pengarang)', strtolower($search));
                    break;
                case 'keyword':
                    $this->db->group_start();
                    $this->db->like('LOWER(judul_utama)', strtolower($search));
                    $this->db->or_like('LOWER(nama_pengarang)', strtolower($search));
                    $this->db->or_like('LOWER(deskripsi)', strtolower($search));
                    $this->db->or_like('LOWER(nama_penerbit)', strtolower($search));
                    $this->db->group_end();
                    break;
                default:
                    $this->db->like('LOWER(judul_utama)', strtolower($search));
                    break;
            }

            $this->db->order_by('id_buku', 'ASC');
            $query = $this->db->get();
            $all_results = $query->result();

            // Get unique books by title, keeping the first occurrence
            $unique_books = array();
            foreach ($all_results as $book) {
                if (!isset($unique_books[$book->judul_utama])) {
                    $unique_books[$book->judul_utama] = $book;
                }
            }

            // Convert back to array and apply pagination
            $unique_books_array = array_values($unique_books);
            return array_slice($unique_books_array, $offset, $limit);
        } else {
            // If no search, just get unique books (one representative row per title)
            $this->db->select('b1.judul_utama, b1.nama_pengarang, b1.deskripsi, b1.nama_penerbit, b1.file_fotobuku, b1.id_buku');
            $this->db->from('buku b1');
            $this->db->where('b1.id_buku = (SELECT MIN(b2.id_buku) FROM buku b2 WHERE b2.judul_utama = b1.judul_utama)');
            $this->db->order_by('b1.id_buku', 'ASC');
            $this->db->limit($limit, $offset);
            $query = $this->db->get();
            return $query->result();
        }
    }

    /**
     * Mengambil informasi detail buku beserta lokasi dan status peminjaman berdasarkan ID buku
     */
    public function get_book_by_id($id_buku)
    {
    $this->db->select('
        buku.*,
        detail_buku.id_detailbuku,
        detail_buku.nama_ruang,
        detail_buku.rak_buku,
        detail_buku.bentuk_fisik,
        detail_buku.kondisi_buku,
        lokasi.nama_lokasi
    ');
        $this->db->from('buku');
        $this->db->join('detail_buku', 'detail_buku.id_buku = buku.id_buku', 'left');
        $this->db->join('detail_peminjaman', 'detail_peminjaman.id_detailbuku = detail_buku.id_detailbuku', 'left');
        $this->db->join('lokasi', 'lokasi.id_lokasi = detail_peminjaman.id_lokasipeminjaman', 'left');
        $this->db->where('buku.id_buku', $id_buku);
        $this->db->order_by('detail_peminjaman.id_detailpeminjaman', 'DESC'); // fallback kalau tidak ada tanggal
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row();
    }


    /**
     * Mencari detail buku berdasarkan nomor barcode yang terdaftar di sistem
     */
    public function get_book_by_barcode($barcode)
    {
        $this->db->select('b.*, db.id_detailbuku, db.id_barcode, db.nama_ruang, db.rak_buku as lokasi_rak, db.kondisi_buku,');
        $this->db->from('detail_buku db');
        $this->db->join('buku b', 'db.id_buku = b.id_buku');
        $this->db->where('db.id_barcode', $barcode);
        $query = $this->db->get();
        return $query->row();
    }

    /**
     * Mengambil semua eksemplar buku yang memiliki judul yang sama beserta informasi lokasinya
     */
    public function get_copies_by_title($judul_utama)
    {
        $this->db->select('db.*, b.judul_utama, b.nama_pengarang, b.nama_penerbit, b.tahun_terbit');
        $this->db->from('detail_buku db');
        $this->db->join('buku b', 'db.id_buku = b.id_buku');
        $this->db->where('b.judul_utama', $judul_utama);
        $this->db->order_by('db.id_detailbuku', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Count copies by title (counts detail_buku entries for a given title)
     */
    public function count_copies_by_title($judul_utama)
    {
        $this->db->from('detail_buku db');
        $this->db->join('buku b', 'db.id_buku = b.id_buku');
        $this->db->where('b.judul_utama', $judul_utama);
        return (int) $this->db->count_all_results();
    }

    /**
     * Get total available books by title
     */
    /**
     * Memeriksa status ketersediaan eksemplar buku spesifik (tersedia atau sedang dipinjam)
     */
    public function get_book_status_by_detail($id_detailbuku)
{
    $this->db->select('
        a.id_peminjaman, 
        b.id_detailpeminjaman, 
        b.status_bukukembali, 
        c.id_detailbuku
    ');
    $this->db->from('peminjaman a');
    $this->db->join('detail_peminjaman b', 'a.id_peminjaman = b.id_peminjaman');
    $this->db->join('detail_buku c', 'c.id_detailbuku = b.id_detailbuku');
    $this->db->where('c.id_detailbuku', $id_detailbuku);
    $this->db->order_by('b.id_detailpeminjaman', 'DESC');
    $this->db->limit(1);

    $query = $this->db->get();
    $row = $query->row();

       if (!$row) {
        return 'Tersedia';
    }

    
    if ($row->status_bukukembali == 'f' || $row->status_bukukembali === false) {
        return 'Sedang dipinjam';
    } else {
        return 'Tersedia';
    }
}


    /**
     * Menghitung jumlah eksemplar buku yang sedang dipinjam untuk judul tertentu
     */
    public function count_borrowed_copies_by_title($judul)
{
    // Ambil semua eksemplar dari judul buku tersebut
    $this->db->select('db.id_detailbuku');
    $this->db->from('detail_buku db');
    $this->db->join('buku b', 'b.id_buku = db.id_buku');
    $this->db->where('b.judul_utama', $judul);

    $copies = $this->db->get()->result();

    $borrowed_count = 0;

    // Loop setiap eksemplar dan cek status via get_book_status_by_detail
    foreach ($copies as $copy) {
        $status = $this->get_book_status_by_detail($copy->id_detailbuku);

        if ($status === 'Sedang dipinjam') {
            $borrowed_count++;
        }
    }

    return $borrowed_count;
}

    /**
     * Memeriksa status ketersediaan keseluruhan buku dengan melihat semua eksemplarnya
     */
    public function get_book_overall_status($id_buku)
{
    // Ambil semua detail_buku yang terkait dengan buku ini
    $this->db->select('id_detailbuku');
    $this->db->from('detail_buku');
    $this->db->where('id_buku', $id_buku);
    $query = $this->db->get();
    $details = $query->result();

    // Jika tidak ada eksemplar sama sekali
    if (empty($details)) {
        return 'Tidak tersedia';
    }

    // Cek setiap eksemplar
    $tersedia = false;

    foreach ($details as $detail) {
        $status = $this->get_book_status_by_detail($detail->id_detailbuku);
        if ($status === 'Tersedia') {
            $tersedia = true;
            break; // kalau ada satu saja yang tersedia, kita cukup
        }
    }

    // Kalau semua dipinjam → Tidak tersedia, kalau ada satu tersedia → Tersedia
    return $tersedia ? 'Tersedia' : 'Tidak tersedia';
}

    /**
     * Mengambil data detail barcode buku dengan fitur pencarian dan paginasi untuk pengelolaan koleksi
     */
    public function get_barcode_data($judul_utama, $limit, $offset, $search = '')
{
    $this->db->select('db.id_detailbuku, db.id_buku, db.id_barcode, db.nama_ruang, db.rak_buku, b.nama_penerbit, b.tahun_terbit');
    $this->db->from('detail_buku db');
    $this->db->join('buku b', 'db.id_buku = b.id_buku');
    $this->db->where('b.judul_utama', $judul_utama);

    if (!empty($search)) {
        $this->db->group_start();
        $this->db->like('LOWER(db.id_barcode)', strtolower($search));
        $this->db->or_like('LOWER(db.nama_ruang)', strtolower($search));
        $this->db->or_like('LOWER(db.rak_buku)', strtolower($search));
        $this->db->group_end();
    }

    $this->db->order_by('db.id_barcode', 'ASC');
    $this->db->limit($limit, $offset);
    $query = $this->db->get();

    $results = $query->result(); // <- tetap object

    // Tambahkan nomor urut dan status langsung di object
    $no = $offset + 1;
    foreach ($results as $row) {
        $row->no = $no;
        $row->status = $this->get_book_status_by_detail($row->id_detailbuku);
        $no++;
    }

    return $results; // kembalikan array of object
}


    
    /**
     * Count barcode data with search (joined)
     */
    public function count_barcode_data($judul_utama, $search = '')
    {
        $this->db->from('detail_buku db');
        $this->db->join('buku b', 'db.id_buku = b.id_buku');
        $this->db->where('b.judul_utama', $judul_utama);

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('LOWER(db.id_barcode)', strtolower($search));
            $this->db->or_like('LOWER(db.nama_ruang)', strtolower($search));
            $this->db->or_like('LOWER(db.rak_buku)', strtolower($search));
            $this->db->group_end();
        }

        return (int) $this->db->count_all_results();
    }

    /**
     * Mencari buku-buku terkait berdasarkan kesamaan penulis dengan pengacakan hasil
     */
    public function get_related_books($current_id_buku, $author, $limit = 3)
    {
        $this->db->select('id_buku, judul_utama, nama_pengarang, file_fotobuku');
        $this->db->from('buku');
        $this->db->where('id_buku !=', $current_id_buku);

        if (!empty($author)) {
            $this->db->like('LOWER(nama_pengarang)', strtolower($author));
        }

        // Use random ordering where supported
        $this->db->order_by('RANDOM()');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result();
    }
}
