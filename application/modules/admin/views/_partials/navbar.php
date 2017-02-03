<header class="main-header">
	<a href="<?php echo BASE_URL; ?>" class="logo">
		<!-- mini logo for sidebar mini 50x50 pixels -->
		<div class="logo-mini">
			<div style="width: 46px; height: 46px; background: url('<?php echo BASE_URL; ?>/assets/dist/images/logo.png') no-repeat right 5px 5px;background-size: 40px 40px; filter: brightness(10) ;-webkit-filter: brightness(10);"></div>
		</div>
		<!-- logo for regular state and mobile devices -->
		<span class="logo-lg"><b><?php echo $site_name; ?></b></span>
	</a>
	<nav class="navbar navbar-static-top" role="navigation">
		<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</a>
		<div class="navbar-custom-menu">
			<ul class="nav navbar-nav">
				<li class="dropdown user user-menu">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<span class="hidden-xs"><?php echo $user->name; ?></span>
					</a>
					<ul class="dropdown-menu">
						<li class="user-header">
							<p><?php echo $user->name; ?></p>
						</li>
						<li class="user-footer">
							<div class="pull-left">
								<a href="panel/account" class="btn btn-default btn-flat">Account</a>
							</div>
							<div class="pull-right">
								<a href="panel/logout" class="btn btn-default btn-flat">Sign out</a>
							</div>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</nav>
</header>