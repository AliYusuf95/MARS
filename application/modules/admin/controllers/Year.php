<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Year extends Admin_Controller {
    // TODO: add rules

    public function __construct()
    {
        parent::__construct();
        // Set Page small title
        $this->mPageTitleSmall = 'إعدادات السنة الدراسية';
    }

    public function classes()
    {
        // Setup crud
        $crud = $this->generate_crud('classes','صفوف دراسية');
        $crud->columns('title', 'level_id');
        $crud->display_as('title','إسم الصف')
            ->display_as('level_id','المستوى الدراسي');
        $crud->set_relation('level_id','levels','title',null,'id');
        $crud->set_rules('level_id', 'المستوى الدراسي','trim|required|is_unique[classes.level_id]');
        $crud->set_rules('title', 'إسم الصف','trim|required');

        $this->mPageTitle = 'الصفوف';
        $this->render_crud();
    }

    public function sections()
    {
        // Setup crud
        $crud = $this->generate_crud('sections','الأقسام الدراسية');
        $crud->columns('title', 'class_id');
        $crud->display_as('title','إسم الفرقة')
            ->display_as('class_id','الصف الدراسي');

        $crud->set_relation('class_id','classes','title',null,'id');
        $crud->set_rules('title', 'إسم الفرقة','trim|required');
        $crud->set_rules('class_id', 'الصف الدراسي','trim|required');

        $this->mPageTitle = 'الأقسام';
        $this->render_crud();
    }

    public function subjects()
    {
        // Setup crud
        $crud = $this->generate_crud('subjects','المواد الدراسية');
        $crud->columns('title','semester_id','dates','start_time','end_time');
        $crud->display_as('title','إسم المادة')
            ->display_as('semester_id','الفصل الدراسي')
            ->display_as('dates','الأيام')
            ->display_as('start_time','وقت البدء')
            ->display_as('end_time','وقت الإنتهاء');

        $crud->set_relation('semester_id','semesters','title',null,'id');

        $crud->field_type('dates','multiselect',
            array(
                'Sun' => 'الأحد',
                'Mon' => 'الإثنين',
                'Tue' => 'الثلثاء',
                'Wed' => 'الأربعاء',
                'Thu' => 'الخميس',
                'Fri' => 'الجمعة',
                'Sat' => 'السبت')
        );
        // 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'
        //$crud->required_fields('dates');
        //$crud->set_rules('dates[]', 'الأيام','required|in_list[S,U,M,T,W,H,F]');

        //set start & finish to time type
        $crud->field_type('start_time','time');
        $crud->field_type('end_time','time');

        //set time format
        $crud->callback_column('start_time',array($this,'format_time'));
        $crud->callback_column('end_time',array($this,'format_time'));

        //Fix insert/update value
        $crud->callback_before_insert(array($this,'fix_time'));
        $crud->callback_before_update(array($this,'fix_time'));

        $this->mPageTitle = 'المواد';
        $this->render_crud();
    }

    function fix_time ($fields)
    {
        $fields['start_time'] = date("H:i", strtotime($fields['start_time']));
        $fields['end_time'] = date("H:i", strtotime($fields['end_time']));
        return $fields;
    }

    function format_time ($value, $row)
    {
        return date("h:i A", strtotime($value));
    }

    public function grades()
    {
        // Setup crud
        $crud = $this->generate_crud('terms_grades','توزيع درجات');
        $crud->columns('title','subject_id','grade_id','max');
        $crud->display_as('title','العنوان')
            ->display_as('subject_id','المادة')
            ->display_as('grade_id','نوع الدرجة')
            ->display_as('max','نسبة الدرجة');

        $crud->set_relation('subject_id','subjects','title',null,'id');
        $crud->set_relation('grade_id','grades','title',null,'id');

        $crud->set_rules('max', 'نسبة الدرجة','trim|required|numeric');

        $this->mPageTitle = 'توزيع درجات المواد';
        $this->render_crud();
    }

    public function class_subject()
    {
        // Setup crud
        $crud = $this->generate_crud('classes_subjects','مواد الصفوف');
        $crud->columns('class_id','subject_id');
        $crud->display_as('class_id','الصف')
            ->display_as('subject_id','المادة');

        $crud->set_relation('class_id','classes','title',null,'id');
        $crud->set_relation('subject_id','subjects','title',null,'id');

        $crud->required_fields('class_id','subject_id');

        $crud->set_rules('class_id', 'الصف', 'compare_pk[classes_subjects.class_id.subject_id]');


        $this->mPageTitle = 'مواد الصفوف';
        $this->render_crud();
    }

}