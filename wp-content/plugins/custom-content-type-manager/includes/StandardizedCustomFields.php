<?php
/*------------------------------------------------------------------------------
This plugin standardizes the custom fields for specified content types, e.g.
post, page, and any other custom post-type you register via a plugin.
------------------------------------------------------------------------------*/
class StandardizedCustomFields 
{
	
	//! Private Functions
	//------------------------------------------------------------------------------
	/**
	 * Get custom fields for this content type.
	 * @param string $post_type the name of the post_type, e.g. post, page.
	OUTPUT: array of associative arrays where each associative array describes 
		a custom field to be used for the $content_type specified.
	FUTURE: read these arrays from the database.
	*/
	private static function _get_custom_fields($post_type) {
		if (isset(CCTM::$data['post_type_defs'][$post_type]['custom_fields'])) {
			return CCTM::$data['post_type_defs'][$post_type]['custom_fields'];
		}
		else {
			return array();
		}
	}

	//------------------------------------------------------------------------------
	/**
	 * This determines if the user is editing an existing post.
	 *
	 * @return boolean
	 */
	private static function _is_existing_post()
	{
		if ( substr($_SERVER['SCRIPT_NAME'],strrpos($_SERVER['SCRIPT_NAME'],'/')+1) == 'post.php' ) {
			return true;
		}
		else {
			return false;
		}
	}


	//------------------------------------------------------------------------------
	/**
	 * This determines if the user is creating a new post.
	 *
	 * @return boolean
	 */
	 private static function _is_new_post() {
		if ( substr($_SERVER['SCRIPT_NAME'],strrpos($_SERVER['SCRIPT_NAME'],'/')+1) == 'post-new.php' ) {
			return true;
		}
		else {
			return false;
		}
	}

