<?php
/*
Copyright (c) 2012 Arrgyle
*/
global $post;
$redirect = get_post_meta($post->ID, 'redirect', true);
if ($redirect)
        wp_redirect($redirect);
?>
<!DOCTYPE html>
<head>
	<?php wp_enqueue_script("jquery"); ?>
	<?php wp_head(); ?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php bloginfo('name'); ?> <?php wp_title(); ?></title>
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<link href='http://fonts.googleapis.com/css?family=Sail' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,800' rel='stylesheet' type='text/css'>
	<meta name="keywords" content="Odessa NY news, Schuyler County news, New York, Central New York, Odessa File, Charlie Haeffner, Charlie Heffner, Charles Haeffner, Charles Heffner, Charlie Hefner, Hefner, CNY">
	<meta name="description" content="<?php bloginfo('name'); ?> <?php wp_title(); ?> - The latest breaking news on Odessa NY, Watkins Glen NY and Schuyler County, including sports, business, government, and people, with calendar of events and classified ads.">
</head>
	
<body id="body-<?php the_ID(); ?>">
	<div id="container" class="group">
		<div id="header" class="group">	
			<a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/masthead.jpg" alt="<?php bloginfo('name'); ?>" /></a>
			<div id="menu" class="group">
				<?php wp_nav_menu( array( 'theme_location' => 'menu-1' ) ); ?>
			</div><!-- /#menu -->
		</div><!-- /#header -->
		<div id="top">
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Top') ) : ?>
			<?php endif; ?>
		</div>
		<div id="left">
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Left Side') ) : ?>
			<?php endif; ?>
		</div> <!-- /#left -->
		<div id="content">