<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| CI Bootstrap 3 Configuration
| -------------------------------------------------------------------------
| This file lets you define default values to be passed into views 
| when calling MY_Controller's render() function. 
| 
| See example and detailed explanation from:
|     /application/config/ci_bootstrap_example.php
*/

$config['ci_bootstrap'] = array(

    // Site name
    'site_name' => 'مشروع العمل الإسلامي',

    // Default page title prefix
    'page_title_prefix' => 'مشروع العمل | ',

    // Default page title
    'page_title' => '',

    // Default meta data
    'meta_data'    => array(
        'author'        => 'مشروع العمل الإسلامي - قرية بوري',
        'description'    => 'التعليم الديني المستمر، مشروع العمل الإسلامي - قرية بوري - مملكة البحرين',
        'keywords'        => 'تعليم ديني، تسجيل، بوري، البحرين'
    ),

    // Default scripts to embed at page head or end
    'scripts' => array(
        'head'    => array(
        ),
        'foot'    => array(
            'assets/dist/frontend/lib.min.js',
            'assets/dist/frontend/app.min.js',
            'assets/dist/libraries/offcanvas/bootstrap.offcanvas.min.js'
        ),
    ),

    // Default stylesheets to embed at page head
    'stylesheets' => array(
        'screen' => array(
            'assets/dist/frontend/lib.min.css',
            'assets/dist/frontend/lib-rtl.min.css',
            'assets/dist/libraries/offcanvas/bootstrap.offcanvas.min.css',
            'assets/dist/frontend/app.min.css'
        )
    ),

    // Default CSS class for <body> tag
    'body_class' => '',

    // Multilingual settings
    'languages' => array(
        'default'        => 'ar',
        'autoload'        => array('general'),
        'available'        => array(
            /*'en' => array(
                'label'    => 'English',
                'value'    => 'english'
            ),*/
            'ar' => array(
                'label'    => 'العربية',
                'value'    => 'arabic'
            )
        )
    ),

    // Google Analytics User ID
    'ga_id' => '',

    // Menu items
    'menu' => array(
        'home' => array(
            'name'        => 'الرئيسية',
            'url'        => '',
        ),
        'registration' => array(
            'name'        => 'التسجيل',
            'url'        => 'registration',
        ),
    ),

    'member_menu' => array(
        'home' => array(
            'name'        => 'الرئيسية',
            'url'        => 'member',
        ),
        'attendees' => array(
            'name'        => 'الحضور',
            'url'        => 'member/attendance',
        ),
    ),

    // Login page
    'login_url' => 'login',

    // Restricted pages
    'page_auth' => array(

    ),

    // Email config
    'email' => array(
        'from_email'        => '',
        'from_name'            => '',
        'subject_prefix'    => '',

        // Mailgun HTTP API
        'mailgun_api'        => array(
            'domain'            => '',
            'private_api_key'    => ''
        ),
    ),

    // Debug tools
    'debug' => array(
        'view_data'    => FALSE,
        'profiler'    => FALSE
    ),
);

/*
| -------------------------------------------------------------------------
| Override values from /application/config/config.php
| -------------------------------------------------------------------------
*/
$config['sess_cookie_name'] = 'ci_session_frontend';