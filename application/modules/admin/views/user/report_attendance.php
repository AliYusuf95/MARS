<?php
/**
 * Created by PhpStorm.
 * User: Ali Yusuf
 * Date: 9/24/2016
 * Time: 9:06 PM
 */
?>
<style type="text/css">
    table.v-table, tr, td, th {
        position: relative;
    }
    table.v-table th span {
        transform-origin: 0 50%;
        transform: rotate(-90deg);
        white-space: nowrap;
        display: block;
        position: absolute;
        bottom: 0;
        left: 50%;
    }
    .v-header {
        width: 35px;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">حضور الطلاب</h3>

                <div class="box-tools pull-right">
                    <div class="btn-group">
                        <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-wrench"></i></button>
                        <ul class="dropdown-menu" role="menu">
                            <li><a class="print">Print</a></li>
                            <!--<li class="divider"></li>
                            <li><a href="#">Separated link</a></li>-->
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <style>
                                @media print {
                                    @page {size: landscape}
                                    body {mso-print-color: yes; -webkit-print-color-adjust: exact;}
                                    .table-responsive{direction: rtl;}
                                    .text-center{text-align: center;}
                                    table.v-table td {padding: 8px;}
                                    table.v-table {width: 100%; border-collapse: collapse;}
                                    table.v-table tr th{background: #ddd !important;}
                                    table.v-table tr:nth-child(even){background: #eee !important;}
                                    table.v-table, tr, td, th {position: relative;border: 1px solid;}
                                    table.v-table th span {
                                        transform-origin: 0 50%;
                                        transform: rotate(-90deg);
                                        white-space: nowrap;
                                        display: block;
                                        position: absolute;
                                        bottom: 0;
                                        left: 50%;
                                    }
                                    .v-header {width: 35px;}
                                    .rotate {padding: 8px;}
                                }
                            </style>
                            <table class="table table-bordered table-striped v-table">
                                <thead>
                                    <tr>
                                        <th class="col-xs-1" rowspan="2">#</th>
                                        <th class="rotate" rowspan="2">الإسم</th>
                                        <th class="rotate text-center" colspan="<?php echo count($data['dates']) ?>">التاريخ</th>
                                        <th class="rotate" rowspan="2">ملاحظات</th>
                                    </tr>
                                    <tr>
                                        <?php foreach ($data['dates'] as $date): ?>
                                        <th class="v-header"><span><?php echo $date; ?></span></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $i = 1; foreach ($data['students'] as $student): ?>
                                    <tr>
                                        <td class="text-center"><?php echo $i; ?></td>
                                        <td><?php echo $student['name']; ?></td>
                                        <?php foreach ($student['attendances'] as $attendance): ?>
                                            <td class="text-center"><?php echo $attendance == 1 ? '✔' : '-'; ?></td>
                                        <?php endforeach; ?>
                                        <!--<td class="text-center">-</td>
                                        <td class="text-center">✔</td>-->
                                        <td></td>
                                    </tr>
                                <?php $i++; endforeach; ?>
                                </tbody>
                                <tfoot>
                                <!--<tr>
                                    <th>مجموع الحضور</th>
                                    <th id="count">0</th>
                                </tr>-->
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--/.box-body-->
        </div>
        <!-- /.box -->
    </div>
</div>
<script>
    $(function() {
        var header_height = 0;
        $('table.v-table th span').each(function() {
            if ($(this).outerWidth() > header_height) header_height = $(this).outerWidth();
        });

        $('table.v-table th.v-header').height(header_height);

        $('.print').click(function(e){
            $(".table-responsive").printElement();
        });
    });
</script>