<?php get_header(); ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div class="post" id="post-<?php the_ID(); ?>">

			<h2 class="posttitle">
				<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent link to'); ?> <?php the_title(); ?>"><?php the_title(); ?></a>
			</h2>

			<p class="postmeta">
				<span class="post-date">Posted on <?php the_time('j M, Y') ?></span>&nbsp;
				<span class="post-cate">in <?php the_category(', ') ?></span>&nbsp;&bull;
				<span class="post-tags">Tags: <?php the_tags('', ' ',''); ?></span>
				<?php edit_post_link(__('Edit'), ' &#183; ', ''); ?>
			</p>

			<div class="postentry">
			<?php the_content(__('Read the rest of this entry &raquo;')); ?>
			<?php wp_link_pages(); ?>
			</div>

		</div>

		<div class="post comments">
			<?php comments_template(); ?>
		</div>

	<?php endwhile; else : ?>

		<div class="post">
			<h2><?php _e('Error 404 - Not Found'); ?></h2>
		</div>

	<?php endif; ?>

<?php get_sidebar(); ?>

<?php get_footer(); ?>