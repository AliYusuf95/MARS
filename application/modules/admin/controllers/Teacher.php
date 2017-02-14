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
        $this->push_breadcrumb($this->mPageTitleSmall);
    }

    public function records()
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

    public function section()
    {
        // Setup crud
        $crud = $this->generate_crud('admin_users_sections','مدرس صف');
        if($crud->getState() != 'list')
        {
            $crud->columns('admin_user_id','section_id');
            $crud->display_as('admin_user_id','المدرس')
                ->display_as('section_id','الصف');
            $crud->set_relation('admin_user_id','admin_users','{name} - {mobile}',null,'id');
            $crud->set_relation('section_id','sections','title',null,'id');
            $crud->required_fields('admin_user_id','section_id');
            $crud->set_rules('section_id', 'الفرقة', 'compare_pk[admin_users_sections.section_id.admin_user_id]');

        } else {
            $crud = $this->generate_crud('admin_users', 'فرقة');
            $crud->set_relation_n_n('sections','admin_users_sections','sections','admin_user_id','section_id','title',null,null);
            $crud->columns('name', 'mobile', 'sections');
            $crud->fields('name', 'mobile', 'sections')
                ->display_as('name', 'الإسم')
                ->display_as('mobile', 'رقم الهاتف')
                ->display_as('sections', 'الفرق');
        }

        $this->mPageTitle = 'مدرسي الفرق';
        $this->render_crud();
    }
}