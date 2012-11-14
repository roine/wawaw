<?php echo Html::doctype('html5'); ?>
<!--paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!-- Consider adding a manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">
	<!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<!-- DNS prefetch -->
		<link rel=dns-prefetch href="//fonts.googleapis.com">
		<!-- Use the .htaccess and remove these lines to avoid edge case issues.
		More info: h5bp.com/b/378 -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

		<title><?php echo isset($title) ? $title : 'IKON Back office'; ?></title>

		<meta name="description" content="backend using backbones, fuelphp">
		<meta name="author" content="jonathan de montalembert | demonj92@gmail.com">
		
		<!-- Mobile viewport optimized: j.mp/bplateviewport -->
		<meta name="viewport" content="width=device-width,initial-scale=1">
		
		<!-- Place favicon.ico and apple-touch-icon.png in the root directory:
		mathiasbynens.be/notes/touch-icons -->
		<!-- CSS -->
		<?php
		$options = array(
		    'enabled' => true,
		    'min' => false,
		    'combine' => false,
		    'inline' => false,
		    'attr' => array(),
		    'deps' => array(),
		);
		Casset::add_group('css', 'dashboard_css', array(
			'960gs/fluid.css', 
			'h5bp/normalize.css', 
			'h5bp/non-semantic.helper.classes.css', 
			'h5bp/print.styles.css', 
			'sprites.css',
			'header.css',
			'navigation.css',
			'typographics.css',
			'content.css',
			'footer.css',
			'ie.fixes.css',
			'font-awesome.css',
			'sidebar.css',
			'sprite.lists.css',
			), $options);
		
		echo Casset::render_css();
		echo isset($less) ? $less : '';
			?>
		<?php
		echo Asset::js(array(
			'libs/modernizr-2.0.6.min.js',
			
			));
		?>
	</head>
	<body class='<?php echo Request::active()->controller; ?> <?php echo isset($custom_class) ? $custom_class : ''; ?> <?php echo Request::active()->action; ?>' data-view='<?php echo Request::active()->action; ?>'>
		<div class="row">
				<div class="span16">
					<?php if (Session::get_flash('success')): ?>
						<div class="alert success no-margin top slide">
							<p>
							<?php echo implode('</p><p>', e((array) Session::get_flash('success'))); ?>
							</p>
						</div>
					<?php endif; ?>
					<?php if (Session::get_flash('error')): ?>
						<div class="alert error  no-margin top slide">
							<p>
							<?php echo implode('</p><p>', e((array) Session::get_flash('error'))); ?>
							</p>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<!-- Begin of #height-wrapper -->
		<div id="height-wrapper">
			<!-- Begin of header -->
			<?php echo render('regions/_header'); ?>
			<div role="main" class="container_12" id="content-wrapper">
				<!-- Start of the sidebar -->
				<aside>
					<div id="sidebar_top">
						<div class="userinfo">
							<div class="info">
								<div class="avatar">
									<img src="/assets/img/sprites/userinfo/avatar.png" width="80" height="80" alt="">
								</div>
								<?php echo Html::anchor('users/view/'.$current_user->id, e(ucwords($current_user->username))); ?>
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
						<p>Time in China:<br> <?php echo \Date::time('Asia/Shanghai')->format("<span class='time running local'>%a, %d %b %Y <span>%H</span><span>%M</span><span>%S</span></span>"); ?><br />
						</p>
						<p>Time in USA:<br /> <?php echo Date::time('America/Mexico_City')->format("<span class='time running us'>%a, %d %b %Y <span>%H</span><span>%M</span><span>%S</span></span>"); ?><br />
						</p>
						<?php if(Sentry::user()->has_access('notifications_send')): ?>

						<h2>Send Notification to the back-office users</h2>
						<div class='notificationsSend'>
							<p><textarea></textarea></p>
							<p><input type='submit' class='over color blue button' value='send notification'></p>
						</div>
						<?php endif; ?>
					</div>
				</aside><!-- End of the sidebar-->
			
			<div id="main_content">
				<?php echo $content; ?>
			</div>
			<div class="push clear"></div>
				</div>
				
				
			<a href="#top" id="top-link" ><i class="icon-hand-up"></i></a>

			<div class="clear"></div>
			<div class="push"></div> <!-- BUGFIX if problems with sidebar in Chrome -->
				
		</div> <!-- End of #height-wrapper -->
			
<footer>
				<div class="container_12">
				<?php if(Sentry::user()->has_access('speed_loading_read')): ?>
				Page rendered in {exec_time}s using {mem_usage}mb of memory.
			<?php else: ?>
				Copyright &copy; 2011 IKON Group, all rights reserved.	
			<?php endif; ?>
			<div id="button_bar">
						<ul>
							<li>
								<span><?php echo Html::anchor('/', 'Dashboard'); ?></span>
							</li>
							<li>
								<span><?php echo Html::anchor('settings', 'Settings'); ?></span>
							</li>
						</ul>
					</div>
				</div>

			</footer>
			
			
		<!-- JavaScript at the bottom for fast page loading -->
		<!-- Grab Google CDN's jQuery + jQueryUI, with a protocol relative URL; fall back to local -->		
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.js"></script>
		<script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.1.js"><\/script>')</script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
		<script>window.jQuery.ui || document.write('<script src="js/libs/jquery-ui-1.8.16.min.js"><\/script>')</script>
		<?php
		Casset::add_group('js', 'dashboard_js',array(
			'plugins.js',
			'mylibs/jquery.ba-resize.js',
			'mylibs/jquery.easing.1.3.js',
			'mylibs/jquery.ui.touch-punch.js',
			'mylibs/jquery.validate.js',
			'mylibs/jquery.jgrowl.js',
			'live-notification.js',
			'dashboard.js',
			'mylibs/jquery.scrollTo-min.js',
			'sprintf.js',
			'script.js',
			
			), $options);
		echo Casset::render_js();
			?>
		<!-- end scripts -->

		<script>

			$(window).load(function() {
				$('#accordion').accordion();
				$(window).resize();
			});

		</script>
		
		<!-- Prompt IE 6 users to install Chrome Frame. Remove this if you want to
		support IE 6.
		chromium.org/developers/how-tos/chrome-frame-getting-started -->
		<!--[if lt IE 7 ]>
		<script defer
		src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
		<script
		defer>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
		<![endif]-->
	</body>
	</html>