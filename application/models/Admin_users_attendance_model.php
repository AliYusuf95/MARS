<?php

class Admin_users_attendance_model extends MY_Model {

    public function updateTeachersAttendants($byAdminId)
    {
        $date = $this->input->post('date');
        $idList = $this->input->post('id');
        $sectionList = $this->input->post('section');
        $subjectList = $this->input->post('subject');
        $attendanceList = $this->input->post('attendance');
        $commentList = $this->input->post('comment');

        // get attendance list od date if exist
        $updateList = $this->getTeachersAttendance($sectionList, $subjectList, $date);

        // try to insert or update
        for ($i=0 ; $i < count($idList) ; $i++) {
            $update = false; // update flag
            // check if it's need to update
            for ($j=0 ; $j < count($updateList) ; $j++) {
                if ($idList[$i] == $updateList[$j]['admin_user_id'] &&
                    $sectionList[$i] == $updateList[$j]['section_id'] &&
                    $subjectList[$i] == $updateList[$j]['subject_id']) {
                    $attendance = array(
                        'admin_user_id' => $idList[$i],
                        'section_id' => $sectionList[$i],
                        'subject_id' => $subjectList[$i],
                        'status' => isset($attendanceList[$idList[$i].'-'.$sectionList[$i].'-'.$subjectList[$i]]) ? true : false,
                        'comment' => $commentList[$i],
                        'by_admin_user_id' => $byAdminId
                    );

                    $this->db->update('admin_users_attendance', $attendance,
                        array('admin_user_id' => $idList[$i],
                            'section_id' => $sectionList[$i],
                            'subject_id' => $subjectList[$i],
                            'date' => $date)
                    );
                    $update = true; // change update flag
                    break;
                }
            }
            // otherwise inset new record
            if (!$update){
                $attendance = array(
                    'admin_user_id' => $idList[$i],
                    'section_id' => $sectionList[$i],
                    'subject_id' => $subjectList[$i],
                    'date' => $date,
                    'status' => isset($attendanceList[$idList[$i].'-'.$sectionList[$i].'-'.$subjectList[$i]]) ? true : false,
                    'comment' => $commentList[$i],
                    'by_admin_user_id' => $byAdminId
                );
                $this->db->insert('admin_users_attendance', $attendance);
            }
        }
    }

    public function getTeachersAttendance($sections, $subjects, $date)
    {
        $this->db->distinct();
        $this->db->select('admin_user_id, section_id, subject_id');
        $this->db->from('admin_users_attendance');
        $this->db->where_in('section_id', $sections);
        $this->db->where_in('subject_id', $subjects);
        $this->db->where('date',$date);
        return $this->db->get()->result_array();
    }

}