	//------------------------------------------------------------------------------
	/**
	 * Validate custom fields.  Print message in admin head if there are errors.
	 * TODO: should this even bother doing checks if there are no custom fields?
	 * or would it be possible to do validations on built-in fields?
	 */
	private static function _validate_fields($post_type) {

		global $post;
		
		$error_flag = false; // goes to true if there are errors, forces post to draft status
		
		$Q = new GetPostsQuery();
		$full_post = $Q->get_post($post->ID);

		$custom_fields = self::_get_custom_fields($post_type);
		$validation_errors = array();
		foreach ( $custom_fields as $field_name ) {
			if (!isset(CCTM::$data['custom_field_defs'][$field_name]['type'])) {
				continue;
			}
			$field_type = CCTM::$data['custom_field_defs'][$field_name]['type'];
			
			if (CCTM::include_form_element_class($field_type)) {
				$field_type_name = CCTM::classname_prefix.$field_type;
				$FieldObj = new $field_type_name(); // Instantiate the field element
				$FieldObj->set_props(CCTM::$data['custom_field_defs'][$field_name]);

				$value = '';
				if (isset($full_post[$field_name])) {
					$value = $full_post[$field_name];
				}
				
				// Check for empty json arrays, e.g. [""], convert them to empty PHP array()
				$value_copy = '';
				if ($FieldObj->is_repeatable) {
					//$value_copy = json_decode(stripslashes($value), true);
					$value_copy = $FieldObj->get_value($value, 'to_array');

					if (is_array($value_copy)) {
						foreach ($value_copy as $k => $v) {
							if (empty($v)) {
								unset($value_copy[$k]);
							}
						}
					}
				}
				else {
					$value_copy = $FieldObj->get_value($value, 'to_string');
				}

				
				// Is this field required?  OR did validation fail?
				if ($FieldObj->required && empty($value_copy) ) { // && strlen($value_copy) == 0) {
					$error_flag = true;					
					CCTM::$post_validation_errors[$FieldObj->name] = sprintf(__('The %s field is required.', CCTM_TXTDOMAIN), $FieldObj->label);
				}
				// Do any other validation checks here: TODO
				elseif (!empty($value_copy) && isset($FieldObj->validator) && !empty($FieldObj->validator)) {
					$Validator = CCTM::load_object($FieldObj->validator, 'validator');
					if (isset(CCTM::$data['custom_field_defs'][$field_name]['validator_options'])) {
						$Validator->set_options(CCTM::$data['custom_field_defs'][$field_name]['validator_options']);
					}
					$Validator->set_subject($FieldObj->label);
					$Validator->set_options($FieldObj->validator_options);
					if (is_array($value_copy)) {
						foreach ($value_copy as $i => $val) {
							$value_copy[$i] = $Validator->validate($val);
						}
					}
					else {
						$value_copy = $Validator->validate($value_copy);
					}					
					if (!empty($Validator->error_msg)) { 
						$error_flag = true;
						CCTM::$post_validation_errors[$FieldObj->name] = $Validator->get_error_msg();
					}
				}
				
			}
			else {
				// error!  Can't include the field class.  WTF did you do?
			}
		}
		
		// Print any validation errors.
		if(!empty(CCTM::$post_validation_errors)) {
			$output = '<div class="error"><img src="'.CCTM_URL.'/images/warning-icon.png" width="50" height="44" style="float:left; padding:10px; vertical-align:middle;"/><p style=""><strong>'
				. __('This post has validation errors.  The post will remain in draft status until they are corrected.', CCTM_TXTDOMAIN) 
				. '</strong></p>
				<ul style="clear:both;">';
			
			foreach (CCTM::$post_validation_errors as $fieldname => $e) {
				$output .= '<li>'.$e.'</li>';
			}
			$output .= '</ul></div>';
			
			// You have to print the style because WP is overriding styles after the cctm manager.css is included.
			// This isn't helpful during the admin head event because you'd have to also check validation at the time when
			// the fields are printed in print_custom_fields(), which fires later on.
			
			// We can use this variable to pass data to a point later in the page request. 
			// global $cctm_validation;
			// CCTM::$errors 
			// CCTM::$errors['my_field'] = 'This is the validation error with that field';
			
			$output .= '<style>';
			$output .= file_get_contents(CCTM_PATH.'/css/validation.css');
			$output .= '</style>';

			print $output;
		}
		
		// Override!! set post to draft status if there were validation errors.
		if ($error_flag) {
			global $wpdb;
			$post_id = (int) CCTM::get_value($_POST, 'ID');
			$wpdb->update( $wpdb->posts, array( 'post_status' => 'draft' ), array( 'ID' => $post_id ) );
		}
	}

	//! Public Functions	
	//------------------------------------------------------------------------------
	/**
	* Create the new Custom Fields meta box
	* TODO: allow customization of the name, instead of just 'Custom Fields', and also
	* of the wrapper div.
	*/
	public static function create_meta_box() {
		$content_types_array = CCTM::get_active_post_types();
		foreach ( $content_types_array as $content_type ) {
			add_meta_box( 'cctm_default'
				, __('Custom Fields', CCTM_TXTDOMAIN )
				, 'StandardizedCustomFields::print_custom_fields'
				, $content_type
				, 'normal'
				, 'high'
				, $content_type 
			);
		}
	}

