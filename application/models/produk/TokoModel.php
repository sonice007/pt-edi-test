<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TokoModel extends Render_Model
{
    public function getAllData($draw = null, $show = null, $start = null, $cari = null, $order = null)
    {
        $produk = <<<SQL
            (
                select count(*) from produk as p where p.id_toko = a.id
            ) 
        SQL;
        // Table select
        $this->db->select("a.*, 
            $produk as t_produk,
            IF(a.status = '0' , 'Non-aktif', IF(a.status = '1' , 'Aktif', 'Tidak diketahui')) as status_str");
        $this->db->from("toko a");
        $this->db->where("a.status <>", 99);

        // order by
        if ($order['order'] != null) {
            $columns = $order['columns'];
            $dir = $order['order'][0]['dir'];
            $order = $order['order'][0]['column'];
            $columns = $columns[$order];

            $order_colum = $columns['data'];
            $this->db->order_by($order_colum, $dir);
        }

        // $this->db->order_by('no_urut', 'asc');

        // initial data table
        if ($draw == 1) {
            $this->db->limit(10, 0);
        }

        // pencarian
        if ($cari != null) {
            $this->db->where("(
                a.nama LIKE '%$cari%' or 
                a.keterangan LIKE '%$cari%' or 
                IF(a.status = '0' , 'Non-aktif', IF(a.status = '1' , 'Aktif', 'Tidak diketahui')) LIKE '%$cari%'
            )");
        }

        // pagination
        if ($show != null && $start != null) {
            $this->db->limit($show, $start);
        }

        $result = $this->db->get();
        return $result;
    }


    public function find($id)
    {
        $result = $this->db->get_where("toko", ['id' => $id])->row_array();
        return $result;
    }

    public function getWhere($where = [null])
    {
        $this->db->select("a.*, IF(a.status = '0' , 'Non-aktif', IF(a.status = '1' , 'Aktif', 'Tidak diketahui')) as status_str");
        $this->db->from("toko a");
        $this->db->where($where);
        $exe = $this->db->get();
        if ($exe->num_rows() > 0) {
            $exe = $exe->row_array();
        } else {
            $exe = ' ';
        }

        return $exe;
    }


    public function insert($nama, $keterangan, $logo, $status)
    {
        $this->db->insert("toko", [
            'nama' => $nama,
            'keterangan' => $keterangan,
            'logo' => $logo,
            'status' => $status,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        $result = $this->db->insert_id();
        return $result;
    }


    public function update($id, $nama, $keterangan, $logo, $status)
    {
        $result = $this->db->where('id', $id)->update('toko', [
            'nama' => $nama,
            'keterangan' => $keterangan,
            'logo' => $logo,
            'status' => $status,
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        return $result;
    }


    public function delete($id)
    {
        $result = $this->db->where('id', $id)->update('toko', [
            'status' => '99',
            'updated_at' => date("Y-m-d H:i:s"),
            'deleted_at' => date("Y-m-d H:i:s")
        ]);
        return $result;
    }


    public function select2($key)
    {
        $result = $this->db->select('id, name as text')->from('toko')
            ->where("(
            name LIKE '%$key%' or 
            keterangan LIKE '%$key%'
            )")->where('status', 1)
            ->limit(10)
            ->get()->result_array();
        return $result;
    }

    public function getTokoKontakByID($id_toko=null, $where = ['a.status<>' => 99]){
        $result = $this->db->select('a.id, a.id_toko, a.id_kontak, a.keterangan, a.status, b.nama')->join('kontak b', 'b.id = a.id_kontak')->order_by('a.id')->get_where("toko_kontak a", array_merge(['a.id_toko' => $id_toko], $where))
            ->result_array();
        return $result;
    }

    public function getKontak($where = ['a.status<>' => 99]){
        $result = $this->db->get_where("kontak a", $where)
            ->result_array();
        return $result;
    }

     // answers
    public function toko_kontak_insert($id_toko, $id_kontak, $keterangan, $status)
    {
        $this->db->insert("toko_kontak", [
            'id_toko' => $id_toko,
            'id_kontak' => $id_kontak,
            'keterangan' => $keterangan,
            'status' => $status,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        $result = $this->db->insert_id();
        return $result;
    }


    public function toko_kontak_update($id, $id_toko, $id_kontak, $keterangan, $status)
    {
        $result = $this->db->where('id', $id)->update('toko_kontak', [
            'id_toko' => $id_toko,
            'id_kontak' => $id_kontak,
            'keterangan' => $keterangan,
            'status' => $status,
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        return $result;
    }


    public function toko_kontak_delete($id)
    {
        $result = $this->db->where('id', $id)->update('toko_kontak', [
            'status' => 99,
            'updated_at' => date("Y-m-d H:i:s"),
            'deleted_at' => date("Y-m-d H:i:s")
        ]);
        return $result;
    }
    

    // wilayah
    public function getTokoWilayahByID($id_toko=null, $where = ['a.status<>' => 99]){
        $result = $this->db->select('a.id, a.id_toko, a.id_wilayah, a.keterangan, a.status, b.nama')->join('wilayah b', 'b.id = a.id_wilayah')->order_by('a.id')->get_where("toko_wilayah a", array_merge(['a.id_toko' => $id_toko], $where))
            ->result_array();
        return $result;
    }

    public function getWilayah($where = ['a.status<>' => 99]){
        $result = $this->db->get_where("wilayah a", $where)
            ->result_array();
        return $result;
    }

     // answers
    public function toko_wilayah_insert($id_toko, $id_wilayah, $keterangan, $status)
    {
        $this->db->insert("toko_wilayah", [
            'id_toko' => $id_toko,
            'id_wilayah' => $id_wilayah,
            'keterangan' => $keterangan,
            'status' => $status,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        $result = $this->db->insert_id();
        return $result;
    }


    public function toko_wilayah_update($id, $id_toko, $id_wilayah, $keterangan, $status)
    {
        $result = $this->db->where('id', $id)->update('toko_wilayah', [
            'id_toko' => $id_toko,
            'id_wilayah' => $id_wilayah,
            'keterangan' => $keterangan,
            'status' => $status,
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        return $result;
    }


    public function toko_wilayah_delete($id)
    {
        $result = $this->db->where('id', $id)->update('toko_wilayah', [
            'status' => 99,
            'updated_at' => date("Y-m-d H:i:s"),
            'deleted_at' => date("Y-m-d H:i:s")
        ]);
        return $result;
    }
}
