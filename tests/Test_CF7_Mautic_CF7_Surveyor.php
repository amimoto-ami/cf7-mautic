<?php

/**
 * Created by PhpStorm.
 * User: torounit
 * Date: 2017/03/08
 * Time: 3:01
 */
class Test_CF7_Mautic_CF7_Surveyor extends PHPUnit_Framework_TestCase {

	public function test_check_failure__when_not_installed_cf7() {
		$checker = new CF7_Mautic_CF7_Surveyor();
		$this->assertTrue( is_wp_error( $checker->run() ) );
	}

	public function test_check_pass_when_installed_cf7() {

		$checker = new CF7_Mautic_CF7_Surveyor();
		if ( defined( 'WPCF7_PLUGIN_BASENAME' ) ) {
			$checker->set_cf7_plugin_basename( WPCF7_PLUGIN_BASENAME );
		}
		$this->assertTrue( $checker->run() );
	}

}
