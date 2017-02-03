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
                <h3 class="box-title">قائمة الصفوف</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <?php /** @var Form $form */
                echo $form->open(); ?>
                <?php echo $form->messages(); ?>
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th class="col-xs-10">الصفوف</th>
                    </tr>
                    </thead>
                    <tbody  id="students-table">
                    <?php $counter = 0 ; ?>
                    <?php foreach ($classes as $class): ?>
                        <tr>
                            <td><?php echo ++$counter; ?></td>
                            <td><a href="<?php echo current_url().'/'.$class["id"]; ?>" ><?php echo $class["title"]; ?></a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>مجموع الصفوف</th>
                        <th><?php echo count($classes) ?></th>
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