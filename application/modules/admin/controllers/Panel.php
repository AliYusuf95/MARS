<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Admin Panel management, includes: 
 * 	- Admin Users CRUD
 * 	- Admin User Groups CRUD
 * 	- Admin User Reset Password
 * 	- Account Settings (for login user)
 */
class Panel extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_builder');
        // Set Page small title
        $this->mPageTitleSmall = 'لوحة تحكم الإدارة';
	}

	// Admin Users CRUD
	public function admin_users()
	{
	    // Check webmaster
	    if (!$isWebmaster = $this->verify_page(false, 'panel/admin_users/add_webmaster'))
	        $where_cause = array('admin_groups.id !='=>1);
	    else
            $where_cause = null;

		$crud = $this->generate_crud('admin_users', 'كادر تعليمي');
        $crud->set_relation_n_n('groups', 'admin_users_groups', 'admin_groups', 'user_id', 'group_id', 'description',
            null,$where_cause);
        $crud->columns('username', 'name','groups', 'active');
        $crud->edit_fields('groups');
        $crud->fields('name', 'username', 'mobile', 'email', 'active', 'groups')
            ->display_as('name', 'الإسم')
            ->display_as('username', 'مسمى تسجيل الدخول')
            ->display_as('mobile', 'رقم الهاتف')
            ->display_as('groups', 'المجموعة')
            ->display_as('active', 'الحالة')
            ->display_as('email', 'البريد الإلكتروني');

		// only webmaster can reset Admin User password
		if(!$this->verify_page(false, 'panel/admin_users/delete'))
            $crud->unset_delete();

        if($this->verify_page(false, 'panel/admin_user_reset_password'))
        {
            $crud->add_action('Reset Password', '', $this->mModule.'/panel/admin_user_reset_password', 'fa fa-repeat');
        }

		if ($this->verify_page(false, 'panel/admin_users/add_admin') && !$isWebmaster) {
            // This validation is used only for webmaster group
            if ($this->form_validation->run() == FALSE) {
                $crud->set_rules('groups','مجموعة','required');
            }
        } else if (!$isWebmaster) {
            $crud->unset_add();
        }

        $crud->callback_before_insert(array($this,'insert_admin_user_callback'));

        $this->mPageTitle = 'قائمة أعضاء الكادر التعليمي';
		$this->render_crud();
	}

    function insert_admin_user_callback($post_array) {
        $this->load->library('encrypt');
        $key = 'super-secret-key';
        $post_array['password'] = $this->encrypt->encode($post_array['password'], $key);

        return $post_array;
    }

    // Create Admin User
	public function admin_user_create()
	{
		// (optional) only top-level admin user groups can create Admin User
		//$this->verify_auth(array('webmaster'));

		$form = $this->form_builder->create_form();

		if ($form->validate())
		{
			// passed validation
			$username = $this->input->post('username');
			$email = $this->input->post('email');
			$password = $this->input->post('password');
			$additional_data = array(
				'name'	=> $this->input->post('name'),
				'mobile'		=> $this->input->post('mobile'),
			);
			$groups = $this->input->post('groups');

			// create user (default group as "members")
			$user = $this->ion_auth->register($username, $password, $email, $additional_data, $groups);
			if ($user)
			{
				// success
				$messages = $this->ion_auth->messages();
				$this->system_message->set_success($messages);
			}
			else
			{
				// failed
				$errors = $this->ion_auth->errors();
				$this->system_message->set_error($errors);
			}
			refresh();
		}

		$groups = $this->ion_auth->groups()->result();
		unset($groups[0]);	// disable creation of "webmaster" account
		$this->mViewData['groups'] = $groups;
		$this->mPageTitle = 'إضافة عضو';

		$this->mViewData['form'] = $form;
		$this->render('panel/admin_user_create');
	}

    // Admin User Groups with Permission CRUD
    public function groups()
    {
        $this->verify_page();
        $crud = $this->generate_crud('admin_groups','مجموعة');
        $this->mPageTitle = 'المجموعات';
        $crud->set_relation_n_n('permissions', 'admin_groups_permissions', 'admin_permissions',
            'admin_group_id', 'admin_permission_id', 'description');
        $crud->where('id !=',1);
        $crud->display_as('name', 'الإسم')
            ->display_as('description', 'الوصف')
            ->display_as('permissions', 'الصلاحيات');
        $crud->set_rules('name','الإسم','is_not_arabic_text');
        $crud->callback_field('name',array($this,'add_hint'));
        $crud->required_fields(array('name','description'));
        $this->render_crud();
    }

    function add_hint($value = '', $primary_key = null)
    {
        return '<input id="field-name" class="form-control" value="'.$value
            .'" name="name" type="text" value="manager" maxlength="20" REQUIRED> *الرجاء كتابة الإسم باللغة الإنجلينزية فقط.';
    }

    // Admin User Permission CRUD
    public function admin_permission()
    {
        $this->verify_page();
        $crud = $this->generate_crud('admin_permissions');
        $this->mPageTitle = 'Admin Permissions';
        $this->render_crud();
    }

	// Admin User Reset password
	public function admin_user_reset_password($user_id)
	{
		// only top-level users can reset Admin User passwords
		$this->verify_page(array('webmaster'));

		$form = $this->form_builder->create_form();
		if ($form->validate())
		{
			// pass validation
			$data = array('password' => $this->input->post('new_password'));
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
		$this->mViewData['target'] = $target;

		$this->mViewData['form'] = $form;
		$this->mPageTitle = 'Reset Admin User Password';
		$this->render('panel/admin_user_reset_password');
	}

	// Account Settings
	public function account()
	{
		// Update Info form
		$form1 = $this->form_builder->create_form($this->mModule.'/panel/account_update_info');
		$form1->set_rule_group('panel/account_update_info');
		$this->mViewData['form1'] = $form1;

		// Change Password form
		$form2 = $this->form_builder->create_form($this->mModule.'/panel/account_change_password');
		$form1->set_rule_group('panel/account_change_password');
		$this->mViewData['form2'] = $form2;

		$this->mPageTitle = "Account Settings";
		$this->render('panel/account');
	}

	// Submission of Update Info form
	public function account_update_info()
	{
		$data = $this->input->post();
		if ($this->ion_auth->update($this->mUser->id, $data))
		{
			$messages = $this->ion_auth->messages();
			$this->system_message->set_success($messages);
		}
		else
		{
			$errors = $this->ion_auth->errors();
			$this->system_message->set_error($errors);
		}

		redirect($this->mModule.'/panel/account');
	}

	// Submission of Change Password form
	public function account_change_password()
	{
		$data = array('password' => $this->input->post('new_password'));
		if ($this->ion_auth->update($this->mUser->id, $data))
		{
			$messages = $this->ion_auth->messages();
			$this->system_message->set_success($messages);
		}
		else
		{
			$errors = $this->ion_auth->errors();
			$this->system_message->set_error($errors);
		}

		redirect($this->mModule.'/panel/account');
	}
	
	/**
	 * Logout user
	 */
	public function logout()
	{
		$this->ion_auth->logout();
		redirect($this->mConfig['login_url']);
	}
}
