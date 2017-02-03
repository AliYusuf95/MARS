<?php
defined('BASEPATH') OR exit('No direct script access allowed');
echo $form->messages();
?>

<div class="row">

	<div class="col-md-6">
		<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title">Reset Password for user: </h3>
			</div>
			<div class="box-body">
				<?php echo $form->open(); ?>
					<table class="table table-bordered">
						<tr>
							<th style="width:120px">Username: </th>
							<td><?php echo $target->username; ?></td>
						</tr>
						<tr>
							<th>Name: </th>
							<td><?php echo $target->name; ?></td>
						</tr>
						<tr>
							<th>Email: </th>
							<td><?php echo $target->email; ?></td>
						</tr>
					</table>
					<?php echo $form->bs3_password('New Password', 'new_password'); ?>
					<?php echo $form->bs3_password('Retype Password', 'retype_password'); ?>
					<?php echo $form->bs3_submit(); ?>
				<?php echo $form->close(); ?>
			</div>
		</div>
	</div>
	
</div>