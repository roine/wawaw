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
		// Casset::clear_css_cache();
		//  Casset::clear_js_cache();
		$options = array(
		    'enabled' => true,
		    'min' => false,
		    'combine' => false,
		    'inline' => false,
		    'attr' => array(),
		    'deps' => array(),
		);

		Casset::add_group('css', 'login_css', array(

			'h5bp/normalize.css', 
			// 'h5bp/non-semantic.helper.classes.css', 
			'h5bp/print.styles.css', 
			'sprites.css',
			'navigation.css',
			'typographics.css',
			'content.css',
			'footer.css',
			'sprite.forms.css',
			'ie.fixes.css',
			'font-awesome.css',
			'special-page.css'
			), $options);
		echo Casset::render_css();

			?>
		<?php
		echo Asset::js(array(
			'libs/modernizr-2.0.6.min.js',
			
			));
		
		?>
	</head>
	<body class='<?php echo Request::active()->controller; ?> <?php echo isset($custom_class) ? $custom_class : ''; ?> <?php echo Request::active()->action; ?>
	' data-view='<?php echo Request::active()->action; ?>'>
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
	<?php echo $content; ?>
		<!-- JavaScript at the bottom for fast page loading -->
			<!-- Grab Google CDN's jQuery + jQueryUI, with a protocol relative URL; fall back to local -->		
			<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.js"></script>
			<script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.1.js"><\/script>')</script>

			<?php 
			Casset::add_group('js', 'login_js',array(
				'plugins.js',
				'mylibs/jquery.validate.js',
				'mylibs/jquery.checkbox.js',
				'script.js',
				'login.js',
			), $options);
			echo Casset::render_js();
			 ?>
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