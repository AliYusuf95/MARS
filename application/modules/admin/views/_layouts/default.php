<style>
	@media (min-width: 768px) {
		.sidebar-mini.sidebar-collapse .content-wrapper,
		.sidebar-mini.sidebar-collapse .right-side,
		.sidebar-mini.sidebar-collapse .main-footer {
			margin-right: 50px !important;
		}
	}
	@media (max-width: 767px){
		.content-wrapper, .right-side, .main-footer {
			margin-right: 0;
		}
	}
	@media (min-width: 768px) {
		.sidebar-mini.sidebar-collapse .main-header .navbar {
			margin-right: 50px;
		}
	}
</style>
<div class="wrapper">

	<?php $this->load->view('_partials/navbar'); ?>

	<?php // Left side column. contains the logo and sidebar ?>
	<aside class="main-sidebar">
		<section class="sidebar">
			<div class="user-panel" style="height:65px">
				<div class="pull-left info" style="left:5px">
					<p><?php echo $user->name; ?></p>
					<a href="panel/account"><i class="fa fa-circle text-green"></i> متواجد</a>
				</div>
			</div>
			<?php // (Optional) Add Search box here ?>
			<?php //$this->load->view('_partials/sidemenu_search'); ?>
			<?php $this->load->view('_partials/sidemenu'); ?>
		</section>
	</aside>

	<?php // Right side column. Contains the navbar and content of the page ?>
	<div class="content-wrapper">
		<section class="content-header">
			<h1><?php echo $page_title; if (isset($page_title_small)) echo " <small>$page_title_small</small>" ?></h1>
			<?php $this->load->view('_partials/breadcrumb'); ?>
		</section>
		<section class="content">
			<?php $this->load->view($inner_view); ?>
			<?php $this->load->view('_partials/back_btn'); ?>
		</section>
	</div>

	<?php // Footer ?>
	<?php $this->load->view('_partials/footer'); ?>

</div>