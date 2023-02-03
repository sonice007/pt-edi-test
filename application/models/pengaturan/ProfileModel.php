<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProfileModel extends Render_Model
{
    public function getAllData($draw = null, $show = null, $start = null, $cari = null, $order = null, $filter = null)
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
        // if ($idpart != null && $idpart != 0 && $idpart != '-' && $this->session->userdata('data')['level'] != 'Super Admin') {
        //     $this->db->where("b.id_partner", $idpart);
        // }
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
        if ($filter != null) {
            // by partner
            if ($filter['partner'] != '') {
                if ($filter['partner'] == '-') {
                    $this->db->where('e.nama IS NULL');
                } else if ($filter['partner'] != NULL) {
                    $this->db->where('b.id_partner', $filter['partner']);
                }
            }

            // by posisi
            if ($filter['posisi'] != '' or $filter['posisi'] == null) {
                if ($filter['posisi'] == '-') {
                    $this->db->where('e.nama IS NULL');
                } else if ($filter['posisi'] != NULL) {
                    $this->db->where('b.id_posisi', $filter['posisi']);
                }
            }

            if ($filter['kategorial'] != '') {
                if ($filter['kategorial'] == '-') {
                    $this->db->where('j.nama IS NULL');
                } else if ($filter['kategorial'] != NULL) {
                    $this->db->where('j.id', $filter['kategorial']);
                }
            }
            
            if ($filter['wilayah'] != '') {
                if ($filter['wilayah'] == '-') {
                    $this->db->where('k.nama IS NULL');
                } else if ($filter['wilayah'] != NULL) {
                    $this->db->where('k.id', $filter['wilayah']);
                }
            }

            if ($filter['keanggotaan'] != '') {
                if ($filter['keanggotaan'] == '-') {
                    $this->db->where('a.id_keanggotaan IS NULL');
                } else if ($filter['keanggotaan'] != NULL) {
                    $this->db->where('a.id_keanggotaan', $filter['keanggotaan']);
                }
            }

            if ($filter['usia'] != '') {
                $usia = explode("-" , $filter['usia']);
                $usia_1 = $usia[0];
                $usia_2 = $usia[1];
                $this->db->where('(year(curdate())-year(a.tanggal_lahir)) >= ', $usia_1);
                $this->db->where('(year(curdate())-year(a.tanggal_lahir)) <= ', $usia_2);
            }

            if($filter['jenis_kelamin'] != ''){
                $this->db->where('a.jenis_kelamin', $filter['jenis_kelamin']);
            }

            if($filter['level'] != ''){
                $this->db->where('h.lev_id', $filter['level']);
            }

            if($filter['ulang_tahun'] != ''){

                $this->db->where('DATE_FORMAT(a.tanggal_lahir, "%m") = "'.date('m').'"');
            }
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
                    ORDER BY profile_kategorial.created_at DESC LIMIT 1 ), '' )) LIKE '%$cari%' or
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

    public function list_partner()
    {
        $return = $this->db->select('a.id, a.nama as text')
            ->from('partner a')
            ->where('status', '1')
            ->get()
            ->result_array();
        return $return;
    }

    public function list_posisi()
    {
        $return = $this->db->select('a.id, a.nama as text')
            ->from('posisi a')
            ->where('status', '1')
            ->get()
            ->result_array();
        return $return;
    }

    public function getContact($id = null, $order = null)
    {
        // select tabel
        $this->db->select("
                            a.*,
                            IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) as status_str
                            ");
        $this->db->from("contact a");
        $this->db->where("a.id", $id);

        // order by
        // if ($order['order'] != null) {
        //     $columns = $order['columns'];
        //     $dir = $order['order'][0]['dir'];
        //     $order = $order['order'][0]['column'];
        //     $columns = $columns[$order];

        //     $order_colum = $columns['data'];
        //     $this->db->order_by($order_colum, $dir);
        // }

        $result = $this->db->get();
        return $result;
    }

    public function getContact1($idp = null, $order = null)
    {
        // select tabel
        $this->db->select("
                            a.*,
                            b.nama as tipe_contact,
                            IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) as status_str
                            ");
        $this->db->from("contact a");
        $this->db->join("tipe_contact b", "a.id_tipe_contact = b.id", 'left');
        $this->db->where("a.id_profile", $idp);

        $result = $this->db->get();
        return $result;
    }

    public function getKeanggotaan($idp = null, $order = null)
    {
        // select tabel
        $this->db->select("
                            a.*,
                            a.tanggal as tanggal,
                            b.nama as nama,
                            b.keterangan as keterangan,
                            IF(a.status = '1' , 'Menunggu Konfirmasi', IF(a.status = '2' , 'Diterima', IF(a.status = '3' , 'Ditolak', 'Dinon-aktifkan'))) as status_str
                            ");
        $this->db->from("profile_keanggotaan a");
        $this->db->join("kategori_keanggotaan b", "a.id_kategori_keanggotaan = b.id", 'left');
        $this->db->where("a.id_profile", $idp);
        $this->db->order_by("a.id", "desc");
        $result = $this->db->get();
        return $result;
    }

    public function getIsiContactBy($where = [null])
    {
        $this->db->select("
                            concat(c.nama_depan, ' ', c.nama_belakang) as profile,
                            b.nama as tipe_contact,
                            a.keterangan,
                            IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) as status_str
                            ");
        $this->db->from("contact a");
        $this->db->join("tipe_contact b", "a.id_tipe_contact = b.id", 'left');
        $this->db->join("profile c", "a.id_profile = c.id", 'left');
        $this->db->where($where);
        $exe = $this->db->get();
        if ($exe->num_rows() > 0) {
            $exe = $exe->row_array();
        } else {
            $exe = ' ';
        }

        return $exe;
    }

    public function getAlamat($id = null, $idp = null, $order = null)
    {
        // select tabel
        $this->db->select("
                            a.*,
                            b.nama as jenis_alamat,
                            IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) as status_str
                            ");
        $this->db->from("alamat a");
        $this->db->join("jenis_alamat b", "a.id_jenis_alamat = b.id", 'left');
        $this->db->where("a.id_profile", $idp);
        if ($id != null) {
            $this->db->where("a.id", $id);
        }
        $this->db->order_by('a.status', 'desc');
        $this->db->order_by('a.tanggal_mulai', 'desc');
        // order by
        if ($order['order'] != null) {
            $columns = $order['columns'];
            $dir = $order['order'][0]['dir'];
            $order = $order['order'][0]['column'];
            $columns = $columns[$order];

            $order_colum = $columns['data'];
            $this->db->order_by($order_colum, $dir);
        }

        $result = $this->db->get();
        return $result;
    }

    public function getIsiAlamatBy($where = [null])
    {
        $this->db->select("
                            concat(c.nama_depan, ' ', c.nama_belakang) as profile,
                            a.*,
                            b.nama as jenis_alamat,
                            IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) as status_str
                            ");
        $this->db->from("alamat a");
        $this->db->join("jenis_alamat b", "a.id_jenis_alamat = b.id", 'left');
        $this->db->join("profile c", "a.id_profile = c.id", 'left');
        $this->db->where($where);
        $exe = $this->db->get();
        if ($exe->num_rows() > 0) {
            $exe = $exe->row_array();
        } else {
            $exe = ' ';
        }

        return $exe;
    }

    public function getFormal($id = null, $idp = null, $order = null)
    {
        // select tabel
        $this->db->select("
                            a.*, a.status as status,
                            b.name as peristiwa,
                            c.nama as institusi,
                            d.nama_depan,
                            d.nama_belakang,
                            IF(a.status = '0' , 'direncanakan', IF(a.status = '1' , 'disetujui', 'tidak disetujui')) as status_str,
                            IF(a.status_terlaksana = '0' , 'belum terlaksana', IF(a.status_terlaksana = '1' , 'terlaksana', 'tidak terlaksana')) as status2_str
                            ");
        $this->db->from("data_formal a");
        $this->db->join('data_formal_detail x', 'x.id_data_formal = a.id');
        $this->db->join('dtm_peristiwa b', 'a.dtm_peristiwa_id = b.id', 'left');
        $this->db->join('institusi c', 'c.id = a.id_institusi', 'left');
        $this->db->join('profile d', 'd.id = a.idjabatan', 'left');
        $this->db->where("x.id_profile", $idp);
        $this->db->group_by('a.id');
        if ($id != null) {
            $this->db->where("a.id", $id);
        }

        // order by
        if ($order['order'] != null) {
            $columns = $order['columns'];
            $dir = $order['order'][0]['dir'];
            $order = $order['order'][0]['column'];
            $columns = $columns[$order];

            $order_colum = $columns['data'];
            $this->db->order_by($order_colum, $dir);
        }

        $result = $this->db->get();
        return $result;
    }

    public function getIsiFormalBy($where = [null])
    {
        $this->db->select("
                            concat(c.nama_depan, ' ', c.nama_belakang) as profile,
                            b.name as peristiwa_formal,
                            a.keterangan,
                            a.tempat,
                            a.tanggal_data_formal,
                            IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) as status_str
                            ");
        $this->db->from("data_formal a");
        $this->db->join('dtm_peristiwa b', 'a.dtm_peristiwa_id = b.id', 'left');
        $this->db->join("profile c", "a.id_profile = c.id", 'left');
        $this->db->where($where);
        $exe = $this->db->get();
        if ($exe->num_rows() > 0) {
            $exe = $exe->row_array();
        } else {
            $exe = ' ';
        }

        return $exe;
    }

    public function getEducation($id = null, $idp = null, $order = null)
    {
        // select tabel
        $this->db->select("
                            a.*,
                            b.nama as nama,
                            IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) as status_str
                            ");
        $this->db->from("gelar a");
        $this->db->join("jenis_gelar b", "a.id_jenis_gelar = b.id", "left");
        $this->db->where("a.id_profile", $idp);
        if ($id != null) {
            $this->db->where("a.id", $id);
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
        }

        $result = $this->db->get();
        return $result;
    }

    public function getIsiEducationBy($where = [null])
    {
        $this->db->select("
                            concat(c.nama_depan, ' ', c.nama_belakang) as profile,
                            a.tanggal_lulus,
                            a.lembaga,
                            b.nama as jenis_gelar,
                            IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) as status_str
                            ");
        $this->db->from("gelar a");
        $this->db->join("jenis_gelar b", "a.id_jenis_gelar = b.id", "left");
        $this->db->join("profile c", "a.id_profile = c.id", 'left');
        $this->db->where($where);
        $exe = $this->db->get();
        if ($exe->num_rows() > 0) {
            $exe = $exe->row_array();
        } else {
            $exe = ' ';
        }

        return $exe;
    }

    public function cekNew($id = null)
    {
        if ($id == null) {
            $cek = $this->db->select('a.*,b.nama as kategori')->join('kategori_keanggotaan b', 'b.id = a.id_keanggotaan','left')->get_where("profile a", ["a.status" => 0]);

            $this->db->trans_start();
            if ($cek->num_rows() == 0) {
                $this->db->insert("profile", [
                    "status" => 0
                ]);
                $getID = $this->db->insert_id();
            } else {
                $getID = $cek->result_array()[0]['id'];
            }

            // $cekContact = $this->db->get_where("contact", ["id_profile" => $getID])->num_rows();

            // if ($cekContact == 0) {
            //     $this->db->insert("contact", [
            //         "id_profile" => $getID,
            //         "status" => 1,
            //         "created_at" => date("Y-m-d")
            //     ]);
            // }
            $return = $this->db->select('profile.*,kategori_keanggotaan.nama as keanggotaan, kategori_keanggotaan.id as id_keanggotaan, users.*, level.*')->from('profile')->where('profile.id', $getID)
                ->join('users', 'profile.id_user = users.user_id', 'left')
                ->join('role_users', 'role_users.role_user_id = users.user_id', 'left')
                ->join('level', 'role_users.role_lev_id = level.lev_id', 'left')
                ->join('kategori_keanggotaan', 'kategori_keanggotaan.id = profile.id_keanggotaan', 'left')
                ->get()->row_array();
            $this->db->trans_complete();

            return $return;
        } else {
            $id = $this->db->select('profile.*,kategori_keanggotaan.nama as keanggotaan, kategori_keanggotaan.id as id_keanggotaan, users.*, level.*')->from('profile')->where('profile.id', $id)
                ->join('users', 'profile.id_user = users.user_id', 'left')
                ->join('role_users', 'role_users.role_user_id = users.user_id', 'left')
                ->join('level', 'role_users.role_lev_id = level.lev_id', 'left')
                ->join('kategori_keanggotaan', 'kategori_keanggotaan.id = profile.id_keanggotaan', 'left')
                ->get()->row_array();
            return $id;
        }
    }

    public function getPathner($filter = null)
    {
        // diambil cuman pathner yang aktif == 1
        $this->db->select('id, nama as text');
        $this->db->from('partner');
        $this->db->where('status', 1);
        if ($filter != null) {
            // by partner
            if ($filter['partner'] != '') {
                $this->db->where('id', $filter['partner']);
            }
        }
        $return = $this->db->get()->result_array();
        return $return;
    }

    public function getLevel()
    {
        // diambil cuman pathner yang aktif == 1
        $this->db->select('lev_id, lev_nama');
        $this->db->from('level');
        $this->db->where('lev_status', 'Aktif');
        // if ($this->session->userdata('data')['level'] != 'Super Admin') {
        $this->db->where('lev_id !=', 1);
        // }
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function insert_contact($id, $id_profile, $tipe_kontak, $keterangan, $tanggal, $status)
    {
        $this->db->trans_start();
        $insert = $this->db->insert('contact', [
            'id_profile' => $id_profile,
            'id_tipe_contact' => $tipe_kontak,
            'keterangan' => $keterangan,
            'tanggal' => $tanggal,
            'status' => $status,
            'created_at' => date("Y-m-d H:i:s")
        ]);
        if ($insert) {
            $modul = $this->getMenuTitle();
            $id = $this->db->insert_id();
            $by = $this->getLogId();
            $jenis_perubahan = 1;
            $tipe_kontak = $this->getIsiTableById('tipe_contact', ['id' => $tipe_kontak])['nama'];
            $status = $status == 1 ? $status = 'Aktif' : ($status == 0 ? $status = 'Tidak Aktif' : 'Tidak Diketahui');
            $profile = $this->getIsiTableById('profile', ['id' => $id_profile]);
            $profile = $profile['nama_depan'] . ' ' . $profile['nama_belakang'];
            $data_lama = "Belum ada data";
            $data_baru = "Menambah data baru dengan isi profile = $profile tipe_kontak = $tipe_kontak, keterangan = $keterangan, status = $status";
            $this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
        }


        // $result = $this->db->get_where('alamat', ["id" => $id])->result_array();
        $this->db->trans_complete();
        return $insert;
    }

    public function update_contact($id, $tipe_kontak, $keterangan, $tanggal, $status)
    {
        $this->db->trans_start();
        $scontact = $this->getIsiContactBy(['a.id' => $id]);
        $this->db->where('id', $id);
        $update = $this->db->update('contact', [
            'id_tipe_contact' => $tipe_kontak,
            'keterangan' => $keterangan,
            'tanggal' => $tanggal,
            'status' => $status,
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        if ($update) {
            $modul = $this->getMenuTitle();
            $by = $this->getLogId();
            $jenis_perubahan = 2;
            $status = $status == 1 ? $status = 'Aktif' : ($status == 0 ? $status = 'Tidak Aktif' : 'Tidak Diketahui');
            $tipe_kontak = $this->getIsiTableById('tipe_contact', ['id' => $tipe_kontak])['nama'];
            $data_lama = "Isi data sebelumnya adalah tipe_kontak = $scontact[tipe_contact], keterangan = $scontact[keterangan], status = $scontact[status_str]";
            $data_baru = "Mengubah isi data sebelumnya menjadi tipe_contact = $tipe_kontak, keterangan = $keterangan, status = $status";
            $this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
        }


        $result = $this->db->get_where('contact', ["id" => $id])->result_array();
        $this->db->trans_complete();
        return $result;
    }

    public function insert_kategorial($id_kategorial, $id_profile, $tanggal)
    {
        $this->db->trans_start();
        $insert = $this->db->insert('profile_kategorial', [
            'id_profile' => $id_profile,
            'id_kategorial' => $id_kategorial,
            'tanggal' => $tanggal,
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s")
        ]);
        if ($insert) {
            $upd['id_kategorial'] = $id_kategorial;
            $this->db->where('id', $id_profile);
            $this->db->update('profile', $upd);

            $modul = $this->getMenuTitle();
            $id = $this->db->insert_id();
            $by = $this->getLogId();
            $jenis_perubahan = 1;
            $status = 1;
            $status = $status == 1 ? $status = 'Aktif' : ($status == 0 ? $status = 'Tidak Aktif' : 'Tidak Diketahui');
            $profile = $this->getIsiTableById('profile', ['id' => $id_profile]);
            $profile = $profile['nama_depan'] . ' ' . $profile['nama_belakang'];
            $get_kategorial = $this->db->get_where("kategorial a", ['a.id' => $id_kategorial])->row_array();
            $data_lama = "Belum ada data";
            $data_baru = "Menambah data baru dengan isi profile = $profile kategorial = ".$get_kategorial['nama'].", tanggal = $tanggal, status = $status";
            $this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
        }


        // $result = $this->db->get_where('alamat', ["id" => $id])->result_array();
        $this->db->trans_complete();
        return $insert;
    }

    public function update_kategorial($id, $id_kategorial, $id_profile, $tanggal)
    {
        $this->db->trans_start();

        $kategorial_old = $this->db->select('b.nama, a.status')->join('kategorial b', 'b.id = a.id_kategorial')->get_where("profile_kategorial a", ['a.id' => $id])->row_array();

        


        $this->db->where('id', $id);
        $update = $this->db->update('profile_kategorial', [
            'id_kategorial' => $id_kategorial,
            'tanggal' => $tanggal,
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        if ($update) {
            $modul = $this->getMenuTitle();
            $by = $this->getLogId();
            $jenis_perubahan = 2;
            $status = 1;
            $status = $status == 1 ? $status = 'Aktif' : ($status == 0 ? $status = 'Tidak Aktif' : 'Tidak Diketahui');
            $profile = $this->getIsiTableById('profile', ['id' => $id_profile]);
            $profile = $profile['nama_depan'] . ' ' . $profile['nama_belakang'];

            $get_kategorial = $this->db->get_where("kategorial a", ['a.id' => $id_kategorial])->row_array();
            $status_old = $kategorial_old['status'] == 1 ? $kategorial_old['status'] = 'Aktif' : ($kategorial_old['status'] == 0 ? $kategorial_old['status'] = 'Tidak Aktif' : 'Tidak Diketahui');
            $data_lama = "Isi data sebelumnya adalah tipe_kontak = $profile, kategorial = ".$kategorial_old['nama'].", status = ".$status_old;
            $data_baru = "Mengubah isi data sebelumnya menjadi profile = $profile, kategorial = ".$get_kategorial['nama'].", status = $status";
            $this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
        }


        $result = $this->db->get_where('profile_kategorial', ["id" => $id])->result_array();
        $this->db->trans_complete();
        return $result;
    }

    public function insert_kategori_keanggotaan($id_keanggotaan, $id_profile, $tanggal)
    {
        $this->db->trans_start();
        $insert = $this->db->insert('profile_keanggotaan', [
            'id_profile' => $id_profile,
            'id_kategori_keanggotaan' => $id_keanggotaan,
            'tanggal' => $tanggal,
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s")
        ]);
        if ($insert) {
        
            $modul = $this->getMenuTitle();
            $id = $this->db->insert_id();
            $by = $this->getLogId();
            $jenis_perubahan = 1;
            $status = 1;
            $status = $status == 1 ? $status = 'Aktif' : ($status == 0 ? $status = 'Tidak Aktif' : 'Tidak Diketahui');
            $profile = $this->getIsiTableById('profile', ['id' => $id_profile]);
            $profile = $profile['nama_depan'] . ' ' . $profile['nama_belakang'];
            $data_lama = "Belum ada data";
            $data_baru = "Menambah data baru dengan isi profile = $profile keanggotaan = $id_keanggotaan, tanggal = $tanggal, status = $status";
            $this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
        }


        // $result = $this->db->get_where('alamat', ["id" => $id])->result_array();
        $this->db->trans_complete();
        return $insert;
    }

    public function update_kategori_keanggotaan($id, $id_keanggotaan, $tanggal, $id_profile)
    {
        $this->db->trans_start();
        $scontact = $this->getIsiContactBy(['a.id' => $id]);
        $this->db->where('id', $id);
        $update = $this->db->update('profile_keanggotaan', [
            'id_kategori_keanggotaan' => $id_keanggotaan,
            'tanggal' => $tanggal,
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        if ($update) {
            $get_keanggotaan = $this->db->get_where('kategori_keanggotaan',['id' => $id_keanggotaan])->row_array();
            $upd2['id_keanggotaan'] = $id_keanggotaan;
            $upd2['kode_keanggotaan'] = $get_keanggotaan['nama'];
            $this->db->where('id', $id_profile);
            $this->db->update('profile', $upd2);

            $modul = $this->getMenuTitle();
            $by = $this->getLogId();
            $jenis_perubahan = 2;
            $status = 1;
            $status = $status == 1 ? $status = 'Aktif' : ($status == 0 ? $status = 'Tidak Aktif' : 'Tidak Diketahui');
            // $tipe_kontak = $this->getIsiTableById('tipe_contact', ['id' => $tipe_kontak])['nama'];
            // $data_lama = "Isi data sebelumnya adalah tipe_kontak = $scontact[tipe_contact], keterangan = $scontact[keterangan], status = $scontact[status_str]";
            // $data_baru = "Mengubah isi data sebelumnya menjadi tipe_contact = $tipe_kontak, keterangan = $keterangan, status = $status";
            // $this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
        }


        $result = $this->db->get_where('profile_keanggotaan', ["id" => $id])->result_array();
        $this->db->trans_complete();
        return $result;
    }

    public function ubah_status_kategori_keanggotaan($id, $id_profile, $status)
    {
        $this->db->trans_start();
        $this->db->where('id', $id);
        $update = $this->db->update('profile_keanggotaan', [
            'status' => $status,
            'id_approver' => $this->id_profile,
            'tanggal_approval' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);

        if($update){
            if($status != 3){
                $get_detail = $this->db->get_where('profile_keanggotaan', ['id' => $id])->row_array();
                $get_keanggotaan = $this->db->get_where('kategori_keanggotaan',['id' => $get_detail['id_kategori_keanggotaan']])->row_array();
                $upd2['id_keanggotaan'] = $get_keanggotaan['id'];
                $upd2['kode_keanggotaan'] = $get_keanggotaan['nama'];
                $this->db->where('id', $id_profile);
                $this->db->update('profile', $upd2);
            }
        }

        if($status == 2){
            $upd['status'] = 4;
            $this->db->where('id != ', $id);
            $this->db->where('id_profile', $id_profile);
            $this->db->where('status', 2);
            $this->db->update('profile_keanggotaan', $upd);
        }

        $result = $this->db->get_where('profile_keanggotaan', ["id" => $id])->result_array();
        $this->db->trans_complete();
        return $result;
    }

    public function ubah_status_formal($id, $id_profile, $status)
    {
        $this->db->trans_start();
        $this->db->where('id', $id);
        $update = $this->db->update('data_formal', [
            'id_approval' => $id_profile,
            'tanggal_approval' => date("Y-m-d H:i:s"),
            'idjabatan' => $id_profile,
            'status' => $status,
            'updated_at' => date("Y-m-d H:i:s")
        ]);

        $result = $this->db->get_where('data_formal', ["id" => $id])->result_array();
        $this->db->trans_complete();
        return $result;
    }

    public function insert_wilayah($id_wilayah, $id_profile, $tanggal, $status_wilayah)
    {
        $this->db->trans_start();
        $insert = $this->db->insert('profile_wilayah', [
            'id_profile' => $id_profile,
            'id_wilayah' => $id_wilayah,
            'tanggal' => $tanggal,
            'status' => $status_wilayah,
            'created_at' => date("Y-m-d H:i:s")
        ]);
        $id = $this->db->insert_id();

        if ($insert) {
            $upd['id_wilayah'] = $id_wilayah;
            $this->db->where('id', $id_profile);
            $this->db->update('profile', $upd);
            

            $modul = $this->getMenuTitle();
            $get_wilayah = $this->db->select("a.*,b.nama as nama")->join("wilayah b", "b.id = a.id_wilayah")->get_where("profile_wilayah a", ['a.id' => $id])->row_array();
            $by = $this->getLogId();
            $jenis_perubahan = 1;
            $status = 1;
            $status = $status == 1 ? $status = 'Aktif' : ($status == 0 ? $status = 'Tidak Aktif' : 'Tidak Diketahui');
            $profile = $this->getIsiTableById('profile', ['id' => $id_profile]);
            $profile = $profile['nama_depan'] . ' ' . $profile['nama_belakang'];
            $data_lama = "Belum ada data";
            $data_baru = "Menambah data baru dengan isi profile = ".$profile.", wilayah = ".$get_wilayah['nama'].", tanggal = ".$get_wilayah['tanggal'].", status = ".$get_wilayah['status']."";
            $this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
        }

        $this->db->trans_complete();
        return $insert;
    }

    public function update_wilayah($id, $id_wilayah, $id_profile, $tanggal, $status_wilayah)
    {
        $this->db->trans_start();
        $wilayah_old = $this->db->select('b.nama, a.status, a.tanggal')->join('wilayah b', 'b.id = a.id_wilayah')->get_where("profile_wilayah a", ['a.id' => $id])->row_array();
        
        $this->db->where('id', $id);
        $update = $this->db->update('profile_wilayah', [
            'id_wilayah' => $id_wilayah,
            'tanggal' => $tanggal,
            'status' => $status_wilayah,
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        if ($update) {
            $modul = $this->getMenuTitle();
            $by = $this->getLogId();
            $jenis_perubahan = 2;
            $status = 1;
            $status = $status == 1 ? $status = 'Aktif' : ($status == 0 ? $status = 'Tidak Aktif' : 'Tidak Diketahui');
            $profile = $this->getIsiTableById('profile', ['id' => $id_profile]);
            $profile = $profile['nama_depan'] . ' ' . $profile['nama_belakang'];

            $get_wilayah = $this->db->select("a.*,b.nama as nama")->join("wilayah b", "b.id = a.id_wilayah")->get_where("profile_wilayah a", ['a.id' => $id])->row_array();

            $status_old = $wilayah_old['status'] == 1 ? $wilayah_old['status'] = 'Aktif' : ($wilayah_old['status'] == 0 ? $wilayah_old['status'] = 'Tidak Aktif' : 'Tidak Diketahui');

            $data_lama = "Isi data sebelumnya adalah profile = $profile, wilayah = ".$wilayah_old['nama'].", status = ".$status_old." tanggal = ".$wilayah_old['tanggal'];
            $data_baru = "Mengubah isi data sebelumnya menjadi profile = $profile, wilayah = ".$get_wilayah['nama'].", tanggal = ".$get_wilayah['tanggal']." status = ".$status."";
            $this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
        }


        $result = $this->db->get_where('profile_wilayah', ["id" => $id])->result_array();
        $this->db->trans_complete();
        return $result;
    }

    public function insert_keaktifan($id_jabatan, $id_profile, $tanggal_mulai, $tanggal_selesai, $kode_master, $id_master, $text_master)
    {
        $this->db->trans_start();
        if($tanggal_selesai == "" or $tanggal_selesai == null){
            $status = 1;
        }else{
            $status = 2;
        }
        $insert = $this->db->insert('profile_keaktifan', [
            'id_profile' => $id_profile,
            'id_jabatan' => $id_jabatan,
            'tanggal_mulai' => $tanggal_mulai,
            'tanggal_selesai' => $tanggal_selesai,
            'kode_master' => $kode_master,
            'id_master' => $id_master,
            'text_master' => $text_master,
            'status' => $status,
            'created_at' => date("Y-m-d H:i:s")
        ]);
        if ($insert) {

            $id = $this->db->insert_id();
            
            $get_data = $this->db->select("b.nama_depan, b.nama_belakang, c.nama as jabatan, a.tanggal_mulai, a.tanggal_selesai, a.kode_master, a.text_master")->join('profile b', 'b.id = a.id_profile','left')->join('jabatan c', 'c.id = a.id_jabatan','left')->get_where('profile_keaktifan a', ['a.id' => $id])->row_array();
            $modul = $this->getMenuTitle();
            $by = $this->getLogId();
            $jenis_perubahan = 1;
            $status = 1;
            $status = $status == 1 ? $status = 'Aktif' : ($status == 0 ? $status = 'Tidak Aktif' : 'Tidak Diketahui');
            $data_lama = "Belum ada data";
            $data_baru = "Menambah Data Member - Keaktifan baru dengan isi Nama Lengkap = ".$get_data['nama_depan']." ".$get_data['nama_belakang'].", Jabatan = ".$get_data['jabatan'].", Tanggal Mulai = ".$get_data['tanggal_mulai'].", Tanggal Selesai = ".$get_data['tanggal_selesai'].", Pilihan = ".$get_data['kode_master'].", Master = ".$get_data['text_master'].", Status = ".$status."";
            $this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
        }

        $this->db->trans_complete();
        return $insert;
    }

    public function update_keaktifan($id, $id_jabatan, $id_profile, $tanggal_mulai, $tanggal_selesai, $kode_master, $id_master, $text_master)
    {
        $this->db->trans_start();

        $get_data_lama = $this->db->select("b.nama_depan, b.nama_belakang, c.nama as jabatan, a.tanggal_mulai, a.tanggal_selesai, a.kode_master, a.text_master")->join('profile b', 'b.id = a.id_profile','left')->join('jabatan c', 'c.id = a.id_jabatan','left')->get_where('profile_keaktifan a', ['a.id' => $id])->row_array();

        if($tanggal_selesai == "" or $tanggal_selesai == null){
            $status = 1;
        }else{
            $status = 2;
        }


        $this->db->where('id', $id);
        $update = $this->db->update('profile_keaktifan', [
            'id_profile' => $id_profile,
            'id_jabatan' => $id_jabatan,
            'tanggal_mulai' => $tanggal_mulai,
            'tanggal_selesai' => $tanggal_selesai,
            'kode_master' => $kode_master,
            'id_master' => $id_master,
            'text_master' => $text_master,
            'status' => $status,
            'created_at' => date("Y-m-d H:i:s")
        ]);
        if ($update) {
            $modul = $this->getMenuTitle();
            $by = $this->getLogId();
            $jenis_perubahan = 2;

            $get_data = $this->db->select("b.nama_depan, b.nama_belakang, c.nama as jabatan, a.tanggal_mulai, a.tanggal_selesai, a.kode_master, a.text_master")->join('profile b', 'b.id = a.id_profile','left')->join('jabatan c', 'c.id = a.id_jabatan','left')->get_where('profile_keaktifan a', ['a.id' => $id])->row_array();

            $status = $status == 1 ? $status = 'Aktif' : ($status == 0 ? $status = 'Tidak Aktif' : 'Tidak Diketahui');
            $data_lama = "Isi Data Member - Keaktifan sebelumnya adalah Nama Lengkap = ".$get_data_lama['nama_depan']." ".$get_data_lama['nama_belakang'].", Jabatan = ".$get_data_lama['jabatan'].", Tanggal Mulai = ".$get_data_lama['tanggal_mulai'].", Tanggal Selesai = ".$get_data_lama['tanggal_selesai'].", Pilihan = ".$get_data_lama['kode_master'].", Master = ".$get_data_lama['text_master'].", Status = ".$status."";

            $data_baru = "Mengubah isi Data Member - Keaktifan sebelumnya menjadi Nama Lengkap = ".$get_data['nama_depan']." ".$get_data['nama_belakang'].", Jabatan = ".$get_data['jabatan'].", Tanggal Mulai = ".$get_data['tanggal_mulai'].", Tanggal Selesai = ".$get_data['tanggal_selesai'].", Pilihan = ".$get_data['kode_master'].", Master = ".$get_data['text_master'].", Status = ".$status."";
            $this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
        }


        $result = $this->db->get_where('profile_keaktifan', ["id" => $id])->result_array();
        $this->db->trans_complete();
        return $result;
    }

    public function insert_alamat($id, $id_profile, $jenis_alamat, $alamat, $domisili, $tanggal_mulai, $tanggal_selesai, $status)
    {
        $this->db->trans_start();
        $insert = $this->db->insert('alamat', [
            'id_profile' => $id_profile,
            'id_jenis_alamat' => $jenis_alamat,
            'alamat' => $alamat,
            'domisili' => $domisili,
            'tanggal_mulai' => $tanggal_mulai,
            'tanggal_selesai' => $tanggal_selesai,
            'status' => $status,
            'created_at' => date("Y-m-d H:i:s")
        ]);
        if ($insert) {
            $modul = $this->getMenuTitle();
            $id = $this->db->insert_id();
            $by = $this->getLogId();
            $jenis_perubahan = 1;
            $status = $status == 1 ? $status = 'Aktif' : ($status == 0 ? $status = 'Tidak Aktif' : 'Tidak Diketahui');
            $jenis_alamat = $this->getIsiTableById('jenis_alamat', ['id' => $jenis_alamat])['nama'];
            $profile = $this->getIsiTableById('profile', ['id' => $id_profile]);
            $profile = $profile['nama_depan'] . ' ' . $profile['nama_belakang'];
            $data_lama = "Belum ada data";
            $data_baru = "Menambah data baru dengan isi profile = $profile, jenis_alamat = $jenis_alamat, alamat = $alamat, domisili = $domisili, tanggal_mulai = $tanggal_mulai, tanggal_selesai = $tanggal_selesai, status = $status";
            $this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
        }


        // $result = $this->db->get_where('alamat', ["id" => $id])->result_array();
        $this->db->trans_complete();
        return $insert;
    }

    public function update_alamat($id, $id_profile, $jenis_alamat, $alamat, $domisili, $tanggal_mulai, $tanggal_selesai, $status)
    {
        $this->db->trans_start();
        $salamat = $this->getIsiAlamatBy(['a.id' => $id]);
        $this->db->where('id', $id);
        $update = $this->db->update('alamat', [
            'id_profile' => $id_profile,
            'id_jenis_alamat' => $jenis_alamat,
            'alamat' => $alamat,
            'domisili' => $domisili,
            'tanggal_mulai' => $tanggal_mulai,
            'tanggal_selesai' => $tanggal_selesai,
            'status' => $status,
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        if ($update) {
            $modul = $this->getMenuTitle();
            $by = $this->getLogId();
            $jenis_perubahan = 2;
            $status = $status == 1 ? $status = 'Aktif' : ($status == 0 ? $status = 'Tidak Aktif' : 'Tidak Diketahui');
            $jenis_alamat = $this->getIsiTableById('jenis_alamat', ['id' => $jenis_alamat])['nama'];
            $profile = $this->getIsiTableById('profile', ['id' => $id_profile]);
            $profile = $profile['nama_depan'] . ' ' . $profile['nama_belakang'];
            $data_lama = "Isi data sebelumnya adalah  id_profile = $salamat[id_profile], jenis_alamat = $salamat[jenis_alamat], alamat = $salamat[alamat], domisili = $salamat[domisili], tanggal_mulai = $salamat[tanggal_mulai], tanggal_selesai = $salamat[tanggal_selesai], status = $salamat[status_str]";
            $data_baru = "Mengubah isi data sebelumnya menjadi profile = $profile, jenis_alamat = $jenis_alamat, alamat = $alamat, domisili = $domisili, tanggal_mulai = $tanggal_mulai, tanggal_selesai = $tanggal_selesai, status = $status";
            $this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
        }

        $result = $this->db->get_where('alamat', ["id" => $id])->result_array();
        $this->db->trans_complete();
        return $result;
    }

    public function insert_formal($id, $kode, $kode_tahun_bulan, $kode_kategori, $id_profile, $peristiwa_formal, $tempat, $tanggal_data_formal, $status, $keterangan, $keterangan_2, $rencana_tanggal, $id_profile_pasangan, $id_institusi, $idjabatan)
    {
        $this->db->trans_start();

        // cek apakah ada foto yang dikirim
        $ubah_foto = false;
        if ($_FILES['upload_hardcopy']['name'] != '') {
            // simpan foto
            $foto = $this->saveFileFormal();
            $ubah_foto = true;
            $file = $foto['data'];
        }else{
            $file = "";
        }

        $ubah_foto2 = false;
        if ($_FILES['photo']['name'] != '') {
            // simpan foto
            $foto2 = $this->saveFileFormal2();
            $ubah_foto2 = true;
            $file2 = $foto2['data'];
        }else{
            $file2 = "";
        }

        $insert = $this->db->insert('data_formal', [
            'kode' => $kode,
            'kode_tahun_bulan' => $kode_tahun_bulan,
            'kode_kategori' => $kode_kategori,
            'id_profile' => $id_profile,
            'dtm_peristiwa_id' => $peristiwa_formal,
            'keterangan' => $keterangan,
            'tempat' => $tempat,
            'photo' => $file2,
            'tanggal_data_formal' => $tanggal_data_formal,
            'keterangan_2' => $keterangan_2,
            'rencana_tanggal' => $rencana_tanggal,
            'upload_hardcopy' => $file,
            'status' => $status,
            'id_institusi' => $id_institusi,
            'idjabatan' => $idjabatan,
            'created_at' => date("Y-m-d H:i:s")
        ]);

        $id = $this->db->insert_id();

        $cek = $this->db->get_where('dtm_peristiwa',['id' => $peristiwa_formal])->row_array();
        if($cek != null){
            if($cek['kategori'] == 2){ // pernikahan
                $get_posisi = $this->db->select('b.id_posisi')->join('users b','b.user_id = a.id_user')->get_where('profile a', ['a.id' => $id_profile])->row_array();
                if($get_posisi != null){
                    $id_posisi = $get_posisi['id_posisi'];
                }else{
                    $id_posisi = 0;
                }
                $insert_detail = $this->db->insert('data_formal_detail',[
                    'id_data_formal' => $id,
                    'id_profile' => $id_profile,
                    'id_posisi' => $id_posisi,
                    'status' => 1,
                    'created_at' => date("Y-m-d H:i:s")
                ]);

                $get_posisi = $this->db->select('b.id_posisi')->join('users b','b.user_id = a.id_user')->get_where('profile a', ['a.id' => $id_profile_pasangan])->row_array();
                if($get_posisi != null){
                    $id_posisi = $get_posisi['id_posisi'];
                }else{
                    $id_posisi = 0;
                }
                $insert_detail = $this->db->insert('data_formal_detail',[
                    'id_data_formal' => $id,
                    'id_profile' => $id_profile_pasangan,
                    'id_posisi' => $id_posisi,
                    'status' => 1,
                    'created_at' => date("Y-m-d H:i:s")
                ]);
            }else{
                $get_posisi = $this->db->select('b.id_posisi')->join('users b','b.user_id = a.id_user')->get_where('profile a', ['a.id' => $id_profile])->row_array();
                if($get_posisi != null){
                    $id_posisi = $get_posisi['id_posisi'];
                }else{
                    $id_posisi = 0;
                }
                $insert_detail = $this->db->insert('data_formal_detail',[
                    'id_data_formal' => $id,
                    'id_profile' => $id_profile,
                    'id_posisi' => $id_posisi,
                    'status' => 1,
                    'created_at' => date("Y-m-d H:i:s")
                ]);
            }
        }


        if ($insert) {
            $modul = $this->getMenuTitle();
            $by = $this->getLogId();
            $jenis_perubahan = 1;
            $status = $status == 1 ? $status = 'Aktif' : ($status == 0 ? $status = 'Tidak Aktif' : 'Tidak Diketahui');
            $profile = $this->getIsiTableById('profile', ['id' => $id_profile]);
            $profile = $profile['nama_depan'] . ' ' . $profile['nama_belakang'];
            $peristiwa_formal = $this->getIsiTableById('dtm_peristiwa', ['id' => $peristiwa_formal])['name'];
            $data_lama = "Belum ada data";
            $data_baru = "Menambah data baru dengan isi profile = $profile, peristiwa_formal = $peristiwa_formal, keterangan = $keterangan, tempat = $tempat, tanggal_data_formal = $tanggal_data_formal, status = $status";
            $this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
        }


        $this->db->trans_complete();
        return $insert;
    }

    public function update_formal($id, $kode, $kode_tahun_bulan, $kode_kategori, $id_profile, $peristiwa_formal, $tempat, $tanggal_data_formal, $status, $keterangan, $keterangan_2, $rencana_tanggal, $nama_calon_1, $tanggal_lahir_calon_1, $nama_calon_2, $tanggal_lahir_calon_2, $id_institusi, $idjabatan)
    {
        $this->db->trans_start();
        $sdata_formal = $this->getIsiFormalBy(['a.id' => $id]);

        // cek apakah ada foto yang dikirim
        $ubah_foto = false;
        if ($_FILES['upload_hardcopy']['name'] != '') {
            // simpan foto
            $foto = $this->saveFileFormal();
            $ubah_foto = true;
            $file = $foto['data'];
        }else{
            $file = "";
        }

        $ubah_foto2 = false;
        if ($_FILES['photo']['name'] != '') {
            // simpan foto
            $foto2 = $this->saveFileFormal2();
            $ubah_foto2 = true;
            $file2 = $foto2['data'];
        }else{
            $file2 = "";
        }
        
        $this->db->where('id', $id);
        $update = $this->db->update('data_formal', [
            'kode' => $kode,
            'kode_tahun_bulan' => $kode_tahun_bulan,
            'kode_kategori' => $kode_kategori,
            'id_profile' => $id_profile,
            'dtm_peristiwa_id' => $peristiwa_formal,
            'tempat' => $tempat,
            'photo' => $file2,
            'keterangan' => $keterangan,
            'tanggal_data_formal' => $tanggal_data_formal,
            'keterangan_2' => $keterangan_2,
            'rencana_tanggal' => $rencana_tanggal,
            'upload_hardcopy' => $file,
            'status' => $status,
            'nama_calon_1' => $nama_calon_1,
            'tanggal_lahir_calon_1' => $tanggal_lahir_calon_1,
            'nama_calon_2' => $nama_calon_2,
            'tanggal_lahir_calon_2' => $tanggal_lahir_calon_2,
            'id_institusi' => $id_institusi,
            'idjabatan' => $idjabatan,
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        if ($update) {
            $modul = $this->getMenuTitle();
            $by = $this->getLogId();
            $jenis_perubahan = 2;
            $status = $status == 1 ? $status = 'Aktif' : ($status == 0 ? $status = 'Tidak Aktif' : 'Tidak Diketahui');
            $profile = $this->getIsiTableById('profile', ['id' => $id_profile]);
            $profile = $profile['nama_depan'] . ' ' . $profile['nama_belakang'];
            $data_lama = "Isi data sebelumnya adalah  profile = $sdata_formal[profile], peristiwa_formal = $sdata_formal[peristiwa_formal], keterangan = $sdata_formal[keterangan], tempat = $sdata_formal[tempat], tanggal_data_formal = $sdata_formal[tanggal_data_formal], status = $sdata_formal[status_str]";
            $data_baru = "Mengubah isi data sebelumnya menjadi profile = $profile, peristiwa_formal = $peristiwa_formal, keterangan = $keterangan, tempat = $tempat, tanggal_data_formal = $tanggal_data_formal, status = $status";
            $this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
        }

        $result = $this->db->get_where('alamat', ["id" => $id])->result_array();
        $this->db->trans_complete();
        return $result;
    }

    public function insert_education($id, $id_profile, $nama, $tanggal_lulus, $lembaga, $status)
    {
        $this->db->trans_start();
        $insert = $this->db->insert('gelar', [
            'id_profile' => $id_profile,
            'id_jenis_gelar' => $nama,
            'tanggal_lulus' => $tanggal_lulus,
            'lembaga' => $lembaga,
            'status' => $status,
            'created_at' => date("Y-m-d H:i:s")
        ]);
        if ($insert) {
            $modul = $this->getMenuTitle();
            $id = $this->db->insert_id();
            $by = $this->getLogId();
            $jenis_perubahan = 1;
            $profile = $this->getIsiTableById('profile', ['id' => $id_profile]);
            $profile = $profile['nama_depan'] . ' ' . $profile['nama_belakang'];
            $gelar = $this->getIsiTableById('jenis_gelar', ['id' => $nama])['nama'];
            $data_lama = "Belum ada data";
            $data_baru = "Menambah data baru dengan isi profile = $profile, gelar = $gelar, tanggal_lulus = $tanggal_lulus, lembaga = $lembaga, status = $status";
            $this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
        }


        $this->db->trans_complete();
        return $insert;
    }

    public function update_education($id, $id_profile, $nama, $tanggal_lulus, $lembaga, $status)
    {
        $this->db->trans_start();
        $sgelar = $this->getIsiEducationBy(['a.id' => $id]);
        $this->db->where('id', $id);
        $update = $this->db->update('gelar', [
            'id_profile' => $id_profile,
            'id_jenis_gelar' => $nama,
            'tanggal_lulus' => $tanggal_lulus,
            'lembaga' => $lembaga,
            'status' => $status,
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        if ($update) {
            $modul = $this->getMenuTitle();
            $by = $this->getLogId();
            $jenis_perubahan = 2;
            $profile = $this->getIsiTableById('profile', ['id' => $id_profile]);
            $profile = $profile['nama_depan'] . ' ' . $profile['nama_belakang'];
            $gelar = $this->getIsiTableById('jenis_gelar', ['id' => $nama])['nama'];
            // masih error
            $data_lama = "";
            // $data_lama = "Isi data sebelumnya adalah  id_profile = $id_profile, id_jenis_gelar = $sgelar[id_jenis_gelar], tanggal_lulus = $sgelar[tanggal_lulus], lembaga = $sgelar[lembaga], status = $sgelar[status_str]";
            $data_baru = "Mengubah isi data sebelumnya menjadi profile = $profile, gelar = $gelar, tanggal_lulus = $tanggal_lulus, lembaga = $lembaga, status = $status";
            $this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
        }

        $result = $this->db->get_where('alamat', ["id" => $id])->result_array();
        $this->db->trans_complete();
        return $result;
    }

    public function hapusDaftar($id, $tbl)
    {
        if ($tbl == 'alamat') {
            $sdata = $this->getIsiAlamatBy(['a.id' => $id]);
            $modul = 'PALM';
        }
        if ($tbl == 'data_formal') {
            $sdata = $this->getIsiFormalBy(['a.id' => $id]);
            $modul = 'PFML';
        }
        if ($tbl == 'gelar') {
            $sdata = $this->getIsiEducationBy(['a.id' => $id]);
            $modul = 'PEDU';
        }
        if ($tbl == 'membership') {
            $sdata = $this->getIsiMembershipBy(['a.id' => $id]);
            // $modul = 'PMBA';
            $modul = 'PMBH';
        }
        if ($tbl == 'contact') {
            $sdata = $this->getIsiContactBy(['a.id' => $id]);
            $modul = 'PKTK';
        }

        if ($tbl == 'profile_kategorial') {
            $sdata = $this->db->select("b.nama_depan, b.nama_belakang, c.nama, a.tanggal")->join('profile b', 'b.id = a.id_profile','left')->join('kategorial c', 'c.id = a.id_kategorial','left')->get_where('profile_kategorial a', ['a.id' => $id])->row_array();
            $data_lama = "Isi Data Member - Kategorial Sebelumnya adalah Nama Lengkap = ".$sdata['nama_depan']." ".$sdata['nama_belakang'].", Kategorial = ".$sdata['nama'].", Tanggal = ".$sdata['tanggal']." ";
            $data_baru = "Menghapus Data Member - Kategorial Berhasil";
        }

        if ($tbl == 'profile_wilayah') {
            $sdata = $this->db->select("b.nama_depan, b.nama_belakang, c.nama, a.tanggal")->join('profile b', 'b.id = a.id_profile','left')->join('wilayah c', 'c.id = a.id_wilayah','left')->get_where('profile_wilayah a', ['a.id' => $id])->row_array();
            $data_lama = "Isi Data Member - Wilayah Sebelumnya adalah Nama Lengkap = ".$sdata['nama_depan']." ".$sdata['nama_belakang'].", Wilayah = ".$sdata['nama'].", Tanggal = ".$sdata['tanggal']." ";
            $data_baru = "Menghapus Data Member - Wilayah Berhasil";
        }

        if ($tbl == 'profile_keanggotaan') {
            $sdata = $this->db->select("b.nama_depan, b.nama_belakang, c.nama as kode, c.keterangan, a.tanggal")->join('profile b', 'b.id = a.id_profile','left')->join('kategori_keanggotaan c', 'c.id = a.id_kategori_keanggotaan','left')->get_where('profile_keanggotaan a', ['a.id' => $id])->row_array();
            $data_lama = "Isi Data Member - Kategori Keanggotaan Sebelumnya adalah Nama Lengkap = ".$sdata['nama_depan']." ".$sdata['nama_belakang'].", Kode = ".$sdata['kode'].", Kategori = ".$sdata['keterangan'].", Tanggal = ".$sdata['tanggal']." ";
            $data_baru = "Menghapus Data Member - Kategori Keanggotaan Berhasil";
        }

        if ($tbl == 'profile_keaktifan') {
            $sdata = $this->db->select("b.nama_depan, b.nama_belakang, c.nama as jabatan, a.tanggal_mulai, a.tanggal_selesai, a.kode_master, a.text_master, a.status")->join('profile b', 'b.id = a.id_profile','left')->join('jabatan c', 'c.id = a.id_jabatan','left')->get_where('profile_keaktifan a', ['a.id' => $id])->row_array();
            if($sdata['status'] == 1){
                $status = "Aktif";
            }else{
                $status = "Tidak Aktif";
            }
            $data_lama = "Isi Data Member - Keaktifan Sebelumnya adalah  Nama Lengkap = ".$sdata['nama_depan']." ".$sdata['nama_belakang'].", Jabatan = ".$sdata['jabatan'].", Tanggal Mulai = ".$sdata['tanggal_mulai'].", Tanggal Selesai = ".$sdata['tanggal_selesai'].", Pilihan = ".$sdata['kode_master'].", Master = ".$sdata['text_master'].", Status = ".$status." ";
            $data_baru = "Menghapus Data Member - Keaktifan Berhasil";
        }



        $sdata = json_encode($sdata);
        $this->db->where('id', $id);
        $result = $this->db->delete($tbl);
        if ($result) {
            $modul = $this->getMenuTitle();
            $by = $this->getLogId();
            $jenis_perubahan = 3;
            $this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
        }
        return $result;
    }

    public function listmembership()
    {
        $return = $this->db->select('a.id, a.nama as text')
            ->from('jenis_membership a')
            ->where('status', '1')
            ->get()
            ->result_array();
        return $return;
    }

    public function listperistiwa()
    {
        $return = $this->db->select('a.id, a.name as text')
            ->from('dtm_peristiwa a')
            ->where('status', '1')
            ->get()
            ->result_array();
        return $return;
    }

    public function listjenisalamat()
    {
        $return = $this->db->select('a.id, a.nama as text')
            ->from('jenis_alamat a')
            ->where('status', '1')
            ->get()
            ->result_array();
        return $return;
    }

    public function listjenisgelar()
    {
        $return = $this->db->select('a.id, a.nama as text')
            ->from('jenis_gelar a')
            ->where('status', '1')
            ->get()
            ->result_array();
        return $return;
    }

    public function listtipekontak()
    {
        $return = $this->db->select('a.id, a.nama as text')
            ->from('tipe_contact a')
            ->where('status', '1')
            ->get()
            ->result_array();
        return $return;
    }


    public function listatasanmember($idprt = null)
    {
        $this->db->select("a.id, concat(a.nama_depan, ' ',a.nama_belakang, ' (', (select partner.nama from profile join users on profile.id_user = users.user_id join partner on users.id_partner = partner.id where profile.id = a.id limit 1), ')') as text")
            ->from("profile a")
            ->join("membership b", "a.id = b.id_profile", "left")
            ->join("jenis_membership c", "b.id_jenis_membership  = c.id", "left")
            ->join("users d", "a.id_user = d.user_id", "left")
            ->join("partner e", "e.id = d.id_partner", "left")
            ->where("a.status", "1")
            ->where("b.status", "1")
            ->where("c.id like", "1");
        if ($this->session->userdata('data')['level'] != 'Super Admin') {
            $this->db->where("e.id", $idprt);
        }
        $return = $this->db->get()->result_array();
        return $return;
    }

    public function list_atasan($id_level = null)
    {
        $this->db->select("a.id, concat(a.nama_depan, ' ',a.nama_belakang, ' (', (select partner.nama from profile join users on profile.id_user = users.user_id join partner on users.id_partner = partner.id where profile.id = a.id limit 1), ')') as text, d.user_email")
            ->from("profile a")
            ->join("membership b", "a.id = b.id_profile", "left")
            ->join("jenis_membership c", "b.id_jenis_membership  = c.id", "left")
            ->join("users d", "a.id_user = d.user_id", "left")
            ->join("partner e", "e.id = d.id_partner", "left")
            ->where("a.status", "1")
            ->where("b.status", "1");
            if($id_level == 2){
                $this->db->where("c.id like", 1);
            }
            
            if($id_level == 3){
                $this->db->where("c.id < ", 3);
            }

            if($id_level == 4){
                $this->db->where("c.id < ", 3);
            }

            if($id_level == 5){
                $this->db->where("c.id < ", 3);
            }
        
        $return = $this->db->get()->result_array();
        return $return;
    }

    public function emailCheck($email, $id_user)
    {
        return $this->db->select('user_email')
            ->from('users')
            ->where('user_email', $email)
            ->where('user_id <> ', $id_user)
            ->where('user_status <> ', 3)
            ->get()
            ->row_array();
    }

    public function nikCheck($nik, $id_user)
    {
        return $this->db->select('nik')
            ->from('users')
            ->where('nik', $nik)
            ->where('user_id <> ', $id_user)
            ->where('user_status <> ', 3)
            ->get()
            ->row_array();
    }

    public function getKontak($draw = null, $show = null, $start = null, $cari = null, $order = null)
    {
        // select tabel
        $this->db->select("a.*, b.nama as tipe_kontak,IF(a.status = '2' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) as status_str");
        $this->db->from("contact a");
        $this->db->join('tipe_contact b', 'a.id_tipe_contact = b.id', 'left');
        $this->db->where('a.status <>', 3);
        $this->db->where('a.id_profile', $order['profile']['id_profile']);

        $this->db->order_by('a.status' ,'asc');
        $this->db->order_by('a.tanggal' ,'desc');

        // order by
        if ($order['order'] != null) {
            $columns = $order['columns'];
            $dir = $order['order'][0]['dir'];
            $order = $order['order'][0]['column'];
            $columns = $columns[$order];

            $order_colum = $columns['data'];
            $this->db->order_by($order_colum, $dir);
        }

        

        // pencarian
        if ($cari != null) {
            $this->db->where("(
                b.nama LIKE '%$cari%' OR
                a.keterangan LIKE '%$cari%' OR
                IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) LIKE '%$cari%'
                )");
        }

        $result = $this->db->get();
        return $result;
    }

    public function getKategorial($draw = null, $show = null, $start = null, $cari = null, $order = null, $id = null)
    {
        // select tabel
        $this->db->select("a.*, b.nama,IF(a.status = '2' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) as status_str");
        $this->db->from("profile_kategorial a");
        $this->db->join('kategorial b', 'a.id_kategorial = b.id', 'left');
        // $this->db->where('a.status <>', 3);
        if($order['profile']['id_profile'] != null or $order['profile']['id_profile'] != ""){
            $this->db->where('a.id_profile', $order['profile']['id_profile']);
        }
    
        if($id != null){
            $this->db->where('a.id', $id);
        }

        $this->db->order_by('a.tanggal', 'desc');


        // order by
        if ($order['order'] != null) {
            $columns = $order['columns'];
            $dir = $order['order'][0]['dir'];
            $order = $order['order'][0]['column'];
            $columns = $columns[$order];

            $order_colum = $columns['data'];
            $this->db->order_by($order_colum, $dir);
        }

        // pencarian
        if ($cari != null) {
            $this->db->where("(
                b.nama LIKE '%$cari%' OR
                IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) LIKE '%$cari%'
                )");
        }

        $result = $this->db->get();
        return $result;
    }

    public function getKategoriKeanggotaan($draw = null, $show = null, $start = null, $cari = null, $order = null, $id = null)
    {
        // select tabel
        $this->db->select("a.*, b.id as id_keanggotaan,b.nama, b.keterangan as keterangan_keanggotaan, IF(a.status = '4' , 'Dinon-aktifkan', IF(a.status = '3' , 'Ditolak', IF(a.status = '2' , 'Diterima', IF(a.status = '1' , 'Menunggu konfirmasi', 'Tidak Diketahui')))) as status_str, ifnull(concat(d.nama_depan, ' ', d.nama_belakang), '') as nama_atasan,
        a.tanggal_approval");
        $this->db->from("profile_keanggotaan a");
        $this->db->join('kategori_keanggotaan b', 'a.id_kategori_keanggotaan = b.id', 'left');
        $this->db->join('profile d', 'a.id_approver = d.id', 'left');
        // $this->db->where('a.status <>', 3);
        
        if($order['profile']['id_profile'] != null or $order['profile']['id_profile'] != ""){
            $this->db->where('a.id_profile', $order['profile']['id_profile']);
        }
        
        if($id != null){
            $this->db->where('a.id', $id);
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
        }

        // pencarian
        if ($cari != null) {
            $this->db->where("(
                b.nama LIKE '%$cari%' OR
                IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) LIKE '%$cari%'
                )");
        }

        $result = $this->db->get();
        return $result;
    }

    public function getWilayah($draw = null, $show = null, $start = null, $cari = null, $order = null, $id = null)
    {
        // select tabel
        $this->db->select("a.*, a.status as status, b.nama,IF(a.status = '2' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) as status_str");
        $this->db->from("profile_wilayah a");
        $this->db->join('wilayah b', 'a.id_wilayah = b.id', 'left');
        $this->db->where('a.status <>', 3);
         if($order['profile']['id_profile'] != null or $order['profile']['id_profile'] != ""){
            $this->db->where('a.id_profile', $order['profile']['id_profile']);
        }
    
        if($id != null){
            $this->db->where('a.id', $id);
        }
        $this->db->order_by('a.tanggal', 'desc');
        // order by
        if ($order['order'] != null) {
            $columns = $order['columns'];
            $dir = $order['order'][0]['dir'];
            $order = $order['order'][0]['column'];
            $columns = $columns[$order];

            $order_colum = $columns['data'];
            $this->db->order_by($order_colum, $dir);
        }

        // pencarian
        if ($cari != null) {
            $this->db->where("(
                b.nama LIKE '%$cari%' OR
                IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) LIKE '%$cari%'
                )");
        }

        $result = $this->db->get();
        return $result;
    }

    public function getKeaktifan($draw = null, $show = null, $start = null, $cari = null, $order = null, $id = null)
    {
        // select tabel
        $this->db->select("a.*, b.nama, b.keterangan, IF(a.status = '2' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) as status_str");
        $this->db->from("profile_keaktifan a");
        $this->db->join('jabatan b', 'a.id_jabatan = b.id', 'left');
        $this->db->where('a.status <>', 3);
         if($order['profile']['id_profile'] != null or $order['profile']['id_profile'] != ""){
            $this->db->where('a.id_profile', $order['profile']['id_profile']);
        }
    
        if($id != null){
            $this->db->where('a.id', $id);
        }
        $this->db->order_by('a.tanggal_mulai', 'desc');
        // order by
        if ($order['order'] != null) {
            $columns = $order['columns'];
            $dir = $order['order'][0]['dir'];
            $order = $order['order'][0]['column'];
            $columns = $columns[$order];

            $order_colum = $columns['data'];
            $this->db->order_by($order_colum, $dir);
        }

        // pencarian
        if ($cari != null) {
            $this->db->where("(
                b.nama LIKE '%$cari%' OR
                IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) LIKE '%$cari%'
                )");
        }

        $result = $this->db->get();
        return $result;
    }

    public function getMembership($draw = null, $show = null, $start = null, $cari = null, $order = null)
    {
        // select tabel
        $this->db->select("a.id, a.id_profile,
        a.id_jenis_membership as id_jenis,
        a.id_approver,
        ifnull(concat(d.nama_depan, ' ', d.nama_belakang), '') as nama_atasan,
        a.tanggal_approval,
        b.nama,
        a.status,
        a.tanggal_anggota as tanggal,
        IF(a.status = '0' , 'Persetujuan dimintakan', IF(a.status = '1' , 'Disetujui', IF(a.status = '9' , 'Belum di minta persetujuan', IF(a.status = '2' , 'Ditolak', IF(a.status = '3' , 'Tidak Aktif', 'Tidak Diketahui'))))) as status_str,
        a.created_at,
        a.updated_at");
        $this->db->from("membership a");
        $this->db->join("jenis_membership b", "a.id_jenis_membership = b.id");
        // $this->db->join("membership_atasan c", "a.id_profile = c.id_profile and a.id = c.id_membership", "left");
        $this->db->join("profile d", "a.id_approver = d.id", "left");
        $this->db->where('a.id_profile', $order['profile']['id_profile']);
        $this->db->order_by("a.id", "DESC");

        // order by
        if ($order['order'] != null) {
            $columns = $order['columns'];
            $dir = $order['order'][0]['dir'];
            $order = $order['order'][0]['column'];
            $columns = $columns[$order];

            $order_colum = $columns['data'];
            $this->db->order_by($order_colum, $dir);
        }

        // // initial data table
        // if ($draw == 1) {
        //     $this->db->limit(10, 0);
        // }

        // pencarian
        if ($cari != null) {
            $this->db->where("(b.nama LIKE '%$cari%' or a.tanggal_anggota LIKE '%$cari%' or IF(a.status = '0' , 'Meminta Persetujuan', IF(a.status = '1' , 'Disetujui', IF(a.status = '2' , 'Ditolak', IF(a.status = '3' , 'Tidak Aktif', 'Tidak Diketahui')))) LIKE '%$cari%')");
        }

        // // pagination
        // if ($show != null && $start != null) {
        //     $this->db->limit($show, $start);
        // }

        $result = $this->db->get();
        return $result;
    }

    public function getIsiMembershipBy($where = [null])
    {
        $this->db->select("
        concat(e.nama_depan, ' ', e.nama_belakang) as profile,
        ifnull(concat(d.nama_depan, ' ', d.nama_belakang), '') as nama_atasan,
        b.nama as jenis_membership,
        a.tanggal_anggota,
        IF(a.status = '0' , 'Meminta Persetujuan', IF(a.status = '1' , 'Disetujui', IF(a.status = '2' , 'Ditolak', IF(a.status = '3' , 'Tidak Aktif', 'Tidak Diketahui')))) as status_str");
        $this->db->from("membership a");
        $this->db->join("jenis_membership b", "a.id_jenis_membership = b.id");
        $this->db->join("membership_atasan c", "a.id_profile = c.id_profile and a.id = c.id_membership", "left");
        $this->db->join("profile d", "c.id_profile_atasan = d.id", "left");
        $this->db->join("profile e", "c.id_profile = e.id", 'left');
        $this->db->where($where);
        $exe = $this->db->get();
        if ($exe->num_rows() > 0) {
            $exe = $exe->row_array();
        } else {
            $exe = ' ';
        }

        return $exe;
    }

    public function getIsiMembershiAtasanpBy($where = [null])
    {
        $this->db->select("
        concat(e.nama_depan, ' ', e.nama_belakang) as profile,
        b.nama as jenis_membership,
        ifnull(concat(d.nama_depan, ' ', d.nama_belakang), '') as nama_atasan,
        IF(a.status = '1' , 'Aktif', IF(a.status = '2' , 'Tidak Aktif', 'Tidak Diketahui')) as status_str");
        $this->db->from("membership a");
        $this->db->join("jenis_membership b", "a.id_jenis_membership = b.id");
        $this->db->join("membership_atasan c", "a.id_profile = c.id_profile and a.id = c.id_membership", "left");
        $this->db->join("profile d", "c.id_profile_atasan = d.id", "left");
        $this->db->join("profile e", "c.id_profile = e.id", 'left');
        $this->db->where($where);
        $exe = $this->db->get();
        if ($exe->num_rows() > 0) {
            $exe = $exe->row_array();
        } else {
            $exe = ' ';
        }

        return $exe;
    }

    public function membershipCheck($id_jenis, $id_profile)
    {
        return $this->db->select('id')
            ->from('membership')
            ->where('id_profile', $id_profile)
            ->where('id_jenis_membership', $id_jenis)
            ->where('status < ', 2)
            ->get()
            ->row_array();
    }

    public function getPosisi(){
        return $this->db->get_where('posisi', ['status' => 1])->result_array();
    }

    public function insert_membership($id_jenis, $id_profile, $tanggal, $id_atasan)
    {
        $this->db->trans_start();
        // $membership = $this->db->select('id, status')
        //     ->from('membership')
        //     ->where('id_profile', $id_profile)
        //     ->get();
        // $num_rows = $membership->num_rows();
        // $this->db->reset_query();

        // $this->db->insert('membership', [
        //     'id_profile' => $id_profile,
        //     'id_jenis_membership' => $id_jenis,
        //     'id_approver' => $this->session->userdata('data')['id'],
        //     'tanggal_anggota' => $tanggal,
        //     'status' => $num_rows > 0 ? 2 : 1
        // ]);
        if($id_jenis == 1){
            $smembership_atasan = $this->getIsiMembershiAtasanpBy(['a.id_profile' => $id_profile]);
        }elseif($id_jenis == 2){
            $smembership_atasan = $this->getIsiMembershiAtasanpBy(['a.id_profile' => $id_profile, 'b.id' => 1]);

        }elseif($id_jenis == 3){
            $smembership_atasan = $this->getIsiMembershiAtasanpBy(['a.id_profile' => $id_profile, 'b.id < ' => 3]);
            
        }elseif($id_jenis == 4){
            $smembership_atasan = $this->getIsiMembershiAtasanpBy(['a.id_profile' => $id_profile, 'b.id < ' => 3]);
            
        }elseif($id_jenis == 5){
            $smembership_atasan = $this->getIsiMembershiAtasanpBy(['a.id_profile' => $id_profile, 'b.id < ' => 3]);
            
        }
        $smembership = $this->getIsiMembershipBy(['a.id_profile' => $id_profile]);
        $smembership_atasan = json_encode($smembership_atasan);
        $smembership = json_encode($smembership);
        
        // code sebelum diubah
        // $this->db->where('id_profile', $id_profile);
        // $update_atasan = $this->db->update('membership_atasan', [
        //     'status' => 2
        // ]);
        // if ($update_atasan) {
        //     $modul = $this->getMenuTitle();
        //     $by = $this->getLogId();
        //     $jenis_perubahan = 2;
        //     $data_lama = "Isi data sebelumnya adalah $smembership_atasan";
        //     $data_baru = "Mengubah setiap data sebelumnya menjadi status = Tidak Aktif";
        //     $this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
        // }
        // $this->db->reset_query();
        // $this->db->where('id_profile', $id_profile);
        // $this->db->where('status', 1);
        // $update_member = $this->db->update('membership', [
        //     'status' => 3
        // ]);
        // if ($update_member) {
        //     $modul = $this->getMenuTitle();
        //     $by = $this->getLogId();
        //     $jenis_perubahan = 2;
        //     $data_lama = "Isi data sebelumnya adalah $smembership";
        //     $data_baru = "Mengubah setiap data sebelumnya menjadi status = Tidak Aktif";
        //     $this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
        // }
        // $this->db->reset_query();

        if ($id_jenis != 1) {
            $st = 9;
        } else {
            $st = 1;
        }

        $insert1 = $this->db->insert('membership', [
            'id_profile' => $id_profile,
            'id_jenis_membership' => $id_jenis,
            'id_approver' => null,
            'tanggal_anggota' => $tanggal,
            'status' => $st
        ]);

        $id_insert = $this->db->insert_id();
        if ($insert1) {
            $modul = $this->getMenuTitle();
            $by = $this->getLogId();
            $jenis_perubahan = 1;
            $st = $st == 0 ? $st = 'Meminta Persetujuan' : ($st == 1 ? $st = 'Disetujui' : ($st == 2 ? $st = 'Ditolak' : ($st == 3 ? $st = 'Tidak Aktif' : 'Tidak Diketahui')));

            if($id_jenis == 1){
                $this->db->reset_query();
                $this->db->where('id_profile', $id_profile);
                $this->db->where('status', 1);
                $this->db->where('id != ', $id_insert);
                $update_member = $this->db->update('membership', [
                    'status' => 3
                ]);
            }

            $profile = $this->getIsiTableById('profile', ['id' => $id_profile]);
            $profile = $profile['nama_depan'] . ' ' . $profile['nama_belakang'];
            $jenis_membership = $this->getIsiTableById('jenis_membership', ['id' => $id_jenis])['nama'];
            $approver = $this->getIsiTableById('users', ['user_id' => $by])['user_nama'];
            $data_lama = "Belum ada data";
            $data_baru = "Menambah data baru dengan isi profile = $profile, jenis_membership = $jenis_membership, approver = $approver, tanggal_anggota = $tanggal, status = $st";
            $this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
        }

        // $get_atasan = $this->db->get_where()->result_array();
        $insert2 = $this->db->insert('membership_atasan', [
            'id_profile' => $id_profile,
            'id_membership' => $id_insert,
            'id_profile_atasan' => 0,
            'status' => 1
        ]);
        if ($insert2) {
            $modul = $this->getMenuTitle();
            $id_ts = $this->db->insert_id();
            $by = $this->getLogId();
            $jenis_perubahan = 1;
            $st = $st == 0 ? $st = 'Meminta Persetujuan' : ($st == 1 ? $st = 'Disetujui' : ($st == 2 ? $st = 'Ditolak' : ($st == 3 ? $st = 'Tidak Aktif' : 'Tidak Diketahui')));
            $profile = $this->getIsiTableById('profile', ['id' => $id_profile]);
            $profile = $profile['nama_depan'] . ' ' . $profile['nama_belakang'];
            $profile_atasan = $this->getIsiTableById('profile', ['id' => $id_atasan]);
            $profile_atasan = $profile_atasan != ' ' ? ($profile_atasan['nama_depan'] . ' ' . $profile_atasan['nama_belakang']) : '';
            $jenis_membership = $this->getIsiTableById('jenis_membership', ['id' => $id_jenis])['nama'];
            $approver = $this->getIsiTableById('users', ['user_id' => $by])['user_nama'];
            $data_lama = "Belum ada data";
            $data_baru = "Menambah data baru dengan isi profile = $profile, jenis_membership = $jenis_membership, profile_atasan = $profile_atasan, status = Aktif";
            $this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
        }

        $this->db->trans_complete();
        return $id_insert;
    }

    public function set_st_membership($id_profile, $id_membership, $status, $id_atasan)
    {
        if($status == 1){
            // code sebelum diubah
            $smembership_atasan = $this->getIsiMembershiAtasanpBy(['a.id_profile' => $id_profile, 'c.id_profile_atasan' => $id_atasan]);
            $smembership = $this->getIsiMembershipBy(['a.id_profile' => $id_profile, 'c.id_profile_atasan' => $id_atasan]);
            $smembership_atasan = json_encode($smembership_atasan);
            $smembership = json_encode($smembership);

            $this->db->where('id_profile', $id_profile);
            $update_atasan = $this->db->update('membership_atasan', [
                'status' => 2
            ]);

            $this->db->where('id', $id_membership);
            $this->db->update('membership', ['id_approver' => $this->id_profile, 'tanggal_approval' => date('Y-m-d H:i:s')]);

            if ($update_atasan) {
                $modul = $this->getMenuTitle();
                $by = $this->getLogId();
                $jenis_perubahan = 2;
                $data_lama = "Isi data sebelumnya adalah $smembership_atasan";
                $data_baru = "Mengubah setiap data sebelumnya menjadi status = Tidak Aktif";
                $this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
            }
            $this->db->reset_query();
            $this->db->where('id_profile', $id_profile);
            $this->db->where('status', 1);
            $update_member = $this->db->update('membership', [
                'status' => 3
            ]);
            if ($update_member) {
                $modul = $this->getMenuTitle();
                $by = $this->getLogId();
                $jenis_perubahan = 2;
                $data_lama = "Isi data sebelumnya adalah $smembership";
                $data_baru = "Mengubah setiap data sebelumnya menjadi status = Tidak Aktif";
                $this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
            }
            $this->db->reset_query();
        }
            

        $smembership = $this->getIsiMembershipBy(['a.id' => $id_membership, 'a.id_profile' => $id_profile]);
        $this->db->where('id', $id_membership);
        $this->db->where('id_profile', $id_profile);
        $update =  $this->db->update('membership', [
            'status' => $status
        ]);
        if ($update) {
            $modul = $this->getMenuTitle();
            $by = $this->getLogId();
            $jenis_perubahan = 2;
            $status = $status == 0 ? $status = 'Meminta Persetujuan' : ($status == 1 ? $status = 'Disetujui' : ($status == 2 ? $status = 'Ditolak' : ($status == 3 ? $status = 'Tidak Aktif' : 'Tidak Diketahui')));
            $profile = $this->getIsiTableById('profile', ['id' => $id_profile]);
            $profile = $profile['nama_depan'] . ' ' . $profile['nama_belakang'];
            $data_lama = "Isi data sebelumnya adalah membership dengan profile = $profile, status = $smembership[status_str]";
            $data_baru = "Mengubah isi data membership dengan profile = $profile sebelumnya menjadi status = $status";
            $this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
        }
        return $update;
    }

    public function update_membership($id, $id_jenis, $id_profile, $tanggal, $id_atasan)
    {
        $this->db->where('id_profile', $id_profile);
        $this->db->where('id_membership', $id);
        // $this->db->where('id_profile_atasan', $id_atasan);
        $i = $this->db->get('membership_atasan')->num_rows();
        if ($i > 0) {
            $this->db->where('id_profile', $id_profile);
            $this->db->where('id_membership', $id);
            // $this->db->where('id_profile_atasan', $id_atasan);
            $this->db->update('membership_atasan', [
                'id_profile' => $id_profile,
                'id_membership' => $id,
                'id_profile_atasan' => $id_atasan,
                'status' => 1,
            ]);
        } else {
            $this->db->insert('membership_atasan', [
                'id_profile' => $id_profile,
                'id_membership' => $id,
                'id_profile_atasan' => $id_atasan,
            ]);
        }
        $this->db->reset_query();
        $smembership = $this->getIsiMembershipBy(['a.id' => $id]);
        $this->db->where('id', $id);
        $update =  $this->db->update('membership', [
            'id_profile' => $id_profile,
            'id_jenis_membership' => $id_jenis,
            'id_approver' => $this->session->userdata('data')['id'],
            'tanggal_anggota' => $tanggal
        ]);
        if ($update) {
            $modul = $this->getMenuTitle();
            $by = $this->getLogId();
            $jenis_perubahan = 2;
            $profile = $this->getIsiTableById('profile', ['id' => $id_profile]);
            $profile = $profile['nama_depan'] . ' ' . $profile['nama_belakang'];
            $profile_atasan = $this->getIsiTableById('profile', ['id' => $id_atasan]);
            $profile_atasan = $profile_atasan != ' ' ? ($profile_atasan['nama_depan'] . ' ' . $profile_atasan['nama_belakang']) : '';
            $jenis_membership = $this->getIsiTableById('jenis_membership', ['id' => $id_jenis])['nama'];
            $approver = $this->getIsiTableById('users', ['user_id' => $by])['user_nama'];
            // $data_lama = "Isi data sebelumnya adalah profile = $profile, jenis_membership = $smembership[jenis_membership], approver = $approver, tanggal_anggota = $smembership[tanggal_anggota]";
            $data_lama = "";
            $data_baru = "Mengubah isi data sebelumnya menjadi profile = $profile, jenis_membership = $jenis_membership, approver = $approver, tanggal_anggota = $tanggal";
            $this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
        }
        return $update;
    }

    public function set_active_membersip($id, $id_profile, $st_membership)
    {
        $this->db->trans_start();
        // get membership sebelumnya
        $smembership = json_encode($this->getIsiMembershipBy(['a.id_profile' => $id_profile]));
        $membership = $this->db->select('a.id, a.status, b.nama as jenis_membership')
            ->from('membership a')
            ->join('jenis_membership b', 'b.id = a.id_jenis_membership')
            ->where('a.id_profile', $id_profile)
            ->get();
        $num_rows = $membership->num_rows();
        $datas = $membership->result_array();
        $this->db->reset_query();

        if ($st_membership == 3) {
            $this->db->where('id', $id);
            $this->db->update('membership', ['status' => 3]);
            $modul = $this->getMenuTitle();
            $by = $this->getLogId();
            $jenis_perubahan = 2;
            $profile = $this->getIsiTableById('profile', ['id' => $id_profile]);
            $profile = $profile['nama_depan'] . ' ' . $profile['nama_belakang'];
            $data_lama = "Isi data sebelumnya adalah $smembership";
            $data_baru = "Mengubah data status = tidak aktif dimana profile = $profile";
            $this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
            $get_users = $this->db->join('profile b', 'b.id_user = a.user_id')->get_where('users a', ['b.id' => $id_profile])->row_array();
            if($membership->num_rows() > 0){
                $member = $membership->row_array()['jenis_membership'];                
            }else{
                $member = '';
            }
            $return['email'] = true;
            $return['member'] = $member;
        } else {
            // jika membership sama dengan 1 maka
            // membership itu tetap aktif
            if ($num_rows == 1) {
                $idq = $datas[0]['id'];
                $this->db->where('id', $idq);
                $this->db->update('membership', ['status' => 1]);
                // set level nya juga
                $this->db->reset_query();
            } else if ($num_rows >= 1) {
                // set mebership yang dikirim params jadi aktif
                // set level nya juga
                $this->db->where('id', $id);
                $this->db->update('membership', ['status' => 1]);
                $this->db->reset_query();
                // dan yang tidak di kirim params tidak aktif
                $this->db->where('id <>', $id);
                $this->db->where('id_profile <>', $id_profile);
                $this->db->where('status <>', 1);
                $this->db->update('membership', ['status' => 3]);
                $this->db->reset_query();

                $modul = $this->getMenuTitle();
                $by = $this->getLogId();
                $jenis_perubahan = 2;
                $profile = $this->getIsiTableById('profile', ['id' => $id_profile]);
                $profile = $profile['nama_depan'] . ' ' . $profile['nama_belakang'];
                $data_lama = "Isi data sebelumnya adalah $smembership";
                $data_baru = "Mengubah data status = 1 dimana profile = $profile lalu mengubah status member yang aktif/disetujui lain menjadi tidak aktif";
                $this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
                $return['email'] = false;
            }
        }
        $this->db->trans_complete();
        return $return;
    }

    public function sendConfirmEmailNotifikasi($id, $email, $member)
    {
        $this->load->model('loginModel', 'login');
        $content = '<p>Anda telah dinonaktifkan sebagai member '.$member.' dalam Aplikasi Audit System End to End. Terimakasih';
        // production
        // $this->send_email($email, $content, 'Member '.$member.' Dinonaktifkan');
        return (true);
    }

    public function getIsiProfileById($where = [null])
    {

        $this->db->select(" 
        `a` .*, b.id_partner, `h`.lev_nama, `b`.`nik`, `b`.`user_email` as `email`, b.user_email_status,
        IF(a.jenis_kelamin = '1', 'Laki-Laki', IF(a.jenis_kelamin = '2', 'Perempuan', 'Tidak Diketahui')) as jk,
        IF(a.status_verifikasi = '1', 'Approved',
        IF(a.status_verifikasi = '2', 'Rejected', 'Tidak Diketahui')) as st_ver,
        IF(a.status = '2', 'Tidak Aktif', IF(a.status = '1', 'Aktif', 'Tidak Diketahui')) as status_str,
        `e`.`nama` as `nama_partner`
        ");
        $this->db->from("profile a");
        $this->db->join("users b", "a.id_user = b.user_id", "left");
        $this->db->join("partner e", "e.id = b.id_partner", "left");
        $this->db->join("contact f", "f.id_profile = a.id", "left");
        $this->db->join('role_users g', 'g.role_user_id = b.user_id', 'left');
        $this->db->join('level h', 'g.role_lev_id = h.lev_id', 'left');
        $exe = $this->db->get();
        if ($exe->num_rows() > 0) {
            $exe = $exe->row_array();
        } else {
            $exe = ' ';
        }

        return $exe;
    }
    public function simpan(
        $id,
        $email,
        $nik,
        $nama_depan,
        $nama_belakang,
        $jk,
        $id_partner,
        $id_level,
        $change_email,
        $kode,
        $kode_keanggotaan,
        $kategori,
        $id_kategorial,
        $id_wilayah,
        $id_posisi,
        $flag,
        $tanggal_registrasi,
        $tanggal_lahir,
        $tanggal_meninggal
        // $tgl,
        // $approved
    ) {
        $this->db->trans_start();
        // $membership = $this->db->select('b.id_level')
        //     ->from('membership a')
        //     ->join('jenis_membership b', 'b.id = a.id_jenis_membership')
        //     ->where('a.id_profile', $id)
        //     ->where('a.status', 1)
        //     ->get()
        //     ->row_array();
        // $this->db->reset_query();

        // if ($membership == null) {
        //     $membership['id_level'] = 123;
        //     // insert membership juga wkwk
        //     $this->db->insert('membership', [
        //         'id_profile' => $id,
        //         'id_jenis_membership' => 123,
        //         'id_approver' => $this->session->userdata('data')['id'],
        //         'tanggal_anggota' => Date('Y-m-d'),
        //         'status' => 1,
        //     ]);
        //     $this->db->reset_query();
        // }

        // $level = $membership['id_level'];
        $level = $id_level;
        // user
        $user = $this->db->select('id_user')->from('profile')->where('id', $id)->get()->row_array();
        $id_user = $user['id_user'];
        $this->db->reset_query();

        // get no telepon
        $no_telepon = 0;
        $this->db->reset_query();
        // insert user
        if ($id_user == null) {
            $id_user = $this->user_insert($level, $nama_depan, $no_telepon, $email, '123456', 1, $nik, $id_partner, 0, $id, $id_posisi);
            $this->db->reset_query();
        }
        // update user
        else {
            $user_status = $this->db->select('user_email_status')->from('users')->where('user_id', $id_user)->get()->row_array();
            $user_status = $user_status == null ? 0 : $user_status['user_email_status'];
            $this->db->reset_query();
            if ($user_status == 0) {
                $change_email = 0;
            } else {
                $change_email = $change_email ? 0 : 1;
            };
            $this->user_update($id_user, $level, $nama_depan, $no_telepon, $email, '', 1, $nik, $id_partner, $change_email, $id, $id_posisi);
            $this->db->reset_query();
        }

        // cek apakah ada foto yang dikirim
        $ubah_foto = false;
        if ($_FILES['photo']['name'] != '') {
            // simpan foto
            $foto = $this->saveFile();
            $ubah_foto = true;

            $get_photo = $this->db->select('photo')->from('profile')->where('id', $id)->get()->row_array();
            $this->db->reset_query();
            // delete foto
            if ($get_photo != null) {
                if ($get_photo['photo'] != null && $get_photo['photo'] != '') {
                    $this->deleteFile($get_photo['photo']);
                }
            }
        }

        // simpan profile
        $sprofile = $this->getIsiProfileById(['id' => $id]);
        $this->db->where('id', $id);

        $kode_tahun_bulan = explode('-', $tanggal_registrasi);
        $kode_tahun_bulan = $kode_tahun_bulan[0].$kode_tahun_bulan[1];
        $data = [
            'id_user' => $id_user,
            'nama_depan' => $nama_depan,
            'nama_belakang' => $nama_belakang,
            'jenis_kelamin' => $jk == "Laki-Laki" ? 1 : 2,
            // 'status_verifikasi' => $approved == "Approved" ? 1 : 2,
            // 'tanggal_anggota' => $tgl,
            'kode' => $kode,
            'kode_tahun_bulan' => $kode_tahun_bulan,
            'kode_keanggotaan' => $kode_keanggotaan,
            'id_keanggotaan' => $kategori,
            'id_kategorial' => $id_kategorial,
            'id_wilayah' => $id_wilayah,
            'flag' => $flag,
            'tanggal_registrasi' => $tanggal_registrasi,
            'tanggal_lahir' => $tanggal_lahir,
            'tanggal_meninggal' => $tanggal_meninggal,
            'created_at' => date("Y-m-d"),
            'status' => 1
        ];
        // jika foto diubah
        if ($ubah_foto) {
            $data['photo'] = $foto['data'];
        }

        $return = $this->db->update('profile', $data);
        if ($return) {
            $modul = $this->getMenuTitle();
            $by = $this->getLogId();
            $jenis_perubahan = 2;
            $data_lama = "Isi data sebelumnya adalah id = $sprofile[id], nama_depan = $sprofile[nama_depan], nama_belakang = $sprofile[nama_belakang], jenis_kelamin = $sprofile[jk], status = $sprofile[status_str], photo = $sprofile[photo]";
            $data_baru = "Mengubah isi data sebelumnya menjadi id = $sprofile[id], nama_depan = $nama_depan, nama_belakang = $nama_belakang, jenis_kelamin = " . ($jk == "Laki-Laki" ? 1 : 2) . ", status = Aktif, photo = " . ($ubah_foto == true ? $foto['data'] : '') . "";
            $this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
        }

        // kirim


        $this->db->trans_complete();
        return $return;
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

    private function user_insert($level, $nama, $telepon, $username, $password, $status, $nik, $id_partner, $change_email, $id_profile, $id_posisi)
    {
        $this->db->trans_start();
        $data['user_email_status']             = $change_email;
        $data['user_nama']             = $nama;
        $data['user_email']         = $username;
        $data['user_password']         = $this->b_password->bcrypt_hash($password);
        $data['user_phone']         = $telepon ?? '';
        $data['user_status']         = $status;
        $data['nik']         = $nik;
        $data['id_partner']         = $id_partner;
        $data['id_posisi']         = $id_posisi;
        $data['user_tgl_lahir']         = null;

        // Insert users
        $execute                     = $this->db->insert('users', $data);
        $execute1                     = $this->db->insert_id();

        $data2['role_user_id']         = $execute1;
        $data2['role_lev_id']         = $level;

        // Insert role users
        $execute2                     = $this->db->insert('role_users', $data2);
        $exe['id']                     = $execute1;
        $exe['level']                 = $this->_cek('level', 'lev_id', $level, 'lev_nama');
        if ($execute && $execute2) {
            $modul           = $this->getMenuTitle();
            $role_id         = $this->db->insert_id();
            $by              = $this->getLogId();
            $jenis_perubahan = 1;
            $level = $this->getIsiTableById('level', ['lev_id' => $level])['lev_nama'];
            $partner = $this->getIsiTableById('partner', ['id' => $id_partner]);
            $partner != ' ' ? $partner = $partner['nama'] : $partner = ' ';
            $status = ($status == 0 ? $status = 'Tidak Aktif' : ($status == 1 ? $status = 'Aktif' : ($status == 2 ? $status = 'Pending' : 'Tidak Diketahui')));
            $data_lama       = "Belum ada data";
            $data_baru       = "Menambah data baru dengan isi id = $id_profile, level = $level, nik = $nik, partner = $partner, user_nama = $nama, user_email = $username, user_email_status = $change_email, user_phone = $telepon, user_status = $status";
            $this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
        }
        $this->db->trans_complete();


        return $execute1;
    }

    private function user_update($id, $level, $nama, $telepon, $username, $password, $status, $nik, $id_partner, $change_email, $id_profile, $id_posisi)
    {
        $this->db->trans_start();
        $susers = $this->getIsiUsersBy(['user_id' => $id]);
        $srole = $this->getIsiRoleBy(['role_user_id' => $id]);
        $data['user_email_status']             = (string)$change_email;
        $data['user_nama']             = $nama;
        $data['user_email']         = $username;
        $data['user_phone']         = $telepon ?? '';
        $data['user_status']         = $status;
        $data['id_partner']         = $id_partner;
        $data['id_posisi']         = $id_posisi;
        $data['nik']         = $nik;
        $data['user_tgl_lahir']         = null;
        $data['updated_at']         = Date("Y-m-d H:i:s", time());
        if ($password != '') {
            $data['user_password']         = $this->b_password->bcrypt_hash($password);
        }

        // Update users
        $execute                     = $this->db->where('user_id', $id);
        $execute                     = $this->db->update('users', $data);

        $data2['role_user_id']         = $id;
        $data2['role_lev_id']         = $level;

        // Update role users
        $execute2                     = $this->db->where('role_user_id', $id);
        $execute2                      = $this->db->update('role_users', $data2);
        $exe['id']                     = $id;
        $exe['level']                 = $this->_cek('level', 'lev_id', $level, 'lev_nama');
        if ($execute && $execute2) {
            $modul           = $this->getMenuTitle();
            $by              = $this->getLogId();
            $jenis_perubahan = 2;
            $level = $this->getIsiTableById('level', ['lev_id' => $level])['lev_nama'];
            $partner = $this->getIsiTableById('partner', ['id' => $id_partner]);
            $partner != ' ' ? $partner = $partner['nama'] : $partner = ' ';
            $status = ($status == 0 ? $status = 'Tidak Aktif' : ($status == 1 ? $status = 'Aktif' : ($status == 2 ? $status = 'Pending' : 'Tidak Diketahui')));
            $data_lama       = "Isi data sebelumnya adalah id = $id_profile, level = $srole[level], nik = $susers[nik], id_partner = $susers[id_partner], user_nama = $susers[user_nama], user_email = $susers[user_email], user_email_status = $susers[user_email_status], user_phone = $susers[user_phone], user_status = $susers[status_str]";
            $data_baru       = "Mengubah isi data sebelumnya menjadi id = $id_profile, level = $level, nik = $nik, partner = $partner, user_nama = $nama, user_email = $username, user_email_status = $change_email, user_phone = $telepon, user_status = $status";
            $this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
        }
        $this->db->trans_complete();



        return $execute;
    }

    private function saveFileFormal()
    {
        $config['upload_path']          = './files/data-formal/';
        $config['allowed_types']        = 'gif|jpg|png|jpeg|JPG|PNG|JPEG';
        $config['file_name']            = md5(uniqid("ktm_home", true));
        $config['overwrite']            = true;
        $config['max_size']             = 8024;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if ($this->upload->do_upload('upload_hardcopy')) {
            return [
                'status' => true,
                'data' => $this->upload->data("file_name"),
                'message' => 'Success'
            ];
        } else {
            return [
                'status' => false,
                'data' => null,
                'message' => $this->upload->display_errors('', '')
            ];
        }
    }

    private function saveFileFormal2()
    {
        $config['upload_path']          = './files/data-formal/photo/';
        $config['allowed_types']        = 'gif|jpg|png|jpeg|JPG|PNG|JPEG';
        $config['file_name']            = md5(uniqid("ktm_home", true));
        $config['overwrite']            = true;
        $config['max_size']             = 8024;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if ($this->upload->do_upload('photo')) {
            return [
                'status' => true,
                'data' => $this->upload->data("file_name"),
                'message' => 'Success'
            ];
        } else {
            return [
                'status' => false,
                'data' => null,
                'message' => $this->upload->display_errors('', '')
            ];
        }
    }

    private function saveFile()
    {
        $config['upload_path']          = './files/profile/';
        $config['allowed_types']        = 'gif|jpg|png|jpeg|JPG|PNG|JPEG';
        $config['file_name']            = md5(uniqid("ktm_home", true));
        $config['overwrite']            = true;
        $config['max_size']             = 8024;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if ($this->upload->do_upload('photo')) {
            return [
                'status' => true,
                'data' => $this->upload->data("file_name"),
                'message' => 'Success'
            ];
        } else {
            return [
                'status' => false,
                'data' => null,
                'message' => $this->upload->display_errors('', '')
            ];
        }
    }

    private function deleteFile($file)
    {
        $res_foto = true;
        if (file_exists('./files/profile/' . $file)) {
            $res_foto = unlink('./files/profile/' . $file);
        }
        return $res_foto;
    }

    public function delete($id_profile, $id_user)
    {
        $this->db->trans_start();
        $sprofile = $this->getIsiTableById('profile', ['id' => $id_profile]);
        $sprofile = json_encode($sprofile);
        $susers = $this->getIsiTableById('users', ['user_id' => $id_user]);
        $susers = json_encode($susers);
        $this->db->where('id', $id_profile);
        $profile = $this->db->update('profile', ['status' => 3, 'deleted_at' => date("Y-m-d H:i:s")]);
        if ($profile) {
            $modul = $this->getMenuTitle();
            $by = $this->getLogId();
            $jenis_perubahan = 3;
            $data_lama = "Isi data sebelumnya adalah $sprofile";
            $data_baru = "Menghapus data berhasil";
            $this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
        }
        $this->db->reset_query();
        $this->db->where('user_id', $id_user);
        $users = $this->db->update('users', ['user_status' => 3]);
        if ($users) {
            $modul = $this->getMenuTitle();
            $by = $this->getLogId();
            $jenis_perubahan = 3;
            $data_lama = "Isi data sebelumnya adalah $sprofile";
            $data_baru = "Menghapus data berhasil";
            $this->setActivityLog($modul, $by, $jenis_perubahan, $data_lama, $data_baru);
        }
        $this->db->trans_complete();
        return $profile && $users;
    }

    public function list_kategori($id_menu)
    {
        $return = $this->db->select('a.id, a.nama as text')
            ->from('kategori_keanggotaan a')
            ->where('status', '1')
            ->get()
            ->result_array();
        return $return;
    }

    public function list_master_keaktifan($id_pilihan)
    {
        $return = $this->db->select('a.id, a.nama as text')
            ->from('keaktifan_master a')
            ->join('keaktifan_pilihan b', 'b.id = a.id_pilihan_keaktifan')
            ->where('b.nama', $id_pilihan)
            ->where('a.status', '1')
            ->get()
            ->result_array();
        return $return;
    }

    public function list_kategorial()
    {
        $return = $this->db->select('a.id, a.nama as text')
            ->from('kategorial a')
            ->where('status', '1')
            ->get()
            ->result_array();
        return $return;
    }

    public function list_kategori_keanggotaan()
    {
        $return = $this->db->select('a.id, a.nama as text, a.keterangan')
            ->from('kategori_keanggotaan a')
            ->order_by('no_urut','asc')
            ->where('status', '1')
            ->get()
            ->result_array();
        return $return;
    }

    public function list_wilayah()
    {
        $return = $this->db->select('a.id, a.nama as text')
            ->from('wilayah a')
            ->where('status', '1')
            ->get()
            ->result_array();
        return $return;
    }

    public function list_jabatan()
    {
        $return = $this->db->select('a.id, a.nama as text')
            ->from('jabatan a')
            ->where('status', '1')
            ->get()
            ->result_array();
        return $return;
    }

    public function list_pengarah()
    {
        $return = $this->db->select('a.id, a.nama as text')
            ->from('pengarah a')
            ->where('status', '1')
            ->get()
            ->result_array();
        return $return;
    }

    public function list_komisi()
    {
        $return = $this->db->select('a.id, a.nama as text')
            ->from('komisi a')
            ->where('status', '1')
            ->get()
            ->result_array();
        return $return;
    }

    public function list_kepanitiaan()
    {
        $return = $this->db->select('a.id, a.nama as text')
            ->from('kepanitiaan a')
            ->where('status', '1')
            ->get()
            ->result_array();
        return $return;
    }

    public function list_keanggotaan()
    {
        $return = $this->db->select('a.id, a.nama as text')
            ->from('kategori_keanggotaan a')
            ->order_by('no_urut', 'asc')
            ->where('status', '1')
            ->get()
            ->result_array();
        return $return;
    }

    public function getDetailFormal($id)
    {
        $return = $this->db->select('a.*,b.nama as institusi, c.nama_depan, c.nama_belakang, d.id_partner, d.id_posisi')
            ->from('data_formal a')
            ->join('institusi b', 'b.id = a.id_institusi', 'left')
            ->join('profile c', 'c.id = a.idjabatan', 'left')
            ->join('users d', 'd.user_id = c.id_user', 'left')
            ->where('a.id', $id)
            ->get()
            ->row_array();
        return $return;
    }

    public function getDataPribadi($id)
    {
        $return = $this->db->select('a.*')
            ->from('profile a')
            ->where('a.id', $id)
            ->get()
            ->row_array();
        return $return;
    }

    public function getCalonSuami($id=null)
    {
        // 1 = suami
        // 8 = calon suami
        $id_posisi = 1;        
        $return = $this->db->select('a.*,b.id_partner as keluarga, b.id_posisi')
            ->from('profile a')
            ->join('users b', 'b.user_id = a.id_user', 'left')
            ->where('a.id', $id)
            ->where('b.id_posisi', $id_posisi)
            ->get()
            ->row_array();
        if($return == null){
            $id_posisi = 8;        
            $return = $this->db->select('a.*,b.id_partner as keluarga, b.id_posisi')
                ->from('profile a')
                ->join('users b', 'b.user_id = a.id_user', 'left')
                ->where('a.id', $id)
                ->where('b.id_posisi', $id_posisi)
                ->get()
                ->row_array(); 
        }
        return $return;
    }

    public function getCalonSuamiAlias($id=null)
    {
        // 2 = istri
        // 9 = calon istri
        $id_posisi = 2;        
        $return = $this->db->select('a.*,b.id_partner as keluarga, b.id_posisi')
            ->from('profile a')
            ->join('users b', 'b.user_id = a.id_user', 'left')
            ->where('a.id', $id)
            ->where('b.id_posisi', $id_posisi)
            ->get()
            ->row_array();
        if($return == null){
            $id_posisi = 9;        
            $return = $this->db->select('a.*,b.id_partner as keluarga, b.id_posisi')
                ->from('profile a')
                ->join('users b', 'b.user_id = a.id_user', 'left')
                ->where('a.id', $id)
                ->where('b.id_posisi', $id_posisi)
                ->get()
                ->row_array(); 
        }
        return $return;
    }

    public function getCalonSuamiTambah($keluarga=null){
        $id_posisi = 1;        
        $return = $this->db->select('a.*,b.id_partner as keluarga, b.id_posisi')
            ->from('profile a')
            ->join('users b', 'b.user_id = a.id_user', 'left')
            ->where('b.id_partner', $keluarga)
            ->where('b.id_posisi', $id_posisi)
            ->get()
            ->row_array();
        if($return == null){
            $id_posisi = 8;        
            $return = $this->db->select('a.*,b.id_partner as keluarga, b.id_posisi')
                ->from('profile a')
                ->join('users b', 'b.user_id = a.id_user', 'left')
                ->where('b.id_partner', $keluarga)
                ->where('b.id_posisi', $id_posisi)
                ->get()
                ->row_array(); 
        }
        return $return;
    }



    public function getCalonIstri($keluarga=null)
    {
        // 2 = istri
        // 9 = calon istri
        $id_posisi = 2;
        $return = $this->db->select('a.*,b.id_partner as keluarga, b.id_posisi')
            ->from('profile a')
            ->join('users b', 'b.user_id = a.id_user')
            ->where('b.id_partner', $keluarga)
            ->where('b.id_posisi', $id_posisi)
            ->get()
            ->row_array();
        if($return == null){
            $id_posisi = 9;
            $return = $this->db->select('a.*,b.id_partner as keluarga')
                ->from('profile a')
                ->join('users b', 'b.user_id = a.id_user')
                ->where('b.id_partner', $keluarga)
                ->where('b.id_posisi', $id_posisi)
                ->get()
                ->row_array();
        }
        return $return;
    }

    public function getCalonIstriAlias($keluarga=null)
    {
        // 1 = suami
        // 8 = calon suami
        $id_posisi = 1;
        $return = $this->db->select('a.*,b.id_partner as keluarga, b.id_posisi')
            ->from('profile a')
            ->join('users b', 'b.user_id = a.id_user')
            ->where('b.id_partner', $keluarga)
            ->where('b.id_posisi', $id_posisi)
            ->get()
            ->row_array();
        if($return == null){
            $id_posisi = 8;
            $return = $this->db->select('a.*,b.id_partner as keluarga')
                ->from('profile a')
                ->join('users b', 'b.user_id = a.id_user')
                ->where('b.id_partner', $keluarga)
                ->where('b.id_posisi', $id_posisi)
                ->get()
                ->row_array();
        }
        return $return;
    }
}


// membership aktif cuma satu belum
