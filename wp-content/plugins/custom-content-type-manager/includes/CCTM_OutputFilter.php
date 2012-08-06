<?php
/**
 * @package CCTM_OutputFilter
 *
 * Abstract class for standardizing output filters.
 */
abstract class CCTM_OutputFilter {

	/**
	 * Most filters should be publicly visible, but some should only be used via direct invocation 
	 */
	public $show_in_menus = true;
	
	/**
	 * What kind of data can this filter accept? string|array|mixed
	 */
	public $input_type;
	
	/**
	 * What kind of data does this filter output? string|array|mixed 
	 */
	public $output_type;
	
	
	/**
	 * Apply the filter.
	 *
	 * @param 	mixed 	input
	 * @param	mixed	optional arguments
	 * @return mixed
	 */
	abstract public function filter($input, $options=null);


	/**
	 * @return string	a description of what the filter is and does.
	 */
	abstract public function get_description();


	/**
	 * Show the user how to use the filter inside a template file.
	 *
	 * @return string 	a code sample 
	 */
	abstract public function get_example($fieldname='my_field',$fieldtype);


	/**
	 * @return string	the human-readable name of the filter.
	 */
	abstract public function get_name();

	/**
	 * @return string	the URL where the user can read more about the filter
	 */
	abstract public function get_url();
	
	
	/**
	 * Converts an input to an array -- this handles strings, PHP arrays, and JSON arrays.
	 * This function is useful for any field that may need to handle both single and 
	 * "repeatable" inputs.
	 *
	 * @param	mixed	
	 * @return	array
	 */
	public function to_array($input) {
		
		if (empty($input)) {
			return array();
		}
		
		$the_array = array();
		
		if (is_array($input)) {
			return $input; // No JSON converting necessary: PHP array supplied.
		}
		else {
			// This will destroy the input if it's not json
			$output = json_decode($input, true);
			
			// See http://code.google.com/p/wordpress-custom-content-type-manager/issues/detail?id=121
			if ( !is_array($output) ) {
				if (empty($output) && !empty($input)) {
					return array($input);
				}
				else {
					return array($output);
				}			
			}
			else {
				return $output;
			}
		}
	}
		
}
/*EOF*/