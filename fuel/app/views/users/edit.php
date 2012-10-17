<?php echo render('regions/_header'); ?>
<div id="content-wrapper" class="container_12" role="main">
	<div id="main_content">
	<h2 class="grid_12">Edit <?php echo $user->username; ?>'s account</h2>
		<div class="clean"></div>
			<div class="grid_6" style='margin-left:25%'>
				<div class="box">
					<div class="header">
						<img width="16" height="16" alt="" src="/assets/img/icons/packs/fugue/16x16/ui-text-field-format.png">
						<h3>Edit</h3>
						<span></span>
					</div>
					<?php echo render('users/_editForm'); ?>
				</div> <!-- End of .box -->
			</div>
	</div>
</div>
