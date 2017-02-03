<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

    public $autoload = array(
        'model' => array('User_model' => 'users')
    );

    public function index()
    {
        $this->mPageTitle = 'الرئيسية';
        $this->mMenu['home']['name'] = lang('home');
        $this->mMenu['registration']['name'] = lang('sign_up');

        $studentsCount = $this->users->count_all();
        $this ->mViewData['counters'] = array(
          array(
              'name' => 'عدد الطلاب المسجلين',
              'counts'=>$studentsCount),
            array(
                'name' => 'عدد المدرسين',
                'counts'=>60)
        );

        // jquery
        $this->add_script('assets/dist/libraries/jquery/jquery.min.js',true,'head');

        $this->render('home', 'full_width');
    }
}