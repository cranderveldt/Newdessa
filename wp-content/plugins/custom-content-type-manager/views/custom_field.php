<?php
/*
How to save status?
http://stackoverflow.com/questions/5578405/wordpress-cannot-save-metaboxes-position-in-plugin
Probably gotta do an on-click event, get the ids of the various boxes, then store their classes
in a hidden field.
*/
?>
<script type="text/javascript">
jQuery(document).ready( function() {
   // jQuery('.postbox h3').prepend('<a class="togbox">+</a> ');
    jQuery('.postbox h3').click( function() {
        jQuery(jQuery(this).parent().get(0)).toggleClass('closed');
    });
    jQuery('.handlediv').click( function() {
        jQuery(jQuery(this).parent().get(0)).toggleClass('closed');
    });
});
</script>

<div class="metabox-holder">

<form id="custom_post_type_manager_basic_form" method="post" action="">


	<table class="custom_field_info">
		<tr>
			<td colspan="2">
				<h3 class="field_type_name"><?php print $data['name']; ?></h3>
			</td>
		</tr>
		<tr>
			<td>
				<span class="custom_field_icon"><?php print $data['icon']; ?></span>
			</td>
			<td>
				<span class="custom_field_description"><?php print $data['description']; ?>
				<br />
				<a href="<?php print $data['url']; ?>" target="_blank"><?php _e('More Information', CCTM_TXTDOMAIN); ?></a>
				</span>
			</td>
		</tr>
	</table>
	<?php wp_nonce_field($data['action_name'], $data['nonce_name']); ?>
	
	<?php print $data['fields']; ?>
	
	<div class="postbox">
		<div class="handlediv" title="Click to toggle"><br /></div>
		<h3 class="hndle"><span><?php _e('Associations', CCTM_TXTDOMAIN); ?></span></h3>
		<div class="inside">
			<p class="cctm_decscription"><?php _e('Which post-types should this field be attached to?', CCTM_TXTDOMAIN); ?></p>
			
			<?php print $data['associations']; ?>
		</div><!-- /inside -->
	</div><!-- /postbox -->
		
	<br />
	<input type="submit" class="button-primary" value="<?php _e('Save', CCTM_TXTDOMAIN ); ?>" />

	<a href="<?php print get_admin_url(false, 'admin.php'); ?>?page=cctm_fields&a=list_custom_field_types" title="<?php _e('Cancel'); ?>" class="button"><?php _e('Cancel'); ?></a>
</form>

</div><!-- /metabox-holder -->