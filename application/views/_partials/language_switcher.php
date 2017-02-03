<?php if ( !empty($available_languages) && count($available_languages) > 1): ?>
	<ul class="nav navbar-nav navbar-<?php if ($language != 'ar'){ echo 'right';} else {echo 'left';} ?>">
		<li><a onclick="return false;"><?php echo lang('current_language'); ?>: <?php echo $language; ?></a></li>
		<li class="dropdown">
			<a data-toggle='dropdown' class='dropdown-toggle' href='#'>
				<i class="fa fa-globe"></i>
				<span class='caret'></span>
			</a>
			<ul role='menu' class='dropdown-menu'>
				<?php foreach ($available_languages as $abbr => $item): ?>
				<li><a href="<?php echo lang_url($abbr); ?>"><?php echo $item['label']; ?></a></li>
				<?php endforeach; ?>
			</ul>
		</li>
	</ul>
<?php endif; ?>