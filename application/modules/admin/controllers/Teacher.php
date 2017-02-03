<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teacher extends Admin_Controller
{

    public $autoload = array(
        'model' => array('User_model' => 'users',
            'Class_model' => 'classes')
    );

    public function __construct()
    {
        parent::__construct();
        // Set Page small title
        $this->mPageTitleSmall = 'المدرسين';
    }

    public function index()
    {
        // Setup crud
        $crud = $this->generate_crud('admin_users', 'مدرس');
        $crud->set_model('Custom_CRUD_model');
        $this->ion_auth->get_users_groups();
        $crud->basic_model->set_custom_query('SELECT * FROM admin_users NATURAL JOIN admin_users_groups WHERE admin_users_groups.group_id=5 AND admin_users.active = 1');
        $crud->columns('name', 'mobile', 'email');
        $crud->fields('name', 'mobile', 'email')
            ->display_as('name', 'الإسم')
            ->display_as('mobile', 'رقم الهاتف')
            ->display_as('email', 'البريد الإلكتروني');

        $this->mPageTitle = 'المدرسين';
        $this->render_crud();
    }

    public function attendance()
    {
        // create form variable
        $this->load->library('form_builder');
        $this->mViewData['form'] = $this->form_builder->create_form();
        // pass data to the view
        $this->mPageTitle = "تسجيل حضور";
        $this->mViewData["dates"] = array(date("Y-m-d"));
        $this->mViewData["users"] = $this->users->select('id, name, IFNULL(mobile,"-") as mobile')->as_array()->get_all();
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            if ($this->form_validation->run()) {
                $this->system_message->set_success("تم الحفظ بنجاح");
            } else {
                $this->system_message->set_error("حدث خطأ، يرجى المحاولة مرة أخرى.");
            }
            refresh();
        }

        // add iCheck plugin
        $this->add_stylesheet("assets/dist/libraries/iCheck/skins/flat/grey.css");
        $this->add_script("assets/dist/libraries/iCheck/icheck.min.js");

        //render
        $this->render('user/attendance');

    }
}