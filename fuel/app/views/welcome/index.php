		
		<!-- Begin of #height-wrapper -->
		<div id="height-wrapper">
			<!-- Begin of header -->

			<?php echo render('regions/_header', $data); ?>

			<!-- Start of the content -->
			<div role="main" class="container_12" id="content-wrapper">
				<!-- Start of the sidebar -->
				<aside>
					<div id="sidebar_top">
						<div class="userinfo">
							<div class="info">
								<div class="avatar">
									<img src="/assets/img/sprites/userinfo/avatar.png" width="80" height="80" alt="">
								</div>
								<a href="#">0 Messages</a>
							</div>
							<ul class="links">
								<li>
									<strong class='capitalize'><?php echo Html::anchor('users/view/'.$current_user->id, e(ucwords($current_user->username))); ?></strong>
								</li>
								<li>
									<?php echo Html::anchor('settings', 'Settings'); ?>
								</li>
								<li>
									<?php echo Html::anchor('admin/logout', 'Logout'); ?>
								</li>
							</ul>
							<div class="clear"></div>
						</div>
					</div>
					<div id="sidebar_content">
						<h2>Information</h2>
						Time in China: <?php echo \Date::time('Asia/Shanghai')->format("<div class='time running local'>%a, %d %b %Y <span>%H</span><span>%M</span><span>%S</span></div>"); ?><br />
						Time in USA: <?php echo Date::time('America/Mexico_City')->format("<div class='time running us'>%a, %d %b %Y <span>%H</span><span>%M</span><span>%S</span></div>"); ?><br />
					</div>
				</aside><!-- End of the sidebar-->
				
				<!-- Start of the main content -->
				<div id="main_content">
				
					<!-- <h2 class="grid_12">Dashboard</h2> -->
					<div class="clean"></div>
					<div id="stats_container" class='f1_container'>
						<div class='f1_card'>
				<?php if(Sentry::user()->has_access('ajax_dashboard')): ?>
				<?php $lang = array("en" => "English", "cn" => "Chinese", "ru" => "Russian", "tw" => "Taiwanese"); ?>
				<?php foreach($lang as $k => $v) : ?>
					<div class="grid_6 <?php echo $k; ?> <?php echo ($k == 'en' || $k == 'cn') ? 'front' : 'back';?> face">
						<div class="box " id='<?php echo $k; ?>_stat'>
							<div class="header">
								<img src="/assets/img/icons/16x16/list.png" alt="" width="16"
								height="16">
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
			</div>
			</div> <!-- End of #stats_container -->
				</div> <!-- End of #main_content -->
				<div class="push clear"></div>
					
			</div> <!-- End of #content-wrapper -->
			<div class="clear"></div>
			<div class="push"></div> <!-- BUGFIX if problems with sidebar in Chrome -->
				
		</div> <!-- End of #height-wrapper -->
	


		


