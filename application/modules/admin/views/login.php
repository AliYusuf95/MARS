<div class="login-box">

	<div class="login-logo"><b>
			<a href="<?php echo BASE_URL; ?>" class="logo"><b><?php echo $site_name; ?></b></a>
	</b></div>

	<div class="login-box-body">
		<p class="login-box-msg">سجل دخولك، لفتح لوحة التحكم</p>
		<?php echo $form->open(); ?>
			<?php echo $form->messages(); ?>
			<?php echo $form->bs3_text('إسم المستخدم', 'username', ENVIRONMENT==='development' ? 'webmaster' : ''); ?>
			<?php echo $form->bs3_password('كلمة المرور', 'password', ENVIRONMENT==='development' ? 'webmaster' : ''); ?>
			<div class="row">
				<div class="col-xs-8">
					<div class="checkbox">
						<label><input type="checkbox" name="remember"> تذكرني</label>
					</div>
				</div>
				<div class="col-xs-4">
					<?php echo $form->bs3_submit('تسجيل الدخول', 'btn btn-primary btn-block btn-flat'); ?>
				</div>
			</div>
		<?php echo $form->close(); ?>
	</div>

</div>