	//------------------------------------------------------------------------------
	/**
	 * WP only allows users to select PUBLISHED pages of the same post_type in their hierarchical
	 * menus.  And there are no filters for this whole thing save at the end to filter the generated 
	 * HTML before it is sent to the browser. Arrgh... this is grossly inefficient!!
	 * It's inefficient, but here we optionally pimp out the HTML to offer users sensible choices for
	 * hierarchical parents.
	 *
	 * @param	string	incoming html element for selecting a parent page, e.g.
	 *						<select name="parent_id" id="parent_id">
	 *					        <option value="">(no parent)</option>
	 *					        <option class="level-0" value="706">Post1</option>
	 *						</select>	
	 *
	 * See http://wordpress.org/support/topic/cannot-select-parent-when-creatingediting-a-page	 
	 */
	public static function customized_hierarchical_post_types( $html ) {
		global $wpdb, $post;
		
		// Otherwise there be errors on the Settings --> Reading page
		if (empty($post)) {
			return $html;
		}

		$post_type = $post->post_type;
		
		
		// customize if selected
		if (isset(CCTM::$data['post_type_defs'][$post_type]['hierarchical'])
			&& CCTM::$data['post_type_defs'][$post_type]['hierarchical'] 
			&& CCTM::$data['post_type_defs'][$post_type]['cctm_hierarchical_custom']) {
			// filter by additional parameters
			if ( CCTM::$data['post_type_defs'][$post_type]['cctm_hierarchical_includes_drafts'] ) {
				$args['post_status'] = 'publish,draft,pending';	
			}
			else {
				$args['post_status'] = 'publish';
			}
			
			$args['post_type'] = CCTM::$data['post_type_defs'][$post_type]['cctm_hierarchical_post_types'];
			// We gotta ensure ALL posts are returned.
			// See http://code.google.com/p/wordpress-custom-content-type-manager/issues/detail?id=114
			$args['numberposts'] 	= -1;
			// And we tweak the order: http://code.google.com/p/wordpress-custom-content-type-manager/issues/detail?id=227
			$args['orderby'] 		= 'title';
			$args['order'] 			= 'ASC';

			$posts = get_posts($args);

			$html = '<select name="parent_id" id="parent_id">
				<option value="">(no parent)</option>
			';
			foreach ( $posts as $p ) {
				$is_selected = '';
				if ( $p->ID == $post->post_parent ) {
					$is_selected = ' selected="selected"';	
				}
				// We add the __() to post_title for the benefit of translation plugins. E.g. see issue 279
				$html .= sprintf('<option class="level-0" value="%s"%s>%s (%s)</option>', $p->ID, $is_selected, __($p->post_title), $p->post_type);
			}
			$html .= '</select>';
		}
		return $html;
	}

