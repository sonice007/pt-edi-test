<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Biodata extends Render_Controller
{


    public function index()
    {
        $akses = ['Super Admin', 'User'];
        $get_lv = $this->session->userdata('data')['level'];

        if (!in_array($get_lv, $akses)) {
            redirect('my404', 'refresh');
        }

        if($this->session->userdata('data')['level'] == "User"){
            redirect("biodata/tambah", "refresh");
        }
        // Page Settings
        $this->title = $this->getMenuTitle();
        $this->navigation = ['Biodata'];
        $this->plugins = ['datatables', 'select2', 'moment'];

        // Breadcrumb setting
        $this->breadcrumb_1 = 'Dashboard';
        $this->breadcrumb_1_url = base_url();
        $this->breadcrumb_2 = 'Biodata';
        $this->breadcrumb_2_url = base_url() . 'biodata';




        $get_lv = $this->session->userdata('data')['level'];
        if ($get_lv == 'Super Admin') {
            $this->content      = 'biodata/list';
        } else {
            // content
            redirect('biodata/tambah', 'refresh');
        }

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

        $data = $this->model->getAllData($draw, $length, $start, $_cari, $order)->result_array();
        $count = $this->model->getAllData(null, null, null, $_cari, $order)->num_rows();


        $this->output_json(['recordsTotal' => $count, 'recordsFiltered' => $count, 'draw' => $draw, 'search' => $_cari, 'data' => $data]);
    }


    public function tambah($id = null)
    {
        // $akses = [10, 9, 8, 7];
        // $get_lv = $this->session->userdata('data')['level'];
        // if (!in_array($get_lv, $akses)) {
        //     redirect('my404', 'refresh');
        // }

        if($this->session->userdata('data')['level'] == "Super Admin"){
            if($id == null){
                redirect("biodata", "refresh");
            }
        }
        // Page Settings
        $this->title = $this->getMenuTitle();
        $this->navigation = ['Users', 'Data Member'];
        $this->plugins = ['datatables', 'select2', 'masonry', 'moment'];

        // Breadcrumb setting
        $this->breadcrumb_1 = 'Dashboard';
        $this->breadcrumb_1_url = base_url();
        $this->breadcrumb_2 = 'Biodata';
        $this->breadcrumb_3_url = base_url() . 'biodata/tambah';
        $this->data['isUbah'] = $id != null;

        // content
        $id_user = $this->session->userdata('data')['id'];
        $get_biodata = $this->db->join('users b', 'b.user_id = a.id_user', 'left')->get_where('profile a', ['b.user_id' => $id_user])->row_array();

       
        if($id == null){
            if($this->session->userdata('data')['level'] == "User"){
                $ceknew = $this->model->cekNew($id);
                if ($ceknew == null) {
                    redirect('/biodata');
                    return;
                }
                $id = $ceknew['id'];
            }else{
                $id = $get_biodata['id'];
            }
        }else{
            $get_biodata = $this->db->join('users b', 'b.user_id = a.id_user', 'left')->get_where('profile a', ['a.id' => $id])->row_array();
            $id_user = $get_biodata['id_user'];
        }


        $this->data['id_user'] = $id_user;
        $this->data['biodata'] = $get_biodata;

        $this->data['getID'] = $id;

        $this->content      = 'biodata/tambah';

        // $partner_alias = $this->db->select('b.nama as nama')->join('term_management b', 'b.id_menu = a.menu_id')->get_where('menu a', ['a.menu_id' => 106])->row_array();
        // $this->data['partner_alias'] = $partner_alias['nama'];

        // $membership_alias = $this->db->select('b.nama as nama')->join('term_management b', 'b.id_menu = a.menu_id')->get_where('menu a', ['a.menu_id' => 102])->row_array();
        // $this->data['membership_alias'] = $membership_alias['nama'];

        // $data_formal = $this->db->select('b.nama as nama')->join('term_management b', 'b.id_menu = a.menu_id')->get_where('menu a', ['a.menu_id' => 109])->row_array();
        // $this->data['data_formal_alias'] = $data_formal['nama'];

        // // $kategori_keanggotaan_alias = $this->db->select('b.nama as nama')->join('term_management b', 'b.id_menu = a.menu_id')->get_where('menu a', ['a.menu_id' => 128])->row_array();
        // // $this->data['kategori_keanggotaan_alias'] = $kategori_keanggotaan_alias['nama'];

        // $kategorial_alias = $this->db->select('b.nama as nama')->join('term_management b', 'b.id_menu = a.menu_id')->get_where('menu a', ['a.menu_id' => 126])->row_array();
        // $this->data['kategorial_alias'] = $kategorial_alias['nama'];

        // $wilayah_alias = $this->db->select('b.nama as nama')->join('term_management b', 'b.id_menu = a.menu_id')->get_where('menu a', ['a.menu_id' => 127])->row_array();
        // $this->data['wilayah_alias'] = $wilayah_alias['nama'];

        // $jabatan_alias = $this->db->select('b.nama as nama')->join('term_management b', 'b.id_menu = a.menu_id')->get_where('menu a', ['a.menu_id' => 132])->row_array();
        // $this->data['jabatan_alias'] = $jabatan_alias['nama'];

        // $pilihan_alias = $this->db->select('b.nama as nama')->join('term_management b', 'b.id_menu = a.menu_id')->get_where('menu a', ['a.menu_id' => 139])->row_array();
        // $this->data['pilihan_keaktifan_alias'] = $pilihan_alias['nama'];

        // $pilihan_alias = $this->db->select('b.nama as nama')->join('term_management b', 'b.id_menu = a.menu_id')->get_where('menu a', ['a.menu_id' => 140])->row_array();
        // $this->data['master_keaktifan_alias'] = $pilihan_alias['nama'];

        // $part = $this->session->userdata('data')['id'];
        // $cek_part = $this->db->get_where('users', ['user_id' => $part])->result_array();
        // $idpart = $cek_part[0]['id_partner'];
        // if ($idpart == null || $idpart == 0 || $idpart == '-' || $this->session->userdata('data')['level'] == 'Super Admin') {
        //     $idprt = '';
        //     $stpart = 'kosong';
        // } else {
        //     $idprt = '';
        //     $stpart = '';
        // }
        // $this->data['idpart'] = $idpart;
        // $this->data['stpart'] = $stpart;

        // $this->data['pilihanKeaktifan'] = $this->db->get_where('keaktifan_pilihan', ['status' => 1])->result_array();
        // $this->data['membership'] = $this->model->listmembership();
        // $this->data['peristiwa'] = $this->model->listperistiwa();
        // $this->data['institusi'] = $this->db->get_where('institusi', ['status' => 1])->result_array();
        // $this->data['isiJabatan'] = $this->db->select('a.nama_depan, a.nama_belakang, a.id')
        //     ->join('profile a', 'a.id = pk.id_profile')
        //     ->join('membership b', 'b.id_profile = a.id')
        //     ->get_where(
        //         'profile_keaktifan pk',
        //         ['b.id_jenis_membership ' => 1, 'b.status' => 1]
        //     )
        //     ->result_array();
        // $this->data['jenisalamat'] = $this->model->listjenisalamat();
        // $this->data['jenisgelar'] = $this->model->listjenisgelar();
        // $this->data['tipekontak'] = $this->model->listtipekontak();
        // $this->data['kategorial'] = $this->model->list_kategorial();
        // // $this->data['kategori_keanggotaan'] = $this->model->list_kategori_keanggotaan();
        // $this->data['wilayah'] = $this->model->list_wilayah();
        // $this->data['atasanmember'] = $this->model->listatasanmember($idprt);
        
        // // $this->data['contact'] = $this->model->getContact($ceknew['id'])->result_array();
        // $this->data['isUbah'] = $id != null;
        // $this->data['partner'] = $this->model->getPathner();
        // $this->data['level'] = $this->model->getLevel();
        // $this->data['posisi'] = $this->model->getPosisi();

        // Send data to view
        $this->render();
    }

    public function lihat()
    {
        $id_user = $this->session->userdata('data')['id'];
        $id = $this->db->get_where('profile', ['id_user' => $id_user])->result_array();
        if ($id != null) {
            $id = $id[0]['id'];
        } else {
            redirect('/pengaturan/profile');
            return;
        }
        // Page Settings
        $this->title = "Profil Pengguna";
        $this->navigation = ['Profile'];
        $this->plugins = ['datatables', 'select2', 'masonry', 'moment'];

        // Breadcrumb setting
        $this->breadcrumb_1 = 'Dashboard';
        $this->breadcrumb_1_url = base_url();
        $this->breadcrumb_2 = 'Lihat Profil';
        $this->breadcrumb_2_url = base_url() . 'pengaturan/profile/lihat';
        $this->data['isUbah'] = $id != null;
        $this->data['isLihat'] = $id != null;

        // content
        $this->content      = 'pengaturan/profile/lihat';

        $ceknew = $this->model->cekNew($id);
        if ($ceknew == null) {
            redirect('/pengaturan/profile');
            return;
        }

        $partner_alias = $this->db->select('b.nama as nama')->join('term_management b', 'b.id_menu = a.menu_id')->get_where('menu a', ['a.menu_id' => 106])->row_array();
        $this->data['partner_alias'] = $partner_alias['nama'];

        $membership_alias = $this->db->select('b.nama as nama')->join('term_management b', 'b.id_menu = a.menu_id')->get_where('menu a', ['a.menu_id' => 102])->row_array();
        $this->data['membership_alias'] = $membership_alias['nama'];

        $data_formal = $this->db->select('b.nama as nama')->join('term_management b', 'b.id_menu = a.menu_id')->get_where('menu a', ['a.menu_id' => 109])->row_array();
        $this->data['data_formal_alias'] = $data_formal['nama'];

        $kategori_keanggotaan_alias = $this->db->select('b.nama as nama')->join('term_management b', 'b.id_menu = a.menu_id')->get_where('menu a', ['a.menu_id' => 128])->row_array();
        $this->data['kategori_keanggotaan_alias'] = $kategori_keanggotaan_alias['nama'];

        $kategorial_alias = $this->db->select('b.nama as nama')->join('term_management b', 'b.id_menu = a.menu_id')->get_where('menu a', ['a.menu_id' => 126])->row_array();
        $this->data['kategorial_alias'] = $kategorial_alias['nama'];

        $wilayah_alias = $this->db->select('b.nama as nama')->join('term_management b', 'b.id_menu = a.menu_id')->get_where('menu a', ['a.menu_id' => 127])->row_array();
        $this->data['wilayah_alias'] = $wilayah_alias['nama'];

        $jabatan_alias = $this->db->select('b.nama as nama')->join('term_management b', 'b.id_menu = a.menu_id')->get_where('menu a', ['a.menu_id' => 132])->row_array();
        $this->data['jabatan_alias'] = $jabatan_alias['nama'];

        $pilihan_alias = $this->db->select('b.nama as nama')->join('term_management b', 'b.id_menu = a.menu_id')->get_where('menu a', ['a.menu_id' => 139])->row_array();
        $this->data['pilihan_keaktifan_alias'] = $pilihan_alias['nama'];

        $pilihan_alias = $this->db->select('b.nama as nama')->join('term_management b', 'b.id_menu = a.menu_id')->get_where('menu a', ['a.menu_id' => 140])->row_array();
        $this->data['master_keaktifan_alias'] = $pilihan_alias['nama'];

        $part = $this->session->userdata('data')['id'];
        $cek_part = $this->db->get_where('users', ['user_id' => $part])->result_array();
        $idpart = $cek_part[0]['id_partner'];
        if ($idpart == null || $idpart == 0 || $idpart == '-' || $this->session->userdata('data')['level'] == 'Super Admin') {
            $idprt = '';
            $stpart = 'kosong';
        } else {
            $idprt = '';
            $stpart = '';
        }
        $this->data['idpart'] = $idpart;
        $this->data['stpart'] = $stpart;

        $this->data['pilihanKeaktifan'] = $this->db->get_where('keaktifan_pilihan', ['status' => 1])->result_array();
        $this->data['membership'] = $this->model->listmembership();
        $this->data['peristiwa'] = $this->model->listperistiwa();
        $this->data['institusi'] = $this->db->get_where('institusi', ['status' => 1])->result_array();
        $this->data['isiJabatan'] = $this->db->select('a.nama_depan, a.nama_belakang, a.id')
            ->join('profile a', 'a.id = pk.id_profile')
            ->join('membership b', 'b.id_profile = a.id')
            ->get_where(
                'profile_keaktifan pk',
                ['b.id_jenis_membership ' => 1, 'b.status' => 1]
            )
            ->result_array();
        $this->data['jenisalamat'] = $this->model->listjenisalamat();
        $this->data['jenisgelar'] = $this->model->listjenisgelar();
        $this->data['tipekontak'] = $this->model->listtipekontak();
        $this->data['kategorial'] = $this->model->list_kategorial();
        $this->data['kategori_keanggotaan'] = $this->model->list_kategori_keanggotaan();
        $this->data['wilayah'] = $this->model->list_wilayah();
        $this->data['atasanmember'] = $this->model->listatasanmember($idprt);
        $this->data['getID'] = $ceknew['id'];
        // $this->data['contact'] = $this->model->getContact($ceknew['id'])->result_array();
        $this->data['profile'] = $ceknew;
        $this->data['isUbah'] = $id != null;
        $this->data['partner'] = $this->model->getPathner();
        $this->data['level'] = $this->model->getLevel();
        $this->data['posisi'] = $this->model->getPosisi();

        $get_calon_suami = $this->model->getCalonSuami($id);

        if ($get_calon_suami != null) {
            $this->data['get_calon_suami'] = $get_calon_suami;
            $this->data['get_calon_istri'] = $this->model->getCalonIstri($get_calon_suami['keluarga']);
        } else {
            $this->data['get_calon_suami'] = $this->model->getCalonIstri($ceknew['id_partner']);
            $this->data['get_calon_istri'] = $this->model->getCalonSuamiTambah($ceknew['id_partner']);
        }

        // Send data to view
        $this->render();
    }

    public function emailCheck()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('id_user', 'Id User', 'trim|required|numeric');
        if ($this->form_validation->run() == FALSE) {
            $this->output_json([
                'status' => false,
                'data' => null,
                'message' => validation_errors()
            ], 400);
        } else {
            $email = $this->input->post('email');
            $id_user = $this->input->post('id_user');
            $result = $this->model->emailCheck($email, $id_user);

            $code = $result == null ? 200 : 409;
            $status = $result == null;
            $this->output_json([
                'status' => $status,
                'length' => 1,
                'data' =>  $result,
                'message' => $status ? 'Email belum digunakan' : 'Email sudah terdaftar'
            ], $code);
        }
    }

    public function nikCheck()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('nik', 'nik', 'trim|required|numeric');
        $this->form_validation->set_rules('id_user', 'Id User', 'trim|required|numeric');
        if ($this->form_validation->run() == FALSE) {
            $this->output_json([
                'status' => false,
                'data' => null,
                'message' => validation_errors()
            ], 400);
        } else {
            $no_ktp = $this->input->post('no_ktp');
            $id_user = $this->input->post('id_user');
            $result = $this->model->nikCheck($no_ktp, $id_user);

            $code = $result == null ? 200 : 409;
            $status = $result == null;
            $this->output_json([
                'status' => $status,
                'length' => 1,
                'data' =>  $result,
                'message' => $status ? 'Nik belum terdaftar' : 'Nik sudah terdaftar'
            ], $code);
        }
    }

    // pendidikan
    public function getPendidikan()
    {
        $order = ['order' => $this->input->post('order'), 'columns' => $this->input->post('columns')];
        $start = $this->input->post('start');
        $draw = $this->input->post('draw');
        $draw = $draw == null ? 1 : $draw;
        $length = $this->input->post('length');
        $cari = $this->input->post('search');
        $order['profile']['id_profile'] = $this->input->post('idp');

        if (isset($cari['value'])) {
            $_cari = $cari['value'];
        } else {
            $_cari = null;
        }

        $data = $this->model->getPendidikan($draw, $length, $start, $_cari, $order)->result_array();
        $count = $this->model->getPendidikan(null, null, null, $_cari, $order, null)->num_rows();

        $this->output_json(['recordsTotal' => $count, 'recordsFiltered' => $count, 'draw' => $draw, 'search' => $_cari, 'data' => $data]);
    }

    public function insert_pendidikan()
    {
        $id                             = $this->input->post("id_pendidikan");
        $id_profile                     = $this->input->post("id_profile");
        $jenjang_pendidikan_terakhir    = $this->input->post("jenjang_pendidikan_terakhir");
        $nama_institusi_akademik        = $this->input->post("nama_institusi_akademik");
        $jurusan                        = $this->input->post("jurusan");
        $tahun_lulus                    = $this->input->post("tahun_lulus");
        $ipk                            = $this->input->post("ipk");
        $status                         = $this->input->post("status_pendidikan");
        $result                         = $this->model->insert_pendidikan($id, $id_profile, $jenjang_pendidikan_terakhir, $nama_institusi_akademik, $jurusan, $tahun_lulus, $ipk, $status);
        $code                           = $result ? 200 : 500;
        $this->output_json(["data" => $result], $code);
    }

    public function update_pendidikan()
    {
        $id                             = $this->input->post("id_pendidikan");
        $id_profile                     = $this->input->post("id_profile");
        $jenjang_pendidikan_terakhir    = $this->input->post("jenjang_pendidikan_terakhir");
        $nama_institusi_akademik        = $this->input->post("nama_institusi_akademik");
        $jurusan                        = $this->input->post("jurusan");
        $tahun_lulus                    = $this->input->post("tahun_lulus");
        $ipk                            = $this->input->post("ipk");
        $status                         = $this->input->post("status_pendidikan");
        $result = $this->model->update_pendidikan($id, $id_profile, $jenjang_pendidikan_terakhir, $nama_institusi_akademik, $jurusan, $tahun_lulus, $ipk, $status);
        $code = $result ? 200 : 500;
        $this->output_json(["data" => $result], $code);
    }

    public function getPendidikanByID()
    {
        $id = $this->input->post('id');
        $order = ['order' => $this->input->post('order'), 'columns' => $this->input->post('columns')];
        $data = $this->model->getPendidikanByID($id, $order)->result_array();
        $count = $this->model->getPendidikanByID($id, $order)->num_rows();
        $this->output_json(['recordsTotal' => $count, 'recordsFiltered' => $count, 'data' => $data]);
    }
    // end pendidikan

    // pelatihan
    public function getPelatihan()
    {
        $order = ['order' => $this->input->post('order'), 'columns' => $this->input->post('columns')];
        $start = $this->input->post('start');
        $draw = $this->input->post('draw');
        $draw = $draw == null ? 1 : $draw;
        $length = $this->input->post('length');
        $cari = $this->input->post('search');
        $order['profile']['id_profile'] = $this->input->post('idp');

        if (isset($cari['value'])) {
            $_cari = $cari['value'];
        } else {
            $_cari = null;
        }

        $data = $this->model->getPelatihan($draw, $length, $start, $_cari, $order)->result_array();
        $count = $this->model->getPelatihan(null, null, null, $_cari, $order, null)->num_rows();

        $this->output_json(['recordsTotal' => $count, 'recordsFiltered' => $count, 'draw' => $draw, 'search' => $_cari, 'data' => $data]);
    }

    public function insert_pelatihan()
    {
        $id                     = $this->input->post("id_pelatihan");
        $id_profile             = $this->input->post("id_profile");
        $nama_kursus_seminar    = $this->input->post("nama_kursus_seminar");
        $sertifikat             = $this->input->post("sertifikat");
        $tahun                  = $this->input->post("tahun");
        $status                 = $this->input->post("status_pelatihan");
        $result                 = $this->model->insert_pelatihan($id, $id_profile, $nama_kursus_seminar, $sertifikat, $tahun, $status);
        $code                   = $result ? 200 : 500;
        $this->output_json(["data" => $result], $code);
    }

    public function update_pelatihan()
    {
        $id                     = $this->input->post("id_pelatihan");
        $id_profile             = $this->input->post("id_profile");
        $nama_kursus_seminar    = $this->input->post("nama_kursus_seminar");
        $sertifikat             = $this->input->post("sertifikat");
        $tahun                  = $this->input->post("tahun");
        $status                 = $this->input->post("status_pelatihan");
        $result                 = $this->model->update_pelatihan($id, $id_profile, $nama_kursus_seminar, $sertifikat, $tahun, $status);
        $code                   = $result ? 200 : 500;
        $this->output_json(["data" => $result], $code);
    }

    public function getPelatihanByID()
    {
        $id = $this->input->post('id');
        $order = ['order' => $this->input->post('order'), 'columns' => $this->input->post('columns')];
        $data = $this->model->getPelatihanByID($id, $order)->result_array();
        $count = $this->model->getPelatihanByID($id, $order)->num_rows();
        $this->output_json(['recordsTotal' => $count, 'recordsFiltered' => $count, 'data' => $data]);
    }
    // end pelatihan

    // pekerjaan
    public function getPekerjaan()
    {
        $order = ['order' => $this->input->post('order'), 'columns' => $this->input->post('columns')];
        $start = $this->input->post('start');
        $draw = $this->input->post('draw');
        $draw = $draw == null ? 1 : $draw;
        $length = $this->input->post('length');
        $cari = $this->input->post('search');
        $order['profile']['id_profile'] = $this->input->post('idp');

        if (isset($cari['value'])) {
            $_cari = $cari['value'];
        } else {
            $_cari = null;
        }

        $data = $this->model->getPekerjaan($draw, $length, $start, $_cari, $order)->result_array();
        $count = $this->model->getPekerjaan(null, null, null, $_cari, $order, null)->num_rows();

        $this->output_json(['recordsTotal' => $count, 'recordsFiltered' => $count, 'draw' => $draw, 'search' => $_cari, 'data' => $data]);
    }

    public function insert_pekerjaan()
    {
        $id                     = $this->input->post("id_pekerjaan");
        $id_profile             = $this->input->post("id_profile");
        $nama_perusahaan        = $this->input->post("nama_perusahaan");
        $posisi_terakhir        = $this->input->post("posisi_terakhir");
        $pendapatan_terakhir    = $this->input->post("pendapatan_terakhir");
        $tahun                  = $this->input->post("tahun_pekerjaan");
        $status                 = $this->input->post("status_pekerjaan");
        $result                 = $this->model->insert_pekerjaan($id, $id_profile, $nama_perusahaan, $posisi_terakhir, $pendapatan_terakhir, $tahun, $status);
        $code                   = $result ? 200 : 500;
        $this->output_json(["data" => $result], $code);
    }

    public function update_pekerjaan()
    {
        $id                     = $this->input->post("id_pekerjaan");
        $id_profile             = $this->input->post("id_profile");
        $nama_perusahaan        = $this->input->post("nama_perusahaan");
        $posisi_terakhir        = $this->input->post("posisi_terakhir");
        $pendapatan_terakhir    = $this->input->post("pendapatan_terakhir");
        $tahun                  = $this->input->post("tahun_pekerjaan");
        $status                 = $this->input->post("status_pekerjaan");
        $result                 = $this->model->update_pekerjaan($id, $id_profile, $nama_perusahaan, $posisi_terakhir, $pendapatan_terakhir, $tahun, $status);
        $code                   = $result ? 200 : 500;
        $this->output_json(["data" => $result], $code);
    }

    public function getPekerjaanByID()
    {
        $id = $this->input->post('id');
        $order = ['order' => $this->input->post('order'), 'columns' => $this->input->post('columns')];
        $data = $this->model->getPekerjaanByID($id, $order)->result_array();
        $count = $this->model->getPekerjaanByID($id, $order)->num_rows();
        $this->output_json(['recordsTotal' => $count, 'recordsFiltered' => $count, 'data' => $data]);
    }
    // end pekerjaan

    public function simpan()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('id', 'id Profile', 'trim|required|numeric');
        $this->form_validation->set_rules('nama', 'Nama Belakang', 'trim|required');
        // $this->form_validation->set_rules('id_partner', 'Partner', 'trim|required|numeric');
        if ($this->form_validation->run() == FALSE) {
            $this->output_json([
                'status' => false,
                'data' => null,
                'message' => validation_errors()
            ], 400);
        } else {
            $id = $this->input->post('id');
            $id_user = $this->input->post('id_user');
            $posisi = $this->input->post('posisi');
            $nama = $this->input->post('nama');
            $no_ktp = $this->input->post('no_ktp');
            $tempat_lahir = $this->input->post('tempat_lahir');
            $tanggal_lahir = $this->input->post('tanggal_lahir');
            $jenis_kelamin = $this->input->post('jenis_kelamin');
            $agama = $this->input->post('agama');
            $golongan_darah = $this->input->post('golongan_darah');
            $status = $this->input->post('status');
            $alamat_ktp = $this->input->post('alamat_ktp');
            $alamat_tinggal = $this->input->post('alamat_tinggal');
            $email = $this->input->post('email');
            $no_telp = $this->input->post('no_telp');
            $orang_terdekat = $this->input->post('orang_terdekat');
            $skill = $this->input->post('skill');
            $status_ditempatkan = $this->input->post('status_ditempatkan');
            $penghasilan = $this->input->post('penghasilan');
            
            
            $result = $this->model->simpan(
                $id,
                $id_user,
                $posisi,
                $nama,
                $no_ktp,
                $tempat_lahir,
                $tanggal_lahir,
                $jenis_kelamin,
                $agama,
                $golongan_darah,
                $status,
                $alamat_ktp,
                $alamat_tinggal,
                $email,
                $no_telp,
                $orang_terdekat,
                $skill,
                $status_ditempatkan,
                $penghasilan
            );
            $code = $result != null ? 200 : 400;
            $status = $result != null;
            $this->output_json([
                'status' => $status,
                'length' => 1,
                'data' =>  $result,
                'message' => $status ? 'Data berhasil ditambahkan' : 'Data gagal ditambahkan'
            ], $code);
        }
    }

    public function delete()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('id_profile', 'Id Profile', 'trim|required|numeric');
        $this->form_validation->set_rules('id_user', 'Id user', 'trim|required|numeric');
        if ($this->form_validation->run() == FALSE) {
            $this->output_json([
                'status' => false,
                'data' => null,
                'message' => validation_errors()
            ], 400);
        } else {
            $id_profile = $this->input->post('id_profile');
            $id_user = $this->input->post('id_user');
            $result = $this->model->delete($id_profile, $id_user);

            $code = $result != null ? 200 : 400;
            $status = $result != null;
            $this->output_json([
                'status' => $status,
                'length' => 1,
                'data' =>  $result,
                'message' => $status ? 'Data berhasil dihapus' : 'Data gagal dihapus'
            ], $code);
        }
    }



    public function hapusDaftar()
    {
        $id = $this->input->post("id");
        $tbl = $this->input->post("tbl");
        $result = $this->model->hapusDaftar($id, $tbl);
        $code = $result ? 200 : 500;
        $this->output_json(["data" => $result], $code);
    }

    function __construct()
    {
        parent::__construct();
        // Cek session
        $this->sesion->cek_session();

     

        $this->load->model("biodataModel", 'model');
        $this->default_template = 'templates/dashboard';
        $this->load->library('plugin');
        $this->load->helper('url');
    }
}
