<?php

/**
 * Config file for form validation
 * Reference: http://www.codeigniter.com/user_guide/libraries/form_validation.html
 * (Under section "Creating Sets of Rules")
 */

$config = array(

	// Admin User Login
	'login/index' => array(
		array(
			'field'		=> 'username',
			'label'		=> 'إسم المستخدم',
			'rules'		=> 'trim|required',
		),
		array(
			'field'		=> 'password',
			'label'		=> 'كلمة المرور',
			'rules'		=> 'trim|required',
		),
	),

	'user/att' => array(
		array(
			'field'		=> 'date',
			'label'		=> 'الحضور',
			'rules'		=> 'trim|required',
		)
	),

	// Create User
	'user/create' => array(
		array(
			'field'		=> 'first_name',
			'label'		=> 'First Name',
			'rules'		=> 'trim|required',
		),
		array(
			'field'		=> 'last_name',
			'label'		=> 'Last Name',
			'rules'		=> 'trim|required',
		),
		array(
			'field'		=> 'username',
			'label'		=> 'Username',
			'rules'		=> 'trim|is_unique[users.username]',				// use email as username if empty
		),
		array(
			'field'		=> 'email',
			'label'		=> 'Email',
			'rules'		=> 'trim|required|valid_email|is_unique[users.email]',
		),
		array(
			'field'		=> 'password',
			'label'		=> 'Password',
			'rules'		=> 'trim|required',
		),
		array(
			'field'		=> 'retype_password',
			'label'		=> 'Retype Password',
			'rules'		=> 'trim|required|matches[password]',
		),
	),

	// Reset User Password
	'user/reset_password' => array(
		array(
			'field'		=> 'new_password',
			'label'		=> 'كلمة المرور الجديدة',
			'rules'		=> 'required',
		),
		array(
			'field'		=> 'retype_password',
			'label'		=> 'تأكيد كلمة المرور الجديدة',
			'rules'		=> 'required|matches[new_password]',
		),
	),

	// Create Admin User
	'panel/admin_user_create' => array(
		array(
			'field'		=> 'username',
			'label'		=> 'مسمى تسجيل الدخول',
			'rules'		=> 'required|is_unique[admin_users.username]|is_not_arabic_text',
		),
        array(
            'field'		=> 'cpr',
            'label'		=> 'الرقم الشخصي',
            'rules'		=> 'required|is_unique[admin_users.cpr]|integer|exact_length[9]',
        ),
		array(
			'field'		=> 'name',
			'label'		=> 'الإسم',
			'rules'		=> 'required',
		),
		// Admin User can have no email
		array(
			'field'		=> 'email',
			'label'		=> 'البريد الإلكتروني',
			'rules'		=> 'valid_email|is_unique[admin_users.email]',
		),
        array(
            'field'		=> 'mobile',
            'label'		=> 'الهاتف',
            'rules'		=> 'exact_length[8]',
        ),
		array(
			'field'		=> 'password',
			'label'		=> 'كلمة المرور',
			'rules'		=> 'required',
		),
		array(
			'field'		=> 'retype_password',
			'label'		=> 'تأكيد كلمة المرور',
			'rules'		=> 'required|matches[password]',
		),
	),

	// Reset Admin User Password
	'panel/admin_user_reset_password' => array(
		array(
			'field'		=> 'new_password',
			'label'		=> 'كلمة المرور الجديدة',
			'rules'		=> 'required',
		),
		array(
			'field'		=> 'retype_password',
			'label'		=> 'تأكيد كلمة المرور الجديدة',
			'rules'		=> 'required|matches[new_password]',
		),
	),

	// Admin User Update Info
	'panel/account_update_info' => array(
		array(
			'field'		=> 'name',
			'label'		=> 'الإسم',
			'rules'		=> 'trim|required|is_arabic_text',
		),
		array(
			'field'		=> 'mobile',
			'label'		=> 'رقم الهاتف',
			'rules'		=> 'trim|required|exact_length[8]|is_number',
		),
        array(
            'field'		=> 'email',
            'label'		=> 'البريد الإلكتروني',
            'rules'		=> 'trim|required|valid_email|is_unique[users.email]',
        ),
	),

	// Admin User Change Password
	'panel/account_change_password' => array(
        array(
            'field'		=> 'new_password',
            'label'		=> 'كلمة المرور الجديدة',
            'rules'		=> 'required|min_length[6]',
        ),
        array(
            'field'		=> 'retype_password',
            'label'		=> 'تأكيد كلمة المرور الجديدة',
            'rules'		=> 'required|matches[new_password]',
        ),
    ),

    'staff_crew/reset_password' => array(
        array(
            'field'		=> 'new_password',
            'label'		=> 'كلمة المرور الجديدة',
            'rules'		=> 'required|min_length[1]|max_length[15]',
        ),
        array(
            'field'		=> 'retype_password',
            'label'		=> 'تأكيد كلمة المرور الجديدة',
            'rules'		=> 'required|matches[new_password]',
        ),
    ),

    'user/attendance' => array(
        array(
            'field'		=> 'date',
            'label'		=> 'التاريخ',
            'rules'		=> 'required',
        ),
        array(
            'field'		=> 'section',
            'label'		=> 'الفرقة',
            'rules'		=> 'required',
        ),
        array(
            'field'		=> 'subject',
            'label'		=> 'المادة',
            'rules'		=> 'required',
        )
    ),

    'teacher/attendance' => array(
        array(
            'field'		=> 'date',
            'label'		=> 'التاريخ',
            'rules'		=> 'required',
        )
    ),

    'panel/admin_user' => array(
        array(
            'field'		=> 'groups[]',
            'label'		=> 'التاريخ',
            'rules'		=> 'required|not_value[1]',
        )
    ),

);