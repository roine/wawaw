<h2 class="grid_12">Dashboard</h2>
<div class="clean"></div>
<div class='grid_12'>
	<div id="stats_container">
	<?php if(Sentry::user()->has_access('ajax_dashboard')): ?>
	<?php $lang = array("en" => "English", "cn" => "Chinese", "ru" => "Russian", "tw" => "Taiwanese"); ?>
	<?php foreach($lang as $k => $v) : ?>
		<div class="grid_6 <?php echo $k; ?> <?php echo ($k == 'en' || $k == 'cn') ? 'front' : 'back';?> face">
			<div class="box effect1" id='<?php echo $k; ?>_stat'>
				<div class="header">
					<img src="/assets/img/icons/16x16/list.png" alt="" width="16" height="16">
					<h3>Stats List for <?php  echo $v; ?></h3>
					<span></span>
				</div>
				<div class="content">
					<div class="alert info no-margin top">Today x people registered.
						<span class="icon"></span>
					</div>
					<ul class="stats-list">
						<li>
							<a>Platforms <div><span>Today</span><span>Week</span><span>Month</span></div></a>
						</li>
						<?php foreach($tables as $table) : ?>
						<?php if((Sentry::user()->has_access($table['table'].'_read')  || Sentry::user()->has_access('all_tables_read')) && $table['table'] != 'all'): ?>
						<li>
							<a href="customers/<?php echo $table['url']; ?>" id='<?php echo $table['table']; ?>'><?php echo $table['CleanName']; ?> <div><span><img src ="/assets/img/misc/ajax/loading1-1.gif" ></span><span><img src ="/assets/img/misc/ajax/loading1-1.gif" ></span><span><img src ="/assets/img/misc/ajax/loading1-1.gif" ></span></div></a>
						</li>
						<?php endif; ?>
						<?php endforeach; ?>
					</ul>
				</div> <!-- End of .content -->
				<div class="actions">
					<div class="actions-left"></div>
					<div class="actions-right">
						<a class="button" href="statistics.php">Go to stats &raquo;</a>
					</div>
				</div> <!-- End of .actions -->
				<div class="clear"></div>
			</div> <!-- End of .box -->
		</div> <!-- End of .grid_6 -->
		<?php endforeach; ?>
		<?php endif; ?>
	</div> <!-- End of #stats_container -->
</div>
<!-- end grid_12 -->
<div class="clear"></div>







