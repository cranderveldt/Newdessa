<?php
/*------------------------------------------------------------------------------
Load up the validator and return the validator's options.

@param	string	validator
------------------------------------------------------------------------------*/
$validator = CCTM::get_value($_POST,'validator');

$V = CCTM::load_validator($validator);

if (!empty($V)){
	$validator_options = $V->get_options_html();
	printf('<div class="postbox"><h3 class="hndle"><span>%s</span></h3>
			<div class="inside">%s</div></div>', __('Options', CCTM_TXTDOMAIN), $validator_options);
}
else {
	 print '<pre>'.__('Error loading validator.', CCTM_TXTDOMAIN).'</pre>';
}

/*EOF*/