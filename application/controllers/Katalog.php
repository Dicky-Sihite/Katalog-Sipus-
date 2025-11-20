<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Controller Katalog
 */
class Katalog extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Buku_model');
        $this->load->library('pagination');
        $this->load->helper('url');
    }

    /**
     * Halaman Katalog
     */
    public function index()
    {
        // Get search query and type
        $search = $this->input->get('search');
        $search_type = $this->input->get('search_type') ?: 'title'; // Default to title search

        // Get and validate per-page value
        $per_page = $this->get_per_page();

        // Pagination configuration
        $config = $this->build_pagination_config($per_page, $this->Buku_model->count_all_books($search, $search_type));

        $this->pagination->initialize($config);

        // Get page
        $page = $this->input->get('page') ? ($this->input->get('page') - 1) * $per_page : 0;

        // Get books
        $data['books'] = $this->Buku_model->get_books($per_page, $page, $search, $search_type);

        foreach ($data['books'] as $book) {
            $book->status_global = $this->Buku_model->get_book_overall_status($book->id_buku);
        }




        // Prepare pagination data
        $pagination_data = $this->build_pagination_data($config['total_rows'], $config['per_page']);
        $data['pagination'] = !empty($pagination_data)
            ? $this->load->view('katalog/pagination', array('pagination_data' => $pagination_data), TRUE)
            : '';
        $data['total_rows'] = $config['total_rows'];
        $data['per_page'] = $per_page;
        $data['search'] = $search;
        $data['search_type'] = $search_type;
        $data['current_page'] = $pagination_data ? $pagination_data['current_page'] : 1;

        // Use base layout
        $data['title'] = 'Katalog Online - Sistem Informasi Perpustakaan';
        $data['content'] = 'katalog/index';
        $this->load->view('layouts/base', $data);
    }

    /**
     * Get validated per-page value (defaults to 9, allows 3/9/25)
     */
    private function get_per_page()
    {
        $per_page_raw = $this->input->get('per_page');
        $per_page = $per_page_raw ? (int)$per_page_raw : 9;
        if (!in_array($per_page, [3, 9, 25])) {
            return 9;
        }
        return $per_page;
    }

    /**
     * Build standard pagination config with consistent styling
     */
    private function build_pagination_config($per_page, $total_rows)
    {
        $config = array();
        $config['base_url'] = base_url('katalog');
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $per_page;
        $config['uri_segment'] = 2;
        $config['use_page_numbers'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';

        // Styling
        $config['full_tag_open'] = '<nav><ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['first_link'] = '«';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = '»';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '›';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '‹';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close'] = '</span></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link');
        $config['display_pages'] = TRUE; // always display number links
        $config['reuse_query_string'] = TRUE; // keep filters/search
        return $config;
    }

    /**
     * Prepare pagination data for the view (with precomputed page range)
     */
    private function build_pagination_data($total_rows, $per_page)
    {
        $current_page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
        $total_pages = (int) ceil(max(0, $total_rows) / max(1, $per_page));
        if ($total_pages <= 1) {
            return array();
        }

        $start_page = max(1, $current_page - 2);
        $end_page = min($total_pages, $current_page + 2);

        $query_params = $_GET;
        unset($query_params['page']);

        return array(
            'current_page' => $current_page,
            'total_pages' => $total_pages,
            'start_page' => $start_page,
            'end_page' => $end_page,
            'base_url' => base_url('katalog'),
            'query_params' => $query_params
        );
    }

    /**
     * Prepare pagination data for detail page (always show if more data than per_page)
     */
    private function build_detail_pagination_data($total_rows, $per_page, $base_url)
    {
        $current_page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
        $total_pages = (int) ceil(max(0, $total_rows) / max(1, $per_page));

        // For detail page, only show pagination if there are more entries than per_page
        if ($total_rows <= $per_page) {
            return array();
        }

        $start_page = max(1, $current_page - 2);
        $end_page = min($total_pages, $current_page + 2);

        $query_params = $_GET;
        unset($query_params['page']);

        return array(
            'current_page' => $current_page,
            'total_pages' => $total_pages,
            'start_page' => $start_page,
            'end_page' => $end_page,
            'base_url' => $base_url,
            'query_params' => $query_params
        );
    }

    /**
     * Halaman Detail Buku
     */
    public function detail($id_buku)
    {
        $data['book'] = $this->Buku_model->get_book_by_id($id_buku);

        if (!$data['book']) {
            show_404();
        }


        // Get barcode search and pagination parameters
        $barcodeSearch = $this->input->get('barcode_search');
        $perPage = $this->get_per_page();
        $page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
        $offset = ($page - 1) * $perPage;

        // Get barcode data with search and pagination
        $data['barcodeData'] = $this->Buku_model->get_barcode_data($data['book']->judul_utama, $perPage, $offset, $barcodeSearch);
        foreach ($data['barcodeData'] as &$barcode) {
            $barcode->status = $this->Buku_model->get_book_status_by_detail($barcode->id_detailbuku);
        }
        unset($barcode);
        $data['totalBarcodes'] = $this->Buku_model->count_barcode_data($data['book']->judul_utama, $barcodeSearch);
        $data['barcodeSearch'] = $barcodeSearch;
        $data['perPage'] = $perPage;
        $data['page'] = $page;

        // Get copies (books with same title) for summary
        $data['copies'] = $this->Buku_model->get_copies_by_title($data['book']->judul_utama);
        $data['total_copies'] = count($data['copies']);
        $data['borrowed_copies'] = $this->Buku_model->count_borrowed_copies_by_title($data['book']->judul_utama);
        $data['available_copies'] = $data['total_copies'] - $data['borrowed_copies'];


        // Get related books (different titles by same author or similar)
        $data['relatedBooks'] = $this->Buku_model->get_related_books($data['book']->id_buku, $data['book']->nama_pengarang, 3);

        // Setup pagination for barcode data
        $pagination_data = $this->build_detail_pagination_data($data['totalBarcodes'], $perPage, base_url('katalog/detail/' . $id_buku));
        $data['pagination'] = !empty($pagination_data)
            ? $this->load->view('katalog/pagination', array('pagination_data' => $pagination_data), TRUE)
            : '';

        // Use base layout
        $data['title'] = $data['book']->judul_utama . ' - Sistem Informasi Perpustakaan';
        $data['content'] = 'katalog/detail';
        $this->load->view('layouts/base', $data);
    }
}
