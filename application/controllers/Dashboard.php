<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends Render_Controller
{
	public function index()
	{
		// Page Settings
		$this->title = $this->getMenuTitle();
		$this->navigation = ['Dashboard'];

		$this->plugins = ['datatables', 'daterangepicker', 'datepicker', 'chartjs'];
		$this->load->library('libs');

		// Breadcrumb setting
		$this->breadcrumb_1 = 'Home';
		$this->breadcrumb_1_url = '#';
		$this->breadcrumb_2 = 'Dashboard';
		$this->breadcrumb_2_url = base_url() . 'dashboard';

		// widget ========================================================================================================
		// counter =======================================================================================================

		if($this->session->userdata('data')['level'] == "Super Admin"){
			$data['c_all'] = $this->db->join('role_users b', 'b.role_user_id = a.user_id')->join('level c', 'c.lev_id = b.role_lev_id')->get_where('users a', ['c.lev_nama' => 'User'])->num_rows();
			$this->data['summary'] = $data;
			$this->content = 'dashboard/admin';
		}else{
			$this->data['email'] = $this->session->userdata('data')['email'];
			$this->content = 'dashboard/user';
		}

		// Send data to view
		$this->render();
	}

	public function count()
	{
		// $to = date('Y-m-d');
		// $from = date('Y-m-d', strtotime($to . " - 600 days"));

		// $date = $this->counter->getByDateRange($from, $to);
		// $month = $this->counter->getByMonthRange('2021-05', '2021-06');
		// $year = $this->counter->getByMonthYear(2021, 2025);

		// var_dump($month);
		// die;
	}

	function __construct()
	{
		parent::__construct();
		$this->sesion->cek_session();
		$this->default_template = 'templates/dashboard';
		$this->load->library('plugin');
		$this->load->helper('url');

		// Cek session

		// model
		$this->load->model("DashboardModel", 'model');
		$this->load->model("CounterModel", 'counter');
	}
}

/* End of file Dashboard.php */
/* Location: ./application/controllers/Dashboard.php */