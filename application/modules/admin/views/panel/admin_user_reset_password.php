<?php echo $form->messages(); ?>

<div class="row">

	<div class="col-md-6">
		<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title">إعادة كلمة المرور: </h3>
			</div>
			<div class="box-body">
				<?php echo $form->open(); ?>
					<table class="table table-bordered">
						<tr>
							<th style="width:120px">مسمى تسجيل الدخول: </th>
							<td><?php echo $target->username; ?></td>
						</tr>
						<tr>
							<th>الإسم: </th>
							<td><?php echo $target->name; ?></td>
						</tr>
						<tr>
							<th>الهاتف: </th>
							<td><?php echo $target->mobile; ?></td>
						</tr>
						<tr>
							<th>البريد الإلكتروني: </th>
							<td><?php echo $target->email; ?></td>
						</tr>
					</table>
					<?php echo $form->bs3_password('كلمة المرور', 'new_password'); ?>
					<?php echo $form->bs3_password('إعادة كلمة المرور', 'retype_password'); ?>
					<?php echo $form->bs3_submit('حفظ'); ?>
				<?php echo $form->close(); ?>
			</div>
		</div>
	</div>
	
</div>