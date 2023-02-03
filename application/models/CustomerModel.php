<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CustomerModel extends Render_Model
{
    public function getAllData($draw = null, $show = null, $start = null, $cari = null, $order = null)
    {
        // Table select
        $this->db->select("a.*, IF(a.status = '0' , 'Non-aktif', IF(a.status = '1' , 'Aktif', 'Tidak diketahui')) as status_str");
        $this->db->from("customer a");
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
                a.email LIKE '%$cari%' or 
                a.nama LIKE '%$cari%' or 
                a.no_telpon LIKE '%$cari%' or 
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
        $result = $this->db->get_where("customer", ['id' => $id])->row_array();
        return $result;
    }

    public function select2($key)
    {
        $result = $this->db->select('id, name as text')->from('customer')
            ->where("(
            name LIKE '%$key%' or 
            keterangan LIKE '%$key%'
            )")->where('status', 1)
            ->limit(10)
            ->get()->result_array();
        return $result;
    }
}
