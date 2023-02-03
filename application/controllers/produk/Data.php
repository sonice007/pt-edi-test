<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Data extends Render_Controller
{
    function __construct()
    {
        parent::__construct();
        // Cek session
        $this->sesion->cek_session();
        $this->default_template = 'templates/dashboard';
        $this->load->model('produk/dataModel', 'model');
        $this->foto_folder = './files/produk/data/';
        $this->load->library('plugin');
        $this->load->helper('url');;
    }

    public function index()
    {
        $this->title = "Data Produk";
        $this->content = 'produk/data';
        $this->navigation = ['Produk','Data Produk'];
        $this->plugins = ['datatables', 'summernote'];

        // Breadcrumb setting
        $this->breadcrumb_1 = 'Dashboard';
        $this->breadcrumb_1_url = base_url();
        $this->breadcrumb_2 = 'Produk';
        $this->breadcrumb_2_url = "#";
        $this->breadcrumb_3 = 'Data';
        $this->breadcrumb_3_url = base_url() . 'Data';

        // Send data to view
        $this->render();
    }

    public function ajax_data()
    {
        $order = ['order' => $this->input->post('order'), 'columns' => $this->input->post('columns')];
        $start = $this->input->post('start');
        $draw = $this->input->post('draw');
        $draw = $draw == null ? 1 : $draw;
        $length = $this->input->post('length');
        $cari = $this->input->post('search');

        if (isset($cari['value'])) {
            $_cari = $cari['value'];
        } else {
            $_cari = null;
        }

        $date_start = $this->input->post('date_start');
        $date_end = $this->input->post('date_end');

        $filter = [
            'date' => [
                'start' => $date_start,
                'end' => $date_end,
            ]
        ];

        $data = $this->model->getAllData($draw, $length, $start, $_cari, $order, $filter)->result_array();
        $count = $this->model->getAllData(null, null, null, $_cari, $order, $filter)->num_rows();

        $this->output_json(['recordsTotal' => $count, 'recordsFiltered' => $count, 'draw' => $draw, 'search' => $_cari, 'data' => $data]);
    }

    public function list_title()
    {
        $key = $this->input->post('key');
        $id = $this->input->post('id');
        $result = null;
        if ($key != '') {
            $result = $this->model->list_title($key, $id);
        }
        $this->output_json($result ?? []);
    }

    public function list_toko()
    {
        $key = $this->input->post('key');
        $id = $this->input->post('id');
        $result = null;
        if ($key != '') {
            $result = $this->model->list_toko($key, $id);
        }
        $this->output_json($result ?? []);
    }

    public function ajax_data_gambar()
    {
        $order = ['order' => $this->input->post('order'), 'columns' => $this->input->post('columns')];
        $start = $this->input->post('start');
        $draw = $this->input->post('draw');
        $draw = $draw == null ? 1 : $draw;
        $length = $this->input->post('length');
        $cari = $this->input->post('search');

        if (isset($cari['value'])) {
            $_cari = $cari['value'];
        } else {
            $_cari = null;
        }

        $date_start = $this->input->post('date_start');
        $date_end = $this->input->post('date_end');

        $filter = [
            'id_produk' => $this->input->post('id_produk')
        ];

        $data = $this->model->getProdukGambar($draw, $length, $start, $_cari, $order, $filter)->result_array();
        $count = $this->model->getProdukGambar(null, null, null, $_cari, $order, $filter)->num_rows();

        $this->output_json(['recordsTotal' => $count, 'recordsFiltered' => $count, 'draw' => $draw, 'search' => $_cari, 'data' => $data]);
    }

    public function ajax_data_varian()
    {
        $order = ['order' => $this->input->post('order'), 'columns' => $this->input->post('columns')];
        $start = $this->input->post('start');
        $draw = $this->input->post('draw');
        $draw = $draw == null ? 1 : $draw;
        $length = $this->input->post('length');
        $cari = $this->input->post('search');

        if (isset($cari['value'])) {
            $_cari = $cari['value'];
        } else {
            $_cari = null;
        }

        $date_start = $this->input->post('date_start');
        $date_end = $this->input->post('date_end');

        $filter = [
            'id_produk' => $this->input->post('id_produk')
        ];

        $data = $this->model->getProdukVarian($draw, $length, $start, $_cari, $order, $filter)->result_array();
        $count = $this->model->getProdukVarian(null, null, null, $_cari, $order, $filter)->num_rows();

        $this->output_json(['recordsTotal' => $count, 'recordsFiltered' => $count, 'draw' => $draw, 'search' => $_cari, 'data' => $data]);
    }

    public function getAllProdukVarianUkuran()
    {
        $id_produk_varian = $this->input->post("ukuran_id_produk_varian") ?? $this->input->get("ukuran_id_produk_varian");
        $result = $this->model->getAllProdukVarianUkuran($id_produk_varian);
        $this->output_json(["data" => $result]);
    }
    
}
