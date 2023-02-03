<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BiodataModel extends Render_Model
{
    public function getAllData($draw = null, $show = null, $start = null, $cari = null, $order = null, $filter = null)
    {
        // select datatable
        $pendidikan = <<<SQL
            (
                SELECT jenjang_pendidikan_terakhir FROM `profile_pendidikan` as p_pendidikan 
                WHERE p_pendidikan.id_profile = a.id ORDER BY p_pendidikan.tahun_lulus DESC Limit 1
            ) 
        SQL;
        $this->db->select("a.*, $pendidikan as pendidikan");
        $this->db->from("profile a");
        $this->db->join("users b", "a.id_user = b.user_id");
        $this->db->group_by('a.id');
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
                a.nama LIKE '%$cari%' or
                a.posisi LIKE '%$cari%' or
                $pendidikan LIKE '%$cari%' or 
                IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) LIKE '%$cari%'
                )"
            );
        }

        // pagination
        if ($show != null && $start != null) {
            $this->db->limit($show, $start);
        }

        $result = $this->db->get();
        return $result;
    }


   

    public function cekNew($id = null)
    {
        if ($id == null) {
            $cek = $this->db->select('a.*')->get_where("profile a", ["a.status" => 1000]);

            $this->db->trans_start();
            if ($cek->num_rows() == 0) {
                $this->db->insert("profile", [
                    "status" => 1000
                ]);
                $getID = $this->db->insert_id();
            } else {
                $getID = $cek->result_array()[0]['id'];
            }

            $return = $this->db->select('profile.*, users.*, level.*')->from('profile')->where('profile.id', $getID)
                ->join('users', 'profile.id_user = users.user_id', 'left')
                ->join('role_users', 'role_users.role_user_id = users.user_id', 'left')
                ->join('level', 'role_users.role_lev_id = level.lev_id', 'left')
                ->get()->row_array();
            $this->db->trans_complete();

            return $return;
        } else {
            $id = $this->db->select('profile.*, users.*, level.*')->from('profile')->where('profile.id', $id)
                ->join('users', 'profile.id_user = users.user_id', 'left')
                ->join('role_users', 'role_users.role_user_id = users.user_id', 'left')
                ->join('level', 'role_users.role_lev_id = level.lev_id', 'left')
                ->get()->row_array();
            return $id;
        }
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

    // pendidikan
    public function insert_pendidikan($id, $id_profile, $jenjang_pendidikan_terakhir, $nama_institusi_akademik, $jurusan, $tahun_lulus, $ipk, $status)
    {
        $this->db->trans_start();
        $insert = $this->db->insert('profile_pendidikan', [
            'id_profile' => $id_profile,
            'jenjang_pendidikan_terakhir' => $jenjang_pendidikan_terakhir,
            'nama_institusi_akademik' => $nama_institusi_akademik,
            'jurusan' => $jurusan,
            'tahun_lulus' => $tahun_lulus,
            'ipk' => $ipk,
            'status' => $status,
            'created_at' => date("Y-m-d H:i:s")
        ]);
        $this->db->trans_complete();
        return $insert;
    }

    public function update_pendidikan($id, $id_profile, $jenjang_pendidikan_terakhir, $nama_institusi_akademik, $jurusan, $tahun_lulus, $ipk, $status)
    {
        $this->db->trans_start();
        $this->db->where('id', $id);
        $update = $this->db->update('profile_pendidikan', [
            'jenjang_pendidikan_terakhir' => $jenjang_pendidikan_terakhir,
            'nama_institusi_akademik' => $nama_institusi_akademik,
            'jurusan' => $jurusan,
            'tahun_lulus' => $tahun_lulus,
            'ipk' => $ipk,
            'status' => $status,
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        

        $result = $this->db->get_where('profile_pendidikan', ["id" => $id])->result_array();
        $this->db->trans_complete();
        return $result;
    }

    public function getPendidikanByID($id = null, $order = null)
    {
        // select tabel
        $this->db->select("
                            a.*,
                            IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) as status_str
                            ");
        $this->db->from("profile_pendidikan a");
        $this->db->where("a.id", $id);

        $result = $this->db->get();
        return $result;
    }

    public function getPendidikan($draw = null, $show = null, $start = null, $cari = null, $order = null)
    {
        // select tabel
        $this->db->select("a.*,IF(a.status = '2' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) as status_str");
        $this->db->from("profile_pendidikan a");
        $this->db->where('a.status <>', 3);
        $this->db->where('a.id_profile', $order['profile']['id_profile']);

        $this->db->order_by('a.status', 'asc');

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
                a.jenjang_pendidikan_terakhir LIKE '%$cari%' OR
                a.nama_institusi_akademik LIKE '%$cari%' OR
                a.jurusan LIKE '%$cari%' OR
                a.tahun_lulus LIKE '%$cari%' OR
                a.ipk LIKE '%$cari%' OR
                IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) LIKE '%$cari%'
                )");
        }

        $result = $this->db->get();
        return $result;
    }

    // end pendidikan

    // pelatihan
    public function insert_pelatihan($id, $id_profile, $nama_kursus_seminar, $sertifikat, $tahun, $status)
    {
        $this->db->trans_start();
        $insert = $this->db->insert('profile_pelatihan', [
            'id_profile' => $id_profile,
            'nama_kursus_seminar' => $nama_kursus_seminar,
            'sertifikat' => $sertifikat,
            'tahun' => $tahun,
            'status' => $status,
            'created_at' => date("Y-m-d H:i:s")
        ]);
        $this->db->trans_complete();
        return $insert;
    }

    public function update_pelatihan($id, $id_profile, $nama_kursus_seminar, $sertifikat, $tahun, $status)
    {
        $this->db->trans_start();
        $this->db->where('id', $id);
        $update = $this->db->update('profile_pelatihan', [
            'nama_kursus_seminar' => $nama_kursus_seminar,
            'sertifikat' => $sertifikat,
            'tahun' => $tahun,
            'status' => $status,
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        

        $result = $this->db->get_where('profile_pelatihan', ["id" => $id])->result_array();
        $this->db->trans_complete();
        return $result;
    }

    public function getPelatihanByID($id = null, $order = null)
    {
        // select tabel
        $this->db->select("a.*,IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) as status_str");
        $this->db->from("profile_pelatihan a");
        $this->db->where("a.id", $id);

        $result = $this->db->get();
        return $result;
    }

    public function getPelatihan($draw = null, $show = null, $start = null, $cari = null, $order = null)
    {
        // select tabel
        $this->db->select("a.*,IF(a.status = '2' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) as status_str");
        $this->db->from("profile_pelatihan a");
        $this->db->where('a.status <>', 3);
        $this->db->where('a.id_profile', $order['profile']['id_profile']);

        $this->db->order_by('a.status', 'asc');

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
                a.nama_kursus_seminar LIKE '%$cari%' OR
                a.sertifikat LIKE '%$cari%' OR
                a.tahun LIKE '%$cari%' OR
                IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) LIKE '%$cari%'
                )");
        }

        $result = $this->db->get();
        return $result;
    }
    // end pelatihan

    // pekerjaan
    public function insert_pekerjaan($id, $id_profile, $nama_perusahaan, $posisi_terakhir, $pendapatan_terakhir, $tahun, $status)
    {
        $this->db->trans_start();
        $insert = $this->db->insert('profile_pekerjaan', [
            'id_profile' => $id_profile,
            'nama_perusahaan' => $nama_perusahaan,
            'posisi_terakhir' => $posisi_terakhir,
            'pendapatan_terakhir' => $pendapatan_terakhir,
            'tahun' => $tahun,
            'status' => $status,
            'created_at' => date("Y-m-d H:i:s")
        ]);
        $this->db->trans_complete();
        return $insert;
    }

    public function update_pekerjaan($id, $id_profile, $nama_perusahaan, $posisi_terakhir, $pendapatan_terakhir, $tahun, $status)
    {
        $this->db->trans_start();
        $this->db->where('id', $id);
        $update = $this->db->update('profile_pekerjaan', [
            'nama_perusahaan' => $nama_perusahaan,
            'posisi_terakhir' => $posisi_terakhir,
            'pendapatan_terakhir' => $pendapatan_terakhir,
            'tahun' => $tahun,
            'status' => $status,
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        

        $result = $this->db->get_where('profile_pekerjaan', ["id" => $id])->result_array();
        $this->db->trans_complete();
        return $result;
    }

    public function getPekerjaanByID($id = null, $order = null)
    {
        // select tabel
        $this->db->select("a.*,IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) as status_str");
        $this->db->from("profile_pekerjaan a");
        $this->db->where("a.id", $id);

        $result = $this->db->get();
        return $result;
    }

    public function getPekerjaan($draw = null, $show = null, $start = null, $cari = null, $order = null)
    {
        // select tabel
        $this->db->select("a.*,IF(a.status = '2' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) as status_str");
        $this->db->from("profile_pekerjaan a");
        $this->db->where('a.status <>', 3);
        $this->db->where('a.id_profile', $order['profile']['id_profile']);

        $this->db->order_by('a.status', 'asc');

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
                a.nama_perusahaan LIKE '%$cari%' OR
                a.posisi_terakhir LIKE '%$cari%' OR
                a.pendapatan_terakhir LIKE '%$cari%' OR
                a.tahun LIKE '%$cari%' OR
                IF(a.status = '0' , 'Tidak Aktif', IF(a.status = '1' , 'Aktif', 'Tidak Diketahui')) LIKE '%$cari%'
                )");
        }

        $result = $this->db->get();
        return $result;
    }
    // end pelatihan


    public function hapusDaftar($id, $tbl)
    {
        $this->db->where('id', $id);
        $result = $this->db->delete($tbl);
        return $result;
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

    public function nikCheck($no_ktp, $id_user)
    {
        return $this->db->select('no_ktp')
            ->from('profile')
            ->where('no_ktp', $no_ktp)
            ->where('id_user <> ', $id_user)
            ->get()
            ->row_array();
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
        $id_user,
        $posisi,
        $nama,
        $no_ktp,
        $tempat_lahir,
        $tanggal_lahir,
        $jenis_kelamin,
        $agama,
        $golongan_darah,
        $status,
        $alamat_ktp,
        $alamat_tinggal,
        $email,
        $no_telp,
        $orang_terdekat,
        $skill,
        $status_ditempatkan,
        $penghasilan
    ) {
        $this->db->trans_start();
        $level = $this->session->userdata('data')['level_id'];
        $this->db->reset_query();

        // simpan profile
        $this->db->where('id', $id);

        $data = [
            'id_user' => $id_user,
            'posisi' => $posisi,
            'nama' => $nama,
            'no_ktp' => $no_ktp,
            'tempat_lahir' => $tempat_lahir,
            'tanggal_lahir' => $tanggal_lahir,
            'jenis_kelamin' => $jenis_kelamin,
            'agama' => $agama,
            'golongan_darah' => $golongan_darah,
            'status' => $status,
            'alamat_ktp' => $alamat_ktp,
            'alamat_tinggal' => $alamat_tinggal,
            'email' => $email,
            'no_telp' => $no_telp,
            'orang_terdekat' => $orang_terdekat,
            'skill' => $skill,
            'status_ditempatkan' => $status_ditempatkan,
            'penghasilan' => $penghasilan,
            'created_at' => date("Y-m-d")
        ];
        
        $return = $this->db->update('profile', $data);

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
            if(isset($this->session->userdata('data')['id'])){
                $by = $this->session->userdata('data')['id'];
            }else{
                $by = 1;
            }
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
            if(isset($this->session->userdata('data')['id'])){
                $by = $this->session->userdata('data')['id'];
            }else{
                $by = 1;
            }
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


    public function delete($id_profile, $id_user)
    {
        $this->db->trans_start();
        $this->db->where('id', $id_profile);
        $profile = $this->db->delete('profile');


        $this->db->where('user_id', $id_user);
        $users = $this->db->delete('users');

        $this->db->reset_query();
        $this->db->where('id_profile', $id_profile);
        $pendidikan = $this->db->delete('profile_pendidikan');

        $this->db->reset_query();
        $this->db->where('id_profile', $id_profile);
        $pekerjaan = $this->db->delete('profile_pekerjaan');

        $this->db->reset_query();
        $this->db->where('id_profile', $id_profile);
        $pelatihan = $this->db->delete('profile_pelatihan');

        $this->db->trans_complete();
        return $profile;
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

}
