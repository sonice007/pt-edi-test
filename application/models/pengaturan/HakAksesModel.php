<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HakAksesModel extends Render_Model
{


	public function getAllData($id = null)
	{
		$where 			= array();

		if ($id != null) {
			$where 		= array('a.rola_id' => $id);
		}

		$exe 			= $this->db->select(' a.*, b.*, c.menu_id as parent_id, c.menu_nama as parent, d.lev_nama ')
			->join('menu b', 'b.menu_id = a.rola_menu_id')
			->join('menu c', 'c.menu_id = b.menu_menu_id', 'left')
			->join('level d', 'd.lev_id = a.rola_lev_id')
			->get_where('role_aplikasi a', $where);

		return $exe->result_array();
	}


	public function getDataLevel()
	{
		$exe 			= $this->db->get('level');

		return $exe->result_array();
	}


	public function getDataParent()
	{
		$exe 			= $this->db->get_where('menu', ['menu_menu_id' => 0]);

		return $exe->result_array();
	}


	public function getDataChild($id)
	{
		$exe 			= $this->db->get_where('menu', ['menu_menu_id' => $id]);

		return $exe->result_array();
	}


	public function getIsiHABy($where = [null])
	{
		$this->db->select(' a.*, b.*, c.menu_id as parent_id, c.menu_nama as parent, d.lev_nama as level ');
		$this->db->join('menu b', 'b.menu_id = a.rola_menu_id');
		$this->db->join('menu c', 'c.menu_id = b.menu_menu_id', 'left');
		$this->db->join('level d', 'd.lev_id = a.rola_lev_id');
		$exe = $this->db->get_where('role_aplikasi a', $where);
		if ($exe->num_rows() > 0) {
			$exe = $exe->row_array();
		} else {
			$exe = ' ';
		}

		return $exe;
	}

	public function insert($level, $menu, $sub_menu = null)
	{
		$this->db->trans_start();
		if ($sub_menu != null) {
			$data['rola_menu_id'] 	= $sub_menu;
		} else {
			$data['rola_menu_id'] 	= $menu;
		}

		$data['rola_lev_id'] 		= $level;

		$execute 					= $this->db->insert('role_aplikasi', $data);
		$exe['id'] 					= $this->db->insert_id();
		$exe['level'] 				= $this->_cek('level', 'lev_id', $level, 'lev_nama');
		$exe['parent'] 				= $this->cekMenu($menu);
		$exe['child'] 				= $this->cekMenu($sub_menu);
		if ($execute) {
			$modul           = $this->getMenuTitle();
			$id              = $this->db->insert_id();
			$by              = $this->getLogId();
			$jenis_perubahan = 1;
			if ($sub_menu != null) {
				$nama_menu 	= $this->getIsiTableById('menu', ['menu_id' => $sub_menu])['menu_nama'];
			} else {
				$nama_menu 	= $this->getIsiTableById('menu', ['menu_id' => $menu])['menu_nama'];
			}
			$level = $this->getIsiTableById('level', ['lev_id' => $level])['lev_nama'];
			$data_lama       = "Belum ada data";
			$data_baru       = "Menambah data baru dengan isi menu = $nama_menu, level = $level";
			$this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
		}
		$this->db->trans_complete();

		return $exe;
	}


	public function update($id, $level, $menu, $sub_menu)
	{
		$this->db->trans_start();
		$sebelumnya = $this->getIsiHABy(['rola_id' => $id]);
		if ($sub_menu != null) {
			$data['rola_menu_id'] 	= $sub_menu;
		} else {
			$data['rola_menu_id'] 	= $menu;
		}

		$data['rola_lev_id'] 		= $level;

		$execute 					= $this->db->where('rola_id', $id);
		$execute 					= $this->db->update('role_aplikasi', $data);
		$exe['id'] 					= $id;
		$exe['level'] 				= $this->_cek('level', 'lev_id', $level, 'lev_nama');
		$exe['parent'] 				= $this->cekMenu($menu);
		$exe['child'] 				= $this->cekMenu($sub_menu);
		if ($execute) {
			$modul           = $this->getMenuTitle();
			$by              = $this->getLogId();
			$jenis_perubahan = 2;
			if ($sub_menu != null) {
				$nama_menu 	= $this->getIsiTableById('menu', ['menu_id' => $sub_menu])['menu_nama'];
			} else {
				$nama_menu 	= $this->getIsiTableById('menu', ['menu_id' => $menu])['menu_nama'];
			}
			$level = $this->getIsiTableById('level', ['lev_id' => $level])['lev_nama'];
			$data_lama       = "Isi data sebelumnya adalah menu = $sebelumnya[menu_nama], level = $sebelumnya[level]";
			$data_baru       = "Mengubah isi data sebelumnya menjadi menu = $nama_menu, level = $level";
			$this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
		}
		$this->db->trans_complete();

		return $exe;
	}


	public function delete($id)
	{
		$this->db->trans_start();
		$sebelumnya = $this->getIsiHABy(['rola_id' => $id]);
		$exe 						= $this->db->where('rola_id', $id);
		$exe 						= $this->db->delete('role_aplikasi');
		if ($exe) {
			$modul           = $this->getMenuTitle();
			$by              = $this->getLogId();
			$jenis_perubahan = 3;
			$data_lama       = "Isi data sebelumnya adalah menu = $sebelumnya[menu_nama], level = $sebelumnya[level]";
			$data_baru       = "Menghapus data lama";
			$this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
		}
		$this->db->trans_complete();

		return $exe;
	}
}

/* End of file HakAksesModel.php */
/* Location: ./application/models/pengaturan/HakAksesModel.php */