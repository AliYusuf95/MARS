<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/** @var Form $form */
echo $form->messages();
?>

<div class="row">

	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title">معلومات المستخدم: </h3>
			</div>
			<div class="box-body">
				<?php echo $form->open(); ?>
					<table class="table table-bordered">
                        <tr>
                            <th>الإسم: </th>
                            <td><?php echo $target->name; ?></td>
                        </tr>
                        <tr>
                            <th style="width:120px">مسمى تسجيل الدخول: </th>
                            <td><?php echo $target->username; ?></td>
                        </tr>
						<tr>
							<th style="width:120px">المسمى: </th>
							<td><?php echo $target->group; ?></td>
						</tr>
                        <tr>
                            <th>رقم الهاتف: </th>
                            <td><?php echo $target->mobile; ?></td>
                        </tr>
						<tr>
							<th>البريد الإلكتروني: </th>
							<td><?php echo $target->email; ?></td>
						</tr>
					</table>
                <br/>
					<?php echo $form->bs3_password('كلمة المرور الجديدة', 'new_password','',array('class'=>'col-md-3')); ?>
                <br/><br/>
					<?php echo $form->bs3_password('تأكيد كلمة المرور الجديدة', 'retype_password','',array('class'=>'col-md-3')); ?>
                <br/><br/>
					<?php echo $form->bs3_submit('حفظ'); ?>
				<?php echo $form->close(); ?>
			</div>
		</div>
	</div>
	
</div>