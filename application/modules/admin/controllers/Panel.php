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

    private $isWebmaster = false;

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_builder');
		// Check webmaster
        $this->isWebmaster = $this->verify_page(false, 'panel/admin_users/add_webmaster');
        // Set Page small title
        $this->mPageTitleSmall = 'لوحة تحكم الإدارة';
        $this->push_breadcrumb($this->mPageTitleSmall);
	}

	// Admin Users CRUD
	public function admin_users()
	{
	    // Check webmaster
	    if (!$this->isWebmaster)
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

        $crud->callback_field('username',array($this,'add_hint'));

		// only webmaster can reset Admin User password
		if(!$this->verify_page(false, 'panel/admin_users/delete'))
            $crud->unset_delete();

        if($this->verify_page(false, 'panel/admin_user_reset_password'))
        {
            $crud->add_action('Reset Password', '', $this->mModule.'/panel/admin_user_reset_password', 'fa fa-repeat');
        }

		if ($this->verify_page(false, 'panel/admin_users/add_admin') && !$this->isWebmaster) {
            // This validation is used only for non webmaster group
            if ($this->form_validation->run() == FALSE) {
                $crud->set_rules('groups','مجموعة','required');
            }
        } else if (!$this->isWebmaster) {
            $crud->unset_add();
        }

        $this->mPageTitle = 'قائمة أعضاء الكادر التعليمي';
		$this->render_crud();
	}

    function add_hint($value = '', $primary_key = null)
    {
        return '<input id="field-name" class="form-control" value="'.$value.
            '" name="username" type="text" maxlength="20" REQUIRED><br/>'.
            '<span class="text-red"> *الرجاء كتابة مسمى تسجيل الدخول باللغة الإنجلينزية فقط.</span>';
    }

    // Create Admin User
	public function admin_user_create()
	{
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
				'cpr'		=> $this->input->post('cpr'),
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
        $this->mPageTitle = 'المجموعات';
        $this->verify_page();
        $crud = $this->generate_crud('admin_groups','مجموعة');
        $crud->set_relation_n_n('permissions', 'admin_groups_permissions', 'admin_permissions',
            'admin_group_id', 'admin_permission_id', 'description','updated_at');
        // remove webmaster group
        $crud->where('id !=',1);

        // Remove groups that have edit permission for non webmaster
        if (!$this->isWebmaster) {
            foreach ($this->groups_permission->getGroupsWithPermission('panel/groups') as $id)
                $crud->where('id !=', $id);
        }

        $crud->display_as('name', 'الإسم')
            ->display_as('description', 'الوصف')
            ->display_as('permissions', 'الصلاحيات');
        $crud->field_type('permissions','text');
        $crud->set_rules('name','الإسم','is_not_arabic_text');

        // no need to edit these fields
        if ($crud->getState() == 'edit') {
            $crud->callback_field('name', array($this, 'name_readonly'));
            $crud->callback_field('description', array($this, 'description_readonly'));
        }

        if ($crud->getState() == 'add') {
            $crud->callback_field('name', array($this, 'groups_name_add_hint'));
        }

        $this->render_crud();
    }

    function groups_name_add_hint($value = '', $primary_key = null)
    {
        return '<input id="field-name" class="form-control" value="'.$value.
            '" name="name" type="text" maxlength="20" REQUIRED><br/>'.
            '<span class="text-red"> *الرجاء كتابة الاسم باللغة الإنجلينزية فقط.</span>';
    }

    function name_readonly($value = '', $primary_key = null) {
        return $value.'<input id="field-name" value="'.$value .'" name="name" type="hidden">';
    }

    function description_readonly($value = '', $primary_key = null) {
        return $value.'<input id="field-name" value="'.$value .'" name="description" type="hidden">';
    }

	// Admin User Reset password
	public function admin_user_reset_password($user_id)
	{
		// only top-level users can reset Admin User passwords
		$this->verify_page(array('webmaster'));
		if($user_id == null || $user_id == '')
            redirect(base_url('admin').'/panel/admin_users');
		else {

            $form = $this->form_builder->create_form();
            if ($form->validate()) {
                // pass validation
                $data = array('password' => $this->input->post('new_password'));
                if ($this->ion_auth->update($user_id, $data)) {
                    $messages = $this->ion_auth->messages();
                    $this->system_message->set_success($messages);
                } else {
                    $errors = $this->ion_auth->errors();
                    $this->system_message->set_error($errors);
                }
                refresh();
            }

            $this->load->model('admin_user_model', 'admin_users');
            $target = $this->admin_users->get($user_id);
            $this->mViewData['target'] = $target;

            $this->mViewData['form'] = $form;

            $this->mPageTitle = 'إعادة تعيين كلمة المرور';
            $this->mPageTitleSmall = "جميع الأعضاء";
            $this->push_breadcrumb('جميع الأعضاء','panel/admin_users');

            $this->render('panel/admin_user_reset_password');
        }   
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
        $form2->set_rule_group('panel/account_change_password');
		$this->mViewData['form2'] = $form2;

		$this->mPageTitle = "إعدادات الحساب";
		$this->render('panel/account');
	}

	// Submission of Update Info form
	public function account_update_info()
	{
        if ($this->input->server('REQUEST_METHOD') != 'POST')
            redirect($this->mModule.'/panel/account');
		$data = $this->input->post();
        if ($this->form_validation->run()) {
            if ($this->ion_auth->update($this->mUser->id, $data)) {
                $messages = $this->ion_auth->messages();
                $this->system_message->set_success($messages);
            } else {
                $errors = $this->ion_auth->errors();
                $this->system_message->set_error($errors);
            }
        } else
            $this->system_message->set_error($this->form_validation->error_string());

		redirect($this->mModule.'/panel/account');
	}

	// Submission of Change Password form
	public function account_change_password()
	{
        if ($this->input->server('REQUEST_METHOD') != 'POST')
            redirect($this->mModule.'/panel/account');
		$data = array('password' => $this->input->post('new_password'));
        if ($this->form_validation->run()){
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
        } else
            $this->system_message->set_error($this->form_validation->error_string());

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
