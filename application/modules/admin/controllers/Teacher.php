<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Admin_user_model admin_users
 * @property Admin_users_sections_model admin_users_sections
 * @property Subject_model subjects
 * @property Section_model sections
 * @property Admin_users_attendance_model teachers_attendance
 */
class Teacher extends Admin_Controller
{

    public $autoload = array(
        'model' => array(
            'Admin_user_model'=> 'admin_users',
            'Admin_users_sections_model' => 'admin_users_sections',
            'Admin_users_attendance_model' => 'teachers_attendance',
            'Subject_model' => 'subjects',
            'Section_model' => 'sections'
        )
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

    public function reports_attendance()
    {

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
                // update attendance
                $this->teachers_attendance->updateTeachersAttendants($this->mUser->id);

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

            $users = $this->admin_users_sections->getTeachersOfDate($date);
            $users = $this->admin_users_sections->getTeachersStatus($users,$date);
            $this->mViewData["users"] = $users;
        }
        else {
            if (!$this->validateDate($date))
                $date = date("Y-m-d");

            $this->mViewData["datePicker"] = true;
            $users = $this->admin_users_sections->getTeachersOfDate($date);
            $users = $this->admin_users_sections->getTeachersStatus($users,$date);
            $this->mViewData["users"] = $users;

            $this->load->model('admin_users_sections_model', 'teachers');
            $this->mViewData["availableTeachers"] = $this->admin_users->getActiveAdminUsers();
            $this->mViewData["availableSubjects"] = $this->subjects->getAvailableSubjects($date);
            $this->mViewData["availableSections"] = $this->sections->getAvailableSections($date);
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

    private function validateDate($date)
    {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

}