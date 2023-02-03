<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PenggunaModel extends Render_Model
{


	public function getAllData()
	{
		$exe = $this->db->select('*')
			->from('users a')
			->join('role_users b', 'a.user_id = b.role_user_id', 'left')
			->join('level c', 'c.lev_id = b.role_lev_id', 'left')
			->where('a.user_status <>', 3)
			->get();

		return $exe->result_array();
	}


	public function getDataDetail($id)
	{
		$exe 						= $this->db->select('*')
			->join('users b', 'b.user_id = a.role_user_id', 'left')
			->where('a.role_user_id', $id)
			->get('role_users a');

		return $exe->row_array();
	}


	public function getDataLevel()
	{
		$exe 						= $this->db->get('level');

		return $exe->result_array();
	}


	public function getIsiUsersBy($where = [null])
	{
		$this->db->select("
            a.*,
            IF(a.user_status = '0' , 'Tidak Aktif', IF(a.user_status = '1' , 'Aktif', IF(a.user_status = '2' , 'Pending', 'Tidak Diketahui'))) as status_str
            ");
		$this->db->from("users a");
		$this->db->where($where);
		$exe = $this->db->get();
		if ($exe->num_rows() > 0) {
			$exe = $exe->row_array();
		} else {
			$exe = ' ';
		}

		return $exe;
	}


	public function getIsiRoleBy($where = [null])
	{
		$this->db->select("
            a.*,
            b.lev_nama as level
            ");
		$this->db->from("role_users a");
		$this->db->join("level b", "a.role_lev_id = b.lev_id", "left");
		$this->db->where($where);
		$exe = $this->db->get();
		if ($exe->num_rows() > 0) {
			$exe = $exe->row_array();
		} else {
			$exe = ' ';
		}

		return $exe;
	}

	public function insert($level, $nama, $telepon, $username, $password, $status)
	{
		$this->db->trans_start();
		$data['user_nama'] 			= $nama;
		$data['user_email'] 		= $username;
		$data['user_password'] 		= $this->b_password->bcrypt_hash($password);
		$data['user_phone'] 		= $telepon;
		$data['user_status'] 		= $status;

		$data['user_email_status'] 	= 1;

		// Insert users
		$execute 					= $this->db->insert('users', $data);
		$execute1 					= $this->db->insert_id();

		$data2['role_user_id'] 		= $execute1;
		$data2['role_lev_id'] 		= $level;

		// Insert role users
		$execute2 					= $this->db->insert('role_users', $data2);
		$exe['id'] 					= $execute1;
		$exe['level'] 				= $this->_cek('level', 'lev_id', $level, 'lev_nama');
		if ($execute && $execute2) {
			$modul           = $this->getMenuTitle();
			$role_id         = $this->db->insert_id();
			$by              = $this->getLogId();
			$jenis_perubahan = 1;
			$level = $this->getIsiTableById('level', ['lev_id' => $level])['lev_nama'];
			$status = ($status == 0 ? $status = 'Tidak Aktif' : ($status == 1 ? $status = 'Aktif' : ($status == 2 ? $status = 'Pending' : 'Tidak Diketahui')));
			$data_lama       = "Belum ada data";
			$data_baru       = "Menambah data baru dengan isi level = $level, user_nama = $nama, user_email = $username, user_phone = $telepon, user_status = $status";
			$this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
		}
		$this->db->trans_complete();


		return $exe;
	}


	public function update($id, $level, $nama, $telepon, $username, $password, $status)
	{
		$this->db->trans_start();
		$susers = $this->getIsiUsersBy(['user_id' => $id]);
		$srole = $this->getIsiRoleBy(['role_user_id' => $id]);
		$data['user_nama'] 			= $nama;
		$data['user_email'] 		= $username;
		$data['user_phone'] 		= $telepon;
		$data['user_status'] 		= $status;
		$data['user_email_status'] 	= 1;
		$data['updated_at'] 		= Date("Y-m-d H:i:s", time());
		if ($password != '') {
			$data['user_password'] 		= $this->b_password->bcrypt_hash($password);
		}

		// Update users
		$execute 					= $this->db->where('user_id', $id);
		$execute 					= $this->db->update('users', $data);

		$data2['role_user_id'] 		= $id;
		$data2['role_lev_id'] 		= $level;

		// Update role users
		$execute2 					= $this->db->where('role_user_id', $id);
		$execute2 				 	= $this->db->update('role_users', $data2);
		$exe['id'] 					= $id;
		$exe['level'] 				= $this->_cek('level', 'lev_id', $level, 'lev_nama');
		if ($execute && $execute2) {
			$modul           = $this->getMenuTitle();
			$by              = $this->getLogId();
			$jenis_perubahan = 2;
			$level = $this->getIsiTableById('level', ['lev_id' => $level])['lev_nama'];
			$status = ($status == 0 ? $status = 'Tidak Aktif' : ($status == 1 ? $status = 'Aktif' : ($status == 2 ? $status = 'Pending' : 'Tidak Diketahui')));
			$data_lama       = "Isi data sebelumnya adalah level = $srole[level], user_nama = $susers[user_nama], user_email = $susers[user_email], user_phone = $susers[user_phone], user_status = $susers[status_str]";
			$data_baru       = "Mengubah isi data sebelumnya menjadi level = $level, user_nama = $nama, user_email = $username, user_phone = $telepon, user_status = $status";
			$this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
		}
		$this->db->trans_complete();


		return $exe;
	}


	public function delete($id)
	{
		$this->db->trans_start();
		$susers = $this->getIsiUsersBy(['user_id' => $id]);
		$srole = $this->getIsiRoleBy(['role_user_id' => $id]);
		// Delete users
		$exe 						= $this->db->where('user_id', $id);
		$exe 						= $this->db->delete('users');

		// Delete role users
		$exe2 						= $this->db->where('role_user_id', $id);
		$exe2 						= $this->db->delete('role_users');
		if ($exe && $exe2) {
			$modul           = $this->getMenuTitle();
			$by              = $this->getLogId();
			$jenis_perubahan = 3;
			$data_lama       = "Isi data sebelumnya adalah level = $srole[level], user_nama = $susers[user_nama], user_email = $susers[user_email], user_phone = $susers[user_phone], user_status = $susers[status_str]";
			$data_baru       = "Menghapus data lama";
			$this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
		}
		$this->db->trans_complete();

		return $exe;
	}
}

/* End of file PenggunaModel.php */
/* Location: ./application/models/pengaturan/PenggunaModel.php */