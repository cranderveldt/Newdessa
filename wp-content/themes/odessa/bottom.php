<div id="bottom">

	<!-- Bottom Left -->
	<div id="bottom-left">
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Bottom Left') ) : ?>
		<h2><?php _e('Recent <span>Entries</span>'); ?></h2>
		<ul>
			<?php wp_get_archives('type=postbypost&limit=10'); ?>
		</ul>
		<?php endif; ?>
	</div>

	<!-- Bottom Mid -->
	<div id="bottom-mid">
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Bottom Middle') ) : ?>
		<h2><?php _e('Arch<span>ives</span>'); ?></h2>
		<ul>
			<?php wp_get_archives('type=monthly&limit=10'); ?>
		</ul>
		<?php endif; ?>
	</div>

	<!-- Bottom Right -->
	<div id="bottom-right">
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Bottom Right') ) : ?>
		<?php wp_list_bookmarks('category_before=&category_after=&categorize=0&title_li=Blog<span>roll</span>&limit=10&orderby=rand'); ?>
		<?php endif; ?>
	</div>

</div> <!-- end of #bottom -->