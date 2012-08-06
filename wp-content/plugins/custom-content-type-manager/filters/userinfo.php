<?php
/**
 * @package CCTM_OutputFilter
 * 
 * Takes an integer representing a user id and returns user data.
 
         (
            [ID] => 1
            [user_login] => everett
            [user_pass] => $P$BNhPdXe8ZJ2miCuSG4IpaAb9BrXivy1
            [user_nicename] => everett
            [user_email] => everett@fireproofsocks.com
            [user_url] => 
            [user_registered] => 2011-10-10 15:09:29
            [user_activation_key] => 
            [user_status] => 0
            [display_name] => everett
        )
 
     [roles] => Array
        (
            [0] => administrator
        )
 
 */

class CCTM_userinfo extends CCTM_OutputFilter {

	/**
	 * Apply the filter.
	 *
	 * @param 	mixed 	user id or an array of them
	 * @param	mixed	optional formatting string
	 * @return mixed
	 */
	public function filter($input, $options=null) {
		$tpl = '<div class="cctm_userinfo" id="cctm_user_[+ID+]">[+user_nicename+]: [+user_email+]</div>';
		if (!empty($options)) {
			$tpl = $options;
		}

	
		$inputs = $this->to_array($input);
		$output = '';
		foreach ($inputs as $input) {
			$input = (int) $input;
			$user = get_user_by('id',$input);
			$output .= CCTM::parse($tpl, get_object_vars($user->data));
		}
		
		return $output;
	}


	/**
	 * @return string	a description of what the filter is and does.
	 */
	public function get_description() {
		return __("The <em>userinfo</em> retrieves a user object by its user ID. It accepts an optional a formatting template to format the user information.", CCTM_TXTDOMAIN);
	}


	/**
	 * Show the user how to use the filter inside a template file.
	 *
	 * @return string 	a code sample 
	 */
	public function get_example($fieldname='my_field',$fieldtype) {
		return '<?php print_custom_field("'.$fieldname.':userinfo"); ?>';
	}


	/**
	 * @return string	the human-readable name of the filter.
	 */
	public function get_name() {
		return __('User', CCTM_TXTDOMAIN);
	}

	/**
	 * @return string	the URL where the user can read more about the filter
	 */
	public function get_url() {
		return __('http://code.google.com/p/wordpress-custom-content-type-manager/wiki/userinfo_OutputFilter', CCTM_TXTDOMAIN);
	}
		
}
/*EOF*/