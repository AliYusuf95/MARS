<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// NOTE: this controller inherits from MY_Controller instead of Member_Controller,
// since no authentication is required
class Login extends MY_Controller {

    /**
     * Login page and submission
     */
    public function index()
    {
        $this->load->library('form_builder');

        $this->mPageTitle = 'تسجيل الدخول';

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
                redirect($this->mModule.$this->mLanguage.'/member');
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
        $this->mBodyClass = 'gray-bg';
        $this->render('login', 'full_width');
    }
}
