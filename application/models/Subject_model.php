<?php 

class Subject_model extends MY_Model {

    public function getAvailableSubjects($date)
    {
        $this->db->distinct();
        $this->db->select('subjects.id as id, subjects.title as title');
        $this->db->from('semesters');
        $this->db->join('subjects', 'semesters.id = subjects.semester_id');
//        $this->db->join('classes_subjects', 'subjects.id = classes_subjects.subject_id');
//        $this->db->join('classes', 'classes.id = classes_subjects.class_id');
//        $this->db->join('sections', 'classes.id = sections.class_id');
        $this->db->where("start_date <=",$date);
        $this->db->where("end_date >=",$date);
        return $this->db->get()->result_array();
    }

}