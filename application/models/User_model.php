<?php 

class User_model extends MY_Model {

    function get_attendance($date){
        $query = $this->db->select('users.id, users.name , users.mobile, IFNULL(status,false) as status, date')
        ->from('users')
        ->join('users_attendance', 'users_attendance.user_id = users.id', 'left')
        ->where("date",$date)->get();
        return $query->result();
    }

}