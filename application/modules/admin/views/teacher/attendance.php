<?php
/**
 * Created by PhpStorm.
 * User: Ali Yusuf
 * Date: 9/24/2016
 * Time: 9:06 PM
 */
?>

<?php if(count($users) < 1 && !$datePicker): ?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">عذراً .. لا تستطيع تسجيل الحضور</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-10">
                        <b>لا يوجد تسجيل حضور للمدرسين في هذا اليوم.</b>
                    </div>
                </div>
                <?php if ($datePicker): ?>
                    <br/>
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="datepicker" class="col-md-3 col-sm-5 col-xs-12 control-label">الرجاء إختيار تاريخ آخر</label>
                            <div id="cal-icon" class="input-group date col-lg-4 col-md-5 col-sm-7 col-xs-12">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input name="date" type="text" value="<?php echo $attendanceDate; ?>" class="form-control pull-right" id="datepicker">
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="box-footer">
                <h5><span>للمزيد من التفاصيل يرجى التواصل مع الهيئة التعليمية.</span></h5>
            </div>
        </div>
    </div>
</div>
<?php if ($datePicker): ?>
<script>
    $(document).ready(function() {
        //Date picker
        $('#datepicker').datepicker({
            autoclose: true,
            rtl: true,
            language: 'ar',
            format: 'yyyy-mm-dd',
            todayHighlight: true,
            //            startDate: <?php //echo "'$startDate'"; ?>//,
            //            endDate: <?php //echo "'$endDate'"; ?>//,
            //            daysOfWeekDisabled: <?php //echo "'$daysOfWeekDisabled'"; ?>//,
            //            daysOfWeekHighlighted: <?php //echo "'$daysOfWeekHighlighted'"; ?>//,
            disableTouchKeyboard: true
        });
        $('#cal-icon').on('click', function (e) {
            $('#datepicker').datepicker('show');
        }).on('changeDate', function (e) {
            //            var disableDays = [<?php //echo "$daysOfWeekHighlighted"; ?>//];
            //            if(disableDays.indexOf(e.date.getDay()) == -1)
            //                swal('ملاحظة','اليوم الذي اخترته غير مدرج ضمن أيام التعليم بحسب إعدادات المقرر.','warning');
            var dateString = e.date.getFullYear() + '-' + ('0' + (e.date.getMonth() + 1)).slice(-2) + '-' + ('0' + e.date.getDate()).slice(-2);
            console.log(dateString);
            $(location).attr('href', 'teacher/attendance/' + dateString);
        });
    });
