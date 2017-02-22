<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teacher extends Admin_Controller
{

    public $autoload = array(
        'model' => array('User_model' => 'users',
            'Class_model' => 'classes')
    );

    private $form;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_builder');
        $this->form = $this->form_builder->create_form();
        // Set Page small title
        $this->mPageTitleSmall = 'المدرسين';
        $this->push_breadcrumb($this->mPageTitleSmall);
    }

    public function records()
    {
        // Setup crud
        $crud = $this->generate_crud('admin_users', 'مدرس');
        $crud->set_relation_n_n('sections','admin_users_sections','sections','admin_user_id','section_id','title',null,array('title IS NOT NULL'=>NULL));
        $crud->set_relation_n_n('subjects','admin_users_sections','subjects','admin_user_id','subject_id','title',null,array('title IS NOT NULL'=>NULL));
        $crud->columns('name', 'mobile', 'sections','subjects')
            ->set_read_fields('name', 'mobile', 'email', 'sections')
            ->display_as('name', 'الإسم')
            ->display_as('mobile', 'رقم الهاتف')
            ->display_as('email', 'البريد الإلكتروني')
            ->display_as('sections', 'الفرق')
            ->display_as('subjects', 'المواد');

        $crud->unset_add();
        $crud->unset_delete();
        $crud->unset_edit();

        $this->mPageTitle = 'المدرسين';
        $this->render_crud();
    }

    public function attendance($date = null)
    {
        if ($date == null)
            $date = date("Y-m-d");

        // create form variable
        $this->load->library('form_builder');
        $this->mViewData['form'] = $this->form_builder->create_form();

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            if ($this->form->validate()) {
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
                                'by_admin_user_id' => $this->mUser->id
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
                            'by_admin_user_id' => $this->mUser->id
                        );
                        $this->db->insert('admin_users_attendance', $attendance);
                    }
                }
                $this->system_message->set_success("تم الحفظ بنجاح");
                refresh();
            } else {
                $this->system_message->set_error("حدث خطأ، يرجى المحاولة مرة أخرى.");
                refresh();
            }
        }

        if (!$this->verify_page(false,'teacher/any_day_attendance')) {
            $date = date("Y-m-d");
            $this->mViewData["datePicker"] = false;

            $users = $this->getTeachersOfDate($date);
            $users = $this->getTeachersStatus($users,$date);
            $this->mViewData["users"] = $users;
        }
        else {
            if (!$this->validateDate($date))
                $date = date("Y-m-d");

            $this->mViewData["datePicker"] = true;
            $users = $this->getTeachersOfDate($date);
            $users = $this->getTeachersStatus($users,$date);
            $this->mViewData["users"] = $users;

            $this->load->model('admin_users_sections_model', 'teachers');
            $this->mViewData["availableTeachers"] = $this->getAvailableTeachers();
            $this->mViewData["availableSubjects"] = $this->getAvailableSubjects($date);
            $this->mViewData["availableSections"] = $this->getAvailableSections($date);
        }

        $this->mViewData["attendanceDate"] = $date;

        // add iCheck plugin
        $this->add_stylesheet("assets/dist/libraries/iCheck/skins/flat/flat.css");
        $this->add_script("assets/dist/libraries/iCheck/icheck.min.js");
        // date picker
        $this->add_stylesheet("assets/dist/libraries/datepicker/datepicker3.css");
        $this->add_script("assets/dist/libraries/datepicker/bootstrap-datepicker.js");
        $this->add_script("assets/dist/libraries/datepicker/locales/bootstrap-datepicker.ar.js");
        // sweetalert
        $this->add_stylesheet('assets/dist/libraries/sweetalert/sweetalert2.min.css');
        $this->add_script('assets/dist/libraries/sweetalert/sweetalert2.js');
        // select2
        $this->add_stylesheet('assets/dist/libraries/select2/css/select2.min.css');
        $this->add_script('assets/dist/libraries/select2/js/select2.full.min.js');

        // pass data to the view
        $this->mPageTitle = "تسجيل حضور";
        //render
        $this->render('teacher/attendance');
    }

    public function section_teacher()
    {
        // Setup crud
        $crud = $this->generate_crud('admin_users_sections','مدرس صف');
        $crud->columns('admin_user_id','section_id','subject_id');
        $crud->display_as('admin_user_id','المدرس')
            ->display_as('section_id','الصف')
            ->display_as('subject_id','المادة');
        $crud->set_relation('admin_user_id','admin_users','name',null,'id');
        $crud->set_relation('section_id','sections','title',null,'id');
        $crud->set_relation('subject_id','subjects','title',null,'id');
        $crud->required_fields('admin_user_id','section_id','subject_id');
        $crud->set_rules('section_id', 'الفرقة', 'compare_pk[admin_users_sections.section_id.admin_user_id.subject_id]');

        $this->mPageTitle = 'مدرسي الفرق';
        $this->render_crud();
    }

    private function getAvailableSections($date)
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

    private function getAvailableSubjects($date)
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

    private function getAvailableTeachers()
    {
        $this->db->distinct();
        $this->db->select('admin_users.id as id, admin_users.name as name, IFNULL(admin_users.mobile,"-") as mobile');
        $this->db->from('admin_users');
        $this->db->where("active",1);
//        $this->db->from('semesters');
//        $this->db->join('subjects', 'semesters.id = subjects.semester_id');
//        $this->db->join('classes_subjects', 'subjects.id = classes_subjects.subject_id');
//        $this->db->join('classes', 'classes.id = classes_subjects.class_id');
//        $this->db->join('sections', 'classes.id = sections.class_id');
//        $this->db->join('admin_users_sections', 'sections.id = admin_users_sections.section_id');
//        $this->db->join('admin_users', 'admin_users.id = admin_users_sections.admin_user_id');
//        $this->db->join('admin_users_attendance', 'admin_users.id = admin_users_attendance.admin_user_id');
//        $this->db->where('admin_users_sections.admin_user_id IS NOT',NULL);
//        $this->db->where("start_date <=",$date);
//        $this->db->where("end_date >=",$date);
        return $this->db->get()->result_array();
    }

    private function getTeachersOfDate($date)
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

    private function getTeachersStatus($users,$date)
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

    private function validateDate($date)
    {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    private function getTeachersAttendance($sections, $subjects, $date)
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