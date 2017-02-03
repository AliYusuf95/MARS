<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    *[role="form"] {
        max-width: 530px;
        padding: 15px;
        margin: 0 auto;
        background-color: #fff;
        border-radius: 0.3em;
    }

    *[role="form"] h2 {
        margin-bottom: 1em;
    }
</style>
<div class="container">
    <?php /** @var Form $form */
    echo $form->open(); ?>
    <h2>إستمارة التسجيل</h2>
    <?php
    echo $form->messages();
    ?>
    <div class="form-group" data-name="">
        <label for="fullName" class="col-sm-3 control-label">الاسم الكامل <code>*</code></label>
        <div class="col-sm-9">
            <input type="text" name="name" id="name" value="" placeholder="الاسم الكامل" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label for="cpr" class="col-sm-3 control-label">الرقم الشخصي <code>*</code></label>
        <div class="col-sm-9">
            <input type="tel" name="cpr" id="cpr" value="" placeholder="الرقم الشخصي" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label for="mobile" class="col-sm-3 control-label">رقم الهاتف <code>*</code></label>
        <div class="col-sm-9">
            <input type="tel" name="mobile" id="mobile" value="" placeholder="رقم الهاتف" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label for="email" class="col-sm-3 control-label">البريد الإلكتروني</label>
        <div class="col-sm-9">
            <input type="email" name="email" id="email" value="" placeholder="البريد الإلكتروني" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label for="class" class="col-sm-3 control-label">المرحلة الدراسية <code>*</code></label>
        <div class="col-sm-9">
            <?php /** @var array $levels */
            echo form_dropdown('ed_level', $levels, array(),'id="level" class="form-control" required'); ?>
        </div>
    </div><!-- /.form-group -->
    <div class="form-group">
        <div class="col-sm-9 col-sm-offset-3">
            <button type="submit" class="btn btn-primary btn-block">إرسال طلب التسجيل</button>
        </div>
    </div>
    <div class="form-group" data-name="">
        <hr/>
        <p class="col-sm-12"><code>*</code> خانة مطلوبة.</p>
    </div>
    <?php echo $form->close(); ?>
    <script type="text/javascript">

        jQuery.validator.addMethod("notValue", function(value, element, params) {
            return this.optional(element) || value != params;
        }, "Please select correct value.");

        jQuery.validator.addMethod("isNumber", function(value, element) {
            return this.optional(element) || /^([0-9]|[\u0660-\u0669])+$/.test(value);
        }, "Please select correct value.");

        jQuery.validator.addMethod("isText", function(value, element) {
            return this.optional(element) || /^([\u0620-\u064a ])+$/.test(value);
        }, "Please select correct value.");

        $("#registration_form").validate({
            rules: {
                name: {
                    required: true,
                    isText:true,
                    minlength:5
                },
                cpr:{
                    required: true,
                    isNumber:true,
                    minlength:9,
                    maxlength:9
                },
                mobile: {
                    required: true,
                    isNumber: true,
                    minlength:8,
                    maxlength:8
                },
                email: {email: true},
                ed_level: {required: true, notValue:0}
            },
            messages: {
                fullName: {
                    required: "أدخل الإسم الثلاثي",
                    isText: "أدخل الإسم الثلاثي",
                    minlength: "أدخل الإسم الثلاثي"
                },
                cpr:{
                    required: "أدخل الرقم الشخصي",
                    isNumber:"الرقم الشخصي مكون من 9 أرقام فقط",
                    minlength:"الرقم الشخصي مكون من 9 أرقام فقط",
                    maxlength:"الرقم الشخصي مكون من 9 أرقام فقط"
                },
                mobile: {
                    required: "أدخل رقم الهاتف",
                    isNumber:"رقم الهاتف مكون من 8 أرقام",
                    minlength:"رقم الهاتف مكون من 8 أرقام",
                    maxlength:"رقم الهاتف مكون من 8 أرقام"
                },
                email: {email: "أدخل بريد إلكتروني صحيح"},
                level: {
                    required: "أختر المستوى الدراسي",
                    notValue:"أختر المستوى الدراسي"
                }
            },
            errorClass: "has-error",
            validClass: "has-success",
            focusInvalid: false,
            highlight: function(element, errorClass, validClass) {
                $(element).closest(".form-group").addClass(errorClass).removeClass(validClass);
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).closest(".form-group").removeClass(errorClass).addClass(validClass);
            },
            errorPlacement: function(error, element) {}, // remove labels
            invalidHandler: function(event, validator) {
                // 'this' refers to the form
                var errorsCount = validator.numberOfInvalids();
                if (errorsCount) {

                    $.each(validator.invalid,function (i,e){
                        console.log(e); // error messages
                    });
                    swal('هناك خطأ','تأكد من إدخال البيانات بشكل صحيح','error');
                }
            }
        });
    </script>
</div> <!-- ./container -->