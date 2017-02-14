<?php echo $form1->messages(); ?>

<div class="row">
	<div class="col-md-6">
		<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title">معلومات الحساب:</h3>
			</div>
			<div class="box-body">
				<?php echo $form1->open(); ?>
					<?php echo $form1->bs3_text('الإسم', 'name', $user->name); ?>
					<?php echo $form1->bs3_text('الهاتف', 'mobile', $user->mobile); ?>
					<?php echo $form1->bs3_text('البريد الإلكتروني', 'email', $user->email); ?>
					<?php echo $form1->bs3_submit('تحديث'); ?>
				<?php echo $form1->close(); ?>
			</div>
		</div>
	</div>
	
	<div class="col-md-6">
		<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title">تغيير كلمة المرور:</h3>
			</div>
			<div class="box-body">
				<?php echo $form2->open(); ?>
					<?php echo $form2->bs3_password('كلمة المرور الجديدة', 'new_password'); ?>
					<?php echo $form2->bs3_password('تأكيد كلمة المرور الجديدة', 'retype_password'); ?>
					<?php echo $form2->bs3_submit('تحديث'); ?>
				<?php echo $form2->close(); ?>
			</div>
		</div>
	</div>
</div>