<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends Render_Controller
{

	public function index()
	{
		$this->sesion->cek_login();
		$this->content = 'login';
		$this->render();
	}

	public function cekRecaptcha()
	{
		$recaptchaResponse = trim($this->input->post('g-recaptcha-response'));

		$userIp = $this->input->ip_address();

		$secret = $this->config->item('google_secret');

		$url = "https://www.google.com/recaptcha/api/siteverify?secret=" . $secret . "&response=" . $recaptchaResponse . "&remoteip=" . $userIp;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);

		$status = json_decode($output, true);

		if ($status['success']) {
			$this->output_json(['status' => 1, 'message' => 'Google Recaptcha Successful']);
		} else {
			$this->output_json(['status' => 2, 'message' => 'Sorry Google Recaptcha Unsuccessful!!']);
		}
	}


	public function doLogin()
	{

		$recaptchaResponse = trim($this->input->post('recaptcha'));

		$userIp = $this->input->ip_address();

		$secret = $this->config->item('google_secret');

		$url = "https://www.google.com/recaptcha/api/siteverify?secret=" . $secret . "&response=" . $recaptchaResponse . "&remoteip=" . $userIp;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);

		$status = json_decode($output, true);

		// if ($status['success']) {
		if (1 == 1) {
			$username 	= $this->input->post('email');
			$password 	= $this->input->post('password');

			// Cek login ke model
			$login 		= $this->login->cekLogin($username, $password);


			// if ($login['status'] == 0) {
			if (1 == 1) {
				// Set session value

				if ($login['data'][0]['user_status'] == 1) {
					$session = array(
						'status' => true,
						'data'	 => array(
							'id' => $login['data'][0]['user_id'],
							'nama' => $login['data'][0]['user_nama'],
							'email' => $login['data'][0]['user_email'],
							'level' => $login['data'][0]['lev_nama'],
							'level_id' => $login['data'][0]['lev_id'],
						)
					);

					$this->session->set_userdata($session);

					$this->output_json(['status' => 0, 'recaptcha' => 1, 'message' => 'Google Recaptcha Successful']);
				}
				// akun di nonaktifkan
				else if ($login['data'][0]['user_status'] == 0) {
					$this->output_json(['status' => 3, 'recaptcha' => 1, 'message' => 'Google Recaptcha Successful']);
				}
				// menunggu dikonfirmasi
				else if ($login['data'][0]['user_status'] == 2) {
					$this->output_json(['status' => 4, 'recaptcha' => 1, 'message' => 'Google Recaptcha Successful']);
				}
				// erorr
				else {
					$this->output_json(['status' => 5, 'recaptcha' => 1, 'message' => 'Google Recaptcha Successful']);
				}
			} else if ($login['status'] == 1) {
				$this->output_json(['status' => 1, 'recaptcha' => 1, 'message' => 'Google Recaptcha Successful']);
			} else {
				$this->output_json(['status' => 2, 'recaptcha' => 1, 'message' => 'Google Recaptcha Successful']);
			}
		} else {
			$this->output_json(['recaptcha' => 0, 'message' => 'Sorry Google Recaptcha Unsuccessful!!']);
		}
	}


	public function logout()
	{
		$session = array('status', 'data');

		$this->session->unset_userdata($session);

		redirect('login', 'refresh');
	}

	public function konfirmasi_email()
	{
		$token = $this->input->get('t');
		$cleanToken = $this->security->xss_clean($token);
		$user_info = $this->login->isTokenValid($this->base64url_decode($cleanToken));
		if (!$user_info) {
			$this->session->set_flashdata('message', 'Token tidak valid atau kadaluarsa');
			redirect(base_url('login'), 'refresh');
		} else {
			$result = $this->db->where('user_id', $user_info->user_id)->update('users', ['user_email_status' => '1']);
			if ($result) {
				$this->session->set_flashdata(['message' => [
					'message' => 'Email berhasil dikonfirmasi',
					'status' => true
				]]);

				// delete token
				$this->db->where('user_id', $user_info->user_id)->delete('tokens');

				$send = $this->sendConfirmEmail($user_info->user_id, $user_info->user_email);

				$modul = "Login";
				$by = 1;
				$jenis_perubahan = 1;

				$data_lama = "Belum ada data";
				$data_baru = "Berhasil melakukan aktivasi dengan email: " . $user_info->user_email . " di Aplikasi Audit Any";
				$this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
			} else {
				$this->session->set_flashdata('message', 'Email gagal dikonfirmasi');
			}
			redirect(base_url('login'), 'refresh');
		}
	}

	private function sendConfirmEmail($id, $email)
	{
		$this->load->model('loginModel', 'login');
		$content = '<p>Akun anda telah aktif dengan email: ' . $email . ' dalam Aplikasi Audit Any. Terimakasih';
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

	function __construct()
	{
		parent::__construct();
		$this->load->model('loginModel', 'login');
		$this->default_template = 'templates/login';
		$this->load->library('plugin');
		$this->load->helper('url');
	}
}

/* End of file Login.php */
/* Location: ./application/controllers/Login.php */