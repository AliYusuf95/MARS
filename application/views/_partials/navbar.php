<button type="button" class="navbar-toggle offcanvas-toggle" data-toggle="offcanvas" data-target="#js-bootstrap-offcanvas">
	<span class="sr-only">Toggle navigation</span>
	<span class="icon-bar"></span>
	<span class="icon-bar"></span>
	<span class="icon-bar"></span>
</button>
<nav class="navbar navbar-default navbar-fixed-top navbar-offcanvas navbar-offcanvas-right navbar-offcanvas-touch" role="navigation" id="js-bootstrap-offcanvas">
<div class="container">

	<div class="navbar-header">
		<a class="navbar-brand" href=""><?php echo $site_name; ?></a>
	</div>

	<div>

		<ul class="nav navbar-nav navbar">
			<?php foreach ($menu as $parent => $parent_params): ?>

				<?php if (empty($parent_params['children'])): ?>

					<?php $active = ($current_uri==$parent_params['url'] || $ctrler==$parent); ?>
					<li <?php if ($active) echo 'class="active"'; ?>>
						<a href='<?php echo $parent_params['url']; ?>'>
							<?php echo $parent_params['name']; ?>
						</a>
					</li>

				<?php else: ?>

					<?php $parent_active = ($ctrler==$parent); ?>
					<li class='dropdown <?php if ($parent_active) echo 'active'; ?>'>
						<a data-toggle='dropdown' class='dropdown-toggle' href='#'>
							<?php echo $parent_params['name']; ?> <span class='caret'></span>
						</a>
						<ul role='menu' class='dropdown-menu'>
							<?php foreach ($parent_params['children'] as $name => $url): ?>
								<li><a href='<?php echo $url; ?>'><?php echo $name; ?></a></li>
							<?php endforeach; ?>
						</ul>
					</li>

				<?php endif; ?>

			<?php endforeach; ?>
		</ul>

		<?php $this->load->view('_partials/language_switcher'); ?>
        <!--ul class="nav navbar-nav navbar-left">
            <a href="admin" class="btn btn-block btn-primary navbar-btn">تسجيل دخول</a>
            <a href="admin" class="btn btn-block btn-warning navbar-btn">الكادر التعليمي</a>
        </ul-->
        <div class="nav navbar-nav navbar-left">
            <?php if ( !$this->ion_auth->logged_in() ): ?>
            <a href="login" class="btn btn-primary navbar-btn visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">لوحة التحكم</a>
            <a href="admin" class="btn btn-warning navbar-btn visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">الكادر التعليمي</a>
            <?php else: ?>
            <div class="dropdown">
                <a href="#" class="btn btn-default navbar-btn visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <?php echo $user->name; ?> <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" style="right: auto;left: 0;" aria-labelledby="dropdownMenu1">
                    <?php if(isset($member_menu))
                    foreach ($member_menu as $parent => $parent_params): ?>
                        <?php $active = ($ctrler==$parent_params['url']); ?>
                        <li <?php if ($active) echo 'class="active"'; ?>>
                            <a href='<?php echo $parent_params['url']; ?>'>
                                <?php echo $parent_params['name']; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    <li role="separator" class="divider"></li>
                    <li><a href="member/logout">تسجيل خروج</a></li>
                </ul>
            </div>
            <?php endif; ?>
        </div>

        <ul class="nav navbar-nav navbar-left">

        </ul>

	</div>

</div>
</nav>