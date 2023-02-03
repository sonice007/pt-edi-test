<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tambah extends Render_Controller
{
    function __construct()
    {
        parent::__construct();
        // Cek session
        $this->sesion->cek_session();
        $this->default_template = 'templates/dashboard';
        $this->load->model('produk/dataModel', 'model');
        $this->load->model('master/kategoriModel', 'category');
        $this->load->model('master/tagsModel', 'tag');
        $this->foto_folder = './files/produk/data/';
        $this->load->library('plugin');
        $this->load->helper('url');;
    }

    public function index()
    {
        $this->title = "Data";
        $this->content = 'produk/tambah';
        $this->navigation = ['Produk','Data Produk'];
        $this->plugins = ['datatables', 'summernote', 'select2'];

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

    public function insert()
    {
        $id_toko = $this->input->post("id_toko");
        $status = $this->input->post("status");
        $judul = $this->input->post("judul");
        $slug = $this->input->post("slug");
        $spesifikasi = $this->input->post("spesifikasi");
        $deskripsi = $this->input->post("deskripsi");
        $this->db->trans_start();
        $insert_id = $this->model->insert($id_toko, $judul, $slug, $spesifikasi, $deskripsi, $status);
        // add tags
        $tags = $this->input->post('tags');
        foreach ($tags ?? [] as $value) {
            if (is_numeric($value)) {
                $this->model->insertTag($insert_id, $value);
            } else {
                // insert new tag
                $name = $value;
                $description = "Buat baru melalui fitur tambah produk";
                $status = 1;
                $tag_id = $this->tag->insert($name, $description, $status);
                $this->model->insertTag($insert_id, $tag_id);
            }
        }
        // add categories
        $categories = $this->input->post('categories');
        foreach ($categories ?? [] as $value) {
            // remove all categories with the article
            // $this->model->removeAllCategory($id);
            if (is_numeric($value)) {
                $this->model->insertCategory($insert_id, $value);
            } else {
                // insert new category
                $name = $value;
                $description = "Buat baru melalui fitur tambah produk";
                $status = 1;
                $category_id = $this->category->insert($name, $description, $status);
                $this->model->insertCategory($insert_id, $category_id);
            }
        }
        $this->db->trans_complete();
        $result = $insert_id;

        $this->output_json(["data" => $result]);
    }
}
