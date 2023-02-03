<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Toko extends Render_Controller
{
    function __construct()
    {
        parent::__construct();
        // Cek session
        $this->sesion->cek_session();
        $this->default_template = 'templates/dashboard';
        $this->load->model('produk/tokoModel', 'model');
        $this->foto_folder = './files/produk/toko/';
        $this->load->library('plugin');
        $this->load->helper('url');;
    }

    public function index()
    {
        // Page Settings
        // $this->title = $this->getMenuTitle();
        $this->title = "Toko";
        $this->content = 'produk/toko';
        $this->navigation = ['Produk','Toko'];
        $this->plugins = ['datatables', 'summernote'];

        // Breadcrumb setting
        $this->breadcrumb_1 = 'Dashboard';
        $this->breadcrumb_1_url = base_url();
        $this->breadcrumb_2 = 'Produk';
        $this->breadcrumb_2_url = "#";
        $this->breadcrumb_3 = 'Toko';
        $this->breadcrumb_3_url = base_url() . 'Toko';

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

    public function insert()
    {
        $nama = $this->input->post("nama");
        $keterangan = $this->input->post("keterangan");
        $status = $this->input->post("status") ?? 1;
        $ph = $this->uploadFile("logo");
        $logo = $ph['data'];
        $result = $this->model->insert($nama, $keterangan, $logo, $status);
        $this->output_json(["data" => $result]);
    }

    public function update()
    {
        $id = $this->input->post("id");
        $nama = $this->input->post("nama");
        $keterangan = $this->input->post("keterangan");
        $status = $this->input->post("status") ?? 1;
        $logo = $this->input->post("old_logo");
        if (isset($_FILES['logo']['name'])) {
            if ($_FILES['logo']['name'] != '') {
                $ph = $this->uploadFile("logo");
                $logo = $ph['data'];
            }
        }
        $result = $this->model->update($id, $nama, $keterangan, $logo, $status);
        $this->output_json(["data" => $result]);
    }


    public function delete()
    {
        $id = $this->input->post("id");
        $result = $this->model->delete($id);
        $this->output_json(["data" => $result]);
    }

    public function find()
    {
        $id = $this->input->post("id") ?? $this->input->get("id");
        $result = $this->model->find($id);
        $this->output_json(["data" => $result]);
    }

    public function select2()
    {
        $key = $this->input->get('search') ?? $this->input->post('search');
        $new = $this->input->get('new') ?? $this->input->post('new');
        $all = $this->input->get('all') ?? $this->input->post('all');

        $result = $this->model->select2($key);
        if ($all != null) {
            $result = array_merge([['id' => "", 'text' => ' All Toko']], $result);
        }

        if ($new != null && $key != '') {
            $result = array_merge([['id' => $key, 'text' => $key . ' (Create New)']], $result);
        }

        $this->output_json(['results' => $result]);
    }

    function cekNama(){
        $key = $this->input->get('key');
        $type = $this->input->get('type');
        $default = $this->input->get('default');
        if($type == "edit"){
            $check = $this->db->get_where('toko', ['nama != ' => $default, 'nama' => $key, 'status' => 1])->num_rows();
        }else{
            $check = $this->db->get_where('toko', ['nama' =>$key, 'status' => 1])->num_rows();
        }
        $this->output_json($check);
    }

    public function kontak_insert()
    {
        $id_toko = $this->input->post("id_toko");
        $id_kontak = $this->input->post("id_kontak");
        $keterangan = $this->input->post("keterangan");
        $status = $this->input->post("status") ?? 1;
        $result = $this->model->toko_kontak_insert($id_toko, $id_kontak, $keterangan, $status);
        $this->output_json(["data" => $result]);
    }

    public function kontak_update()
    {
        $id = $this->input->post("id_toko_kontak");
        $id_toko = $this->input->post("id_toko");
        $id_kontak = $this->input->post("id_kontak");
        $keterangan = $this->input->post("keterangan");
        $status = $this->input->post("status") ?? 1;
        $result = $this->model->toko_kontak_update($id, $id_toko, $id_kontak, $keterangan, $status);
        $this->output_json(["data" => $result]);
    }

    public function kontak_delete()
    {
        $id = $this->input->post("id");
        $result = $this->model->toko_kontak_delete($id);
        $this->output_json(["data" => $result]);
    }

    public function getAllTokoKontak()
    {
        $id_toko = $this->input->post("id_toko") ?? $this->input->get("id_toko");
        $result = $this->model->getTokoKontakByID($id_toko);
        $this->output_json(["data" => $result]);
    }

    public function listKontak()
    {
        $result = $this->model->getKontak();
        $this->output_json(["data" => $result]);
    }

    // wilayah
    public function wilayah_insert()
    {
        $id_toko = $this->input->post("id_toko_w");
        $id_wilayah = $this->input->post("id_wilayah");
        $keterangan = $this->input->post("keterangan");
        $status = $this->input->post("status") ?? 1;
        $result = $this->model->toko_wilayah_insert($id_toko, $id_wilayah, $keterangan, $status);
        $this->output_json(["data" => $result]);
    }

    public function wilayah_update()
    {
        $id = $this->input->post("id_toko_wilayah");
        $id_toko = $this->input->post("id_toko_w");
        $id_wilayah = $this->input->post("id_wilayah");
        $keterangan = $this->input->post("keterangan");
        $status = $this->input->post("status") ?? 1;
        $result = $this->model->toko_wilayah_update($id, $id_toko, $id_wilayah, $keterangan, $status);
        $this->output_json(["data" => $result]);
    }

    public function wilayah_delete()
    {
        $id = $this->input->post("id");
        $result = $this->model->toko_wilayah_delete($id);
        $this->output_json(["data" => $result]);
    }

    public function getAllTokoWilayah()
    {
        $id_toko = $this->input->post("id_toko") ?? $this->input->get("id_toko");
        $result = $this->model->getTokoWilayahByID($id_toko);
        $this->output_json(["data" => $result]);
    }

    public function listWilayah()
    {
        $result = $this->model->getWilayah();
        $this->output_json(["data" => $result]);
    }
}
