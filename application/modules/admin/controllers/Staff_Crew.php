<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staff_Crew extends Admin_Controller
{

    /**
     * @var Form
     */
    private $form;
    public $autoload = array(
        'model' => array('User_model' => 'users',
            'Class_model' => 'classes')
    );

    public function __construct()
    {
        parent::__construct();
        // Set Page small title
        $this->mPageTitleSmall = 'الهيئة التعليمية';
        $this->load->library('form_builder');
        $this->form = $this->form_builder->create_form();
    }

    public function admin()
    {
        // Setup crud
        $crud = $this->generate_crud('admin_users', 'إداري');
        $crud->set_model('Custom_CRUD_model');
        $this->ion_auth->get_users_groups();
        $crud->basic_model->set_custom_query('SELECT * FROM admin_users NATURAL JOIN admin_users_groups WHERE admin_users_groups.group_id=2');
        $crud->columns('name', 'username', 'mobile', 'email', 'active');
        $crud->fields('name', 'username', 'mobile', 'email', 'active')
            ->display_as('name', 'الإسم')
            ->display_as('username', 'مسمى تسجيل الدخول')
            ->display_as('mobile', 'رقم الهاتف')
            ->display_as('active', 'الحالة')
            ->display_as('email', 'البريد الإلكتروني');

        // only webmaster and admin can reset user password
        if ($this->ion_auth->in_group(array('webmaster', 'admin')))
        {
            $crud->add_action('Reset Password', '', 'admin/staff_crew/reset_password', 'fa fa-repeat');
        }

        $this->mPageTitle = 'الإدارة';
        $this->render_crud();
    }

    public function manager()
    {
        // Setup crud
        $crud = $this->generate_crud('admin_users', 'مشرف عام');
        $crud->set_model('Custom_CRUD_model');
        $this->ion_auth->get_users_groups();
        $crud->basic_model->set_custom_query('SELECT * FROM admin_users NATURAL JOIN admin_users_groups WHERE admin_users_groups.group_id=3');
        $crud->columns('name', 'username', 'mobile', 'email', 'active');
        $crud->fields('name', 'username', 'mobile', 'email', 'active')
            ->display_as('name', 'الإسم')
            ->display_as('username', 'مسمى تسجيل الدخول')
            ->display_as('mobile', 'رقم الهاتف')
            ->display_as('active', 'الحالة')
            ->display_as('email', 'البريد الإلكتروني');

        // only webmaster and admin can reset user password
        if ($this->ion_auth->in_group(array('webmaster', 'admin')))
        {
            $crud->add_action('Reset Password', '', 'admin/staff_crew/reset_password', 'fa fa-repeat');
        }

        $this->mPageTitle = 'الإشراف العام';
        $this->render_crud();
    }

    public function staff()
    {
        // Setup crud
        $crud = $this->generate_crud('admin_users', 'مشرف');
        $crud->set_model('Custom_CRUD_model');
        $this->ion_auth->get_users_groups();
        $crud->basic_model->set_custom_query('SELECT * FROM admin_users NATURAL JOIN admin_users_groups WHERE admin_users_groups.group_id=4');
        $crud->columns('name', 'username', 'mobile', 'email', 'active');
        $crud->fields('name', 'username', 'mobile', 'email', 'active')
            ->display_as('name', 'الإسم')
            ->display_as('username', 'مسمى تسجيل الدخول')
            ->display_as('mobile', 'رقم الهاتف')
            ->display_as('active', 'الحالة')
            ->display_as('email', 'البريد الإلكتروني');

        // only webmaster and admin can reset user password
        if ($this->ion_auth->in_group(array('webmaster', 'admin')))
        {
            $crud->add_action('Reset Password', '', 'admin/staff_crew/reset_password', 'fa fa-repeat');
        }

        $this->mPageTitle = 'الإشراف';
        $this->render_crud();
    }

    public function teacher()
    {
        // Setup crud
        $crud = $this->generate_crud('admin_users', 'مدرس');
        $crud->set_model('Custom_CRUD_model');
        $this->ion_auth->get_users_groups();
        $crud->basic_model->set_custom_query('SELECT * FROM admin_users NATURAL JOIN admin_users_groups WHERE admin_users_groups.group_id=5');
        $crud->columns('name', 'username', 'mobile', 'email', 'active');
        $crud->fields('name', 'username', 'mobile', 'email', 'active')
            ->display_as('name', 'الإسم')
            ->display_as('username', 'مسمى تسجيل الدخول')
            ->display_as('mobile', 'رقم الهاتف')
            ->display_as('active', 'الحالة')
            ->display_as('email', 'البريد الإلكتروني');

        // only webmaster and admin can reset user password
        if ($this->ion_auth->in_group(array('webmaster', 'admin')))
        {
            $crud->add_action('Reset Password', '', 'admin/staff_crew/reset_password', 'fa fa-repeat');
        }

        $this->mPageTitle = 'المدرسين';
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
                'users'                => 'admin_users',
                'groups'            => 'admin_groups',
                'users_groups'        => 'admin_users_groups',
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

        $this->load->model('admin_user_model', 'admin_users');
        $target = $this->admin_users->get($user_id);
        $this->db->select('description');
        $this->db->from('admin_groups');
        $this->db->join('admin_users_groups','admin_groups.id = admin_users_groups.group_id');
        $this->db->where('user_id',$user_id);
        $target->group = $this->db->get()->result()[0]->description;
        $this->mViewData['target'] = $target;

        $this->mViewData['form'] = $this->form;
        $this->mPageTitle = 'إعادة تعيين كلمة المرور';
        $this->render('staff_crew/reset_password');
    }
}