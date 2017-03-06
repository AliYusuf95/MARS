<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Ion_auth_model ion_auth_model
 * @property User_model users
 * @property Admin_users_sections_model teacher_sections
 * @property Section_model sections
 * @property Subject_model subjects
 * @property Users_attendance_model user_attendance
 */
class User extends Admin_Controller {

    /**
     * @var Form
     */
    private $form;
    public $autoload = array(
        'model' => array(
            'User_model' => 'users',
            'Class_model' => 'classes',
            'Section_model' => 'sections',
            'Subject_model' => 'subjects',
            'Admin_users_sections_model' => 'teacher_sections',
            'Users_attendance_model' => 'user_attendance',
        )
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

    // Frontend User CRUD
    public function requests()
    {
        $crud = $this->generate_crud('users','بيانات الطالب');
        $crud->columns('name', 'mobile', 'cpr', 'level_id', 'created_at')
            ->fields('name', 'mobile', 'cpr', 'email', 'level_id', 'active')
            ->set_read_fields('name', 'mobile', 'cpr', 'email', 'level_id', 'active', 'created_at')
            ->where('users.active',0);

        $crud->display_as('name','الإسم')
            ->display_as('mobile','الهاتف')
            ->display_as('email','البريد الإلكتروني')
            ->display_as('active','الحالة')
            ->display_as('cpr','الرقم الشخصي')
            ->display_as('created_at','تاريخ التسجيل')
            ->display_as('level_id','المستوى الدراسي');

        $crud->set_relation('level_id','levels','title',null,'id');

        $crud->set_rules('level_year','عام المستوى الدراسي','numeric');

        // only webmaster and admin can reset user password
        if ($this->verify_page(false,'user/reset_password'))
        {
            $crud->add_action('Reset Password', '', 'admin/user/reset_password', 'fa fa-repeat');
        }

        $this->mPageTitle = 'القائمة';
        $this->add_stylesheet('assets/dist/admin/rtl/crud-rtl.css');
        $this->render_crud();
    }

    // Create Frontend User
    /*public function create()
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
    }*/

    // User Groups CRUD
    public function group()
    {
        $crud = $this->generate_crud('groups', 'مجموعة');

        $crud->display_as('name','الإسم')
            ->display_as('description','الوصف');

        $this->mPageTitle = 'المجموعات';
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

    public function attendance($sectionId = null, $subjectId = null, $date = null){
        // create form variable
        $this->mViewData['form'] = $this->form;
        // pass data to the view
        $this->mPageTitle = "تسجيل الحضور";

        if ($date == null)
            $date = date("Y-m-d");

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            if ($this->form->validate()) {
                $date = $this->input->post('date');
                $section_id = $this->input->post('section');
                $subject_id = $this->input->post('subject');
                $idList = $this->input->post('id');
                $attendanceList = $this->input->post('attendance');

                // get attendance list od date if exist
                $updateList = $this->user_attendance->getStudentsAttendanceOnDate($section_id, $subject_id, $date);

                // try to insert or update
                for ($i=0 ; $i < count($idList) ; $i++) {
                    $update = false; // update flag
                    // check if it's need to update
                    for ($j=0 ; $j < count($updateList) ; $j++) {
                        if ($idList[$i] == $updateList[$j]['user_id']) {
                            $user_attendance = array(
                                'status' => isset($attendanceList[$idList[$i]]) ? true : false,
                                'by_admin_user_id' => $this->mUser->id
                            );
                            $this->db->update('users_attendance', $user_attendance,
                                array('user_id' => $idList[$i],
                                    'section_id' => $section_id,
                                    'subject_id' => $subject_id,
                                    'date' => $date));
                            $update = true; // change update flag
                            break;
                        }
                    }
                    // otherwise inset new record
                    if (!$update){
                        $users_attendance = array(
                            'user_id' => $idList[$i],
                            'date' => $date,
                            'section_id' => $section_id,
                            'subject_id' => $subject_id,
                            'status' => isset($attendanceList[$idList[$i]]) ? true : false,
                            'by_admin_user_id' => $this->mUser->id
                        );
                        $this->db->insert('users_attendance', $users_attendance);
                    }
                }

                $this->system_message->set_success("تم الحفظ بنجاح");
                refresh();
            } else {
                $this->system_message->set_error("حدث خطأ، يرجى المحاولة مرة أخرى.");
                refresh();
            }
        }

        if ($sectionId == null || $subjectId == null)
        {
            if (!$this->verify_page(false,'user/previous_days_attendance') &&
                !$this->verify_page(false,'user/any_day_attendance')) {

                $this->mViewData["sections"] = $this->teacher_sections->getTeacherSections($this->mUser->id);
            }
            else
                $this->mViewData["sections"] = $this->sections->getAllSections();

            $this->mViewData["baseUrl"] = base_url($this->mModule.'/'.$this->mLanguage.'/'.$this->mCtrler.'/'.$this->mAction);
            //render
            $this->render('user/attendance_sections');
        }
        // Not valid section OR ( not valid teacher AND don't has permissions )
        else if (!$this->isValidSection($sectionId, $subjectId) || (
            !$this->isValidTeacher($sectionId,$subjectId) &&
            !$this->verify_page(false,'user/previous_days_attendance') &&
            !$this->verify_page(false,'user/any_day_attendance'))) {

            $this->render('user/attendance_wrong_section');
        }
        // Not valid date AND don't has permissions
        else if (!$this->isValidDate($subjectId,$date) &&
            !$this->verify_page(false,'user/previous_days_attendance') &&
            !$this->verify_page(false,'user/any_day_attendance')) {

            $dateInfo = $this->getDateInformation($sectionId);
            $this->mViewData["dates"] = $dateInfo["dates"];
            $this->mViewData["startDate"] = $dateInfo["start_date"];
            $this->mViewData["endDate"] = $dateInfo["end_date"];
            $this->render('user/attendance_wrong_date');
        }
        else {
            $this->mPageTitle = $this->sections->select('title')->get($sectionId)->title
                .' - '.$this->subjects->select('title')->get($subjectId)->title;
            $this->mPageTitleSmall = "تسجيل الحضور";
            $this->push_breadcrumb('تسجيل الحضور','user/attendance');

            $this->mViewData["datePicker"] =
                $this->verify_page(false,'user/previous_days_attendance') ||
                $this->verify_page(false,'user/any_day_attendance');

            if ($this->mViewData["datePicker"]){
                $dateInfo = $this->getDateInformation($sectionId);
                $this->mViewData["startDate"] = $dateInfo["start_date"];
                $this->mViewData["endDate"] = $dateInfo["end_date"];
                $this->mViewData["daysOfWeekDisabled"] = '';
                $this->mViewData["daysOfWeekHighlighted"] = implode(',',$dateInfo["datesOfWeek"]);

                if (!$this->verify_page(false,'user/any_day_attendance')) {
                    $this->mViewData["daysOfWeekDisabled"] = implode(',',
                        array_diff([0,1,2,3,4,5,6],$dateInfo["datesOfWeek"]));
                }
            }

            $this->mViewData["date"] = $date;
            $this->mViewData["sectionId"] = $sectionId;
            $this->mViewData["subjectId"] = $subjectId;
            $this->mViewData["users"] = $this->sections->getSectionStudentsWithStatus($sectionId,$date);

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

    public function report($sectionId = null, $subjectId = null) {
        // create form variable
        $this->mViewData['form'] = $this->form;
        // pass data to the view
        $this->mPageTitle = "حضور الطلاب";

        if ($sectionId == null || $subjectId == null)
        {
            $this->mViewData["sections"] = $this->sections->getAllSections();
            $this->mViewData["baseUrl"] = base_url($this->mModule.'/'.$this->mLanguage.'/'.$this->mCtrler.'/'.$this->mAction);
            //render
            $this->render('user/attendance_sections');
        } else {
            $this->mPageTitle = $this->sections->select('title')->get($sectionId)->title
                .' - '.$this->subjects->select('title')->get($subjectId)->title;
            $this->mViewData["data"] = $this->user_attendance->getAllStudentsAttendance($sectionId,$subjectId);

            $this->add_script('assets/dist/libraries/printElement/jquery.printElement.js');
            $this->render('user/report_attendance');
        }
    }

    private function isValidSection($sectionId, $subjectId) {
        $this->db->select('subjects.title as title');
        $this->db->from('semesters');
        $this->db->join('subjects', 'semesters.id = subjects.semester_id');
        $this->db->join('classes_subjects', 'subjects.id = classes_subjects.subject_id');
        $this->db->join('classes', 'classes.id = classes_subjects.class_id');
        $this->db->join('sections', 'classes.id = sections.class_id');
        $this->db->where('sections.id',$sectionId);
        $this->db->where('subjects.id',$subjectId);
        $result = $this->db->get()->num_rows();
        return $result == 1;
    }

    private function isValidTeacher($sectionId, $subjectId) {
        $userId = $this->mUser->id;
        return $this->db->select()
            ->from('admin_users_sections')
            ->where('section_id',$sectionId)
            ->where('subject_id',$subjectId)
            ->where('admin_user_id',$userId)->get()->num_rows() == 1;
    }

    /**
     * @param $subjectId
     * @param $day
     * @return bool
     */
    private function isValidDate($subjectId, $day){
        $this->db->select('subjects.dates, semesters.start_date as start_date, semesters.end_date as end_date');
        $this->db->from('semesters');
        $this->db->join('subjects', 'semesters.id = subjects.semester_id');
        $this->db->where('subjects.id',$subjectId);
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
