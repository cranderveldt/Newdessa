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
 * To run these tests, simply navigate to this file in your browser, e.g. 
 * http://cctm:8888/wp-content/plugins/custom-content-type-manager/tests/SummarizePostsUnitTests.php
 *
 * Or execute them via php on the command line:
 *	php /full/path/to/SummarizePostsUnitTests.php
 *
 * @package SummarizePosts
 * @author Everett Griffiths
 * @url http://fireproofsocks.com/
 */


require_once(dirname(__FILE__) . '/simpletest/autorun.php');
require_once(dirname(__FILE__) . '/../../../../wp-config.php');
class SummarizePostsUnitTests extends UnitTestCase {


//	function setUp() { }


	function __construct() {
		parent::__construct('Summarize Posts Unit Tests');
	}
	
	// Make sure we got WP loaded up
	function testWP() {
		$this->assertTrue(defined('CCTM_PATH'));
	}
	
	// SummarizePosts loaded
	function testSP() {
		$this->assertTrue(class_exists('SummarizePosts'));
		$this->assertTrue(class_exists('GetPostsQuery'));
	}

	// Get our object
	function testInit() {
		$Q = new GetPostsQuery();
		$this->assertTrue(is_object($Q));
	}
	
	
	//------------------------------------------------------------------------------
	function test_count_posts1() {
		$Q = new GetPostsQuery();
		$args = array();
		$args['post_type'] = 'page';
		
		$cnt = $Q->count_posts($args);
		$this->assertTrue($cnt == 6);
	}
	
	function test_count_posts2() {
		$Q = new GetPostsQuery();
		$args = array();
		$args['ID'] = 1;
		$cnt = $Q->count_posts($args);
		$this->assertTrue($cnt == 1);
	}	

	function test_count_posts3() {
		$Q = new GetPostsQuery();
		$args = array();
		$args['include'] = 1;
		$cnt = $Q->count_posts($args);
		$this->assertTrue($cnt == 1);
	}

	function test_count_posts4() {
		$Q = new GetPostsQuery();
		$args = array();
		$args['include'] = '1,5,7';
		$cnt = $Q->count_posts($args);
//		print $Q->debug();
//		exit;
		$this->assertTrue($cnt == 3);
	}


	//------------------------------------------------------------------------------
	// Test the # of tagged posts
	function test_get_posts1() {
		$Q = new GetPostsQuery();
		$args = array();
		$args['taxonomy'] = 'post_tag';
		$args['taxonomy_slug'] = 'tag1';
		$cnt = $Q->count_posts($args);
		$this->assertTrue($cnt == 3);
	}

	// Make sure the taxonomy argument is ignored unless the taxonomy_slug OR taxonomy_term accompanies it.
	function test_get_posts2() {
		$Q = new GetPostsQuery();
		$args = array();
		$cnt1 = $Q->count_posts($args);

		$Q = new GetPostsQuery();
		$args = array();
		$args['taxonomy'] = 'post_tag';
		$cnt2 = $Q->count_posts($args);


		$this->assertTrue($cnt1 == $cnt2);
	}

	// Test for a non-existant tag
	function test_get_posts3() {
		$Q = new GetPostsQuery();
		$args = array();
		$args['taxonomy'] = 'does_not_exist';
		$args['taxonomy_slug'] = 'does_not_exist';
		$cnt = $Q->count_posts($args);
		$warnings = strip_tags($Q->get_warnings());
		$this->assertTrue(strpos($warnings, 'Taxonomy does not exist: does_not_exist'));
	}

	// Order By
	
	// Sort on Custom column
	
	// complex sorting

}
 
/*EOF*/