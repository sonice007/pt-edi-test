<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LupaPassword extends Render_Controller
{

    public function index()
    {
        $this->default_template = 'templates/lupa-password';
        $this->content = 'lupa-password';
        $this->render();
    }

    public function sendEmail()
    {
        $email = $this->input->post('email');
        $clean = $this->security->xss_clean($email);
        $userInfo = $this->login->getUserInfoByEmail($clean);

        if ($userInfo == null) {
            $this->output_json([
                'status' => false,
                'message' => 'Maaf Email yang anda masukan tidak terdaftar'
            ], 400);
            return;
        }

        $token = $this->login->insertToken($userInfo->user_id);
        $qstring = $this->base64url_encode($token);
        $url = base_url() . 'lupaPassword/reset_password?t=' . $qstring;
        $content = '<p>Untuk me-reset password silahkan <a href="' . $url . '">klik disini</a></p><br>Token berlaku selama satu hari, sampai jam 24:00 hari ini';

        // debug
        if ($this->debug) {
            $this->output_json([
                'url' => $url
            ]);
            return;
        }

        // set log
        $modul = 'Lupa Password';
        $by = 1;
        $jenis_perubahan = 1;
        $data_lama = "Belum ada data";
        $data_baru = "Melakukan reset password di Aplikasi Audit Any dengan email: ".$email;
        $this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);

        // production
        $this->send_email($email, $content, 'Reset Password');
        $this->output_json(true);
    }

    private function setActivityLog($modul = null, $by = null, $jenis_perubahan = null, $data_lama = null, $data_baru = null)
    {
        $data = [
            'modul' => $modul,
            'by' => $by,
            'jenis_perubahan' => $jenis_perubahan,
            'data_lama' => $data_lama,
            'data_baru' => $data_baru,
        ];
        $exe = $this->db->insert('activity_log', $data);
        return $exe;
    }

    public function reset_password()
    {
        $this->default_template = 'templates/reset-password';
        $token = $this->input->get('t');
        $cleanToken = $this->security->xss_clean($token);

        $user_info = $this->login->isTokenValid($this->base64url_decode($cleanToken)); //either false or array();
        if (!$user_info) {
            $this->session->set_flashdata('message', 'Token tidak valid atau kadaluarsa');
            redirect(base_url('login'), 'refresh');
        }
        $this->data['id'] = $user_info->user_id;
        $this->data['nama'] = $user_info->user_nama;
        $this->data['email'] = $user_info->user_email;
        $this->data['token'] = $this->base64url_encode($token);
        $this->content = 'reset-password';
        $this->render();
    }

    public function save_password()
    {
        $id = $this->input->post('id');
        $password = $this->input->post('password');
        $cleanPost = $this->security->xss_clean($password);
        $update = $this->login->updatePassword($id, $cleanPost);
        if ($update) {
            $this->output_json(['status' => 0]);
            $this->login->removeToken($id);
            // set log
            $get_users = $this->db->get_where('users', ['user_id' => $id])->row_array();
            $modul = 'Reset Password';
            $by = 1;
            $jenis_perubahan = 1;
            $data_lama = "Belum ada data";
            $data_baru = "Password berhasil direset dengan email: ".$get_users['user_email'];
            $this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);

            $send = $this->sendConfirmEmail($id, $get_users['user_email']);
        }
    }

    private function sendConfirmEmail($id, $email)
    {
        $this->load->model('loginModel', 'login');
        $content = '<p>Password anda berhasil direset dengan email: '.$email.'. Silahkan login ke dalam Aplikasi Audit Any dengan menggunakan password yang baru. Terimakasih';
        // debug
        if ($this->debug) {
            return [
                'status' => true
            ];
        }
        // production
        $this->send_email($email, $content, 'Aktivasi Akun Berhasil');
        return (true);
    }

    function __construct()
    {
        parent::__construct();
        $this->load->model('loginModel', 'login');
        $this->load->library('plugin');
        $this->load->helper('url');
    }
}

/* End of file Login.php */
/* Location: ./application/controllers/Login.php */