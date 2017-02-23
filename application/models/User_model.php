<?php 

class User_model extends MY_Model {

    public function getStudentsAttendance($section_id, $subject_id, $date)
    {
        return $this->db->distinct()
            ->select('user_id')
            ->from('users_attendance')
            ->where('section_id',$section_id)
            ->where('subject_id',$subject_id)
            ->where('date',$date)
            ->get()->result_array();
    }

}