<?php
/**
 * Created by PhpStorm.
 * User: Ali Yusuf
 * Date: 9/24/2016
 * Time: 9:06 PM
 */
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">قائمة الأسماء</h3>
                <div class="box-tools pull-right">
                    <div class="has-feedback">
                        <input id="searchInput" type="search" class="form-control input-sm" placeholder="بحث">
                        <span class="glyphicon glyphicon-search form-control-feedback"></span>
                    </div>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <?php /** @var Form $form */
                echo $form->open(); ?>
                <?php echo $form->messages(); ?>
                <?php if(!$datePicker): ?>
                <div class="row">
                    <div class="form-group">
                        <label for="date" class="col-sm-2 control-label">التاريخ</label>
                        <div class="col-sm-10">
                            <?php echo $form->field_dropdown("date",$dates,array(),array("id"=>"date","class"=>"form-control col-sm-3")); ?>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="row">
                    <div class="col-sm-10">
                        <label for="datepicker" class="col-md-2 col-sm-3 col-xs-3 control-label">إختر التاريخ</label>
                        <div id="cal-icon" class="input-group date col-lg-4 col-md-5 col-sm-8 col-xs-9">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input name="date" type="text" class="form-control pull-right" id="datepicker">
                        </div>
                        <!-- /.input group -->
                    </div>
                </div>
                <?php endif; ?>
                <br/>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="col-xs-8">الإسم</th>
                                <th class="col-xs-2">رقم الهاتف</th>
                                <th>الحضور</th>
                            </tr>
                        </thead>
                        <tbody  id="students-table">
                        <?php $counter = 0 ; ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo ++$counter; ?></td>
                                <td><?php echo $user["name"]; ?></td>
                                <td><?php echo $user["mobile"]; ?></td>
                                <td><?php echo $form->field_checkbox("attendance[".$user["id"]."]", '', $user["status"]);
                                    echo $form->field_hidden("id[]",$user["id"]); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3">مجموع الحضور</th>
                                <th id="count">0</th>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="form-group">
                        <?php echo $form->bs3_submit('<i class="fa fa-save"></i> حفظ'); ?>
                    </div>
                <?php echo $form->close(); ?>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->
<script>
    $(document).ready(function(){
        //Enable iCheck plugin for checkboxes
        //iCheck for checkbox and radio inputs
        $('form input[type="checkbox"]').iCheck({
            checkboxClass: 'icheckbox_flat-grey',
            radioClass: 'iradio_flat-grey'
        });

        $('#count').text($('input[type="checkbox"]:checked').length);

        $('form input[type="checkbox"]').on('ifChanged', function(event){
            console.log();
            $('#count').text($('input[type="checkbox"]:checked').length);
        });

        $.extend($.expr[':'], {
            'containsi': function(elem, i, match, array)
            {
                return (elem.textContent || elem.innerText || '').toLowerCase()
                        .indexOf((match[3] || "").toLowerCase()) >= 0;
            }
        });

        $("#searchInput").keyup(function () {
            //split the current value of searchInput
            var data = this.value.trim().replace("أ", "ا").replace("ة", "ه");
            //create a jquery object of the rows
            var jo = $("#students-table").find("tr");
            if (this.value == "") {
                jo.show();
                return;
            }
            //hide all the rows
            jo.hide();
            //Recusively filter the jquery object to get results.
            jo.filter(function (i, v) {
                var $t = $(this).clone();
                $t.html($t.html().replace(/أ/g, "ا").replace(/ة/g, "ه"));
                return !!$t.is(":containsi('" + data + "')");
            }).show(); //show the rows that match.
        });
        <?php if ($datePicker): ?>
        //Date picker
        $('#datepicker').datepicker({
            autoclose: true,
            rtl: true,
            language: 'ar',
            format: 'yyyy-mm-dd',
            startDate: <?php echo "'$startDate'"; ?>,
            endDate: <?php echo "'$endDate'"; ?>,
            daysOfWeekDisabled: <?php echo "'$daysOfWeekDisabled'"; ?>,
            daysOfWeekHighlighted: <?php echo "'$daysOfWeekHighlighted'"; ?>,
            disableTouchKeyboard: true
        });
        $('#cal-icon').on('click', function(e){
            $('#datepicker').datepicker('show');
        }).on('changeDate', function(e) {
            var disableDays = [<?php echo "$daysOfWeekHighlighted"; ?>];
            if(disableDays.indexOf(e.date.getDay()) == -1)
                swal('ملاحظة','اليوم الذي اخترته غير مدرج ضمن أيام التعليم بحسب إعدادات المقرر.','warning');
        });
        <?php endif; ?>
    });
</script>