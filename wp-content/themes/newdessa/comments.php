<?php // Do not delete these lines
	if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

        if (!empty($post->post_password)) { // if there's a password
            if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
				?>

				<p><?php _e("This post is password protected. Enter the password to view comments."); ?><p>

				<?php
				return;
            }
        }

		/* This variable is for alternating comment background, thanks Kubrick */
		$oddcomment = 'alt';
?>

<!-- You can start editing here. -->

<?php if ($comments) : ?>

	<h2 class="comments-num">
		<?php comments_number(__('Comments'), __('1 Comment'), __('% Comments')); ?>
		<?php if ( comments_open() ) : ?>
			<?php _e(' so far '); ?><a href="#postcomment" title="<?php _e('Jump to the comments form'); ?>">&raquo;</a>
		<?php endif; ?>
	</h2>

	<ol id="commentlist">

	<?php foreach ($comments as $comment) : ?>

		<li class="<?php echo $oddcomment; ?>" id="comment-<?php comment_ID() ?>">

		<!-- Gravatar -->
		<div class="comment-gravatar">
			<?php echo get_avatar(get_comment_author_email(), '48', get_bloginfo('template_url')."/images/default_gravatar.gif" ); ?>
		</div>

		<h3 class="comment-title"><?php comment_author_link() ?> <?php _e('said'); ?>,</h3>

		<p class="comment-meta">
			<?php _e('Wrote on '); ?>
			<a href="#comment-<?php comment_ID() ?>">
				<?php comment_date('F j, Y') ?>
				<?php _e(' @ '); ?><?php comment_time() ?>
			</a>
			<?php edit_comment_link(__("Edit"), ' &#183; ', ''); ?>
		</p>

		<div class="comment-text"><?php comment_text() ?></div>
		</li>

		<?php
			if ('alt' == $oddcomment) $oddcomment = '';
			else $oddcomment = 'alt';
		?>

	<?php endforeach; /* end for each comment */ ?>

	</ol>

	<p class="small">
		<?php comments_rss_link(__('Comment <abbr title="Really Simple Syndication">RSS</abbr>')); ?>
		<?php if ( pings_open() ) : ?>
		&#183; <a href="<?php trackback_url() ?>" rel="trackback"><?php _e('TrackBack <abbr title="Uniform Resource Identifier">URI</abbr>'); ?></a>
		<?php endif; ?>
	</p>

<?php else : // this is displayed if there are no comments so far ?>

	<?php if ('open' == $post-> comment_status) : ?>
		<?php /* No comments yet */ ?>

	<?php else : // comments are closed ?>
		<?php /* Comments are closed */ ?>
		<p><?php _e('Comments are closed.'); ?></p>

	<?php endif; ?>

<?php endif; ?>

<?php if ('open' == $post-> comment_status) : ?>

	<h2 id="postcomment"><?php _e('Leave a Comment'); ?></h2>
	<div class="postcomment">

	<?php if ( get_option('comment_registration') && !$user_ID ) : ?>

		<p><?php _e('You must be'); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>"><?php _e('logged in'); ?></a> <?php _e('to post a comment.'); ?></p>

	<?php else : ?>

		<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

		<?php if ( $user_ID ) : ?>

			<p><?php _e('Logged in as'); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="<?php _e('Log out of this account') ?>"><?php _e('Logout'); ?> &raquo;</a></p>

		<?php else : ?>

			<p>
				<strong><?php _e('Name:'); ?> <?php if ($req) _e('(Required)'); ?></strong><br/>
				<input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" tabindex="1" />
			</p>

			<p>
				<strong><?php _e('E-mail:'); ?> <?php if ($req) _e('(Required)'); ?></strong><br/>
				<input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" tabindex="2" />
			</p>

			<p>
				<strong><?php _e('Website:'); ?></strong><br/>
				<input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" tabindex="3" />
			</p>

		<?php endif; ?>

			<p>
				<strong><?php _e('Comment:'); ?></strong><br/>
				<textarea name="comment" id="comment" rows="" cols="" tabindex="4"></textarea>
			</p>

			<p>
				<input name="submit" type="submit" id="submit" tabindex="5" value="<?php _e('Say it'); ?>" />
				<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
			</p>

		<?php do_action('comment_form', $post->ID); ?>

		</form>

	<?php endif; // If registration required and not logged in ?>
	</div>

<?php endif; // if you delete this the sky will fall on your head ?>
