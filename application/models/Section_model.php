<?php 

class Section_model extends MY_Model {

    public function getSectionStudents($sectionId)
    {
        $query = $this->db->select('users.id as id, name, IFNULL(mobile,"-") as mobile')
            ->from('users')
            ->join('users_sections', 'users.id = users_sections.user_id')
            ->where('users_sections.section_id',$sectionId)
            ->order_by('name', 'ASC')
            ->get()->result_array();

        foreach ($query as &$user){
            $result = $this->db->select('users_attendance.`status`')
                ->from('users_attendance')
                ->where('user_id',$user["id"])
                ->where('date',date("Y-m-d"))
                ->get()->result_array();
            $user['status'] = isset($result[0]['status']) ? $result[0]['status'] : false ;
        }

        return $query;
    }

    public function getAvailableSections($date)
    {
        $this->db->distinct();
        $this->db->select('sections.id as id, sections.title as title');
        $this->db->from('semesters');
        $this->db->join('subjects', 'semesters.id = subjects.semester_id');
        $this->db->join('classes_subjects', 'subjects.id = classes_subjects.subject_id');
        $this->db->join('classes', 'classes.id = classes_subjects.class_id');
        $this->db->join('sections', 'classes.id = sections.class_id');
        $this->db->where("start_date <=",$date);
        $this->db->where("end_date >=",$date);
        return $this->db->get()->result_array();
    }

    public function getAllSections()
    {
        return $this->db->select('sections.id as sectionId, sections.title as sectionTitle')
            ->select('subjects.id as subjectId, subjects.title as subjectTitle')
            ->from('admin_users_sections')
            ->join('sections','admin_users_sections.section_id = sections.id')
            ->join('subjects','admin_users_sections.subject_id = subjects.id')
            ->join('classes_subjects','classes_subjects.subject_id = subjects.id')
            ->get()->result_array();
    }
}