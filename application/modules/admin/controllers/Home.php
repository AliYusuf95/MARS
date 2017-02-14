<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Admin_Controller {


    /**
     * Home constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->mPageTitle = 'الرئيسية';
    }

    public function index()
	{
		$this->load->model('user_model', 'users');
		$this->mViewData['count'] = array(
			'users' => $this->users->count_all(),
		);
		$this->render('home');
	}
}
