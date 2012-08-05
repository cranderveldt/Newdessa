<?php
/*
Copyright (c) 2009 < Nate Nuzum >
Use under terms of MIT license
*/
global $post;
$redirect = get_post_meta($post->ID, 'redirect', true);
if ($redirect)
        wp_redirect($redirect);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<?php wp_enqueue_script("jquery"); ?>
	<?php wp_head(); ?>
	<meta name="description" content="<?php bloginfo('name'); ?> <?php wp_title(); ?> | <?php bloginfo('description'); ?>" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php bloginfo('name'); ?> <?php wp_title(); ?></title>
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<link href='http://fonts.googleapis.com/css?family=Sail' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,800' rel='stylesheet' type='text/css'>
</head>
	
<body id="body-<?php the_ID(); ?>">
	<div id="header" class="group">
		<div class="head-wrap">			
			<h1><a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a></h1>
			<p><?php bloginfo('description'); ?></p>
			<div id="menu">
				<?php wp_nav_menu( array( 'theme_location' => 'menu-1' ) ); ?>
			</div>
		</div>
	</div>
	<div id="wrapper" class="group">
		<div id="main" class="group">
			<div id="sidebar">
				
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Sidebar') ) : ?>
				<?php endif; ?>
			</div> <!-- end of #sidebar -->
			<div id="page">