<style>
    .top-box {
        z-index: 100;
        margin: 0 auto;
        padding-top: 90px;
    }
    .middle-box {
        max-width: 400px;
        z-index: 100;
        margin: 0 auto;
        padding-top: 40px;
        padding-bottom: 60px;
        width: 300px;
    }
    .logo-name {
        color: #0275d8;
        font-size: 80px;
        font-weight: 600;
        margin-bottom: 0px;
    }
    .gray-bg {
        color: #777;
    }
</style>
<div class="top-box text-center">

    <h1 class="logo-name"><?php echo $site_name; ?></h1>

</div>
<div class="middle-box text-center">
    <?php /** @var Form $form */
    echo $form->open(); ?>
        <?php echo $form->messages(); ?>
        <div class="form-group">
                <?php echo $form->bs3_text('اسم المستخدم', 'username', ENVIRONMENT==='development' ? '950908711' : ''); ?>
        </div>
        <div class="form-group">
            <?php echo $form->bs3_password('رقم المرور', 'password', ENVIRONMENT==='development' ? 'webmaster' : ''); ?>
        </div>
        <div class="form-group">
            <div class="checkbox">
                <label><input type="checkbox" name="remember"> تذكرني</label>
            </div>
        </div>
        <div class="form-group">
            <?php echo $form->bs3_submit('تسجيل دخول', 'btn btn-primary btn-block'); ?>
        </div>
    <?php echo $form->close(); ?>
</div>