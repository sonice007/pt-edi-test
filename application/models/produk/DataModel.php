<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DataModel extends Render_Model
{
    public function getAllData($draw = null, $show = null, $start = null, $cari = null, $order = null)
    {
        $categories = <<<SQL
            (
                SELECT GROUP_CONCAT(`nama` SEPARATOR ', ') FROM `kategori` as c_all 
                JOIN produk_kategori as cj ON c_all.id = cj.id_kategori 
                WHERE cj.id_produk = a.id
            ) 
        SQL;

        $views = <<<SQL
             ( select count(*) from produk_view as views where views.id_produk = a.id ) 
        SQL;

        $tags = <<<SQL
            (
                SELECT GROUP_CONCAT(`nama` SEPARATOR ', ') FROM `tags` as t_all 
                JOIN produk_tag as tj ON t_all.id = tj.id_tag 
                WHERE tj.id_produk = a.id
            ) 
        SQL;

        $liked = <<<SQL
            (
                select count(*) from produk_favorite as liked where liked.id_produk = a.id
            ) 
        SQL;

        $saved = <<<SQL
            (
                select count(*) from produk_saved as saved where saved.id_produk = a.id
            ) 
        SQL;

        $rating = <<<SQL
            (
                select ifnull((sum(rating.rating) / count(*)),0) from produk_rating as rating where rating.id_produk = a.id
            ) 
        SQL;
        // Table select
        $this->db->select(" 
            $categories as categories,
            $tags as tags,
            $liked as liked,
            $saved as saved,
            $views as view,
            $rating as rating,
            a.id,
            a.judul,
            a.slug,
            a.spesifikasi,
            a.deskripsi,
            a.status,
            a.created_at,
            a.updated_at,
            b.nama as toko,
            IF(a.status = '0' , 'Draft', IF(a.status = '1' , 'Publish', IF(a.status = '2' , 'Unpublish', IF(a.status = '99' , 'Dihapus', 'Tidak diketahui')))) as status_str"
        );
        $this->db->from("produk a");
        $this->db->join("toko b", "b.id = a.id_toko");
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
                a.judul LIKE '%$cari%' or 
                a.spesifikasi LIKE '%$cari%' or 
                a.deskripsi LIKE '%$cari%' or 
                $categories LIKE '%$cari%' or 
                $tags  LIKE '%$cari%' or 
                $rating  LIKE '%$cari%' or 
                $saved LIKE '%$cari%' or 
                $rating LIKE '%$cari%' or 
                IF(a.status = '0' , 'Draft', IF(a.status = '1' , 'Publish', IF(a.status = '2' , 'Unpublish', 'Tidak diketahui'))) LIKE '%$cari%'
            )");
        }

        // pagination
        if ($show != null && $start != null) {
            $this->db->limit($show, $start);
        }

        $result = $this->db->get();
        return $result;
    }

    public function list_title($key, $id)
    {
        $result = $this->db->select('id, slug, judul')
            ->from('produk')
            ->where("(
                judul like '%$key%' or
                slug like '%$key%'
            )")
            ->where("status <>", 99)
            ->where("status <>", 98)
            ->where("id <>", $id)
            ->limit(5)
            ->get()->result_array();
        return $result;
    }

    public function list_toko($key, $id)
    {
        $result = $this->db->select('id, nama')
            ->from('toko')
            ->where("(
                nama like '%$key%'
            )")
            ->where("status <>", 99)
            ->where("status <>", 98)
            ->where("id <>", $id)
            ->limit(5)
            ->get()->result_array();
        return $result;
    }

    public function insert($id_toko, $judul, $slug, $spesifikasi, $deskripsi, $status)
    {
        $this->db->insert("produk", [
            'id_toko'       => $id_toko,
            'judul'         => $judul,
            'slug'          => $slug,
            'spesifikasi'   => $spesifikasi,
            'deskripsi'     => $deskripsi,
            'status'        => $status
        ]);
        $result = $this->db->insert_id();
        return $result;
    }

    public function update($id, $id_toko, $judul, $slug, $spesifikasi, $deskripsi, $status)
    {
        $result = $this->db->where('id', $id)->update('produk', [
            'id_toko'       => $id_toko,
            'judul'         => $judul,
            'slug'          => $slug,
            'spesifikasi'   => $spesifikasi,
            'deskripsi'     => $deskripsi,
            'status'        => $status,
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        return $result;
    }

    public function delete($id)
    {
        $result = $this->db->where('id', $id)->update('produk', [
            'status' => '99',
            'updated_at' => date("Y-m-d H:i:s"),
            'deleted_at' => date("Y-m-d H:i:s")
        ]);
        return $result;
    }


    public function insertTag($id_produk, $id_tag)
    {
        $this->db->insert("produk_tag", [
            'id_produk' => $id_produk,
            'id_tag' => $id_tag
        ]);
        $result = $this->db->insert_id();
        return $result;
    }

    public function insertCategory($id_produk, $id_kategori)
    {
        $this->db->insert("produk_kategori", [
            'id_produk' => $id_produk,
            'id_kategori' => $id_kategori
        ]);
        $result = $this->db->insert_id();
        return $result;
    }

    public function removeAllTag($id)
    {
        $result = $this->db->where('id_produk', $id)->delete('produk_tag');
        return $result;
    }

    public function removeAllCategory($id)
    {
        $result = $this->db->where('id_produk', $id)->delete('produk_kategori');
        return $result;
    }

    // produk gambar
    public function getProdukGambar($draw = null, $show = null, $start = null, $cari = null, $order = null, $filter)
    {
       
        // Table select
        $this->db->select(" 
            a.id,
            a.gambar,
            a.cover,
            a.status,
            a.created_at,
            a.updated_at,
            IF(a.status = '0' , 'Non-aktif', IF(a.status = '1' , 'Aktif', IF(a.status = '99' , 'Dihapus', 'Tidak diketahui'))) as status_str"
        );
        $this->db->from("produk_gambar a");
        $this->db->join("produk b", "b.id = a.id_produk");
        $this->db->where("a.status <>", 99);

        $this->db->where('a.id_produk', $filter['id_produk']);

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
                a.gambar LIKE '%$cari%' or 
                a.cover LIKE '%$cari%' or 
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

    public function insert_gambar($id_produk, $gambar, $cover, $status)
    {
        $this->db->insert("produk_gambar", [
            'id_produk' => $id_produk,
            'gambar' => $gambar,
            'cover' => $cover,
            'status' => $status,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        $result = $this->db->insert_id();
        return $result;
    }

    public function update_gambar($id, $id_produk, $gambar, $cover, $status)
    {
        $result = $this->db->where('id', $id)->update('produk_gambar', [
            'id_produk' => $id_produk,
            'gambar' => $gambar,
            'cover' => $cover,
            'status' => $status,
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        return $result;
    }

    public function delete_gambar($id)
    {
        $result = $this->db->where('id', $id)->update('produk_gambar', [
            'status' => '99',
            'updated_at' => date("Y-m-d H:i:s"),
            'deleted_at' => date("Y-m-d H:i:s")
        ]);
        return $result;
    }

    public function find_gambar($id)
    {
        $result = $this->db->get_where("produk_gambar", ['id' => $id])->row_array();
        return $result;
    }
    // end produk gambar

    // produk varian
    public function getProdukVarian($draw = null, $show = null, $start = null, $cari = null, $order = null, $filter)
    {   
        $stok = <<<SQL
        (
            select ifnull(sum(varian_stok.stok),0) from produk_varian_ukuran as varian_stok where varian_stok.id_produk_varian = a.id
        )
        SQL;
        $ukuran = <<<SQL
             ( select count(*) from produk_varian_ukuran as ukuran where ukuran.id_produk_varian = a.id ) 
        SQL;
        // Table select
        $this->db->select(" 
            $stok as t_stok,
            $ukuran as t_ukuran,
            a.id,
            a.varian,
            a.status,
            a.created_at,
            a.updated_at,
            IF(a.status = '0' , 'Non-aktif', IF(a.status = '1' , 'Aktif', IF(a.status = '99' , 'Dihapus', 'Tidak diketahui'))) as status_str"
        );
        $this->db->from("produk_varian a");
        $this->db->join("produk b", "b.id = a.id_produk");
        $this->db->where("a.status <>", 99);

        $this->db->where('a.id_produk', $filter['id_produk']);

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
                $stok LIKE '%$cari%' or 
                $ukuran LIKE '%$cari%' or 
                a.varian LIKE '%$cari%' or 
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

    public function insert_varian($id_produk, $varian, $status)
    {
        $this->db->insert("produk_varian", [
            'id_produk' => $id_produk,
            'varian' => $varian,
            'status' => $status,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        $result = $this->db->insert_id();
        return $result;
    }

    public function update_varian($id, $id_produk, $varian, $status)
    {
        $result = $this->db->where('id', $id)->update('produk_varian', [
            'id_produk' => $id_produk,
            'varian' => $varian,
            'status' => $status,
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        return $result;
    }

    public function delete_varian($id)
    {
        $result = $this->db->where('id', $id)->update('produk_varian', [
            'status' => '99',
            'updated_at' => date("Y-m-d H:i:s"),
            'deleted_at' => date("Y-m-d H:i:s")
        ]);
        return $result;
    }

    public function find_varian($id)
    {
        $result = $this->db->get_where("produk_varian", ['id' => $id])->row_array();
        return $result;
    }
    // end produk varian

    // produk varian ukuran
    public function getAllProdukVarianUkuran($id_produk_varian, $where = ['status<>' => 99])
    {
        $result = $this->db->get_where("produk_varian_ukuran", array_merge(['id_produk_varian' => $id_produk_varian], $where))
            ->result_array();
        return $result;
    }
    
    public function ukuran_insert($id_produk_varian, $ukuran, $berat, $stok, $status)
    {
        $this->db->insert("produk_varian_ukuran", [
            'id_produk_varian' => $id_produk_varian,
            'ukuran' => $ukuran,
            'berat' => $berat,
            'stok' => $stok,
            'status' => $status,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        $result = $this->db->insert_id();
        return $result;
    }


    public function ukuran_update($id, $id_produk_varian, $ukuran, $berat, $stok, $status)
    {
        $result = $this->db->where('id', $id)->update('produk_varian_ukuran', [
            'id_produk_varian' => $id_produk_varian,
            'ukuran' => $ukuran,
            'berat' => $berat,
            'stok' => $stok,
            'status' => $status,
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        return $result;
    }


    public function ukuran_delete($id)
    {
        $result = $this->db->where('id', $id)->update('produk_varian_ukuran', [
            'status' => 99,
            'updated_at' => date("Y-m-d H:i:s"),
            'deleted_at' => date("Y-m-d H:i:s")
        ]);
        return $result;
    }


    public function ukuran_find($id)
    {
        $result = $this->db->get_where("produk_varian_ukuran", ['id' => $id, 'deleted_at<>' => null])->row_array();
        return $result;
    }
    // end produk varian ukuran
}