	//------------------------------------------------------------------------------
	/**
	 * We use this to print out the large icon
	 * http://code.google.com/p/wordpress-custom-content-type-manager/issues/detail?id=188
	 */
	public static function print_admin_header() {

		$file = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], '/')+1);
		if ( !in_array($file, array('post.php', 'post-new.php','edit.php'))) {
			return;
		}
		$post_type = CCTM::get_value($_GET, 'post_type');
		if (empty($post_type)) {
			$post_id = (int) CCTM::get_value($_GET, 'post');
			if (empty($post_id)) {
				return;
			}
			$post = get_post($post_id);
			$post_type = $post->post_type;
		}
		
		// Only do this stuff for active post-types (is_active can be 1 for built-in or 2 for foreign)
		if (!isset(CCTM::$data['post_type_defs'][$post_type]['is_active']) || !CCTM::$data['post_type_defs'][$post_type]['is_active']) {
			return; 
		}
		
		// Show the big icon: http://code.google.com/p/wordpress-custom-content-type-manager/issues/detail?id=136
		if ( isset(CCTM::$data['post_type_defs'][$post_type]['use_default_menu_icon']) 
			&& CCTM::$data['post_type_defs'][$post_type]['use_default_menu_icon'] == 0 ) { 
			$baseimg = basename(CCTM::$data['post_type_defs'][$post_type]['menu_icon']);
			// die($baseimg); 
			if ( file_exists(CCTM_PATH . '/images/icons/32x32/'. $baseimg) ) {
				printf('
				<style>
					#icon-edit, #icon-post {
					  background-image:url(%s);
					  background-position: 0px 0px;
					}
				</style>'
				, CCTM_URL . '/images/icons/32x32/'. $baseimg);
			}
		}
		
		// Validate the custom fields: only need to do this AFTER a post-new.php has been created.
		$file = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], '/')+1);
		if ( in_array($file, array('post.php'))) {
			self::_validate_fields($post_type);
		}

	}

	/*------------------------------------------------------------------------------
	Display the new Custom Fields meta box inside the WP manager.
	INPUT:
	@param object $post passed to this callback function by WP. 
	@param object $callback_args will always have a copy of this object passed (I'm not sure why),
		but in $callback_args['args'] will be the 7th parameter from the add_meta_box() function.
		We are using this argument to pass the content_type.
	
	@return null	this function should print form fields.
	------------------------------------------------------------------------------*/
	public static function print_custom_fields($post, $callback_args='') 
	{		
		$post_type = $callback_args['args']; // the 7th arg from add_meta_box()
		$custom_fields = self::_get_custom_fields($post_type);
		$output = '';

		// If no custom content fields are defined, or if this is a built-in post type that hasn't been activated...
		if ( empty($custom_fields) ) {
			return;
		}
		
		foreach ( $custom_fields as $cf ) {
			if (!isset(CCTM::$data['custom_field_defs'][$cf])) {
				// throw error!!
				continue;
			}
			$def = CCTM::$data['custom_field_defs'][$cf];
			
			if (isset($def['required']) && $def['required'] == 1) {
				$def['label'] = $def['label'] . '*'; // Add asterisk
			}
			
			$output_this_field = '';
			CCTM::include_form_element_class($def['type']); // This will die on errors
			$field_type_name = CCTM::classname_prefix.$def['type'];
			$FieldObj = new $field_type_name(); // Instantiate the field element
			
			if ( self::_is_new_post() ) {	
				$FieldObj->set_props($def);
				$output_this_field = $FieldObj->get_create_field_instance();
			}
			else {
				$current_value = get_post_meta( $post->ID, $def['name'], true );
				// Check for validation errors.
				if (isset(CCTM::$post_validation_errors[ $def['name'] ])) {
					$def['error_msg'] = sprintf('<span class="cctm_validation_error">%s</span>', CCTM::$post_validation_errors[ $def['name'] ]);
					if (isset($def['class'])) {
						$def['class'] .= 'cctm_validation_error';
					}
					else {
						$def['class'] = 'cctm_validation_error';
					}
					
				}
				$FieldObj->set_props($def);
				$output_this_field =  $FieldObj->get_edit_field_instance($current_value);
			}
						
			$output .= $output_this_field;
		}
		
		// Print the nonce: this offers security and it will help us know when we 
		// should do custom saving logic in the save_custom_fields function
		$output .= '<input type="hidden" name="_cctm_nonce" value="'.wp_create_nonce('cctm_create_update_post').'" />';

 		// Print the form
 		print '<div class="form-wrap">'.$output.'</div>';
 
	}


	/*------------------------------------------------------------------------------
	Remove the default Custom Fields meta box. Only affects the content types that
	have been activated.
	INPUTS: sent from WordPress
	------------------------------------------------------------------------------*/
	public static function remove_default_custom_fields( $type, $context, $post ) 
	{
		$content_types_array = CCTM::get_active_post_types();
		foreach ( array( 'normal', 'advanced', 'side' ) as $context ) {
			foreach ( $content_types_array as $content_type ) {
				remove_meta_box( 'postcustom', $content_type, $context );
			}
		}
	}
	
	//------------------------------------------------------------------------------
	/**
	 * Save the new Custom Fields values. If the content type is not active in the 
	 * CCTM plugin or its custom fields are not being standardized, then this function 
	 * effectively does nothing.
	 *
	 * WARNING: This function is also called when the wp_insert_post() is called, and
	 * we don't want to step on its toes. We want this to kick in ONLY when a post 
	 * is inserted via the WP manager. 
	 * see http://code.google.com/p/wordpress-custom-content-type-manager/issues/detail?id=52
	 * 
	 * @param	integer	$post_id id of the post these custom fields are associated with
	 * @param	object	$post  the post object
	 */
	public static function save_custom_fields( $post_id, $post ) 
	{

		// Bail if you're not in the admin editing a post
		if (!self::_is_existing_post() && !self::_is_new_post() ) {
			return;
		}
		
		// Bail if this post-type is not active in the CCTM
		if ( !isset(CCTM::$data['post_type_defs'][$post->post_type]['is_active']) 
			|| CCTM::$data['post_type_defs'][$post->post_type]['is_active'] == 0) {
			return;
		}
	
		// Bail if there are no custom fields defined in the CCTM
		if ( empty(CCTM::$data['post_type_defs'][$post->post_type]['custom_fields']) ) {
			return;
		}
		
		// See issue http://code.google.com/p/wordpress-custom-content-type-manager/issues/detail?id=80
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
			return $post_id;
		}

		// Use this to ensure you save custom fields only when saving from the edit/create post page
		$nonce = CCTM::get_value($_POST, '_cctm_nonce');
		if (! wp_verify_nonce($nonce, 'cctm_create_update_post') ) {
			return;
		}

		if ( !empty($_POST) ) {			
			$custom_fields = self::_get_custom_fields($post->post_type);
			$validation_errors = array();
			foreach ( $custom_fields as $field_name ) {
				if (!isset(CCTM::$data['custom_field_defs'][$field_name]['type'])) {
					continue;
				}
				$field_type = CCTM::$data['custom_field_defs'][$field_name]['type'];

				if (CCTM::include_form_element_class($field_type)) {
					$field_type_name = CCTM::classname_prefix.$field_type;
					$FieldObj = new $field_type_name(); // Instantiate the field element
					$FieldObj->set_props(CCTM::$data['custom_field_defs'][$field_name]);
					$value = $FieldObj->save_post_filter($_POST, $field_name);

					if (defined('CCTM_DEBUG') && CCTM_DEBUG == true) {			
						$myFile = "/tmp/cctm.txt";
						$fh = fopen($myFile, 'a') or die("can't open file");
						fwrite($fh, "Field Type: $field_type  Value: $value\n");
						fclose($fh);
					}
										
					// Custom fields can return a literal null if they don't save data to the db.
					if ($value !== null) {
					
						// Check for empty json arrays, e.g. [""], convert them to empty PHP array()
						$value_copy = $value;
						if ($FieldObj->is_repeatable) {
							$value_copy = json_decode(stripslashes($value), true);

							if (is_array($value_copy)) {
								foreach ($value_copy as $k => $v) {
									if (empty($v)) {
										unset($value_copy[$k]);
									}
								}
							}
						}
						// Is this field required?  
						if ($FieldObj->required && empty($value_copy)) {

							// Override!! set post to draft status
							global $wpdb;
							$post_id = (int) CCTM::get_value($_POST, 'ID');
							$wpdb->update( $wpdb->posts, array( 'post_status' => 'draft' ), array( 'ID' => $post_id ) );
							// set flash message to notify user
							$validation_errors[$FieldObj->name] = 'required';
							update_post_meta($post_id, $field_name, $value);
						}
						// We do some more work to ensure the database stays lean
						elseif(empty($value_copy) && !CCTM::get_setting('save_empty_fields')) {
							// Delete the row from wp_postmeta, or don't write it at all
							delete_post_meta($post_id, $field_name);
						}
						else {
							update_post_meta($post_id, $field_name, $value);
						}
					}					
					
				}
				else {
					// error!  Can't include the field class.  WTF did you do?
				}
			}
			
			// Pass validation errors like this: fieldname => validator, e.g. myfield => required
			if (!empty($validation_errors)) {
				if (defined('CCTM_DEBUG') && CCTM_DEBUG == true) {			
					$myFile = "/tmp/cctm.txt";
					$fh = fopen($myFile, 'a') or die("can't open file");
					fwrite($fh, json_encode($validation_errors)."\n");
					fclose($fh);
				}

				CCTM::set_flash(json_encode($validation_errors));
			}
		}
	}


} // End of class

/*EOF*/