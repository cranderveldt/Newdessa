<?php get_header(); ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	<div class="post" id="post-<?php the_ID(); ?>">

		<?php if (!is_page('Blog')) { ?>
		<h2 id="h2-<?php the_ID(); ?>" class="pagetitle"><?php the_title(); ?></h2>
		<?php } ?>

		<?php the_content(__('Read the rest of this page &raquo;')); ?>
		<?php wp_link_pages(); ?>
		
		<?php edit_post_link(__('Edit'), '<p>', '</p>'); ?>
		
	</div>
	<div class="post comments"><?php comments_template(); ?></div>
	<?php endwhile; endif; ?>

<?php get_sidebar(); ?>

<?php get_footer(); ?>