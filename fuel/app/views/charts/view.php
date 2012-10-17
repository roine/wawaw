<?php echo render('regions/_header'); ?>
<div id="content-wrapper" class="container_12" role="main">

	<div id="main_content">

		<h2 class="grid_12">Daily Subscription</h2>
		<div class="clean"></div>
		<div class="grid_12">
			<div class="box">
				<div class="header">
					<img src="/assets/img/icons/16x16/graph.png" alt="" width="16" height="16">
					<h3>Charts</h3>
					<ul>
						<li><a href="#lines">Line</a></li>
						<li><a href="#area">Area</a></li>
						<li><a href="#bar">Bar</a></li>
					</ul>
				</div>
				<div class="content">
					<div class="graph medium tab-content" id="lines"></div>
					<div class="graph medium tab-content" id="area"></div>
					<div class="graph medium tab-content" id="bar"></div>
				</div>
			</div> <!-- End of .box -->
		</div>
	</div>
</div>
