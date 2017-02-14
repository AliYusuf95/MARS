<?php echo $form->messages(); ?>

<div class="row">

	<div class="col-md-6">
		<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title">معلومات العضو</h3>
			</div>
			<div class="box-body">
				<?php echo $form->open(); ?>

					<?php echo $form->bs3_text('مسمى تسجيل الدخول', 'username'); ?>
					<?php echo $form->bs3_text('الإسم', 'name'); ?>
					<?php echo $form->bs3_text('رقم الهاتف', 'mobile'); ?>
					<?php echo $form->bs3_text('البريد الإلكتروني', 'email'); ?>
					<?php echo $form->bs3_password('كلمة المرور', 'password'); ?>
					<?php echo $form->bs3_password('تأكيد كلمة المرور', 'retype_password'); ?>

					<?php if ( !empty($groups) ): ?>
					<div class="form-group">
						<label for="groups">المجموعات</label>
						<div>
						<?php foreach ($groups as $group): ?>
							<label class="checkbox-inline">
								<input type="checkbox" name="groups[]" value="<?php echo $group->id; ?>"> <?php echo $group->description; ?>
							</label>
                            <br/>
						<?php endforeach; ?>
						</div>
					</div>
					<?php endif; ?>

					<?php echo $form->bs3_submit('إضافة'); ?>
					
				<?php echo $form->close(); ?>
			</div>
		</div>
	</div>
	
</div>