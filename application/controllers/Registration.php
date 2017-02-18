<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registration extends MY_Controller {

    /**
     * @var Form
     */
    private $form;
    public $autoload = array(
        'helper'    => array('url'),
        'model' => array('User_model' => 'users')
    );

    function __construct()
    {
        parent::__construct();
        $this->load->library('form_builder');
        $this->form = $this->form_builder->create_form(null,false,'id="registration_form" class="form-horizontal" role="form"');
    }

    public function index()
    {
        $this->mPageTitle = 'التسجيل';
        $this ->mViewData['form'] = $this->form;
        // level select options
        $this->db->select('id, title');
        $this->db->from('levels');
        $this->db->where('active',1);
        $result = $this->db->get()->result_array();

        $levels = array('0'=>'إختر المرحلة الدراسية');
        foreach ($result as $level)
            $levels[$level["id"]] = $level["title"];

        $this ->mViewData['levels'] = $levels;

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            if ($this->form->validate()) {
                $this->db->select('*');
                $this->db->from('users');
                $this->db->where('cpr',$this->numbers_a2e($this->input->post('cpr')));
                if ($this->db->get()->num_rows() == 0) {
                    $this->db->insert('users',array(
                        'cpr' => $this->numbers_a2e($this->input->post('cpr')),
                        'name' => $this->input->post('name'),
                        'mobile' => $this->numbers_a2e($this->input->post('mobile')),
                        'email' => $this->input->post('email'),
                        'level_id' => $this->input->post('ed_level'),
                        'level_year' => date('Y')
                    ));
                    $this->system_message->set_success("تم التسجيل بنجاح");
                } else {
                    $this->system_message->set_error("عذراً، هذا المستخدم موجود بالفعل !!");
                }
            } else {
                // wrong user information
                $this->system_message->set_error("هنالك خطأ في البيانات المدخلة، يرجى المحاولة مرة أخرى.");
            }
        }

        // jquery
        $this->add_script('assets/dist/libraries/jquery/jquery.min.js',true,'head');
        // jquery.validate
        $this->add_script('assets/dist/libraries/validate/jquery.validate.min.js',true,'head');
        // sweetalert
        $this->add_stylesheet('assets/dist/libraries/sweetalert/sweetalert2.min.css');
        $this->add_script('assets/dist/libraries/sweetalert/sweetalert2.js');

        $this->render('registration_view', 'full_width');
    }

    /**
     * Converts numbers from arabic to english numerals.
     *
     * @param  string $str Arbitrary text
     * @return string Text with eastern Arabic numerals converted into Arabic numerals.
     */
    private function numbers_a2e($str)
    {
        $arabic_eastern = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
        $arabic_western = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        return str_replace($arabic_eastern, $arabic_western, $str);
    }

}