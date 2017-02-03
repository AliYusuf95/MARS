<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends Member_Controller
{
    public function index()
    {
        $this->load->model('user_model', 'users');

        $this->add_script('assets/dist/libraries/jquery/jquery.min.js',true,'head');
        $this->render('home', 'full_width');
    }

    public function login()
    {
        $this->load->library('form_builder');

        if ( $this->ion_auth->logged_in() ){
            $this->ion_auth->logout();
        }

        /** @var Form $form */
        $form = $this->form_builder->create_form();

        if ($form->validate())
        {
            // passed validation
            $identity = $this->input->post('username');
            $password = $this->input->post('password');
            $remember = ($this->input->post('remember')=='on');

            if ($this->ion_auth->login($identity, $password, $remember))
            {
                // login succeed
                $messages = $this->ion_auth->messages();
                $this->system_message->set_success($messages);
                redirect($this->mModule);
            }
            else
            {
                // login failed
                $errors = $this->ion_auth->errors();
                $this->system_message->set_error($errors);
                refresh();
            }
        }

        // display form when no POST data, or validation failed
        $this->mViewData['form'] = $form;
        $this->mBodyClass = 'login-page';
        $this->render('login', 'default');
    }

    public function logout()
    {
        $this->ion_auth->logout();
        redirect($this->mLanguage.'/'.$this->mConfig['login_url']);
    }
}