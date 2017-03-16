<?php

/**
 * Created by PhpStorm.
 * User: torounit
 * Date: 2017/03/08
 * Time: 2:31
 */
class Test_CF7_Mautic_PHP_Checker extends PHPUnit_Framework_TestCase {

	public function test_check_failure_older_php() {
		$php_checker = new CF7_Mautic_PHP_Surveyor();
		$php_checker->set_current_php_version( '5.5' );
		$this->assertTrue( is_wp_error( $php_checker->check() ) );
	}

	public function test_check_pass_newer_php() {
		$php_checker = new CF7_Mautic_PHP_Surveyor();
		$php_checker->set_current_php_version( '5.6' );
		$this->assertTrue( $php_checker->check() );
	}
}
