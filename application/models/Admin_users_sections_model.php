<?php

/**
 * Created by PhpStorm.
 * User: Ali Yusuf
 * Date: 2/4/2017
 * Time: 12:27 AM
 */
class Admin_users_sections_model extends MY_Model {

    public function getAvailableTeachers()
    {
        $this->db->distinct();
        $this->db->select('admin_users.id as id, admin_users.name as name, IFNULL(admin_users.mobile,"-") as mobile');
        $this->db->from('admin_users_sections');
        $this->db->join('admin_users','admin_users.id = admin_users_sections.admin_user_id');
        $this->db->where("active",1);
        return $this->db->get()->result_array();
    }

    public function getTeachersOfDate($date)
    {
        // get teachers from admin users table
        $this->db->select('admin_users.id as id, admin_users.name as name, IFNULL(admin_users.mobile,"-") as mobile');
        $this->db->select('sections.id as sectionId, sections.title as sectionTitle');
        $this->db->select('admin_users_sections.subject_id as subjectId, subjects.title as subjectTitle');
        $this->db->from('semesters');
        $this->db->join('subjects', 'semesters.id = subjects.semester_id');
        $this->db->join('classes_subjects', 'subjects.id = classes_subjects.subject_id');
        $this->db->join('classes', 'classes.id = classes_subjects.class_id');
        $this->db->join('sections', 'classes.id = sections.class_id');
        $this->db->join('admin_users_sections', 'sections.id = admin_users_sections.section_id AND subjects.id = admin_users_sections.subject_id');
        $this->db->join('admin_users', 'admin_users.id = admin_users_sections.admin_user_id');
        $this->db->where('admin_users_sections.admin_user_id IS NOT',NULL);
        $this->db->where("start_date <=",$date);
        $this->db->where("end_date >=",$date);
        $this->db->like('subjects.dates',date("D",strtotime($date)));
        $query1 = $this->db->get()->result_array();

        // get teachers from attendance table
        $this->db->select('admin_users.id as id, admin_users.name as name, IFNULL(admin_users.mobile,"-") as mobile');
        $this->db->select('sections.id as sectionId, sections.title as sectionTitle');
        $this->db->select('subject_id as subjectId, subjects.title as subjectTitle');
        $this->db->from('admin_users_attendance');
        $this->db->join('admin_users', 'admin_users.id = admin_users_attendance.admin_user_id');
        $this->db->join('sections', 'sections.id = admin_users_attendance.section_id');
        $this->db->join('subjects', 'subjects.id = subject_id');
        $this->db->where("admin_users_attendance.date",$date);
        $query2 = $this->db->get()->result_array();

        return array_merge(array_filter($query1, function($e) use ($query2) {return !in_array($e, $query2);}),$query2);
    }

    public function getTeachersStatus($users,$date)
    {
        foreach ($users as &$user){
            $this->db->select('`status`,`comment`');
            $this->db->from('admin_users_attendance');
            $this->db->where('admin_user_id',$user["id"]);
            $this->db->where('section_id',$user["sectionId"]);
            $this->db->where('subject_id',$user["subjectId"]);
            $this->db->where('date',$date);
            $result = $this->db->get()->result_array();
            $user['status'] = isset($result[0]['status']) ? $result[0]['status'] : false;
            $user['comment'] = isset($result[0]['comment']) ? $result[0]['comment'] : '';
        }

        return $users;
    }

    public function getTeacherSections($id)
    {
        return $this->db->select('sections.id as sectionId, sections.title as sectionTitle')
            ->select('subjects.id as subjectId, subjects.title as subjectTitle')
            ->from('admin_users_sections')
            ->join('sections','admin_users_sections.section_id = sections.id')
            ->join('subjects','admin_users_sections.subject_id = subjects.id')
            ->join('classes_subjects','classes_subjects.subject_id = subjects.id')
            ->where('admin_user_id',$id)
            ->get()->result_array();
    }

}