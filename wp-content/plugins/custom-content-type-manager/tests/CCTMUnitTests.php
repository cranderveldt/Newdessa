<?php
/**
 * FOR THE DEVELOPER ONLY!!!
 *
 * This class contains unit tests using the SimpleTest framework: http://simpletest.org/
 * 
 * BEFORE YOU RUN TESTS
 *
 * These tests are meant to run in a controlled environment with a specific version of 
 * WordPress, with a specific theme, and with specific plugins enabled or disabled.
 * A dump of the database used is included as reference for all tests.
 *
 * RUNNING TESTS
 *
 *
 * http://codex.wordpress.org/Automated_Testing
 * 
 * @package CCTM
 * @author Everett Griffiths
 * @url http://fireproofsocks.com/
 */

require_once(dirname(__FILE__) . '/simpletest/autorun.php');
require_once(dirname(__FILE__) . '/../../../../wp-config.php');
//require_once(CCTM_PATH .'/includes/CCTM_Validator.php');
//require_once(CCTM_PATH .'/validators/CCTM_FormElement.php');

//require_once(CCTM_PATH .'/includes/CCTM_FormElement.php');

class CCTMUnitTests extends UnitTestCase {
	
	
	/**
	 * Test whether a regular category page displays posts and 
	 * any pages from custom post-types that have been categorized
	 */

/*
    function testCategories() {
    	$page = file_get_contents('http://cctm:8888/category/uncategorized/');
    	
    	print $page;
    }
*/
	// Archives
	// Categories
	// tags
	
/*
    function testTags() {
    	$page = file_get_contents('http://cctm:8888/category/uncategorized/');
    	
    	print $page;
    }
*/

	/**
	 * Make sure we didn't accidentally bundle software that's under the 
	 * Creative Commons License.
	 */
/*
	function testNoCCL() {
	
	}
*/


	/**
	 * Change post_type name
	 */

	/**
	 * Test RSS feed
	 */
	function testRSS() {
		$xml = file_get_contents('http://cctm:8888/feed/');
		
		$this->assertTrue($xml);
	}
	
	//------------------------------------------------------------------------------
	// Test Global Settings
	//------------------------------------------------------------------------------
	// Delete Posts
	// Delete Custom Fields
	// Save Empty Fields
	// Show Pages in RSS Feed
	
	// 
	
	//------------------------------------------------------------------------------
	// Test Validators
	//------------------------------------------------------------------------------
	function testEmail() {
		$V = CCTM::load_object('emailaddress','validator');
		
		$email = 'notan-emailaddress.';
		
		$V->validate($email);		
		$this->assertFalse(empty($V->error_msg));
	}

	function testEmail2() {
		$V = CCTM::load_object('emailaddress','validator');
		$email = 'someone@yahoo.com';
		$V->validate($email);

		$this->assertTrue(empty($V->error_msg));
	}

	function testEmail3() {
		$V = CCTM::load_object('emailaddress','validator');
		$email = 'payer@player-hater.com';
		$V->validate($email);
		$this->assertTrue(empty($V->error_msg));
	}

	//------------------------------------------------------------------------------
	function testNumber() {
		$V = CCTM::load_object('number','validator');
		$number = 'asdf';
		$V->validate($number);
		$this->assertFalse(empty($V->error_msg));
	}


	function testNumber3() {
		$V = CCTM::load_object('number','validator');
		$number = '123';
		$V->validate($number);
		$this->assertTrue(empty($V->error_msg));
	}

	function testNumber4() {
		$V = CCTM::load_object('number','validator');
		$V->min = 4;
		$V->max = 6;
		$number = '10';
		$V->validate($number);
		$this->assertFalse(empty($V->error_msg));
	}

	function testNumber5() {
		$V = CCTM::load_object('number','validator');
		$V->min = 4;
		$V->max = 6;
		$number = '5';
		$V->validate($number);
		$this->assertTrue(empty($V->error_msg));
	}

	function testNumber6() {
		$V = CCTM::load_object('number','validator');
		$V->allow_negative = 1;
		$V->max = 6;
		$number = '-5';
		$V->validate($number);
		$this->assertTrue(empty($V->error_msg));
	}

	function testNumber7() {
		$V = CCTM::load_object('number','validator');
		$V->allow_negative = 0;
		$V->max = 6;
		$number = '-5';
		$V->validate($number);
		$this->assertFalse(empty($V->error_msg));
	}


}
 
/*EOF*/