<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Admin_Controller {

    /**
     * @var Form
     */
    private $form;
    public $autoload = array(
        'model' => array('User_model' => 'users',
            'Class_model' => 'classes',
            'Section_model' => 'sections')
    );
    private $days = array(
        'Sun' => 'الأحد',
        'Mon' => 'الإثنين',
        'Tue' => 'الثلثاء',
        'Wed' => 'الأربعاء',
        'Thu' => 'الخميس',
        'Fri' => 'الجمعة',
        'Sat' => 'السبت'
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_builder');
        $this->form = $this->form_builder->create_form();
        $this->mPageTitleSmall = 'الطلاب';
        $this->push_breadcrumb($this->mPageTitleSmall);
    }

    // Frontend User CRUD
    public function records()
    {
        $crud = $this->generate_crud('users','بيانات الطالب');
        $crud->columns('name', 'mobile', 'level_id');
        $crud->where('users.active',1);
        $crud->display_as('name','الإسم')
            ->display_as('mobile','الهاتف')
            ->display_as('level_id','المستوى الدراسي');
        $crud->set_relation('level_id','levels','title',null,'id');
        
        // only webmaster and admin can change member groups
        if ($crud->getState()=='list' || $this->ion_auth->in_group(array('webmaster', 'admin')))
        {
            $crud->set_relation_n_n('groups', 'users_groups', 'groups', 'user_id', 'group_id', 'name');
        }

        // only webmaster and admin can reset user password
        if ($this->verify_page(false,'user/reset_password'))
        {
            $crud->add_action('Reset Password', '', 'admin/user/reset_password', 'fa fa-repeat');
        }

        // disable direct create / delete Frontend User
        if (!$this->ion_auth->in_group(array('webmaster', 'admin')))
        {
            $crud->unset_add();
            $crud->unset_delete();
        }

        $this->mPageTitle = 'القائمة';
        $this->add_stylesheet('assets/dist/admin/rtl/crud-rtl.css');
        $this->render_crud();
    }

    // Create Frontend User
    public function create()
    {

        if ($this->form->validate())
        {
            // passed validation
            $username = $this->input->post('username');
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $identity = empty($username) ? $email : $username;
            $additional_data = array(
                'first_name'    => $this->input->post('first_name'),
                'last_name'        => $this->input->post('last_name'),
            );
            $groups = $this->input->post('groups');

            // [IMPORTANT] override database tables to update Frontend Users instead of Admin Users
            $this->ion_auth_model->tables = array(
                'users'                => 'users',
                'groups'            => 'groups',
                'users_groups'        => 'users_groups',
                'login_attempts'    => 'login_attempts',
            );

            // proceed to create user
            $user_id = $this->ion_auth->register($identity, $password, $email, $additional_data, $groups);            
            if ($user_id)
            {
                // success
                $messages = $this->ion_auth->messages();
                $this->system_message->set_success($messages);

                // directly activate user
                $this->ion_auth->activate($user_id);
            }
            else
            {
                // failed
                $errors = $this->ion_auth->errors();
                $this->system_message->set_error($errors);
            }
            refresh();
        }

        // get list of Frontend user groups
        $this->load->model('group_model', 'groups');
        $this->mViewData['groups'] = $this->groups->get_all();
        $this->mPageTitle = 'Create User';

        $this->mViewData['form'] = $this->form;
        $this->render('user/create');
    }

    // User Groups CRUD
    public function group()
    {
        $crud = $this->generate_crud('groups');
        $this->mPageTitle = 'User Groups';
        $this->render_crud();
    }

    // Frontend User Reset Password
    public function reset_password($user_id)
    {
        // only top-level users can reset user passwords
        $this->verify_auth(array('webmaster', 'admin'));

        if ($this->form->validate())
        {
            // pass validation
            $data = array('password' => $this->input->post('new_password'));
            
            // [IMPORTANT] override database tables to update Frontend Users instead of Admin Users
            $this->ion_auth_model->tables = array(
                'users'                => 'users',
                'groups'            => 'groups',
                'users_groups'        => 'users_groups',
                'login_attempts'    => 'login_attempts',
            );

            // proceed to change user password
            if ($this->ion_auth->update($user_id, $data))
            {
                $messages = $this->ion_auth->messages();
                $this->system_message->set_success($messages);
            }
            else
            {
                $errors = $this->ion_auth->errors();
                $this->system_message->set_error($errors);
            }
            refresh();
        }

        $this->load->model('user_model', 'users');
        $target = $this->users->get($user_id);
        $this->mViewData['target'] = $target;

        $this->mViewData['form'] = $this->form;
        $this->mPageTitle = 'Reset User Password';
        $this->render('user/reset_password');
    }

    public function attendance($sectionId = null){
        // create form variable
        $this->mViewData['form'] = $this->form;
        // pass data to the view
        $this->mPageTitle = "تسجيل الحضور";

        if ($sectionId == '' || $sectionId == null)
        {
            if (!$this->verify_page(false,'user/previous_days_attendance') &&
                !$this->verify_page(false,'user/any_day_attendance')) {

                $this->load->model('Admin_users_sections_model', 'teacher_sections');
                $userId = $this->ion_auth->user()->row()->id;
                $teacherSections = $this->teacher_sections->select('section_id')->as_array()->get_many_by('admin_user_id', $userId);
                foreach ($teacherSections as $section)
                    $this->mViewData["sections"][] =
                        $this->sections->select('id, title')->as_array()->get($section['section_id']);
            }
            else
                $this->mViewData["sections"] = $this->sections->select('id, title')->as_array()->get_all();

            //render
            $this->render('user/attendance_sections');
        }
        // Not valid section OR ( not valid teacher AND don't has permissions )
        else if (!$this->isValidSection($sectionId) || (
            !$this->isValidTeacher($sectionId) &&
            !$this->verify_page(false,'user/previous_days_attendance') &&
            !$this->verify_page(false,'user/any_day_attendance'))) {

            $this->render('user/attendance_wrong_section');
        }
        // Not valid date AND don't has permissions
        else if (!$this->isValidDate($sectionId,date("Y-m-d")) &&
            !$this->verify_page(false,'user/previous_days_attendance') &&
            !$this->verify_page(false,'user/any_day_attendance')) {

            $dateInfo = $this->getDateInformation($sectionId);
            $this->mViewData["dates"] = $dateInfo["dates"];
            $this->mViewData["startDate"] = $dateInfo["start_date"];
            $this->mViewData["endDate"] = $dateInfo["end_date"];
            $this->render('user/attendance_wrong_date');
        }
        else {
            $this->mPageTitle = $this->sections->select('title')->get($sectionId)->title;
            $this->mPageTitleSmall = "تسجيل الحضور";
            $this->push_breadcrumb('تسجيل الحضور','user/attendance');

            $this->mViewData["datePicker"] =
                $this->verify_page(false,'user/previous_days_attendance') ||
                $this->verify_page(false,'user/any_day_attendance');

            if ($this->mViewData["datePicker"]){
                $dateInfo =  $this->getDateInformation($sectionId);
                $this->mViewData["startDate"] = $dateInfo["start_date"];
                $this->mViewData["endDate"] = $dateInfo["end_date"];
                $this->mViewData["daysOfWeekDisabled"] = '';
                $this->mViewData["daysOfWeekHighlighted"] = implode(',',$dateInfo["datesOfWeek"]);

                if (!$this->verify_page(false,'user/any_day_attendance')) {
                    $this->mViewData["daysOfWeekDisabled"] = implode(',',
                        array_diff([0,1,2,3,4,5,6],$dateInfo["datesOfWeek"]));
                }
            }

            //daysOfWeekDisabled: "0,1,2,3,4,5,6",
            //daysOfWeekHighlighted: "0,1"

            $this->mViewData["dates"] = array(date("Y-m-d") => date("Y-m-d"));
            $this->db->select('users.id as id, name, IFNULL(mobile,"-") as mobile');
            $this->db->from('users');
            $this->db->join('users_sections', 'users.id = users_sections.user_id');
            $this->db->where('users_sections.section_id',$sectionId);
            $this->db->order_by('name', 'ASC');
            $query = $this->db->get()->result_array();

            foreach ($query as &$user){
                $this->db->select('users_attendance.`status`');
                $this->db->from('users_attendance');
                $this->db->where('user_id',$user["id"]);
                $this->db->where('date',date("Y-m-d"));
                $result = $this->db->get()->result_array();
                $user['status'] = isset($result[0]['status']) ? $result[0]['status'] : false ;
            }

            $this->mViewData["users"] = $query;
            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                if ($this->form->validate()) {
                    $date = $this->input->post('date');
                    $idList = $this->input->post('id');
                    $attendanceList = $this->input->post('attendance');

                    $this->db->select('*');
                    $this->db->from('users');
                    $this->db->join('users_sections', 'users.id = users_sections.user_id');
                    $this->db->where('users_sections.section_id',$sectionId);
                    $this->db->where('users_attendance.date',$date);
                    $this->db->join('users_attendance', 'users.id = users_attendance.user_id');
                    if ($this->db->get()->num_rows() == 0) {
                        // set false fro all students
                        for ($i = 0; $i < count($idList); $i++) {
                            $users_attendance = array(
                                'user_id' => $idList[$i],
                                'date' => $date,
                                'status' => false,
                                'admin_user_id' => $this->ion_auth->user()->row()->id
                            );
                            $this->db->insert('users_attendance', $users_attendance);
                        }
                    }

                    for ($i=0 ; $i < count($idList) ; $i++) {
                        $user_attendance = array(
                            'status' => isset($attendanceList[$idList[$i]]) ? true : false,
                            'admin_user_id' => $this->ion_auth->user()->row()->id
                        );
                        $this->db->update('users_attendance', $user_attendance,
                            array('user_id'=>$idList[$i],'date'=>$date));
                    }
                    $this->system_message->set_success("تم الحفظ بنجاح");
                    refresh();
                } else {
                    $this->system_message->set_error("حدث خطأ، يرجى المحاولة مرة أخرى.");
                    refresh();
                }
            }

            // add iCheck plugin
            $this->add_stylesheet("assets/dist/libraries/iCheck/skins/flat/grey.css");
            $this->add_script("assets/dist/libraries/iCheck/icheck.min.js");
            // date picker
            $this->add_stylesheet("assets/dist/libraries/datepicker/datepicker3.css");
            $this->add_script("assets/dist/libraries/datepicker/bootstrap-datepicker.js");
            $this->add_script("assets/dist/libraries/datepicker/locales/bootstrap-datepicker.ar.js");
            // sweetalert
            $this->add_stylesheet('assets/dist/libraries/sweetalert/sweetalert2.min.css');
            $this->add_script('assets/dist/libraries/sweetalert/sweetalert2.js');

            //render
            $this->render('user/attendance');
        }
    }

    private function isValidSection($sectionId) {
        $this->db->select('subjects.title as title');
        $this->db->from('semesters');
        $this->db->join('subjects', 'semesters.id = subjects.semester_id');
        $this->db->join('classes_subjects', 'subjects.id = classes_subjects.subject_id');
        $this->db->join('classes', 'classes.id = classes_subjects.class_id');
        $this->db->join('sections', 'classes.id = sections.class_id');
        $this->db->where('sections.id',$sectionId);
        $result = $this->db->get()->num_rows();
        return $result == 1;
    }

    private function isValidTeacher($sectionId) {
        $userId = $this->ion_auth->user()->row()->id;
        return $this->db->select('*')
            ->from('admin_users_sections')
            ->where('section_id',$sectionId)
            ->where('admin_user_id',$userId)->get()->num_rows() == 1;
    }

    /**
     * @param $sectionId
     * @param $day
     * @return bool
     */
    private function isValidDate($sectionId, $day){
        $this->db->select('subjects.dates, semesters.start_date as start_date, semesters.end_date as end_date');
        $this->db->from('semesters');
        $this->db->join('subjects', 'semesters.id = subjects.semester_id');
        $this->db->join('classes_subjects', 'subjects.id = classes_subjects.subject_id');
        $this->db->join('classes', 'classes.id = classes_subjects.class_id');
        $this->db->join('sections', 'classes.id = sections.class_id');
        $this->db->where('sections.id',$sectionId);
        $this->db->where("start_date <=",$day);
        $this->db->where("end_date >=",$day);
        $this->db->like('subjects.dates',date("D",strtotime($day)));
        $result = $this->db->get()->num_rows();
        return $result == 1;
    }

    private function getDateInformation($sectionId){
        $this->db->select('subjects.dates as dates, semesters.start_date as start_date, semesters.end_date as end_date');
        $this->db->from('semesters');
        $this->db->join('subjects', 'semesters.id = subjects.semester_id');
        $this->db->join('classes_subjects', 'subjects.id = classes_subjects.subject_id');
        $this->db->join('classes', 'classes.id = classes_subjects.class_id');
        $this->db->join('sections', 'classes.id = sections.class_id');
        $this->db->where('sections.id',$sectionId);
        $result = $this->db->get()->result_array();
        if (count($result) > 0) {
            $result = $result[0];
            $days = explode(',', $result['dates']);
            $result['datesOfWeek'] = array_map(function ($d) {
                return date("w", strtotime($d));
            }, $days);
            $result['dates'] = array_map(function ($d) {
                return isset($this->days[$d]) ? $this->days[$d] : "";
            }, $days);
        }
        return $result;
    }

}