</script>
<?php endif; ?>
<?php else: ?>
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
                <div class="row">
                    <div class="col-sm-10">
                        <?php if ($datePicker): ?>
                        <label for="datepicker" class="col-md-2 col-sm-3 col-xs-3 control-label">إختر التاريخ</label>
                        <div id="cal-icon" class="input-group date col-lg-4 col-md-5 col-sm-8 col-xs-9">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input name="date" type="text" value="<?php echo $attendanceDate; ?>" class="form-control pull-right" id="datepicker">
                        </div>
                        <!-- /.input group -->
                        <?php else: ?>
                            <label for="datepicker" class="col-md-2 col-sm-3 col-xs-3 control-label">تاريخ اليوم</label>
                            <div id="cal-icon" class="input-group date col-lg-4 col-md-5 col-sm-8 col-xs-9">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input name="date" type="text" value="<?php echo $attendanceDate; ?>" class="form-control pull-right" id="datepicker" readonly>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <br/>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="col-xs-5">الإسم</th>
                                <th class="col-xs-1">الفرقة</th>
                                <th class="col-xs-1">المادة</th>
                                <th class="col-xs-1">رقم الهاتف</th>
                                <th class="col-xs-1">الحضور</th>
                                <th class="col-xs-2">ملاحظات</th>
                            </tr>
                        </thead>
                        <tbody  id="students-table">
                        <?php $counter = 0 ; ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo ++$counter; ?></td>
                                <td><?php echo $user["name"]; ?></td>
                                <td><?php echo $user["sectionTitle"]; ?></td>
                                <td><?php echo $user["subjectTitle"]; ?></td>
                                <td><?php echo $user["mobile"]; ?></td>
                                <td><?php echo $form->field_checkbox("attendance[{$user["id"]}-{$user["sectionId"]}-{$user["subjectId"]}]", null, $user["status"]);
                                    echo $form->field_hidden("id[]",$user["id"]);
                                    echo $form->field_hidden("subject[]",$user["subjectId"]);
                                    echo $form->field_hidden("section[]",$user["sectionId"]); ?></td>
                                <td><?php echo $form->field_text('comment[]',$user["comment"]); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5">مجموع الحضور</th>
                                <th colspan="2" id="count">0</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
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
<?php if ($datePicker): ?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">إضافة مدرس</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="col-xs-6">المدرس</th>
                            <th class="col-xs-5">الفرقة</th>
                            <th class="col-xs-5">المادة</th>
                            <th class="col-xs-1">إضافة</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="form-group" style="margin-bottom:0;">
                                    <select id="select-teacher" class="form-control select2" style="width: 100%;">
                                        <?php foreach ($availableTeachers as $teacher): ?>
                                            <?php echo "<option data-mobile='{$teacher['mobile']}' value='{$teacher['id']}'>{$teacher['name']}</option>" ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group" style="margin-bottom:0;">
                                    <select id="select-section" class="form-control select2" style="width: 100%;">
                                        <?php foreach ($availableSections as $section): ?>
                                            <?php echo "<option value='{$section['id']}'>{$section['title']}</option>" ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group" style="margin-bottom:0;">
                                    <select id="select-subject" class="form-control select2" style="width: 100%;">
                                        <?php foreach ($availableSubjects as $subject): ?>
                                            <?php echo "<option value='{$subject['id']}'>{$subject['title']}</option>" ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <a id="add-teacher" class="btn btn-success btn-block" role="button"><i class="fa fa-plus"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <h4><span class="text-red">* ملاحظة: </span>إضغط على السجل المضاف لحذفه.</h4>
            </div>
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->
<?php endif; ?>
<script>
    $(document).ready(function(){

        $(window).keydown(function(event){
            if(event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });

        //Initialize Select2 Elements
        $(".select2").select2({
            language: "ar",
            dir: "rtl"
        });

        var $teacher = $("#select-teacher");
        var $section = $("#select-section");
        var $subject = $("#select-subject");
        var $table = $("#students-table");

        $("#add-teacher").on('click', function (evt) {
            var id = $teacher.children("option").filter(":selected").val();
            var secId = $section.children("option").filter(":selected").val();
            var subId = $subject.children("option").filter(":selected").val();
            if($("#attendance\\\["+id+"-"+secId+"-"+subId+"\\\]").length){
                swal('عذراً','هذا المدرس موجود بالفعل.','error');
            } else {
                var name = $teacher.children("option").filter(":selected").text();
                var section = $section.children("option").filter(":selected").text();
                var subject = $subject.children("option").filter(":selected").text();
                var mobile = $teacher.children("option").filter(":selected").data("mobile");
                var counter = parseInt($table.children("tr:last").children("td:first").text()) + 1;
                var markup = "<tr style='background: rgba(0, 166, 90, 0.53); color: #fff;'><td>" + counter + "</td>" +
                    "<td>" + name + "</td>" +
                    "<td>" + section + "</td>" +
                    "<td>" + subject + "</td>" +
                    "<td>" + mobile + "</td>" +
                    "<td><input type='checkbox' name='attendance["+ id + "-" + secId + "-" + subId +"]' id='attendance["+ id + "-" + secId + "-" + subId +"]'/>" +
                    "<input type='hidden' name='id[]' value='"+id+"' />" +
                    "<input type='hidden' name='section[]' value='"+secId+"' />" +
                    "<input type='hidden' name='subject[]' value='"+subId+"' />" +
                    "<td><input style='color: initial;' type='text' name='comment[]' value='' id='comment[]'/></td>" +
                    "</tr>";
                var $row = $table.append(markup);
                $row.find('tr:last').click(function (event) {
                    var $row = $(this);
                    swal({
                        title: 'تنويه',
                        text: 'هل أنت متأكد من حذف هذا السجل.',
                        type: 'info',
                        showCancelButton: true,
                        confirmButtonText: "نعم",
                        cancelButtonText: "لا"
                    }).then(function () {
                        $row.remove();
                    },function (e) {});
                });
                $row.find('input').click(function(e) {
                    e.stopPropagation();
                });
                $row.find('input[type="checkbox"]').iCheck({
                    checkboxClass: 'icheckbox_flat',
                    radioClass: 'iradio_flat'
                }).on('ifChanged', function (event) {
                    $('#count').text($('input[type="checkbox"]:checked').length);
                });
            }
        });

        //Enable iCheck plugin for checkboxes
        //iCheck for checkbox and radio inputs
        var $iCheck = $('form input[type="checkbox"]');
        $iCheck.iCheck({
            checkboxClass: 'icheckbox_flat',
            radioClass: 'iradio_flat'
        });

        $('#count').text($('input[type="checkbox"]:checked').length);

        $iCheck.on('ifChanged', function(event){
            $('#count').text($('input[type="checkbox"]:checked').length);
        });

        // add jQuery function for search
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
            todayHighlight: true,
//            startDate: <?php //echo "'$startDate'"; ?>//,
//            endDate: <?php //echo "'$endDate'"; ?>//,
//            daysOfWeekDisabled: <?php //echo "'$daysOfWeekDisabled'"; ?>//,
//            daysOfWeekHighlighted: <?php //echo "'$daysOfWeekHighlighted'"; ?>//,
            disableTouchKeyboard: true
        });
        $('#cal-icon').on('click', function(e){
            $('#datepicker').datepicker('show');
        }).on('changeDate', function(e) {
//            var disableDays = [<?php //echo "$daysOfWeekHighlighted"; ?>//];
//            if(disableDays.indexOf(e.date.getDay()) == -1)
//                swal('ملاحظة','اليوم الذي اخترته غير مدرج ضمن أيام التعليم بحسب إعدادات المقرر.','warning');
            var dateString = e.date.getFullYear()+'-'+('0' + (e.date.getMonth()+1)).slice(-2) + '-' + ('0' + e.date.getDate()).slice(-2);
            console.log(dateString);
            $(location).attr('href', 'teacher/attendance/'+dateString);
        });
        <?php endif; ?>
    });
</script>
<?php endif; ?>