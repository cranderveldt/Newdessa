<div id="footer">
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer') ) : ?>
		<?php endif; ?>
        <?php do_action('wp_footer'); ?>
</div>	
</div><!-- /#container -->

</body>
</html>