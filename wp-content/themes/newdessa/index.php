<?php get_header(); ?>

	<?php if (have_posts()) : ?>
<!--stuff goes here -->
		<?php $post = $posts[0]; // Thanks Kubrick for this code ?>

		<?php if (is_category()) { ?>
		<div class="page-title">
			<h2 class="page-title-border"><?php echo single_cat_title(); ?></h2>
		</div>
		<div id="top-sidebar">
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Top Sidebar') ) : ?>
			<?php endif; ?>
		</div>
		<?php } elseif (is_tag()) { ?>
		<div class="page-title">
			<h2 class="page-title-border"><?php _e('Tag Archive for'); ?> <?php echo single_tag_title(); ?></h2>
		</div>
 	  	<?php } elseif (is_day()) { ?>
		<div class="page-title">
			<h2 class="page-title-border"><?php _e('Archive for'); ?> <?php the_time('F j, Y'); ?></h2>
		</div>
	 	<?php } elseif (is_month()) { ?>
		<div class="page-title">
			<h2 class="page-title-border"><?php _e('Archive for'); ?> <?php the_time('F, Y'); ?></h2>
		</div>
		<?php } elseif (is_year()) { ?>
		<div class="page-title">
			<h2 class="page-title-border"><?php _e('Archive for'); ?> <?php the_time('Y'); ?></h2>
		</div>
		<?php } elseif (is_author()) { ?>
		<div class="page-title">
			<h2 class="page-title-border"><?php _e('Author Archive'); ?></h2>
		</div>
		<?php } elseif (is_search()) { ?>
		<div class="page-title">
			<h2 class="page-title-border"><?php _e('Search Results'); ?></h2>
		</div>
		<?php } ?>


		<?php while (have_posts()) : the_post(); ?>

			<div class="post" id="post-<?php the_ID(); ?>">

				<h2 class="posttitle">
					<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent link to'); ?> <?php the_title(); ?>"><?php the_title(); ?></a>
				</h2>

				<p class="postmeta">
					<span class="post-date">Posted on <?php the_time('j M, Y') ?></span>&nbsp;&bull;
					<span class="post-tags">Tags: <?php the_tags('', ' ',''); ?></span>&nbsp;&bull;
					<span class="post-cmts"><?php comments_popup_link(__('Post a Comment'), __('1 Comment'), __('% Comments'), 'commentslink', __('Comments off')); ?></span>
					<?php edit_post_link(__('Edit'), ' &#183; ', ''); ?>
				</p>

				<div class="postentry">
				<?php if (is_search()) { ?>
					<?php the_excerpt() ?>
				<?php } else { ?>
					<?php the_content(__('Read the rest of this entry &raquo;')); ?>
				<?php } ?>
				</div>

				<!--
				<?php trackback_rdf(); ?>
				-->

			</div>

		<?php endwhile; ?>



		
		<?php if(function_exists('wp_pagenavi')) : ?>
			<?php wp_pagenavi(); ?>
		<?php else : ?>
			<div class="paged">
				<span class="alignleft"><?php previous_posts_link('&laquo; Newer Entries') ?></span>
				<span class="alignright"><?php next_posts_link('Older Entries &raquo;') ?></span>
			</div>
		<?php endif; ?>




	<?php else : ?>

		<div class="post">
			<h2><?php _e('Error 404 - Not Found'); ?></h2>
		</div>

	<?php endif; ?>


<?php get_sidebar(); ?>

<?php get_footer(); ?>