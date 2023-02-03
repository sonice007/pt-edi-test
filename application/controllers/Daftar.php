<?php

defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Daftar
 * @author Soni Setiawan -> untuk semua function yang ada di class Daftar
 */
class Daftar extends Render_Controller
{
    /**
     * Fungsi index untuk menampilkan tampilan message
     */
    public function index()
    {
        $this->sesion->cek_login();
        $this->content = 'daftar';
        $this->render();
    }

    public function submit(){
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $simpan = $this->model->submit($email, $password);
        if($simpan){
            $this->output_json(['status' => 1, 'message' => 'Pendaftaran berhasil']);
        }else{
            $this->output_json(['status' => 0, 'message' => 'Pendaftaran gagal']);
        }
    }


    function __construct()
    {
        parent::__construct();
        // Cek session
        // $this->sesion->cek_session();
        // $akses = ['Super Admin', 'User'];
        // $get_lv = $this->session->userdata('data')['level'];
        // if (!in_array($get_lv, $akses)) {
        //     redirect('my404', 'refresh');
        // }
        $this->load->model("daftarModel", 'model');
        $this->default_template = 'templates/daftar';
        $this->load->library('plugin');
        $this->load->helper('url');
    }
}
