<?php echo $form->messages(); ?>

<div class="row">

	<div class="col-md-6">
		<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title">معلومات العضو</h3>
			</div>
			<div class="box-body">
                <?php echo $form->open(); ?>
                <?php echo $form->bs3_text('مسمى تسجيل الدخول <code>*</code>', 'username',null,array('placeholder'=>'example@example.com')); ?>
                <span class="help-block">يفضل أن يكون مسمى تسجيل الدخول هو البريد الإلكتروني.</span>
                <?php echo $form->bs3_text('البريد الإلكتروني', 'email',null,array('placeholder'=>'example@example.com')); ?>
                <?php echo $form->bs3_text('الإسم <code>*</code>', 'name'); ?>
                <?php echo $form->bs3_text('رقم الهاتف', 'mobile'); ?>
                <?php echo $form->bs3_text('الرقم الشخصي <code>*</code>', 'cpr'); ?>
                <?php echo $form->bs3_password('كلمة المرور <code>*</code>', 'password'); ?>
                <?php echo $form->bs3_password('تأكيد كلمة المرور <code>*</code>', 'retype_password'); ?>
                <?php if ( !empty($groups) ): ?>
                <div class="form-group">
                    <label for="groups">المجموعات <code>*</code></label>
                    <div>
                    <?php foreach ($groups as $group): ?>
                        <label class="checkbox-inline">
                            <input type="radio" name="groups[]" value="<?php echo $group->id; ?>"> <?php echo $group->description; ?>
                        </label>
                        <br/>
                    <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php echo $form->bs3_submit('إضافة'); ?>
                <div class="form-group" data-name="">
                    <hr/>
                    <p class="col-sm-12"><code>*</code> خانة مطلوبة.</p>
                </div>
                <?php echo $form->close(); ?>
			</div>
		</div>
	</div>
	
</div>