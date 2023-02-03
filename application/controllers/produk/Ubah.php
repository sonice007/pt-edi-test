<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ubah extends Render_Controller
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
        $id = $this->input->get('id');
        $get_produk = $this->db->select('a.*, b.nama as toko')->join('toko b', 'b.id = a.id_toko')->get_where('produk a', ['a.id' => $id, 'a.status <> ' => 99])->row_array();
        if($get_produk == null){
            redirect('produk/data', 'refresh');
        }
        $this->title = "Data Produk";
        $this->content = 'produk/ubah';
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
        $this->data['produk'] = $get_produk;

        $tags = $this->db->select('b.id, b.nama as text')
            ->from('produk_tag a')
            ->join('tags b', 'a.id_tag = b.id')
            ->where('a.id_produk', $id)
            ->get()->result_array();

        $categories = $this->db->select('b.id, b.nama as text')
            ->from('produk_kategori a')
            ->join('kategori b', 'a.id_kategori = b.id')
            ->where('a.id_produk', $id)
            ->get()->result_array();

        $this->data['categories'] = $categories;
        $this->data['tags'] = $tags;
        $this->render();
    }


    public function update()
    {
        $id = $this->input->post("id");
        $id_toko = $this->input->post("id_toko");
        $status = $this->input->post("status");
        $judul = $this->input->post("judul");
        $slug = $this->input->post("slug");
        $spesifikasi = $this->input->post("spesifikasi");
        $deskripsi = $this->input->post("deskripsi");

        $this->db->trans_start();
        $result = $this->model->update($id, $id_toko, $judul, $slug, $spesifikasi, $deskripsi, $status);
        // add tags
        $tags = $this->input->post('tags');
        // remove all tags with the article
        $this->model->removeAllTag($id);
        foreach ($tags ?? [] as $value) {
            if (is_numeric($value)) {
                $this->model->insertTag($id, $value);
            } else {
                // insert new tag
                $name = $value;
                $description = "Buat baru melalui fitur tambah produk";
                $status = 1;
                $tag_id = $this->tag->insert($name, $description, $status);
                $this->model->insertTag($id, $tag_id);
            }
        }
        // add categories
        $categories = $this->input->post('categories');
        // remove all categories with the article
        $this->model->removeAllCategory($id);
        foreach ($categories ?? [] as $value) {
            if (is_numeric($value)) {
                $this->model->insertCategory($id, $value);
            } else {
                // insert new category
                $name = $value;
                $description = "Buat baru melalui fitur tambah produk";
                $status = 1;
                $category_id = $this->category->insert($name, $description, $status);
                $this->model->insertCategory($id, $category_id);
            }
        }

        $this->db->trans_complete();
        $this->output_json(["data" => $result]);
    }

    // produk gambar
    public function insert_gambar()
    {
        $id_produk = $this->input->post("id_produk");
        $ph = $this->uploadFile("gambar");
        $gambar = $ph['data'];
        $cover = $this->input->post("cover");
        $status = $this->input->post("status") ?? 1;
        $result = $this->model->insert_gambar($id_produk, $gambar, $cover, $status);
        $this->output_json(["data" => $result]);
    }

    public function update_gambar()
    {
        $id = $this->input->post("id_produk_gambar");
        $id_produk = $this->input->post("id_produk");
        $cover = $this->input->post("cover");
        $status = $this->input->post("status") ?? 1;
        $gambar = $this->input->post("old_gambar");
        if (isset($_FILES['gambar']['name'])) {
            if ($_FILES['gambar']['name'] != '') {
                $ph = $this->uploadFile("gambar");
                $gambar = $ph['data'];
            }
        }
        $result = $this->model->update_gambar($id, $id_produk, $gambar, $cover, $status);
        $this->output_json(["data" => $result]);
    }

    public function delete_gambar()
    {
        $id = $this->input->post("id");
        $result = $this->model->delete_gambar($id);
        $this->output_json(["data" => $result]);
    }

    public function find_gambar()
    {
        $id = $this->input->post("id") ?? $this->input->get("id");
        $result = $this->model->find_gambar($id);
        $this->output_json(["data" => $result]);
    }
    // end produk gambar

    // produk varian
    public function insert_varian()
    {
        $id_produk = $this->input->post("id_produk");
        $varian = $this->input->post("varian");
        $status = $this->input->post("status") ?? 1;
        $result = $this->model->insert_varian($id_produk, $varian, $status);
        $this->output_json(["data" => $result]);
    }

    public function update_varian()
    {
        $id = $this->input->post("id_produk_varian");
        $id_produk = $this->input->post("id_produk");
        $varian = $this->input->post("varian");
        $status = $this->input->post("status") ?? 1;
        $result = $this->model->update_varian($id, $id_produk, $varian, $status);
        $this->output_json(["data" => $result]);
    }

    public function delete_varian()
    {
        $id = $this->input->post("id");
        $result = $this->model->delete_varian($id);
        $this->output_json(["data" => $result]);
    }

    public function find_varian()
    {
        $id = $this->input->post("id") ?? $this->input->get("id");
        $result = $this->model->find_varian($id);
        $this->output_json(["data" => $result]);
    }
    // end produk varian

    // produk varian ukuran
    public function ukuran_insert()
    {
        $id_produk_varian = $this->input->post("ukuran_id_produk_varian");
        $ukuran = $this->input->post("ukuran");
        $berat = $this->input->post("berat");
        $stok = $this->input->post("stok");
        $status = $this->input->post("status") ?? 1;
        $result = $this->model->ukuran_insert($id_produk_varian, $ukuran, $berat, $stok, $status);
        $this->output_json(["data" => $result]);
    }

    public function ukuran_update()
    {
        $id = $this->input->post("ukuran_id");
        $id_produk_varian = $this->input->post("ukuran_id_produk_varian");
        $ukuran = $this->input->post("ukuran");
        $berat = $this->input->post("berat");
        $stok = $this->input->post("stok");
        $status = $this->input->post("status") ?? 1;
        $result = $this->model->ukuran_update($id, $id_produk_varian, $ukuran, $berat, $stok, $status);
        $this->output_json(["data" => $result]);
    }


    public function ukuran_delete()
    {
        $id = $this->input->post("id");
        $result = $this->model->ukuran_delete($id);
        $this->output_json(["data" => $result]);
    }
    // end profuk varian ukuran
}
