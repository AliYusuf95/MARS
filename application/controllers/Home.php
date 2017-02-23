<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Home
 * @property User_model users
 * @property Admin_users_sections_model teachers
 */
class Home extends MY_Controller {

    public $autoload = array(
        'model' => array(
            'User_model' => 'users',
            'Admin_users_sections_model' => 'teachers'
        )
    );

    public function index()
    {
        $this->mPageTitle = 'الرئيسية';
        $this->mMenu['home']['name'] = lang('home');
        $this->mMenu['registration']['name'] = lang('sign_up');

        $studentsCount = $this->users->count_all();
        $teachersCount = count($this->teachers->getAvailableTeachers());
        $this ->mViewData['counters'] = array(
          array(
              'name' => 'عدد الطلاب المسجلين',
              'counts'=>$studentsCount),
            array(
                'name' => 'عدد المدرسين',
                'counts'=>$teachersCount)
        );

        // jquery
        $this->add_script('assets/dist/libraries/jquery/jquery.min.js',true,'head');

        $this->render('home', 'full_width');
    }
}