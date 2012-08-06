<?php
/**
 * This handles custom columns when creating lists of posts/pages in the manager.
 * We use an object here so we can rely on a "dynamic funciton name" via __call() where
 * the function called corresponds to the post-type name. 
 *
 * WARNING: this requires that the post-type is named validly, i.e. in a name that would
 * be valid as a PHP function.
 * See 
 *	http://codex.wordpress.org/Plugin_API/Action_Reference/manage_posts_custom_column
 *	http://codex.wordpress.org/Plugin_API/Filter_Reference/manage_edit-post_type_columns
 *
 * manage_edit-${post_type}_columns 
 */
class CCTM_Columns {

	/**
	 * Sets the post-type, e.g. 'books'
	 */
	public $post_type; 
	
	/**
	 * Goes to true if the custom columns do not include the built-in title field
	 */
	public $no_title_flag = false;
	
	// Count as we iterate over columns in each row
	public $new_row = true;
	public $last_post;
	
	/**
	 * This is the magic function, named after the post-type in question.
	 *
	 * @param	string	$post_type
	 * @param	array	$default_columns associative array (set by WP);
	 * @return	array	associative array of column ids and translated names.
	 */
	public function __call($post_type, $default_columns) {

		$custom_columns = array('cb' => '<input type="checkbox" />',); // the output
		$built_in_columns = array(
			//'cb' => '<input type="checkbox" />',
			'title' => __('Title'), // post_title
			'author' => __('Author'), // lookup on wp_users
			'comments' => __('Comments'),
			'date' => __('Date')
		);
		$raw_columns = array();
		if (isset(CCTM::$data['post_type_defs'][$post_type]['cctm_custom_columns'])) {
			$raw_columns = CCTM::$data['post_type_defs'][$post_type]['cctm_custom_columns'];
		}
		
		// The $raw_columns contains a simple array, e.g. array('field1','wonky');
		// we need to create an associative array.
		// Look up what kind of column this is.
		foreach ($raw_columns as $c) {
			if (isset($built_in_columns[$c])) {
				$custom_columns[$c] = $built_in_columns[$c]; // already translated
			}
			// Custom Field
			elseif (isset(CCTM::$data['custom_field_defs'][$c])) {
				$custom_columns[$c] = __(CCTM::$data['custom_field_defs'][$c]['label']);
			}
			// Taxonomy
			elseif (false) {
			
			}
		}
		if (!isset($custom_columns['title'])) {
			$this->no_title_flag = true;
		}
		return $custom_columns;
	}
	
	//------------------------------------------------------------------------------
	/**
	 * Populate the custom data for a given column.  This function should actually
	 * *print* data, not just return it.
	 * Oddly, WP doesn't even send the column this way unless it is something custom.
	 * Note that things get all broken and wonky if you do not include the post title column,
	 * so this function has some customizations here to print out the various eye-candy
	 * Edit/Trash/View links in that case.
	 *
	 */
	public function populate_custom_column_data($column) {

		global $post;
		
		if ($this->last_post != $post->ID) {
			$this->new_row = true;
		}
		
		if ($this->no_title_flag && $this->new_row) {			
			print '<strong><a class="row-title" href="post.php?post='.$post->ID.'&amp;action=edit">';
		}

		print_custom_field($column);
		
		if ($this->no_title_flag && $this->new_row) {
			print '</a></strong>
				<div class="row-actions"><span class="edit"><a href="post.php?post='.$post->ID.'&amp;action=edit" title="'.__('Edit').'">'.__('Edit').'</a> | </span>
				<span class="inline hide-if-no-js"><a href="#" class="editinline">'.__('Quick Edit').'</a> | </span><span class="trash"><a class="submitdelete" href="'.get_delete_post_link($post->ID).'">'.__('Trash').'</a> | </span><span class="view"><a href="'.get_permalink($post->ID).'" rel="permalink">'.__('View').'</a></span>';
			$this->new_row = false;
			$this->last_post = $post->ID;
		}		
	}
}
/*EOF*/