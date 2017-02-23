<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Ali Yusuf
 * Date: 1/24/2017
 * Time: 7:10 PM
 */
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">قائمة الفرق</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <?php /** @var Form $form */
                echo $form->open(); ?>
                <?php echo $form->messages(); ?>
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                    <tr>
                        <th class="col-xs-2">#</th>
                        <th class="col-xs-5">الصف</th>
                        <th class="col-xs-5">المادة</th>
                    </tr>
                    </thead>
                    <tbody  id="students-table">
                    <?php $counter = 0 ; ?>
                    <?php foreach ($sections as $class):
                        $baseUrl .= '/'.$class["sectionId"].'/'.$class["subjectId"];
                        ?>
                        <tr style="cursor: pointer;" onclick="window.location.href='<?php echo $baseUrl; ?>';">
                            <td><?php echo ++$counter; ?></td>
                            <td><!--<a href="<?php /*echo $url; */?>" >--><?php echo $class["sectionTitle"]; ?><!--</a>--></td>
                            <td><!--<a href="<?php /*echo $url; */?>" >--><?php echo $class["subjectTitle"]; ?><!--</a>--></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>المجموع</th>
                        <th colspan="2"><?php echo count($sections) ?></th>
                    </tr>
                    </tfoot>
                </table>
                <?php echo $form->close(); ?>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->