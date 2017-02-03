<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Config file for form validation
 * http://www.codeigniter.com/user_guide/libraries/form_validation.html (Under section "Creating Sets of Rules")
 */

$config = array(
	/** Example: 
	'auth/login' => array(
		array(
			'field'		=> 'email',
			'label'		=> 'Email',
			'rules'		=> 'required|valid_email',
		),
		array(
			'field'		=> 'password',
			'label'		=> 'Password',
			'rules'		=> 'required',
		),
	),*/
	'login/index' => array(
		array(
			'field'		=> 'username',
			'label'		=> 'Username',
			'rules'		=> 'required',
		),
		array(
			'field'		=> 'password',
			'label'		=> 'Password',
			'rules'		=> 'required',
		),
	),

    'registration/index' => array(
        array(
            'field' => 'name',
            'label' => 'Full Name',
            'rules' => 'trim|required|is_arabic_text|min_length[5]',
            'errors' => array(
                'required'  => 'خانة الإسم مطلوبة',
                'is_arabic_text'    => 'أدخل الإسم الثلاثي',
                'min_length'    => 'أدخل الإسم الثلاثي'
            ),
        ),
        array(
            'field' => 'cpr',
            'label' => 'CPR',
            'rules' => 'trim|required|exact_length[9]|is_number|is_unique[users.cpr]',
            'errors' => array(
                'required'  => 'خانة الرقم الشخصي مطلوب',
                'is_unique' => 'الرقم الشخصي موجود مسبقا',
                'exact_length'  => 'الرقم الشخصي مكون من 9 أرقام',
                'is_number'  => 'الرقم الشخصي مكون من 9 أرقام'
            ),
        ),
        array(
            'field' => 'mobile',
            'label' => 'Mobile',
            'rules' => 'trim|required|is_number|exact_length[8]',
            'errors' => array(
                'required' => 'خانة رقم الهاتف مطلوبة',
                'exact_length'  => 'رقم الهاتف مكون من 8 أرقام',
                'is_number' => 'رقم الهاتف مكون من 8 أرقام',
            ),
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|valid_email',
            'errors' => array(
                'valid_email' => 'البريد الإلكتروني غير صحيح',
            ),
        ),
        array(
            'field' => 'ed_level',
            'label' => 'Education Level',
            'rules' => 'trim|required|not_value[0]',
            'errors' => array(
                'required' => 'خانة المرحلة الدراسية مطلوبة',
                'not_value' => 'إختر المرحلة الدراسية'
            ),
        )
    )
);

/**
 * Google reCAPTCHA settings
 * https://www.google.com/recaptcha/
 */
$config['recaptcha'] = array(
	'site_key'		=> '',
	'secret_key'	=> '',
);
