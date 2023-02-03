<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Render_Model extends CI_Model
{


	/* Default function */
	public function _generate($config = array())
	{

		/**
		 *
		 *
		 **/

		$table  = isset($config['table']) ? $config['table']  : null;
		$field  = isset($config['field']) ? $config['field']  : 'kode';
		$jumlah = isset($config['jumlah']) ? $config['jumlah'] : 5;
		$return = isset($config['return']) ? $config['return'] : 'TEST';

		/**
		 *
		 *
		 **/

		$exe = $this->db->select("RIGHT({$table}.{$field}, {$jumlah}) as id", FALSE)
			->order_by($field, 'DESC')
			->limit(1)
			->get($table);  //cek dulu apakah ada sudah ada kode di tabel.    

		if ($exe->num_rows() <> 0) {

			$data = $exe->row();
			$kode = intval($data->id) + 1;
		} else {
			$kode = 1;
		}

		$tgl 	     = date('Y');
		$batas      = str_pad($kode, $jumlah, '0', STR_PAD_LEFT);
		$kodetampil = $return . '-' . $tgl . '-' . $batas;


		return $kodetampil;
	}

	public function _cek($table = null, $field = null, $id = null, $return = 'nama')
	{
		$exe = $this->db->get_where($table, [$field => $id]);

		return $exe->row_array()[$return];
	}


	public function cekMenu($id)
	{
		$exe  = $this->db->get_where('menu', ['menu_id' => $id]);

		if ($exe->num_rows() > 0) {
			$exe = $exe->row_array()['menu_nama'];
		} else {
			$exe = ' ';
		}

		return $exe;
	}

	public function getLogId()
	{
		return $this->session->userdata('data')['id'];
	}

	public function getIsiTableById($table = null, $where = [null])
	{
		$exe = $this->db->get_where($table, $where);
		if ($exe->num_rows() > 0) {
			$exe = $exe->row_array();
		} else {
			$exe = ' ';
		}

		return $exe;
	}

	public function setActivityLog($modul = null, $by = null, $jenis_perubahan = null, $data_lama = null, $data_baru = null)
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


	public function getMenuTitle()
	{
		$this->load->helper('url');
		$currentURL = current_url();
		$base = base_url();
		$geturl = str_replace($base, '', $currentURL);
		$explode = explode('/', $geturl);
		$menu_url = count($explode) > 1 ? ($explode[0] . "/" . $explode[1]) : $explode[0];

		$this->db->select("a.menu_nama, a.menu_nama as nama");
		$this->db->from("menu a");
		// $this->db->join("term_management b", "b.id_menu = a.menu_id", "left");
		$this->db->where("a.menu_url", $menu_url);
		$return = $this->db->get()->row_array();
		$return = $return != null ? ($return['nama'] != null ? $return['nama'] : $return['menu_nama']) : '';
		return $return;
	}
}

/* End of file Render_Model.php */
/* Location: ./application/core/Render_Model.php */