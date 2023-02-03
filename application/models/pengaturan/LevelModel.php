<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LevelModel extends Render_Model
{


	public function getAllData()
	{
		$exe 						= $this->db->get('level');

		return $exe->result_array();
	}


	public function getDataDetail($id)
	{
		$exe 						= $this->db->get_where('level', ['lev_id' => $id]);

		return $exe->row_array();
	}


	public function getIsiLVBy($where = [null])
	{
		$exe = $this->db->get_where('level a', $where);
		if ($exe->num_rows() > 0) {
			$exe = $exe->row_array();
		} else {
			$exe = ' ';
		}

		return $exe;
	}

	public function insert($nama, $keterangan, $status)
	{
		$data['lev_nama'] 			= $nama;
		$data['lev_keterangan'] 	= $keterangan;
		$data['lev_status'] 		= $status;

		$exe 						= $this->db->insert('level', $data);
		$exe1 						= $this->db->insert_id();
		if ($exe) {
			$modul           = $this->getMenuTitle();
			$by              = $this->getLogId();
			$jenis_perubahan = 1;
			$data_lama       = "Belum ada data";
			$data_baru       = "Menambah data baru dengan isi nama = $nama, keterangan = $keterangan, status = $status";
			$this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
		}
		$this->db->trans_complete();

		return $exe1;
	}


	public function update($id, $nama, $keterangan, $status)
	{
		$this->db->trans_start();
		$sebelumnya = $this->getIsiLVBy(['lev_id' => $id]);
		$data['lev_nama'] 			= $nama;
		$data['lev_keterangan'] 	= $keterangan;
		$data['lev_status'] 		= $status;

		$exe 						= $this->db->where('lev_id', $id);
		$exe1 						= $this->db->update('level', $data);
		if ($exe) {
			$modul           = $this->getMenuTitle();
			$by              = $this->getLogId();
			$jenis_perubahan = 2;
			$data_lama       = "Isi data sebelumnya adalah nama = $sebelumnya[lev_nama], keterangan = $sebelumnya[lev_keterangan], status = $sebelumnya[lev_status]";
			$data_baru       = "Mengubah isi data sebelumnya menjadi nama = $nama, keterangan = $keterangan, status = $status";
			$this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
		}
		$this->db->trans_complete();

		return $exe1;
	}


	public function delete($id)
	{
		$this->db->trans_start();
		$sebelumnya = $this->getIsiLVBy(['lev_id' => $id]);
		$exe 						= $this->db->where('lev_id', $id);
		$exe1 						= $this->db->delete('level');
		if ($exe) {
			$modul           = $this->getMenuTitle();
			$by              = $this->getLogId();
			$jenis_perubahan = 3;
			$data_lama       = "Isi data sebelumnya adalah nama = $sebelumnya[lev_nama], keterangan = $sebelumnya[lev_keterangan], status = $sebelumnya[lev_status]";
			$data_baru       = "Menghapus data lama";
			$this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
		}
		$this->db->trans_complete();

		return $exe1;
	}
}

/* End of file LevelModel.php */
/* Location: ./application/models/pengaturan/LevelModel.php */