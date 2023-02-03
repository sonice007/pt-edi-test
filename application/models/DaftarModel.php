<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DaftarModel extends Render_Model
{
    public function submit($email, $password){
        $this->db->trans_start();
        $level = 124;
        $data['user_email']         = $email;
        $data['user_password']      = $this->b_password->bcrypt_hash($password);
        $data['user_status']        = 1;

        $data['user_email_status']  = 1;

        // Insert users
        $execute                    = $this->db->insert('users', $data);
        $execute1                   = $this->db->insert_id();

        $data2['role_user_id']      = $execute1;
        $data2['role_lev_id']       = $level;

        // Insert role users
        $execute2                   = $this->db->insert('role_users', $data2);
        $exe['id']                  = $execute1;
        $exe['level']               = $this->_cek('level', 'lev_id', $level, 'lev_nama');
        $this->db->trans_complete();
        return $exe;
    }

}
