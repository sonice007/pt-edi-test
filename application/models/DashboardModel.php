<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DashboardModel extends Render_Model
{

    public function getJumlahCompany(): int
    {
        $return = $this->db
            ->query('SELECT count(*) as jumlah FROM client_company WHERE status = 1')
            ->row_array();
        return $return['jumlah'];
    }

    public function getJumlahResiko(): int
    {
        $return = $this->db
            ->query('SELECT count(*) as jumlah FROM resiko WHERE status = 1')
            ->row_array();
        return $return['jumlah'];
    }

    public function getJumlahMembership(): int
    {
        $return = $this->db
            ->query('SELECT count(*) as jumlah FROM jenis_membership WHERE status = 1')
            ->row_array();
        return $return['jumlah'];
    }

    public function getJumlahPeristiwa(): int
    {
        $return = $this->db
            ->query('SELECT count(*) as jumlah FROM dtm_peristiwa WHERE status = 1')
            ->row_array();
        return $return['jumlah'];
    }

    public function getJumlahPatner($tanggal = null): int
    {
        if ($tanggal != null) {
            $tanggal_awal = date("Y-m-d", strtotime($tanggal[0]));
            $tanggal_akhir = date("Y-m-d", strtotime($tanggal[1]));
            $return = $this->db
                ->query('SELECT count(*) as jumlah FROM partner WHERE status = 1 and created_at >= "' . $tanggal_awal . '" and created_at <= "' . $tanggal_akhir . '" ')
                ->row_array();
        } else {
            $return = $this->db
                ->query('SELECT count(*) as jumlah FROM partner WHERE status = 1')
                ->row_array();
        }
        return $return['jumlah'];
    }

    public function getJumlahPengguna($tanggal = null): int
    {
        if ($tanggal != null) {
            $tanggal_awal = date("Y-m-d", strtotime($tanggal[0]));
            $tanggal_akhir = date("Y-m-d", strtotime($tanggal[1]));
            $return = $this->db
                ->query('SELECT count(*) as jumlah FROM profile WHERE status = 1 and kode != "" and created_at >= "' . $tanggal_awal . '" and created_at <= "' . $tanggal_akhir . '" ')
                ->row_array();
        } else {
            $return = $this->db
                ->query('SELECT count(*) as jumlah FROM profile WHERE status = 1 and kode != "" ')
                ->row_array();
        }
        return $return['jumlah'];
    }

    public function getJumlahPenggunaLahir($tanggal = null): int
    {
        if ($tanggal != null) {
            $tanggal_awal = date("Y-m-d", strtotime($tanggal[0]));
            $tanggal_akhir = date("Y-m-d", strtotime($tanggal[1]));
            $return = $this->db
                ->query('SELECT count(*) as jumlah FROM profile WHERE status = 1 and kode != "" and tanggal_lahir is not null and tanggal_lahir >= "' . $tanggal_awal . '" and tanggal_lahir <= "' . $tanggal_akhir . '" ')
                ->row_array();
        } else {
            $return = $this->db
                ->query('SELECT count(*) as jumlah FROM profile WHERE status = 1 and kode != "" and tanggal_lahir is not null ')
                ->row_array();
        }
        return $return['jumlah'];
    }

    public function getJumlahPenggunaMeninggal($tanggal = null): int
    {
        if ($tanggal != null) {
            $tanggal_awal = date("Y-m-d", strtotime($tanggal[0]));
            $tanggal_akhir = date("Y-m-d", strtotime($tanggal[1]));
            $return = $this->db
                ->query('SELECT count(*) as jumlah FROM profile WHERE status = 1 and kode != "" and tanggal_meninggal != "0000-00-00" and tanggal_meninggal >= "' . $tanggal_awal . '" and tanggal_meninggal <= "' . $tanggal_akhir . '" ')
                ->row_array();
        } else {
            $return = $this->db
                ->query('SELECT count(*) as jumlah FROM profile WHERE status = 1 and kode != "" and tanggal_meninggal != "0000-00-00" ')
                ->row_array();
        }
        return $return['jumlah'];
    }

    public function getJumlahPenggunaUlangTahun($tanggal = null): int
    {
        $bulan = date("m");
        $return = $this->db
            ->query('SELECT count(*) as jumlah FROM profile WHERE status = 1 and kode != "" and DATE_FORMAT(tanggal_lahir, "%m") = "' . $bulan . '"  ')
            ->row_array();
        return $return['jumlah'];
    }

    public function getJumlahPenggunaWilayah($tanggal = null)
    {
        if ($tanggal != null) {
            $tanggal_awal = date("Y-m-d", strtotime($tanggal[0]));
            $tanggal_akhir = date("Y-m-d", strtotime($tanggal[1]));
            $return = $this->db->select('count(*) as jumlah')->select('b.nama as wilayah')->group_by('a.id_wilayah')->join('wilayah b', 'b.id = a.id_wilayah')->where('a.created_at >= "' . $tanggal_awal . '" and a.created_at <= "' . $tanggal_akhir . '" ')->get('profile a')->result_array();
        } else {
            $return = $this->db->select('count(*) as jumlah')->select('b.nama as wilayah')->group_by('a.id_wilayah')->join('wilayah b', 'b.id = a.id_wilayah')->get('profile a')->result_array();
        }
        return $return;
    }

    public function getKategoriKeanggotaan()
    {
        $return = $this->db->get_where('kategori_keanggotaan', ['status' => 1])->result_array();
        return $return;
    }

    public function getWilayah()
    {
        $return = $this->db->get_where('wilayah', ['status' => 1])->result_array();
        return $return;
    }

    public function getJabatan()
    {
        $return = $this->db->get_where('jabatan', ['status' => 1])->result_array();
        return $return;
    }

    public function getKategorial()
    {
        $return = $this->db->get_where('kategorial', ['status' => 1])->result_array();
        return $return;
    }

    public function getDataFormal()
    {
        $return = $this->db->get_where('dtm_peristiwa', ['status' => 1])->result_array();
        return $return;
    }

    public function getTotalStatistikWilayah()
    {
        $return = $this->db->join('profile b', 'b.id = a.id_profile')->get_where('profile_wilayah a', ['a.status' => 1, 'b.status' => 1])->num_rows();
        return $return;
    }

    public function getTotalStatistikKeanggotaan()
    {
        $return = $this->db->join('profile b', 'b.id = a.id_profile')->get_where('profile_keanggotaan a', ['a.status' => 2, 'b.status' => 1])->num_rows();
        return $return;
    }

    public function getTotalStatistikKeaktifan()
    {
        $return = $this->db->join('profile b', 'b.id = a.id_profile')->get_where('profile_keaktifan a', ['a.status' => 1, 'b.status' => 1])->num_rows();
        return $return;
    }

    public function getTotalStatistikKategorial()
    {
        $return = $this->db->join('profile b', 'b.id = a.id_profile')->get_where('profile_kategorial a', ['a.status' => 1, 'b.status' => 1])->num_rows();
        return $return;
    }

    public function getTotalStatistikDataFormal()
    {
        $return = $this->db->join('profile b', 'b.id = a.id_profile')->get_where('data_formal a', ['b.status' => 1])->num_rows();
        return $return;
    }
    public function getJumlahPenggunaKeanggotaan($tanggal = null)
    {
        if ($tanggal != null) {
            $tanggal_awal = date("Y-m-d", strtotime($tanggal[0]));
            $tanggal_akhir = date("Y-m-d", strtotime($tanggal[1]));
            $return = $this->db->select('count(*) as jumlah')
                ->select('c.keterangan as keanggotaan')
                ->group_by('c.id')->join('profile_keanggotaan b', 'b.id_profile = a.id')
                ->join('kategori_keanggotaan c', 'c.id = b.id_kategori_keanggotaan')
                ->where('a.created_at >= "' . $tanggal_awal . '" and a.created_at <= "' . $tanggal_akhir . '" ')
                ->where('b.status', 1)
                ->get('profile a')->result_array();
        } else {

            $return = $this->db->select('count(*) as jumlah')
                ->select('c.keterangan as keanggotaan')
                ->group_by('c.id')
                ->join('profile_keanggotaan b', 'b.id_profile = a.id')
                ->join('kategori_keanggotaan c', 'c.id = b.id_kategori_keanggotaan')
                ->where('b.status', 2)
                ->get('profile a')
                ->result_array();
        }
        return $return;
    }

    public function getJumlahPenggunaKeanggotaanTetap($tanggal = null): int
    {
        if ($tanggal != null) {
            $tanggal_awal = date("Y-m-d", strtotime($tanggal[0]));
            $tanggal_akhir = date("Y-m-d", strtotime($tanggal[1]));
            $return = $this->db
                ->query('SELECT count(*) as jumlah FROM profile WHERE status = 1 and id_keanggotaan = 1 and created_at >= "' . $tanggal_awal . '" and created_at <= "' . $tanggal_akhir . '" ')
                ->row_array();
        } else {
            $return = $this->db
                ->query('SELECT count(*) as jumlah FROM profile WHERE status = 1 and id_keanggotaan = 1')
                ->row_array();
        }
        return $return['jumlah'];
    }

    public function getJumlahPenggunaKeanggotaanKontrak($tanggal = null): int
    {
        if ($tanggal != null) {
            $tanggal_awal = date("Y-m-d", strtotime($tanggal[0]));
            $tanggal_akhir = date("Y-m-d", strtotime($tanggal[1]));
            $return = $this->db
                ->query('SELECT count(*) as jumlah FROM profile WHERE status = 1 and id_keanggotaan = 2 and created_at >= "' . $tanggal_awal . '" and created_at <= "' . $tanggal_akhir . '" ')
                ->row_array();
        } else {
            $return = $this->db
                ->query('SELECT count(*) as jumlah FROM profile WHERE status = 1 and id_keanggotaan = 2')
                ->row_array();
        }
        return $return['jumlah'];
    }

    public function getJumlahSPM(): int
    {
        $return = $this->db
            ->query('SELECT count(*) as jumlah FROM spm WHERE status = 1')
            ->row_array();
        return $return['jumlah'];
    }

    public function getJumlahRepository(): int
    {
        $return = $this->db
            ->query('SELECT count(*) as jumlah FROM repository WHERE status = 1')
            ->row_array();
        return $return['jumlah'];
    }

    public function getJumlahActivity(): int
    {
        $return = $this->db
            ->query('SELECT count(*) as jumlah FROM activity WHERE status = 1')
            ->row_array();
        return $return['jumlah'];
    }

    public function getJumlahP2PK(): int
    {
        $return = $this->db
            ->query('SELECT count(*) as jumlah FROM p2pk where status != 3')
            ->row_array();
        return $return['jumlah'];
    }

    public function getJumlahP2PKProgres(): int
    {
        $return = $this->db
            ->query('SELECT count(*) as jumlah FROM p2pk where status = 1')
            ->row_array();
        return $return['jumlah'];
    }

    public function getJumlahP2PKSelesai(): int
    {
        $return = $this->db
            ->query('SELECT count(*) as jumlah FROM p2pk where status = 2')
            ->row_array();
        return $return['jumlah'];
    }

    public function getJumlahP2PKStatus($status): int
    {
        $return = $this->db
            ->query('SELECT count(*) as jumlah FROM p2pk where status_proses = ' . $status . ' and status != 3')
            ->row_array();
        return $return['jumlah'];
    }

    public function getJumlahP2PKStatus2($status): int
    {
        // $return = $this->db
        //     ->query('SELECT count(*) as jumlah FROM p2pk where status_proses = '.$status)
        //     ->row_array();
        // $status = $status+1;
        $return2 = $this->db
            ->query('SELECT count(*) as jumlah FROM p2pk where status_proses = ' . $status . ' and status != 3')
            ->row_array();
        return $return2['jumlah'];
    }

    public function api_getStokDibawa($id_sales): array
    {
        $return = $this->db
            ->select_sum('a.jumlah')
            ->from('stok_keluar_detail a')
            ->join('stok_keluar b', 'b.id = a.id_stok_keluar')
            ->where('b.status', 1)
            ->where('b.id_sales', $id_sales)
            ->get()->row_array();
        $return = [
            'karton' => (int)($return['jumlah'] ?? 0),
            'renceng' => 0
        ];
        return $return;
    }

    public function api_getTerjual($id_sales): array
    {
        $return = $this->db
            ->select_sum('a.jumlah_karton')
            ->select_sum('a.jumlah_renceng')
            ->from('warung_sales_penjualan a')
            ->join('stok_keluar b', 'b.id = a.id_stok_keluar')
            ->where('b.status', 1)
            ->where('b.id_sales', $id_sales)
            ->get()->row_array();
        $return = [
            'karton' => (int)($return['jumlah_karton'] ?? 0),
            'renceng' => (int)($return['jumlah_renceng'] ?? 0),
        ];
        return $return;
    }

    public function api_sisaPenjualan($stok, $terjual): array
    {
        $total_stok_renceng = $stok['karton'] * 12;
        $total_terjual_renceng = ($terjual['karton'] * 12) + $terjual['renceng'];
        $sisa_renceng = $total_stok_renceng - $total_terjual_renceng;
        $karton = floor($sisa_renceng / 12);
        $renceng = $sisa_renceng % 12;
        $return = [
            'karton' => $karton,
            'renceng' => $renceng
        ];
        return $return;
    }

    public function api_warung($id_sales, $id = null, $cari = null): ?array
    {
        $this->db->select("a.id,
            CONCAT(a.nama_pemilik, ' ', '[', a.kode, ']') as nama,
            a.alamat");
        $this->db->from('warung a');
        $this->db->where('a.id_sales', $id_sales);
        if ($cari != null) {
            $this->db->where("(
            a.kode LIKE '%$cari%' or
            a.nama_pemilik LIKE '%$cari%' or
            a.alamat LIKE '%$cari%' or
            a.no_hp LIKE '%$cari%' or
            a.patokan LIKE '%$cari%'
            )");
        }
        $this->db->limit(20);

        if ($id == null) {
            $db = $this->db->get();
            $length = $db->num_rows();
            $return = $db->result_array();
        } else {
            $this->db->where('a.id', $id);
            $db = $this->db->get();
            $length = $db->num_rows();
            $return = $db->row_array();
        }

        if ($return != null) {
            $stok_keluar = $this->db->select('id as id_stok_keluar')
                ->from('stok_keluar')
                ->where('status', 1)
                ->limit(1)
                ->get()
                ->row_array() ?? ['id_stok_keluar' => null];
            if ($id == null) {
                $rows = [];
                foreach ($return as $row) {
                    $rows[] = array_merge($row, [
                        'id_sales' => $id_sales,
                        'id_stok_keluar' => $stok_keluar['id_stok_keluar'],
                    ]);
                }
                $return = $rows;
            } else {
                $return = array_merge($return, [
                    'id_sales' => $id_sales,
                    'id_stok_keluar' => $stok_keluar['id_stok_keluar'],
                ]);
            }
        }

        $return = ['data' => $return, 'length' => $length];
        return $return;
    }

    public function api_listSatuanHarga($id, $cari): ?array
    {
        $this->db->select('id, nama as text, harga, qty, id_produk');
        $this->db->from('satuan_harga');
        $this->db->where('status', 1);
        if ($id != null) {
            $this->db->where('id', $id);
        }

        if ($cari != null) {
            $this->db->where("(
            id LIKE '%$cari%' or
            nama LIKE '%$cari%' or
            harga LIKE '%$cari%' or
            qty LIKE '%$cari%'
            )");
        }

        $result = $this->db->get();
        $length = $result->num_rows();
        $data = $result->result_array();
        $return = ['data' => $data, 'length' => $length];
        return $return;
    }

    public function api_listProduk($id, $cari): ?array
    {
        $this->db->select('id, nama as text');
        $this->db->from('produk');
        $this->db->where('status', 1);
        if ($id != null) {
            $this->db->where('id', $id);
        }

        if ($cari != null) {
            $this->db->where("(
            id LIKE '%$cari%' or
            nama LIKE '%$cari%'
            )");
        }

        $result = $this->db->get();
        $length = $result->num_rows();
        $data = $result->result_array();
        $return = ['data' => $data, 'length' => $length];
        return $return;
    }


    public function api_insertSalesPenjualan(
        $id_stok_keluar,
        $qty,
        $id_produk,
        $id_satuan_harga,
        $id_warung,
        $harga,
        $total_harga,
        $dibayar,
        $sisa,
        $status,
        $id_sales
    ) {
        $karton = floor($qty / 12);
        $renceng = $qty % 12;
        $data = [
            'id_stok_keluar' => $id_stok_keluar,
            'id_produk' => $id_produk,
            'id_satuan_harga' => $id_satuan_harga,
            'id_warung' => $id_warung,
            'harga' => $harga,
            'total_harga' => $total_harga,
            'dibayar' => $dibayar,
            'sisa' => $sisa,
            'status' => $status,
            'jumlah_karton' => $karton,
            'jumlah_renceng' => $renceng,
            'id_sales' => $id_sales,
        ];
        $this->db->insert('warung_sales_penjualan', $data);
        $return = $this->db->insert_id();
        return $return;
    }

    public function api_warung_detail($id_sales, $id = null): ?array
    {
        $this->db->select("a.kode, a.nama_pemilik, a.nama_warung, a.alamat");
        $this->db->from('warung a');
        $this->db->where('a.id_sales', $id_sales);

        if ($id == null) {
            $db = $this->db->get();
            $length = $db->num_rows();
            $return = $db->result_array();
        } else {
            $this->db->where('a.id', $id);
            $db = $this->db->get();
            $length = $db->num_rows();
            $return = $db->row_array();
        }


        $return = ['data' => $return, 'length' => $length];
        return $return;
    }

    public function getReportKeluarga($draw = null, $show = null, $start = null, $cari = null, $order = null, $tanggal = null)
    {
        // PIlih tabel
        $this->db->select("a.*, IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) as status_str");
        $this->db->from("partner a");
        $this->db->where("a.status <> 3");

        // order by
        if ($order['order'] != null) {
            $columns = $order['columns'];
            $dir = $order['order'][0]['dir'];
            $order = $order['order'][0]['column'];
            $columns = $columns[$order];

            $order_colum = $columns['data'];
            $this->db->order_by($order_colum, $dir);
        }

        $this->db->order_by('a.id', 'desc');
        if ($tanggal != "" or $tanggal != null) {
            $tanggal_awal = date("Y-m-d", strtotime($tanggal[0]));
            $tanggal_akhir = date("Y-m-d", strtotime($tanggal[1]));
            $this->db->where("a.created_at >= ", $tanggal_awal);
            $this->db->where("a.created_at <= ", $tanggal_akhir);
        }

        // initial data table
        if ($draw == 1) {
            $this->db->limit(10, 0);
        }

        // pencarian
        if ($cari != null) {
            $this->db->where("(
                a.nama LIKE '%$cari%' or
                a.no_telepon LIKE '%$cari%' or
                a.email LIKE '%$cari%' or
                a.lokasi LIKE '%$cari%' or
                IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) LIKE '%$cari%')");
        }

        // pagination
        if ($show != null && $start != null) {
            $this->db->limit($show, $start);
        }

        $result = $this->db->get();
        return $result;
    }

    public function getReportAnggota($draw = null, $show = null, $start = null, $cari = null, $order = null, $tanggal = null)
    {
        $part = $this->session->userdata('data')['id'];
        $cek_part = $this->db->get_where('users', ['user_id' => $part])->result_array();
        $idpart = $cek_part[0]['id_partner'];
        $this->db->reset_query();

        // select datatable
        $this->db->select(" `a` .*, l.nama as nama_posisi, i.keterangan as keanggotaan, i.nama as nama_keanggotaan, a.created_at as tanggal_daftar, b.id_partner, `h`.lev_nama, `b`.`nik`, `b`.`user_email` as `email`, b.user_email_status, a.tanggal_registrasi,
            (IFNULL((SELECT jenis_membership.nama FROM membership
                join jenis_membership on jenis_membership.id = membership.id_jenis_membership
                WHERE id_profile = a.id and membership.status = 1 limit 1), '')) as membership,
            (IFNULL((SELECT id FROM membership
                        WHERE id_profile = a.id and status < 2 limit 1), '')) as id_membership,
            (IFNULL((SELECT status FROM membership
                        WHERE id_profile = a.id and status < 2 limit 1), '')) as st_membership,
            (IFNULL((SELECT id_profile_atasan FROM membership_atasan atasan
                        JOIN profile ON atasan.id_profile_atasan = profile.id
                        WHERE atasan.id_profile = a.id and atasan.status < 2 limit 1), '')) as id_atasan,
            (IFNULL((SELECT concat(profile.nama_depan, ' ', profile.nama_belakang) FROM membership_atasan atasan
                        JOIN profile ON atasan.id_profile_atasan = profile.id
                        WHERE atasan.id_profile = a.id and atasan.status < 2 limit 1), '')) as atasan,
            IF(a.jenis_kelamin = '1', 'Laki-Laki', IF(a.jenis_kelamin = '2', 'Perempuan', 'Tidak Diketahui')) as jk,
            IF(a.status_verifikasi = '1', 'Approved',
            IF(a.status_verifikasi = '2', 'Rejected', 'Tidak Diketahui')) as st_ver,
            IF(a.status = '2', 'Tidak Aktif', IF(a.status = '1', 'Aktif', 'Tidak Diketahui')) as status_str,
            `e`.`nama` as `nama_partner`,
            (IFNULL(( SELECT tipe_contact.nama FROM contact JOIN tipe_contact ON contact.id_tipe_contact = tipe_contact.id
                        WHERE contact.id_profile = a.id AND contact.status = 1
                        ORDER BY contact.created_at DESC LIMIT 1 ), '' )) AS tipe_contact_sekarang,
            (IFNULL(( SELECT contact.keterangan FROM contact JOIN tipe_contact ON contact.id_tipe_contact = tipe_contact.id
                        WHERE contact.id_profile = a.id AND contact.status = 1
                        ORDER BY contact.created_at DESC LIMIT 1 ), '' )) AS contact_sekarang,
            (IFNULL((SELECT `name` FROM data_formal JOIN dtm_peristiwa ON data_formal.dtm_peristiwa_id = dtm_peristiwa.id
                        WHERE data_formal.id_profile = a.id AND data_formal.status = 1
                        ORDER BY data_formal.tanggal_data_formal DESC LIMIT 1), '')) as peristiwa_formal,
            (IFNULL((SELECT data_formal.created_at FROM data_formal JOIN dtm_peristiwa ON data_formal.dtm_peristiwa_id = dtm_peristiwa.id
                        WHERE data_formal.id_profile = a.id AND data_formal.status = 1
                        ORDER BY data_formal.tanggal_data_formal DESC LIMIT 1), '')) as tgl_formal,
            (IFNULL(( SELECT ja.nama FROM alamat alt 
                        JOIN jenis_alamat ja ON alt.id_jenis_alamat = ja.id
                        WHERE alt.id_profile = a.id AND alt.status = 1
                        ORDER BY alt.tanggal_mulai DESC LIMIT 1 ), '' )) AS jenis_alamat_sekarang,
            (IFNULL(( SELECT alamat FROM alamat WHERE alamat.id_profile = a.id AND alamat.status = 1
                        ORDER BY alamat.tanggal_mulai DESC LIMIT 1 ), '' )) AS alamat_sekarang,
            (IFNULL(( SELECT gelar.tanggal_lulus FROM gelar JOIN jenis_gelar ON gelar.id_jenis_gelar = jenis_gelar.id
                        WHERE gelar.id_profile = a.id AND gelar.status = 1
                        ORDER BY gelar.tanggal_lulus DESC LIMIT 1 ), '' )) AS tanggal_gelar_sekarang,
            (IFNULL(( SELECT gelar.lembaga FROM gelar JOIN jenis_gelar ON gelar.id_jenis_gelar = jenis_gelar.id
                        WHERE gelar.id_profile = a.id AND gelar.status = 1
                        ORDER BY gelar.tanggal_lulus DESC LIMIT 1 ), '' )) AS lembaga_gelar_sekarang,
            (IFNULL(( SELECT jenis_gelar.nama FROM gelar JOIN jenis_gelar ON gelar.id_jenis_gelar = jenis_gelar.id
                        WHERE gelar.id_profile = a.id AND gelar.status = 1
                        ORDER BY gelar.tanggal_lulus DESC LIMIT 1 ), '' )) AS gelar_sekarang,
            (IFNULL(( SELECT kategorial.nama FROM kategorial JOIN profile_kategorial ON profile_kategorial.id_kategorial = kategorial.id
                        WHERE profile_kategorial.id_profile = a.id AND profile_kategorial.status = 1
                        ORDER BY profile_kategorial.tanggal DESC LIMIT 1 ), '' )) AS kategorial_sekarang,
            (IFNULL(( SELECT wilayah.nama FROM wilayah JOIN profile_wilayah ON profile_wilayah.id_wilayah = wilayah.id
                        WHERE profile_wilayah.id_profile = a.id AND profile_wilayah.status = 1
                        ORDER BY profile_wilayah.tanggal DESC LIMIT 1 ), '' )) AS wilayah_sekarang");
        $this->db->from("profile a");
        $this->db->join("users b", "a.id_user = b.user_id", "left");
        $this->db->join("partner e", "e.id = b.id_partner", "left");
        $this->db->join("contact f", "f.id_profile = a.id", "left");
        $this->db->join('role_users g', 'g.role_user_id = b.user_id', 'left');
        $this->db->join('level h', 'g.role_lev_id = h.lev_id', 'left');
        $this->db->join('kategori_keanggotaan i', 'i.id = a.id_keanggotaan', 'left');
        $this->db->join('kategorial j', 'j.id = a.id_kategorial', 'left');
        $this->db->join('wilayah k', 'k.id = a.id_wilayah', 'left');
        $this->db->join('posisi l', 'l.id = b.id_posisi', 'left');
        $this->db->group_by('a.id');
        $this->db->where("a.status !=", 0);
        $this->db->where("a.status !=", 3);
        $this->db->where("h.lev_id !=", 1);
        // filter
        if ($tanggal != "" or $tanggal != null) {
            $tanggal_awal = date("Y-m-d", strtotime($tanggal[0]));
            $tanggal_akhir = date("Y-m-d", strtotime($tanggal[1]));
            $this->db->where("a.created_at >= ", $tanggal_awal);
            $this->db->where("a.created_at <= ", $tanggal_akhir);
        }

        $this->db->order_by('a.id', 'desc');

        // order by
        if ($order['order'] != null) {
            $columns = $order['columns'];
            $dir = $order['order'][0]['dir'];
            $order = $order['order'][0]['column'];
            $columns = $columns[$order];

            $order_colum = $columns['data'];
            $this->db->order_by($order_colum, $dir);
        } else {
            // $this->db->order_by('a.nama_depan', 'asc');
        }

        // initial data table
        if ($draw == 1) {
            $this->db->limit(10, 0);
        }


        // pencarian
        if ($cari != null) {
            $this->db->where("(
                h.lev_nama LIKE '%$cari%' or
                e.nama LIKE '%$cari%' or
                b.nik LIKE '%$cari%' or
                b.user_email LIKE '%$cari%' or
                a.nama_depan LIKE '%$cari%' or
                a.nama_belakang LIKE '%$cari%' or
                a.tanggal_lahir LIKE '%$cari%' or
                DATE_FORMAT(a.tanggal_lahir,'%d-%m-%Y') LIKE '%$cari%' or
                (year(curdate())-year(a.tanggal_lahir)) LIKE '%$cari%' or 
                concat(a.nama_belakang, ' ', a.nama_depan) LIKE '%$cari%' or
                concat(a.nama_depan, ' ', a.nama_belakang) LIKE '%$cari%' or
                (IFNULL(( SELECT wilayah.nama FROM profile_wilayah JOIN wilayah ON profile_wilayah.id_wilayah = wilayah.id
                    WHERE profile_wilayah.id_profile = a.id AND profile_wilayah.status = 1
                    ORDER BY profile_wilayah.created_at DESC LIMIT 1 ), '' )) LIKE '%$cari%' or
                (IFNULL(( SELECT kategorial.nama FROM profile_kategorial JOIN kategorial ON profile_kategorial.id_kategorial = kategorial.id
                    WHERE profile_kategorial.id_profile = a.id AND profile_kategorial.status = 1
                    ORDER BY profile_wilayah.created_at DESC LIMIT 1 ), '' )) LIKE '%$cari%' or
                (IFNULL(( SELECT jenis_gelar.nama FROM gelar JOIN jenis_gelar ON gelar.id_jenis_gelar = jenis_gelar.id
                    WHERE gelar.id_profile = a.id AND gelar.status = 1
                    ORDER BY gelar.tanggal_lulus DESC LIMIT 1 ), '' )) LIKE '%$cari%' or
                (IFNULL((SELECT `name` FROM data_formal JOIN dtm_peristiwa ON data_formal.dtm_peristiwa_id = dtm_peristiwa.id
                    WHERE data_formal.id_profile = a.id AND data_formal.status = 1
                    ORDER BY data_formal.tanggal_data_formal DESC LIMIT 1), '')) LIKE '%$cari%' or
                (IFNULL(( SELECT contact.keterangan FROM contact JOIN tipe_contact ON contact.id_tipe_contact = tipe_contact.id
                    WHERE contact.id_profile = a.id AND contact.status = 1
                    ORDER BY contact.created_at DESC LIMIT 1 ), '' )) LIKE '%$cari%' or
                (IFNULL(( SELECT tipe_contact.nama FROM contact JOIN tipe_contact ON contact.id_tipe_contact = tipe_contact.id
                    WHERE contact.id_profile = a.id AND contact.status = 1
                    ORDER BY contact.created_at DESC LIMIT 1 ), '' )) LIKE '%$cari%' or
                IF(a.jenis_kelamin = '1', 'Laki-Laki', IF(a.jenis_kelamin = '2', 'Perempuan', 'Tidak Diketahui')) LIKE '%$cari%' or
                (ifnull((SELECT jenis_membership.nama FROM membership join jenis_membership on jenis_membership.id = membership.id_jenis_membership WHERE id_profile = a.id limit 1), '')) LIKE '%$cari%' or
                IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) LIKE '%$cari%'
                )");
        }

        // pagination
        if ($show != null && $start != null) {
            $this->db->limit($show, $start);
        }

        $result = $this->db->get();
        return $result;
    }

    public function getReportUlangTahun($draw = null, $show = null, $start = null, $cari = null, $order = null, $bulan = null)
    {
        $part = $this->session->userdata('data')['id'];
        $cek_part = $this->db->get_where('users', ['user_id' => $part])->result_array();
        $idpart = $cek_part[0]['id_partner'];
        $this->db->reset_query();

        // select datatable
        $this->db->select(" `a` .*, l.nama as nama_posisi, i.keterangan as keanggotaan, i.nama as nama_keanggotaan, a.created_at as tanggal_daftar, b.id_partner, `h`.lev_nama, `b`.`nik`, `b`.`user_email` as `email`, b.user_email_status, a.tanggal_registrasi,
            (IFNULL((SELECT jenis_membership.nama FROM membership
                join jenis_membership on jenis_membership.id = membership.id_jenis_membership
                WHERE id_profile = a.id and membership.status = 1 limit 1), '')) as membership,
            (IFNULL((SELECT id FROM membership
                        WHERE id_profile = a.id and status < 2 limit 1), '')) as id_membership,
            (IFNULL((SELECT status FROM membership
                        WHERE id_profile = a.id and status < 2 limit 1), '')) as st_membership,
            (IFNULL((SELECT id_profile_atasan FROM membership_atasan atasan
                        JOIN profile ON atasan.id_profile_atasan = profile.id
                        WHERE atasan.id_profile = a.id and atasan.status < 2 limit 1), '')) as id_atasan,
            (IFNULL((SELECT concat(profile.nama_depan, ' ', profile.nama_belakang) FROM membership_atasan atasan
                        JOIN profile ON atasan.id_profile_atasan = profile.id
                        WHERE atasan.id_profile = a.id and atasan.status < 2 limit 1), '')) as atasan,
            IF(a.jenis_kelamin = '1', 'Laki-Laki', IF(a.jenis_kelamin = '2', 'Perempuan', 'Tidak Diketahui')) as jk,
            IF(a.status_verifikasi = '1', 'Approved',
            IF(a.status_verifikasi = '2', 'Rejected', 'Tidak Diketahui')) as st_ver,
            IF(a.status = '2', 'Tidak Aktif', IF(a.status = '1', 'Aktif', 'Tidak Diketahui')) as status_str,
            `e`.`nama` as `nama_partner`,
            (IFNULL(( SELECT tipe_contact.nama FROM contact JOIN tipe_contact ON contact.id_tipe_contact = tipe_contact.id
                        WHERE contact.id_profile = a.id AND contact.status = 1
                        ORDER BY contact.created_at DESC LIMIT 1 ), '' )) AS tipe_contact_sekarang,
            (IFNULL(( SELECT contact.keterangan FROM contact JOIN tipe_contact ON contact.id_tipe_contact = tipe_contact.id
                        WHERE contact.id_profile = a.id AND contact.status = 1
                        ORDER BY contact.created_at DESC LIMIT 1 ), '' )) AS contact_sekarang,
            (IFNULL((SELECT `name` FROM data_formal JOIN dtm_peristiwa ON data_formal.dtm_peristiwa_id = dtm_peristiwa.id
                        WHERE data_formal.id_profile = a.id AND data_formal.status = 1
                        ORDER BY data_formal.tanggal_data_formal DESC LIMIT 1), '')) as peristiwa_formal,
            (IFNULL((SELECT data_formal.created_at FROM data_formal JOIN dtm_peristiwa ON data_formal.dtm_peristiwa_id = dtm_peristiwa.id
                        WHERE data_formal.id_profile = a.id AND data_formal.status = 1
                        ORDER BY data_formal.tanggal_data_formal DESC LIMIT 1), '')) as tgl_formal,
            (IFNULL(( SELECT ja.nama FROM alamat alt 
                        JOIN jenis_alamat ja ON alt.id_jenis_alamat = ja.id
                        WHERE alt.id_profile = a.id AND alt.status = 1
                        ORDER BY alt.tanggal_mulai DESC LIMIT 1 ), '' )) AS jenis_alamat_sekarang,
            (IFNULL(( SELECT alamat FROM alamat WHERE alamat.id_profile = a.id AND alamat.status = 1
                        ORDER BY alamat.tanggal_mulai DESC LIMIT 1 ), '' )) AS alamat_sekarang,
            (IFNULL(( SELECT gelar.tanggal_lulus FROM gelar JOIN jenis_gelar ON gelar.id_jenis_gelar = jenis_gelar.id
                        WHERE gelar.id_profile = a.id AND gelar.status = 1
                        ORDER BY gelar.tanggal_lulus DESC LIMIT 1 ), '' )) AS tanggal_gelar_sekarang,
            (IFNULL(( SELECT gelar.lembaga FROM gelar JOIN jenis_gelar ON gelar.id_jenis_gelar = jenis_gelar.id
                        WHERE gelar.id_profile = a.id AND gelar.status = 1
                        ORDER BY gelar.tanggal_lulus DESC LIMIT 1 ), '' )) AS lembaga_gelar_sekarang,
            (IFNULL(( SELECT jenis_gelar.nama FROM gelar JOIN jenis_gelar ON gelar.id_jenis_gelar = jenis_gelar.id
                        WHERE gelar.id_profile = a.id AND gelar.status = 1
                        ORDER BY gelar.tanggal_lulus DESC LIMIT 1 ), '' )) AS gelar_sekarang,
            (IFNULL(( SELECT kategorial.nama FROM kategorial JOIN profile_kategorial ON profile_kategorial.id_kategorial = kategorial.id
                        WHERE profile_kategorial.id_profile = a.id AND profile_kategorial.status = 1
                        ORDER BY profile_kategorial.tanggal DESC LIMIT 1 ), '' )) AS kategorial_sekarang,
            (IFNULL(( SELECT wilayah.nama FROM wilayah JOIN profile_wilayah ON profile_wilayah.id_wilayah = wilayah.id
                        WHERE profile_wilayah.id_profile = a.id AND profile_wilayah.status = 1
                        ORDER BY profile_wilayah.tanggal DESC LIMIT 1 ), '' )) AS wilayah_sekarang");
        $this->db->from("profile a");
        $this->db->join("users b", "a.id_user = b.user_id", "left");
        $this->db->join("partner e", "e.id = b.id_partner", "left");
        $this->db->join("contact f", "f.id_profile = a.id", "left");
        $this->db->join('role_users g', 'g.role_user_id = b.user_id', 'left');
        $this->db->join('level h', 'g.role_lev_id = h.lev_id', 'left');
        $this->db->join('kategori_keanggotaan i', 'i.id = a.id_keanggotaan', 'left');
        $this->db->join('kategorial j', 'j.id = a.id_kategorial', 'left');
        $this->db->join('wilayah k', 'k.id = a.id_wilayah', 'left');
        $this->db->join('posisi l', 'l.id = b.id_posisi', 'left');
        $this->db->group_by('a.id');
        $this->db->where("a.status !=", 0);
        $this->db->where("a.status !=", 3);
        $this->db->where("h.lev_id !=", 1);

        $this->db->order_by('a.id', 'desc');

        // order by
        if ($order['order'] != null) {
            $columns = $order['columns'];
            $dir = $order['order'][0]['dir'];
            $order = $order['order'][0]['column'];
            $columns = $columns[$order];

            $order_colum = $columns['data'];
            $this->db->order_by($order_colum, $dir);
        } else {
            // $this->db->order_by('a.nama_depan', 'asc');
        }

        // initial data table
        if ($draw == 1) {
            $this->db->limit(10, 0);
        }

        // filter

        if ($bulan != "" or $bulan != null) {
            $this->db->where('DATE_FORMAT(a.tanggal_lahir, "%m") = "' . $bulan . '"');
        }


        // pencarian
        if ($cari != null) {
            $this->db->where("(
                h.lev_nama LIKE '%$cari%' or
                e.nama LIKE '%$cari%' or
                b.nik LIKE '%$cari%' or
                b.user_email LIKE '%$cari%' or
                a.nama_depan LIKE '%$cari%' or
                a.nama_belakang LIKE '%$cari%' or
                a.tanggal_lahir LIKE '%$cari%' or
                DATE_FORMAT(a.tanggal_lahir,'%d-%m-%Y') LIKE '%$cari%' or
                (year(curdate())-year(a.tanggal_lahir)) LIKE '%$cari%' or 
                concat(a.nama_belakang, ' ', a.nama_depan) LIKE '%$cari%' or
                concat(a.nama_depan, ' ', a.nama_belakang) LIKE '%$cari%' or
                (IFNULL(( SELECT wilayah.nama FROM profile_wilayah JOIN wilayah ON profile_wilayah.id_wilayah = wilayah.id
                    WHERE profile_wilayah.id_profile = a.id AND profile_wilayah.status = 1
                    ORDER BY profile_wilayah.created_at DESC LIMIT 1 ), '' )) LIKE '%$cari%' or
                (IFNULL(( SELECT kategorial.nama FROM profile_kategorial JOIN kategorial ON profile_kategorial.id_kategorial = kategorial.id
                    WHERE profile_kategorial.id_profile = a.id AND profile_kategorial.status = 1
                    ORDER BY profile_wilayah.created_at DESC LIMIT 1 ), '' )) LIKE '%$cari%' or
                (IFNULL(( SELECT jenis_gelar.nama FROM gelar JOIN jenis_gelar ON gelar.id_jenis_gelar = jenis_gelar.id
                    WHERE gelar.id_profile = a.id AND gelar.status = 1
                    ORDER BY gelar.tanggal_lulus DESC LIMIT 1 ), '' )) LIKE '%$cari%' or
                (IFNULL((SELECT `name` FROM data_formal JOIN dtm_peristiwa ON data_formal.dtm_peristiwa_id = dtm_peristiwa.id
                    WHERE data_formal.id_profile = a.id AND data_formal.status = 1
                    ORDER BY data_formal.tanggal_data_formal DESC LIMIT 1), '')) LIKE '%$cari%' or
                (IFNULL(( SELECT contact.keterangan FROM contact JOIN tipe_contact ON contact.id_tipe_contact = tipe_contact.id
                    WHERE contact.id_profile = a.id AND contact.status = 1
                    ORDER BY contact.created_at DESC LIMIT 1 ), '' )) LIKE '%$cari%' or
                (IFNULL(( SELECT tipe_contact.nama FROM contact JOIN tipe_contact ON contact.id_tipe_contact = tipe_contact.id
                    WHERE contact.id_profile = a.id AND contact.status = 1
                    ORDER BY contact.created_at DESC LIMIT 1 ), '' )) LIKE '%$cari%' or
                IF(a.jenis_kelamin = '1', 'Laki-Laki', IF(a.jenis_kelamin = '2', 'Perempuan', 'Tidak Diketahui')) LIKE '%$cari%' or
                (ifnull((SELECT jenis_membership.nama FROM membership join jenis_membership on jenis_membership.id = membership.id_jenis_membership WHERE id_profile = a.id limit 1), '')) LIKE '%$cari%' or
                IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) LIKE '%$cari%'
                )");
        }

        // pagination
        if ($show != null && $start != null) {
            $this->db->limit($show, $start);
        }

        $result = $this->db->get();
        return $result;
    }

    public function getReportKelahiran($draw = null, $show = null, $start = null, $cari = null, $order = null, $usia = null)
    {
        $part = $this->session->userdata('data')['id'];
        $cek_part = $this->db->get_where('users', ['user_id' => $part])->result_array();
        $idpart = $cek_part[0]['id_partner'];
        $this->db->reset_query();

        // select datatable
        $this->db->select(" `a` .*, l.nama as nama_posisi, i.keterangan as keanggotaan, i.nama as nama_keanggotaan, a.created_at as tanggal_daftar, b.id_partner, `h`.lev_nama, `b`.`nik`, `b`.`user_email` as `email`, b.user_email_status, a.tanggal_registrasi,
            (IFNULL((SELECT jenis_membership.nama FROM membership
                join jenis_membership on jenis_membership.id = membership.id_jenis_membership
                WHERE id_profile = a.id and membership.status = 1 limit 1), '')) as membership,
            (IFNULL((SELECT id FROM membership
                        WHERE id_profile = a.id and status < 2 limit 1), '')) as id_membership,
            (IFNULL((SELECT status FROM membership
                        WHERE id_profile = a.id and status < 2 limit 1), '')) as st_membership,
            (IFNULL((SELECT id_profile_atasan FROM membership_atasan atasan
                        JOIN profile ON atasan.id_profile_atasan = profile.id
                        WHERE atasan.id_profile = a.id and atasan.status < 2 limit 1), '')) as id_atasan,
            (IFNULL((SELECT concat(profile.nama_depan, ' ', profile.nama_belakang) FROM membership_atasan atasan
                        JOIN profile ON atasan.id_profile_atasan = profile.id
                        WHERE atasan.id_profile = a.id and atasan.status < 2 limit 1), '')) as atasan,
            IF(a.jenis_kelamin = '1', 'Laki-Laki', IF(a.jenis_kelamin = '2', 'Perempuan', 'Tidak Diketahui')) as jk,
            IF(a.status_verifikasi = '1', 'Approved',
            IF(a.status_verifikasi = '2', 'Rejected', 'Tidak Diketahui')) as st_ver,
            IF(a.status = '2', 'Tidak Aktif', IF(a.status = '1', 'Aktif', 'Tidak Diketahui')) as status_str,
            `e`.`nama` as `nama_partner`,
            (IFNULL(( SELECT tipe_contact.nama FROM contact JOIN tipe_contact ON contact.id_tipe_contact = tipe_contact.id
                        WHERE contact.id_profile = a.id AND contact.status = 1
                        ORDER BY contact.created_at DESC LIMIT 1 ), '' )) AS tipe_contact_sekarang,
            (IFNULL(( SELECT contact.keterangan FROM contact JOIN tipe_contact ON contact.id_tipe_contact = tipe_contact.id
                        WHERE contact.id_profile = a.id AND contact.status = 1
                        ORDER BY contact.created_at DESC LIMIT 1 ), '' )) AS contact_sekarang,
            (IFNULL((SELECT `name` FROM data_formal JOIN dtm_peristiwa ON data_formal.dtm_peristiwa_id = dtm_peristiwa.id
                        WHERE data_formal.id_profile = a.id AND data_formal.status = 1
                        ORDER BY data_formal.tanggal_data_formal DESC LIMIT 1), '')) as peristiwa_formal,
            (IFNULL((SELECT data_formal.created_at FROM data_formal JOIN dtm_peristiwa ON data_formal.dtm_peristiwa_id = dtm_peristiwa.id
                        WHERE data_formal.id_profile = a.id AND data_formal.status = 1
                        ORDER BY data_formal.tanggal_data_formal DESC LIMIT 1), '')) as tgl_formal,
            (IFNULL(( SELECT ja.nama FROM alamat alt 
                        JOIN jenis_alamat ja ON alt.id_jenis_alamat = ja.id
                        WHERE alt.id_profile = a.id AND alt.status = 1
                        ORDER BY alt.tanggal_mulai DESC LIMIT 1 ), '' )) AS jenis_alamat_sekarang,
            (IFNULL(( SELECT alamat FROM alamat WHERE alamat.id_profile = a.id AND alamat.status = 1
                        ORDER BY alamat.tanggal_mulai DESC LIMIT 1 ), '' )) AS alamat_sekarang,
            (IFNULL(( SELECT gelar.tanggal_lulus FROM gelar JOIN jenis_gelar ON gelar.id_jenis_gelar = jenis_gelar.id
                        WHERE gelar.id_profile = a.id AND gelar.status = 1
                        ORDER BY gelar.tanggal_lulus DESC LIMIT 1 ), '' )) AS tanggal_gelar_sekarang,
            (IFNULL(( SELECT gelar.lembaga FROM gelar JOIN jenis_gelar ON gelar.id_jenis_gelar = jenis_gelar.id
                        WHERE gelar.id_profile = a.id AND gelar.status = 1
                        ORDER BY gelar.tanggal_lulus DESC LIMIT 1 ), '' )) AS lembaga_gelar_sekarang,
            (IFNULL(( SELECT jenis_gelar.nama FROM gelar JOIN jenis_gelar ON gelar.id_jenis_gelar = jenis_gelar.id
                        WHERE gelar.id_profile = a.id AND gelar.status = 1
                        ORDER BY gelar.tanggal_lulus DESC LIMIT 1 ), '' )) AS gelar_sekarang,
            (IFNULL(( SELECT kategorial.nama FROM kategorial JOIN profile_kategorial ON profile_kategorial.id_kategorial = kategorial.id
                        WHERE profile_kategorial.id_profile = a.id AND profile_kategorial.status = 1
                        ORDER BY profile_kategorial.tanggal DESC LIMIT 1 ), '' )) AS kategorial_sekarang,
            (IFNULL(( SELECT wilayah.nama FROM wilayah JOIN profile_wilayah ON profile_wilayah.id_wilayah = wilayah.id
                        WHERE profile_wilayah.id_profile = a.id AND profile_wilayah.status = 1
                        ORDER BY profile_wilayah.tanggal DESC LIMIT 1 ), '' )) AS wilayah_sekarang");
        $this->db->from("profile a");
        $this->db->join("users b", "a.id_user = b.user_id", "left");
        $this->db->join("partner e", "e.id = b.id_partner", "left");
        $this->db->join("contact f", "f.id_profile = a.id", "left");
        $this->db->join('role_users g', 'g.role_user_id = b.user_id', 'left');
        $this->db->join('level h', 'g.role_lev_id = h.lev_id', 'left');
        $this->db->join('kategori_keanggotaan i', 'i.id = a.id_keanggotaan', 'left');
        $this->db->join('kategorial j', 'j.id = a.id_kategorial', 'left');
        $this->db->join('wilayah k', 'k.id = a.id_wilayah', 'left');
        $this->db->join('posisi l', 'l.id = b.id_posisi', 'left');
        $this->db->group_by('a.id');
        $this->db->where("a.status !=", 0);
        $this->db->where("a.status !=", 3);
        $this->db->where("h.lev_id !=", 1);
        // filter

        if ($usia != "" or $usia != null) {
            $usia = explode("-", $usia);
            $usia_1 = $usia[0];
            $usia_2 = $usia[1];
            $this->db->where('(year(curdate())-year(a.tanggal_lahir)) >= ', $usia_1);
            $this->db->where('(year(curdate())-year(a.tanggal_lahir)) <= ', $usia_2);
        }

        $this->db->order_by('a.id', 'desc');

        // order by
        if ($order['order'] != null) {
            $columns = $order['columns'];
            $dir = $order['order'][0]['dir'];
            $order = $order['order'][0]['column'];
            $columns = $columns[$order];

            $order_colum = $columns['data'];
            $this->db->order_by($order_colum, $dir);
        } else {
            // $this->db->order_by('a.nama_depan', 'asc');
        }

        // initial data table
        if ($draw == 1) {
            $this->db->limit(10, 0);
        }


        // pencarian
        if ($cari != null) {
            $this->db->where("(
                h.lev_nama LIKE '%$cari%' or
                e.nama LIKE '%$cari%' or
                b.nik LIKE '%$cari%' or
                b.user_email LIKE '%$cari%' or
                a.nama_depan LIKE '%$cari%' or
                a.nama_belakang LIKE '%$cari%' or
                a.tanggal_lahir LIKE '%$cari%' or
                DATE_FORMAT(a.tanggal_lahir,'%d-%m-%Y') LIKE '%$cari%' or
                (year(curdate())-year(a.tanggal_lahir)) LIKE '%$cari%' or 
                concat(a.nama_belakang, ' ', a.nama_depan) LIKE '%$cari%' or
                concat(a.nama_depan, ' ', a.nama_belakang) LIKE '%$cari%' or
                (IFNULL(( SELECT wilayah.nama FROM profile_wilayah JOIN wilayah ON profile_wilayah.id_wilayah = wilayah.id
                    WHERE profile_wilayah.id_profile = a.id AND profile_wilayah.status = 1
                    ORDER BY profile_wilayah.created_at DESC LIMIT 1 ), '' )) LIKE '%$cari%' or
                (IFNULL(( SELECT kategorial.nama FROM profile_kategorial JOIN kategorial ON profile_kategorial.id_kategorial = kategorial.id
                    WHERE profile_kategorial.id_profile = a.id AND profile_kategorial.status = 1
                    ORDER BY profile_wilayah.created_at DESC LIMIT 1 ), '' )) LIKE '%$cari%' or
                (IFNULL(( SELECT jenis_gelar.nama FROM gelar JOIN jenis_gelar ON gelar.id_jenis_gelar = jenis_gelar.id
                    WHERE gelar.id_profile = a.id AND gelar.status = 1
                    ORDER BY gelar.tanggal_lulus DESC LIMIT 1 ), '' )) LIKE '%$cari%' or
                (IFNULL((SELECT `name` FROM data_formal JOIN dtm_peristiwa ON data_formal.dtm_peristiwa_id = dtm_peristiwa.id
                    WHERE data_formal.id_profile = a.id AND data_formal.status = 1
                    ORDER BY data_formal.tanggal_data_formal DESC LIMIT 1), '')) LIKE '%$cari%' or
                (IFNULL(( SELECT contact.keterangan FROM contact JOIN tipe_contact ON contact.id_tipe_contact = tipe_contact.id
                    WHERE contact.id_profile = a.id AND contact.status = 1
                    ORDER BY contact.created_at DESC LIMIT 1 ), '' )) LIKE '%$cari%' or
                (IFNULL(( SELECT tipe_contact.nama FROM contact JOIN tipe_contact ON contact.id_tipe_contact = tipe_contact.id
                    WHERE contact.id_profile = a.id AND contact.status = 1
                    ORDER BY contact.created_at DESC LIMIT 1 ), '' )) LIKE '%$cari%' or
                IF(a.jenis_kelamin = '1', 'Laki-Laki', IF(a.jenis_kelamin = '2', 'Perempuan', 'Tidak Diketahui')) LIKE '%$cari%' or
                (ifnull((SELECT jenis_membership.nama FROM membership join jenis_membership on jenis_membership.id = membership.id_jenis_membership WHERE id_profile = a.id limit 1), '')) LIKE '%$cari%' or
                IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) LIKE '%$cari%'
                )");
        }

        // pagination
        if ($show != null && $start != null) {
            $this->db->limit($show, $start);
        }

        $result = $this->db->get();
        return $result;
    }

    public function getReportKematian($draw = null, $show = null, $start = null, $cari = null, $order = null, $usia = null)
    {
        $part = $this->session->userdata('data')['id'];
        $cek_part = $this->db->get_where('users', ['user_id' => $part])->result_array();
        $idpart = $cek_part[0]['id_partner'];
        $this->db->reset_query();

        // select datatable
        $this->db->select(" `a` .*, l.nama as nama_posisi, i.keterangan as keanggotaan, i.nama as nama_keanggotaan, a.created_at as tanggal_daftar, b.id_partner, `h`.lev_nama, `b`.`nik`, `b`.`user_email` as `email`, b.user_email_status, a.tanggal_registrasi,
            (IFNULL((SELECT jenis_membership.nama FROM membership
                join jenis_membership on jenis_membership.id = membership.id_jenis_membership
                WHERE id_profile = a.id and membership.status = 1 limit 1), '')) as membership,
            (IFNULL((SELECT id FROM membership
                        WHERE id_profile = a.id and status < 2 limit 1), '')) as id_membership,
            (IFNULL((SELECT status FROM membership
                        WHERE id_profile = a.id and status < 2 limit 1), '')) as st_membership,
            (IFNULL((SELECT id_profile_atasan FROM membership_atasan atasan
                        JOIN profile ON atasan.id_profile_atasan = profile.id
                        WHERE atasan.id_profile = a.id and atasan.status < 2 limit 1), '')) as id_atasan,
            (IFNULL((SELECT concat(profile.nama_depan, ' ', profile.nama_belakang) FROM membership_atasan atasan
                        JOIN profile ON atasan.id_profile_atasan = profile.id
                        WHERE atasan.id_profile = a.id and atasan.status < 2 limit 1), '')) as atasan,
            IF(a.jenis_kelamin = '1', 'Laki-Laki', IF(a.jenis_kelamin = '2', 'Perempuan', 'Tidak Diketahui')) as jk,
            IF(a.status_verifikasi = '1', 'Approved',
            IF(a.status_verifikasi = '2', 'Rejected', 'Tidak Diketahui')) as st_ver,
            IF(a.status = '2', 'Tidak Aktif', IF(a.status = '1', 'Aktif', 'Tidak Diketahui')) as status_str,
            `e`.`nama` as `nama_partner`,
            (IFNULL(( SELECT tipe_contact.nama FROM contact JOIN tipe_contact ON contact.id_tipe_contact = tipe_contact.id
                        WHERE contact.id_profile = a.id AND contact.status = 1
                        ORDER BY contact.created_at DESC LIMIT 1 ), '' )) AS tipe_contact_sekarang,
            (IFNULL(( SELECT contact.keterangan FROM contact JOIN tipe_contact ON contact.id_tipe_contact = tipe_contact.id
                        WHERE contact.id_profile = a.id AND contact.status = 1
                        ORDER BY contact.created_at DESC LIMIT 1 ), '' )) AS contact_sekarang,
            (IFNULL((SELECT `name` FROM data_formal JOIN dtm_peristiwa ON data_formal.dtm_peristiwa_id = dtm_peristiwa.id
                        WHERE data_formal.id_profile = a.id AND data_formal.status = 1
                        ORDER BY data_formal.tanggal_data_formal DESC LIMIT 1), '')) as peristiwa_formal,
            (IFNULL((SELECT data_formal.created_at FROM data_formal JOIN dtm_peristiwa ON data_formal.dtm_peristiwa_id = dtm_peristiwa.id
                        WHERE data_formal.id_profile = a.id AND data_formal.status = 1
                        ORDER BY data_formal.tanggal_data_formal DESC LIMIT 1), '')) as tgl_formal,
            (IFNULL(( SELECT ja.nama FROM alamat alt 
                        JOIN jenis_alamat ja ON alt.id_jenis_alamat = ja.id
                        WHERE alt.id_profile = a.id AND alt.status = 1
                        ORDER BY alt.tanggal_mulai DESC LIMIT 1 ), '' )) AS jenis_alamat_sekarang,
            (IFNULL(( SELECT alamat FROM alamat WHERE alamat.id_profile = a.id AND alamat.status = 1
                        ORDER BY alamat.tanggal_mulai DESC LIMIT 1 ), '' )) AS alamat_sekarang,
            (IFNULL(( SELECT gelar.tanggal_lulus FROM gelar JOIN jenis_gelar ON gelar.id_jenis_gelar = jenis_gelar.id
                        WHERE gelar.id_profile = a.id AND gelar.status = 1
                        ORDER BY gelar.tanggal_lulus DESC LIMIT 1 ), '' )) AS tanggal_gelar_sekarang,
            (IFNULL(( SELECT gelar.lembaga FROM gelar JOIN jenis_gelar ON gelar.id_jenis_gelar = jenis_gelar.id
                        WHERE gelar.id_profile = a.id AND gelar.status = 1
                        ORDER BY gelar.tanggal_lulus DESC LIMIT 1 ), '' )) AS lembaga_gelar_sekarang,
            (IFNULL(( SELECT jenis_gelar.nama FROM gelar JOIN jenis_gelar ON gelar.id_jenis_gelar = jenis_gelar.id
                        WHERE gelar.id_profile = a.id AND gelar.status = 1
                        ORDER BY gelar.tanggal_lulus DESC LIMIT 1 ), '' )) AS gelar_sekarang,
            (IFNULL(( SELECT kategorial.nama FROM kategorial JOIN profile_kategorial ON profile_kategorial.id_kategorial = kategorial.id
                        WHERE profile_kategorial.id_profile = a.id AND profile_kategorial.status = 1
                        ORDER BY profile_kategorial.tanggal DESC LIMIT 1 ), '' )) AS kategorial_sekarang,
            (IFNULL(( SELECT wilayah.nama FROM wilayah JOIN profile_wilayah ON profile_wilayah.id_wilayah = wilayah.id
                        WHERE profile_wilayah.id_profile = a.id AND profile_wilayah.status = 1
                        ORDER BY profile_wilayah.tanggal DESC LIMIT 1 ), '' )) AS wilayah_sekarang");
        $this->db->from("profile a");
        $this->db->join("users b", "a.id_user = b.user_id", "left");
        $this->db->join("partner e", "e.id = b.id_partner", "left");
        $this->db->join("contact f", "f.id_profile = a.id", "left");
        $this->db->join('role_users g', 'g.role_user_id = b.user_id', 'left');
        $this->db->join('level h', 'g.role_lev_id = h.lev_id', 'left');
        $this->db->join('kategori_keanggotaan i', 'i.id = a.id_keanggotaan', 'left');
        $this->db->join('kategorial j', 'j.id = a.id_kategorial', 'left');
        $this->db->join('wilayah k', 'k.id = a.id_wilayah', 'left');
        $this->db->join('posisi l', 'l.id = b.id_posisi', 'left');
        $this->db->group_by('a.id');
        $this->db->where("a.status !=", 0);
        $this->db->where("a.status !=", 3);
        $this->db->where("h.lev_id !=", 1);
        $this->db->where("a.tanggal_meninggal != ", "0000-00-00");
        // filter
        if ($usia != "" or $usia != null) {
            $usia = explode("-", $usia);
            $usia_1 = $usia[0];
            $usia_2 = $usia[1];
            $this->db->where('(year(curdate())-year(a.tanggal_meninggal)) >= ', $usia_1);
            $this->db->where('(year(curdate())-year(a.tanggal_meninggal)) <= ', $usia_2);
        }

        $this->db->order_by('a.id', 'desc');

        // order by
        if ($order['order'] != null) {
            $columns = $order['columns'];
            $dir = $order['order'][0]['dir'];
            $order = $order['order'][0]['column'];
            $columns = $columns[$order];

            $order_colum = $columns['data'];
            $this->db->order_by($order_colum, $dir);
        } else {
            // $this->db->order_by('a.nama_depan', 'asc');
        }

        // initial data table
        if ($draw == 1) {
            $this->db->limit(10, 0);
        }


        // pencarian
        if ($cari != null) {
            $this->db->where("(
                h.lev_nama LIKE '%$cari%' or
                e.nama LIKE '%$cari%' or
                b.nik LIKE '%$cari%' or
                b.user_email LIKE '%$cari%' or
                a.nama_depan LIKE '%$cari%' or
                a.nama_belakang LIKE '%$cari%' or
                a.tanggal_lahir LIKE '%$cari%' or
                DATE_FORMAT(a.tanggal_lahir,'%d-%m-%Y') LIKE '%$cari%' or
                (year(curdate())-year(a.tanggal_lahir)) LIKE '%$cari%' or 
                concat(a.nama_belakang, ' ', a.nama_depan) LIKE '%$cari%' or
                concat(a.nama_depan, ' ', a.nama_belakang) LIKE '%$cari%' or
                (IFNULL(( SELECT wilayah.nama FROM profile_wilayah JOIN wilayah ON profile_wilayah.id_wilayah = wilayah.id
                    WHERE profile_wilayah.id_profile = a.id AND profile_wilayah.status = 1
                    ORDER BY profile_wilayah.created_at DESC LIMIT 1 ), '' )) LIKE '%$cari%' or
                (IFNULL(( SELECT kategorial.nama FROM profile_kategorial JOIN kategorial ON profile_kategorial.id_kategorial = kategorial.id
                    WHERE profile_kategorial.id_profile = a.id AND profile_kategorial.status = 1
                    ORDER BY profile_wilayah.created_at DESC LIMIT 1 ), '' )) LIKE '%$cari%' or
                (IFNULL(( SELECT jenis_gelar.nama FROM gelar JOIN jenis_gelar ON gelar.id_jenis_gelar = jenis_gelar.id
                    WHERE gelar.id_profile = a.id AND gelar.status = 1
                    ORDER BY gelar.tanggal_lulus DESC LIMIT 1 ), '' )) LIKE '%$cari%' or
                (IFNULL((SELECT `name` FROM data_formal JOIN dtm_peristiwa ON data_formal.dtm_peristiwa_id = dtm_peristiwa.id
                    WHERE data_formal.id_profile = a.id AND data_formal.status = 1
                    ORDER BY data_formal.tanggal_data_formal DESC LIMIT 1), '')) LIKE '%$cari%' or
                (IFNULL(( SELECT contact.keterangan FROM contact JOIN tipe_contact ON contact.id_tipe_contact = tipe_contact.id
                    WHERE contact.id_profile = a.id AND contact.status = 1
                    ORDER BY contact.created_at DESC LIMIT 1 ), '' )) LIKE '%$cari%' or
                (IFNULL(( SELECT tipe_contact.nama FROM contact JOIN tipe_contact ON contact.id_tipe_contact = tipe_contact.id
                    WHERE contact.id_profile = a.id AND contact.status = 1
                    ORDER BY contact.created_at DESC LIMIT 1 ), '' )) LIKE '%$cari%' or
                IF(a.jenis_kelamin = '1', 'Laki-Laki', IF(a.jenis_kelamin = '2', 'Perempuan', 'Tidak Diketahui')) LIKE '%$cari%' or
                (ifnull((SELECT jenis_membership.nama FROM membership join jenis_membership on jenis_membership.id = membership.id_jenis_membership WHERE id_profile = a.id limit 1), '')) LIKE '%$cari%' or
                IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) LIKE '%$cari%'
                )");
        }

        // pagination
        if ($show != null && $start != null) {
            $this->db->limit($show, $start);
        }

        $result = $this->db->get();
        return $result;
    }

    public function getReportWilayah($draw = null, $show = null, $start = null, $cari = null, $order = null, $jenis = null, $tanggal = null)
    {
        $this->db->reset_query();
        // select datatable
        $this->db->select("*,pw.id as id, a.created_at as tanggal_daftar, c.nama as wilayah");
        $this->db->from("profile_wilayah pw");
        $this->db->join("profile a", "pw.id_profile = a.id");
        $this->db->join("wilayah c", "c.id = pw.id_wilayah");
        $this->db->join("users b", "a.id_user = b.user_id", "left");
        $this->db->where("a.status !=", 0);
        $this->db->where("a.status !=", 3);
        $this->db->where("pw.status", 1);
        // filter
        if ($jenis != "" or $jenis != null) {
            $this->db->where("pw.id_wilayah", $jenis);
        }

        // if($tanggal != "" or $tanggal != null){
        //     $tanggal_awal = date("Y-m-d", strtotime($tanggal[0]));
        //     $tanggal_akhir = date("Y-m-d", strtotime($tanggal[1]));
        //     $this->db->where("pw.created_at >= ", $tanggal_awal);
        //     $this->db->where("pw.created_at <= ", $tanggal_akhir);
        // }

        // $this->db->order_by('pw.id', 'asc');


        // initial data table
        if ($draw == 1) {
            $this->db->limit(10, 0);
        }


        // pencarian
        if ($cari != null) {
            $this->db->where("(
                a.nama_depan LIKE '%$cari%' or
                a.nama_belakang LIKE '%$cari%' or
                a.tanggal_lahir LIKE '%$cari%' or
              )");
        }

        // pagination
        if ($show != null && $start != null) {
            $this->db->limit($show, $start);
        }

        $result = $this->db->get();
        return $result;
    }

    public function getReportDataFormal($draw = null, $show = null, $start = null, $cari = null, $order = null, $jenis = null, $tanggal = null)
    {
        $this->db->reset_query();
        // select datatable
        $this->db->select("*,pw.id as id, a.created_at as tanggal_daftar, c.name as peristiwa");
        $this->db->from("data_formal pw");
        $this->db->join("profile a", "pw.id_profile = a.id");
        $this->db->join("dtm_peristiwa c", "c.id = pw.dtm_peristiwa_id");
        $this->db->join("users b", "a.id_user = b.user_id", "left");
        $this->db->where("a.status !=", 0);
        $this->db->where("a.status !=", 3);
        // filter
        if ($jenis != "" or $jenis != null) {
            $this->db->where("pw.dtm_peristiwa_id", $jenis);
        }

        if ($tanggal != "" or $tanggal != null) {
            $tanggal_awal = date("Y-m-d", strtotime($tanggal[0]));
            $tanggal_akhir = date("Y-m-d", strtotime($tanggal[1]));
            $this->db->where("pw.tanggal_data_formal >= ", $tanggal_awal);
            $this->db->where("pw.tanggal_data_formal <= ", $tanggal_akhir);
        }

        // $this->db->order_by('pw.id', 'asc');


        // initial data table
        if ($draw == 1) {
            $this->db->limit(10, 0);
        }


        // pencarian
        if ($cari != null) {
            $this->db->where("(
                a.nama_depan LIKE '%$cari%' or
                a.nama_belakang LIKE '%$cari%' or
                a.tanggal_lahir LIKE '%$cari%' or
              )");
        }

        // pagination
        if ($show != null && $start != null) {
            $this->db->limit($show, $start);
        }

        $result = $this->db->get();
        return $result;
    }

    public function getReportKeanggotaan($draw = null, $show = null, $start = null, $cari = null, $order = null, $jenis = null, $tanggal = null, $status = null)
    {
        $this->db->reset_query();
        // select datatable
        $this->db->select("*,pw.id as id, a.created_at as tanggal_daftar, c.keterangan as keanggotaan");
        $this->db->from("profile_keanggotaan pw");
        $this->db->join("profile a", "pw.id_profile = a.id");
        $this->db->join("kategori_keanggotaan c", "c.id = pw.id_kategori_keanggotaan");
        $this->db->join("users b", "a.id_user = b.user_id", "left");
        $this->db->where("a.status !=", 0);
        $this->db->where("a.status !=", 3);
        // filter

        if ($status != "") {
            $this->db->where("pw.status", 1);
        } else {
            if ($jenis != "" or $jenis != null) {
                $this->db->where("pw.id_kategori_keanggotaan", $jenis);
                $this->db->where("pw.status", 2);
            }
        }

        if ($tanggal != "" or $tanggal != null) {
            $tanggal_awal = date("Y-m-d", strtotime($tanggal[0]));
            $tanggal_akhir = date("Y-m-d", strtotime($tanggal[1]));
            $this->db->where("pw.tanggal >= ", $tanggal_awal);
            $this->db->where("pw.tanggal <= ", $tanggal_akhir);
        }

        // $this->db->order_by('pw.id', 'asc');


        // initial data table
        if ($draw == 1) {
            $this->db->limit(10, 0);
        }


        // pencarian
        if ($cari != null) {
            $this->db->where("(
                a.nama_depan LIKE '%$cari%' or
                a.nama_belakang LIKE '%$cari%' or
                a.tanggal_lahir LIKE '%$cari%' or
              )");
        }

        // pagination
        if ($show != null && $start != null) {
            $this->db->limit($show, $start);
        }

        $result = $this->db->get();
        return $result;
    }


    public function getReportKategorial($draw = null, $show = null, $start = null, $cari = null, $order = null, $jenis = null, $tanggal = null)
    {
        $this->db->reset_query();
        // select datatable
        $this->db->select("*, a.created_at as tanggal_daftar, c.nama as kategorial");
        $this->db->from("profile_kategorial pw");
        $this->db->join("profile a", "pw.id_profile = a.id");
        $this->db->join("kategorial c", "pw.id_kategorial = c.id");
        $this->db->join("users b", "a.id_user = b.user_id", "left");
        $this->db->where("a.status !=", 0);
        $this->db->where("a.status !=", 3);
        $this->db->where("pw.status", 1);
        // filter
        if ($jenis != "" or $jenis != null) {
            $this->db->where("pw.id_kategorial", $jenis);
        }

        // if($tanggal != "" or $tanggal != null){
        //     $tanggal_awal = date("Y-m-d", strtotime($tanggal[0]));
        //     $tanggal_akhir = date("Y-m-d", strtotime($tanggal[1]));
        //     $this->db->where("pw.created_at >= ", $tanggal_awal);
        //     $this->db->where("pw.created_at <= ", $tanggal_akhir);
        // }

        // $this->db->order_by('pw.id', 'asc');


        // initial data table
        if ($draw == 1) {
            $this->db->limit(10, 0);
        }


        // pencarian
        if ($cari != null) {
            $this->db->where("(
                a.nama_depan LIKE '%$cari%' or
                a.nama_belakang LIKE '%$cari%' or
                a.tanggal_lahir LIKE '%$cari%' or
              )");
        }

        // pagination
        if ($show != null && $start != null) {
            $this->db->limit($show, $start);
        }

        $result = $this->db->get();
        return $result;
    }

    public function getReportKeaktifan($draw = null, $show = null, $start = null, $cari = null, $order = null, $jenis = null, $tanggal = null)
    {
        $this->db->reset_query();
        // select datatable
        $this->db->select("*, a.created_at as tanggal_daftar, c.nama as jabatan");
        $this->db->from("profile_keaktifan pw");
        $this->db->join("profile a", "pw.id_profile = a.id");
        $this->db->join("jabatan c", "c.id = pw.id_jabatan");
        $this->db->join("users b", "a.id_user = b.user_id", "left");
        $this->db->where("a.status !=", 0);
        $this->db->where("a.status !=", 3);
        $this->db->where("pw.status", 1);
        $this->db->group_by("a.id");
        $this->db->group_by("c.id");
        $this->db->group_by("pw.kode_master");
        $this->db->group_by("pw.text_master");
        $this->db->group_by("pw.tanggal_mulai");
        // filter
        if ($jenis != "" or $jenis != null) {
            $this->db->where("pw.kode_master", $jenis);
        }

        // if($tanggal != "" or $tanggal != null){
        //     $tanggal_awal = date("Y-m-d", strtotime($tanggal[0]));
        //     $tanggal_akhir = date("Y-m-d", strtotime($tanggal[1]));
        //     $this->db->where("pw.created_at >= ", $tanggal_awal);
        //     $this->db->where("pw.created_at <= ", $tanggal_akhir);
        // }

        // $this->db->order_by('pw.id', 'asc');


        // initial data table
        if ($draw == 1) {
            $this->db->limit(10, 0);
        }

        // pencarian
        if ($cari != null) {
            $this->db->where("(
                a.nama_depan LIKE '%$cari%' or
                a.nama_belakang LIKE '%$cari%' or
                a.tanggal_lahir LIKE '%$cari%' or
              )");
        }

        // pagination
        if ($show != null && $start != null) {
            $this->db->limit($show, $start);
        }

        $result = $this->db->get();
        return $result;
    }
}
