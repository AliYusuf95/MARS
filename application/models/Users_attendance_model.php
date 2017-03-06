<?php 

class Users_attendance_model extends MY_Model {

    public function getAllStudentsAttendance($section_id, $subject_id)
    {
        // users attendance
        $attendances = $this->db->select('users.id, users.name, users_attendance.date, status')
            ->from('users_attendance')
            ->join('subjects','subjects.id = users_attendance.subject_id')
            ->join('semesters','semesters.id = subjects.semester_id')
            ->join('users','users.id = user_id')
            ->where('users_attendance.section_id',$section_id)
            ->where('subject_id',$subject_id)
            ->where("users_attendance.date BETWEEN start_date AND end_date")
            ->get()->result_object();

        // semester start/end date
        $semester = $this->db->select('start_date, end_date')
            ->join('semesters','semesters.id = subjects.semester_id')
            ->where('subjects.id',$subject_id)
            ->get('subjects')->first_row();

        // subject dates
        $subject = $this->db->select('dates')
            ->where('id',$subject_id)
            ->get('subjects')->first_row();

        // section students
        $students = $this->db->select('users.id as id, name')
        ->from('users')
        ->join('users_sections', 'users.id = users_sections.user_id')
        ->where('users_sections.section_id',$section_id)
        ->order_by('name', 'ASC')
        ->get()->result_array();

        // get all teaching dates
        $teachingDates = $this->getTeachingDates($semester->start_date,$semester->end_date,explode(',',$subject->dates));
        foreach ($attendances as $attendance)
            if (!in_array($attendance->date,$teachingDates))
                $teachingDates[] = $attendance->date;

        // sort dates
        asort($teachingDates);

        foreach ($students as &$student){
            foreach ($teachingDates as $date) {
                $result = array_filter(
                    $attendances, function ($a) use ($date, $student) {
                    return $a->date == $date && $a->id == $student['id'];
                });
                $status = !empty($result) ? (int) array_pop($result)->status : 0;
                $student['attendances'][$date] = $status;
            }
        }
        $data['students'] = $students;
        $data['dates'] = $teachingDates;
        return $data;
    }

    private function getTeachingDates($start_date, $end_date, $expected_days) {
        $start_timestamp = strtotime($start_date);
        $end_timestamp   = strtotime($end_date);
        $dates = array();
        while ($start_timestamp <= $end_timestamp) {
            if (in_array(date('D', $start_timestamp), $expected_days)) {
                $dates[] = date('Y-m-d', $start_timestamp);
            }
            $start_timestamp = strtotime('+1 day', $start_timestamp);
        }
        return $dates;
    }

    public function getStudentsAttendanceOnDate($section_id, $subject_id, $date)
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