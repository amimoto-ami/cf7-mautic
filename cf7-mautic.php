<?php
/**
 * Plugin Name: CF7 Mautic Extention
 * Version: 0.0.4
 * Description: Simple extention to subscribe Contact Form 7's information to Mautic Form.
 * Author: hideokamoto
 * Author URI: http://wp-kyoto.net/
 * Plugin URI: https://github.com/megumiteam/cf7-mautic/
 * Text Domain: cf7-mautic-extention
 * Support PHP Version: 5.6
 * Required Plugin: contact-form-7
 * Domain Path: /languages
 *
 * @package Cf7-mautic-extention
 */


$cf7_mautic_plugin_info = get_file_data( __FILE__, array(
	'minimum_php' => 'Support PHP Version',
) );

define( 'CF7_MAUTIC_ROOT', __FILE__ );
define( 'CF7_MAUTIC_REQUIRE_PHP_VERSION', $cf7_mautic_plugin_info['minimum_php'] );


require_once 'inc/class.environment-surveyor.php';
require_once 'inc/class.php-surveyor.php';
require_once 'inc/class.cf7-surveyor.php';


/**
 * initialize
 */
function cf7_mautic_init() {
	require_once 'inc/class.cf7-mautic.php';
	require_once 'inc/class.admin.php';
	require_once 'inc/class.submit.php';
	$cf7_mautic = CF7_Mautic::get_instance();
	$cf7_mautic->init();
}

/**
 * Bootstrap
 */
function cf7_mautic_bootstrap() {

	$php_checker = new CF7_Mautic_PHP_Surveyor();
	$cf7_checker = new CF7_Mautic_CF7_Surveyor();
	if ( defined( 'WPCF7_PLUGIN' ) ) {
		$cf7_checker->set_cf7_plugin_basename( WPCF7_PLUGIN );
	}

	if ( is_wp_error( $php_checker->run() ) ) {
		return;
	}

	if ( is_wp_error( $cf7_checker->run() ) ) {
		return;
	}
	cf7_mautic_init();
}

add_action( 'plugins_loaded', 'cf7_mautic_bootstrap' );
