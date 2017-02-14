<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class General extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();

        // Set Page small title
        $this->mPageTitleSmall = 'إعدادات عامة';
        $this->push_breadcrumb($this->mPageTitleSmall);
    }

    public function terms()
    {
        // Setup crud
        $crud = $this->generate_crud('terms','السنوات الدراسية');
        $crud->columns('title', 'start_date','end_date');
        $crud->display_as('title','العنوان')
            ->display_as('start_date','تاريخ البدء')
            ->display_as('end_date','تاريخ الإنتهاء');

        $this->mPageTitle = 'السنوات الدراسية';
        $this->render_crud();
    }

    public function semesters()
    {
        // Setup crud
        $crud = $this->generate_crud('semesters','الفصول الدراسية');
        $crud->columns('title','term_id', 'start_date','end_date');
        $crud->display_as('title','العنوان')
            ->display_as('start_date','تاريخ البدء')
            ->display_as('end_date','تاريخ الإنتهاء')
            ->display_as('term_id','السنة الدراسية');
        $crud->set_relation('term_id','terms','title',null,'id');

        $this->mPageTitle = 'الفصول الدراسية';
        $this->render_crud();
    }

    public function levels()
    {
        // Setup crud
        $crud = $this->generate_crud('levels','المستويات الدراسية');
        $crud->columns('title');
        $crud->display_as('title','العنوان');

        if(!$this->ion_auth->in_group(array('webmaster')))
            $crud->unset_delete()->unset_edit()->unset_add();

        $this->mPageTitle = 'المستويات الدراسية';
        $this->render_crud();
    }

    public function grades()
    {
        // Setup crud
        $crud = $this->generate_crud('grades','قائمة الدرجات');
        $crud->columns('title','description');
        $crud->display_as('title','العنوان')
            ->display_as('description','الوصف');

        $crud->unset_delete()->unset_add();
        if($crud->getState() == 'edit') {
            $crud->change_field_type('title', 'readonly');
        }

        $this->mPageTitle = 'قائمة الدرجات';
        $this->render_crud();
    }
}