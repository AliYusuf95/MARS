<?php 

class Admin_user_model extends MY_Model {

    public function getActiveAdminUsers()
    {
        $this->db->distinct();
        $this->db->select('admin_users.id as id, admin_users.name as name, IFNULL(admin_users.mobile,"-") as mobile');
        $this->db->from('admin_users');
        $this->db->where("active",1);
        return $this->db->get()->result_array();
    }

}