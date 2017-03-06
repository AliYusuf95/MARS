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
    'small_name' => 'MSR',

    // Default page title prefix
    'page_title_prefix' => '',

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
            /*'assets/dist/admin/adminlte.min.js',
            'assets/dist/admin/lib.min.js',
            'assets/dist/admin/app.min.js'*/
            'assets/dist/libraries/jquery/jquery.min.js',
        ),
        'foot'    => array(
            'assets/dist/admin/rtl/bootstrap/js/bootstrap.min.js',
            'assets/dist/admin/rtl/dist/js/app.js'
        ),
    ),

    // Default stylesheets to embed at page head
    'stylesheets' => array(
        'screen' => array(
            //'assets/dist/admin/adminlte.min.css',
            //'assets/dist/admin/lib.min.css',
            //'assets/dist/admin/app.min.css',
            'assets/dist/admin/rtl/bootstrap/css/bootstrap.min.css',
            'assets/dist/admin/rtl/dist/css/AdminLTE.min.css',
            'assets/dist/admin/rtl/dist/css/skins/all-skins.min.css',
            'assets/dist/admin/rtl/dist/css/bootstrap-rtl.min.css',
            'assets/dist/admin/rtl/dist/css/font-awesome.min.css',
            'assets/dist/admin/rtl/dist/css/rtl.css',
            /*'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css',
            'https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css'*/
        )
    ),

    // Default CSS class for <body> tag
    'body_class' => 'skin-blue sidebar-mini',

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

    // Menu items
    'menu' => array(
        'home' => array(
            'name'        => 'الرئيسية',
            'url'         => '',
            'icon'        => 'fa fa-home',
        ),
        'general' => array(
            'name'        => 'إعدادات عامة',
            'url'         => 'general',
            'icon'        => 'fa fa-sliders',
            'children'  => array(
                'التقارير'              => 'general/reports',
                'السنوات الدراسية'      => 'general/terms',
                'الفصول الدراسية'       => 'general/semesters',
                'المستويات الدراسية'    => 'general/levels',
                'قائمة الدرجات'         => 'general/grades',
            )
        ),
        'year' => array(
            'name'        => 'إعدادات السنة الدراسية',
            'url'         => 'year',
            'icon'        => 'fa fa-calendar',
            'children'  => array(
                'الصفوف'                => 'year/classes',
                'الفرق'                => 'year/sections',
                'المواد'                => 'year/subjects',
                'توزيع درجات المواد'    => 'year/grades',
                'مواد الصفوف'           => 'year/class_subject',
            )
        ),
        /*'staff_crew' => array(
            'name'        => 'الهيئة التعليمية',
            'url'         => 'semester',
            'icon'        => 'fa fa-briefcase',
            'children'  => array(
                'الإدارة'               => 'staff_crew/admin',
                'الإشراف العام'                => 'staff_crew/manager',
                'الإشراف'                => 'staff_crew/staff',
                'المدرسين'                => 'staff_crew/teacher',
            )
        ),*/
        'teacher' => array(
            'name'        => 'المدرسين',
            'url'         => 'teacher',
            'icon'        => 'fa fa-graduation-cap',
            'children'  => array(
                'القائمة'               => 'teacher/records',
                'الحضور'                => 'teacher/attendance',
                'مدرسي الصفوف'                => 'teacher/section_teacher',
            )
        ),
        'user' => array(
            'name'        => 'الطلاب',
            'url'         => 'user',
            'icon'        => 'fa fa-user',
            'children'  => array(
                'القائمة'           => 'user/records',
                'طلبات التسجيل'           => 'user/requests',
                //'إضافة طالب'            => 'user/create',
                'المجموعات'       => 'user/group',
                'تسجيل الحضور'            => 'user/attendance',
                'حضور الطلاب'            => 'user/report',
            )
        ),
        'panel' => array(
            'name'        => 'لوحة تحكم الإدارة',
            'url'         => 'panel',
            'icon'        => 'fa fa-cog',
            'children'  => array(
                'إعدادات الحساب'            => 'panel/account',
                'جميع الأعضاء'            => 'panel/admin_users',
                'إضافة عضو'        => 'panel/admin_user_create',
                'المجموعات'        => 'panel/groups',
            )
        ),
        'util' => array(
            'name'        => 'Utilities',
            'url'         => 'util',
            'icon'        => 'fa fa-cogs',
            'children'  => array(
                'Database Versions'        => 'util/list_db',
            )
        ),
        'logout' => array(
            'name'        => 'Sign Out',
            'url'         => 'panel/logout',
            'icon'        => 'fa fa-sign-out',
        )
    ),

    // Login page
    'login_url' => 'admin/login',

    // Restricted pages
    // All restrictions are dynamic from db
    /*'page_auth' => array(
        'util'                        => array('webmaster'),
        'util/list_db'                => array('webmaster'),
        'util/backup_db'            => array('webmaster'),
        'util/restore_db'            => array('webmaster'),
        'util/remove_db'            => array('webmaster'),
    ),*/

    // AdminLTE settings
    'adminlte' => array(
        'body_class' => array(
            'webmaster'    => 'skin-blue-light sidebar-mini',
            'admin'        => 'skin-red sidebar-mini',
            'manager'    => 'skin-black sidebar-mini',
            'staff'        => 'skin-blue-light sidebar-mini',
        )
    ),

    // Useful links to display at bottom of sidemenu
    'useful_links' => array(
        array(
            // Optional
            //'auth'        => array('webmaster', 'admin', 'manager', 'staff'),
            'name'        => 'Mobile - الهاتف',
            'url'        => 'tel:+97337709595',
            'target'    => '_blank',
            'icon'      => 'fa-phone',
            'color'        => 'text-green'
        ),
        array(
            'name'        => 'Instagram - إنستقرام',
            'url'        => 'https://www.instagram.com/mashrow/',
            'target'    => '_blank',
            'icon'      => 'fa-instagram',
            'color'        => 'text-orange'
        ),
        array(
            'name'        => 'Twitter - تويتر',
            'url'        => 'https://www.twitter.com/mashrow/',
            'target'    => '_blank',
            'icon'      => 'fa-twitter',
            'color'        => 'text-aqua'
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
$config['sess_cookie_name'] = 'ci_session_admin';