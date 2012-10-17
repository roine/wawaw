<header>
				<!-- Begin of the header toolbar -->
				<div id="header_toolbar">
					<div class="container_12">
						<h1 class="grid_8"><?php echo $site_title; ?></h1>
						<!-- Start of right part -->
						<div class="grid_4">
							<!-- A small toolbar button -->
							<div class="toolbar_small">
								<div class="toolbutton">
									<span>3</span>
									<img src="/assets/img/icons/16x16/mail.png" width="16" height="16" alt="mail" >
								</div>
								<div class="toolbox" >
									<span class="arrow"></span>
									<h3>Your Messages</h3>
									<ul class="mail">
										<li>
											<a href="#"> <strong>10:15</strong>Need your Help!
											<small>
												From: John
											</small> </a>
										</li>
										<li>
											<a href="#"> <strong>9:55</strong>New comment on you theme
											<small>
												From: themeforest
											</small></a>
										</li>
										<li>
											<a href="#"> <strong>Yest.</strong>Successfull backup
											<small>
												From: System
											</small></a>
										</li>
										<li class="read">
											<a href="#"> <strong>2 days</strong>Bug report
											<small>
												From: Jane
											</small></a>
										</li>
									</ul>
									<a class="inbox" href="#">Go to inbox &raquo;</a>
								</div>
							</div> <!-- End of small toolbar button -->
							
							<!-- A large toolbar button -->
							<div class="toolbar_large">
								<div class="toolbutton">
									<div class="toolicon">
										<img src="/assets/img/icons/16x16/user.png" width="16" height="16" alt="user" >
									</div>
									<div class="toolmenu">
										<div class="toolcaption">
											<span><?php echo e(ucwords($current_user['username'])); ?></span>
										</div>
										<!-- Start of menu -->
										<div class="dropdown">
											<ul>
												<li>
													<?php echo Html::anchor('settings', 'Settings'); ?>
												</li>
												<li>
													<?php echo Html::anchor('admin/logout', 'Logout'); ?>
												</li>
											</ul>
										</div> <!-- End of menu -->
									</div>
								</div>
							</div> <!-- End of large toolbar button -->
						</div>
						<!-- End of right part -->
					</div>
				</div>
				<!-- End of the header toolbar -->
				
				<!-- Start of the main header bar -->
				<nav id="header_main">
					<div class="container_12">
						<!-- Start of the main navigation -->
						<ul id="nav_main">
							<li class='dashboard'>
								<a href="/">
								<img src="/assets/img/icons/25x25/dark/computer-imac.png" width=25 height=25 alt="">
								Dashboard</a>
								
							</li>
							<?php if(Sentry::user(null, true)->has_access('customers_index')): ?>
							<li class='customers'>
								<a href="#">
								<img src="/assets/img/icons/25x25/dark/blocks---images.png" width=25 height=25 alt="">
								Customers</a>
								<ul>
									<?php foreach($tables as $k => $v): ?>
										<?php if(Sentry::user()->has_access($v['table'].'_read') || Sentry::user()->has_access('all_tables_read')): ?>
										<li class='<?php echo $v['url']; ?>'>
											<?php echo Html::anchor('customers/'.$v['url'], $v['CleanName']); ?>
										</li>
										<?php endif; ?>
									<?php endforeach; ?>
								</ul>
							</li>
							<?php endif; ?>

							<?php if(Sentry::user(null, true)->has_access('users_index')): ?>
							<li class='users'>
								<a href='#'>
								<img src="/assets/img/icons/25x25/dark/user.png" width=25 height=25 alt="">
								Users</a>
								<ul>

									<?php if(Sentry::user(null, true)->has_access('users_index')): ?>
									<li>
										<?php echo Html::anchor('users', 'User List'); ?>
									</li>
									<?php endif; ?>

									<?php if(Sentry::user(null, true)->has_access('users_create')): ?>
									<li>
										<?php echo Html::anchor('users/create', 'User Create'); ?>
									</li>
									<?php endif; ?>

									<?php if(Sentry::user(null, true)->has_access('groups_index')): ?>
									<li>
										<?php echo Html::anchor('groups', 'Group List'); ?>
									</li>
									<?php endif; ?>

									<?php if(Sentry::user(null, true)->has_access('groups_create')): ?>
									<li>
										<?php echo Html::anchor('groups/create', 'Group Create'); ?>
									</li>
									<?php endif; ?>
								</ul>
							</li>
							<?php endif; ?>
							<?php if(Sentry::user()->has_access('charts_index')): ?>
							<li>
								<a href="#">
								<img src="/assets/img/icons/25x25/dark/chart-3.png" width=25 height=25 alt="">
								Charts</a>
								<ul>
									<li>
										<?php echo Html::anchor('charts', 'Daily Subscription'); ?>
									</li>
									<li>
										<a href="calendar.html">Calendar</a>
									</li>
									<li>
										<a href="explorer.html">Explorer</a>
									</li>
									<li>
										<a href="editor.html">Rich Text Editor</a>
									</li>
									<li>
										<a href="tooltips.html">Tooltips</a>
									</li>
								</ul>
							</li>
							<?php endif; ?>
						</ul>
						<!-- End of the main navigation -->
					</div>
				</nav>
				<div id="nav_sub"></div>
			</header>