<?php
/*------------------------------------------------------------------------------
Used to get a tpl for use in the Post Content widget

@param	integer	post_id
@param	string	css_id instance of the field (has to sync with JS)
------------------------------------------------------------------------------*/
if (!defined('CCTM_PATH')) exit('No direct script access allowed');
if (!current_user_can('edit_posts')) exit('You do not have permission to do that.');

require_once(CCTM_PATH.'/includes/GetPostsQuery.php');

//print '<div>'. print_r($_POST, true).'</div>';
$post_id = CCTM::get_value($_POST, 'post_id');
$target_id = CCTM::get_value($_POST, 'target_id');

// Will be either the single or the multi, depending.
$tpl = '';

$tpl = CCTM::load_tpl('widgets/post_item.tpl');

// Just in case...
if (empty($tpl)) {
	print '<p>'.__('Formatting template not found!', CCTM_TXTDOMAIN).'</p>';
	return;	

}

$Q = new GetPostsQuery();
$post = $Q->get_post($post_id);
$post['edit_selected_post_label'] = __('Edit Selected Post', CCTM_TXTDOMAIN);

$post_type = $post['post_type'];

$post['post_icon'] = CCTM::get_thumbnail($post_id);


if ($post_type == 'attachment') {
	$post['edit_url'] = get_admin_url('','media.php')."?attachment_id=$post_id&action=edit";
}
else {
	$post['edit_url'] = get_admin_url('','post.php')."?post=$post_id&action=edit";
}

$post['target_id'] = $target_id;

print CCTM::parse($tpl, $post);

/*EOF*/