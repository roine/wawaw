<!doctype html> <!--
paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!-- Consider adding a manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">
	<!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<!-- Use the .htaccess and remove these lines to avoid edge case issues.
		More info: h5bp.com/b/378 -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		
		<title>404 :: Back Office by Jonathan de montalembert</title>
		<meta name="description" content="">
		<meta name="author" content="">
		
		<!-- Mobile viewport optimized: j.mp/bplateviewport -->
		<meta name="viewport" content="width=device-width,initial-scale=1">
		
		<!-- Place favicon.ico and apple-touch-icon.png in the root directory:
		mathiasbynens.be/notes/touch-icons -->

		<!-- CSS -->
		<link rel="stylesheet" href="/assets/css/960gs/fluid.css"> <!-- 960.gs Grid System -->
		<!-- The HTML5-Boilerplate CSS styling -->
		<link rel="stylesheet" href="/assets/css/h5bp/normalize.css"> <!-- RECOMMENDED: H5BP reset styles -->
		<link rel="stylesheet" href="/assets/css/h5bp/non-semantic.helper.classes.css"> <!-- RECOMMENDED: H5BP helpers (e.g. .clear or .hidden) -->
		<link rel="stylesheet" href="/assets/css/h5bp/print.styles.css"> <!-- OPTIONAL: H5BP print styles -->
		<!-- The special page's styling -->
		<link rel="stylesheet" href="/assets/css/special-page.css"> <!-- REQUIRED: Special page styling -->
		<link rel="stylesheet" href="/assets/css/sprites.css"> <!-- REQUIRED: Basic sprites (e.g. buttons, jGrowl) -->
		<link rel="stylesheet" href="/assets/css/typographics.css"> <!-- REQUIRED: Typographics -->

		<!-- More ideas for your <head> here: h5bp.com/d/head-Tips -->
		
		<!-- All JavaScript at the bottom, except this Modernizr build incl. Respond.js
		Respond is a polyfill for min/max-width media queries. Modernizr enables HTML5
		elements & feature detects;
		for optimal performance, create your own custom Modernizr build:
		www.modernizr.com/download/ -->
		<script src="/assets/js/libs/modernizr-2.0.6.min.js"></script>
	</head>
	
	<body class="special_page">
		<div class="topt">
			<div class="gradient"></div>
			<div class="white"></div>
			<div class="shadow"></div>
		</div>
		<div class="content">
			<div class="error_nr">
				<h1>404!</h1>
			</div>
			<div class="error_text">
				<?php echo Html::anchor('/', 'Back to dashboard', array('class' => 'button')); ?>
			</div>
		</div>

						
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
