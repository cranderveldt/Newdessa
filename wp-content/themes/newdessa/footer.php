<?php do_action('wp_footer'); ?>
</div>
<div id="footer">
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer') ) : ?>
		<?php endif; ?>
</div>	
</div>

</body>
</html>