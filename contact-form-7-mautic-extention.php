<?php
/**
 * Plugin Name: Contact Form 7 Mautic Extention
 * Version: 0.0.1
 * Description: Send Contacf Form 7 form data to Mautic lead list.
 * Author: hideokamoto
 * Author URI: http://wp-kyoto.net/
 * Plugin URI: PLUGIN SITE HERE
 * Text Domain: contact-form-7-mautic-extention
 * Domain Path: /languages
 * @package Contact-form-7-mautic-extention
 */

/**
 * If Contact Form 7 is deactivated,
 * This Plugin doesn't work
 **/
if ( ! mautic_is_activate_cf7() ) {
	return;
} else {
	define( 'CF7_Mautic_ROOT', __FILE__ );
	require_once 'inc/class.admin.php';
	require_once 'inc/class.submit.php';
	$CF7_Mautic = CF7_Mautic::get_instance();
	$CF7_Mautic->init();
}

class CF7_Mautic {
	private $Base;
	private static $instance;
	private static $text_domain;

	private function __construct() {
	}

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			$c = __CLASS__;
			self::$instance = new $c();
		}
		return self::$instance;
	}

	/**
	 * Get Plugin text_domain
	 *
	 * @return string
	 * @since 0.0.1
	 */
	public static function text_domain() {
		static $text_domain;

		if ( ! $text_domain ) {
			$data = get_file_data( CF7_Mautic_ROOT , array( 'text_domain' => 'Text Domain' ) );
			$text_domain = $data['text_domain'];
		}
		return $text_domain;
	}

	public function init() {
		$admin = CF7_Mautic_Admin::get_instance();
		add_action( 'admin_menu', array( $admin, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $admin, 'settings_init' ) );
		$submit = CF7_Mautic_Submit::get_instance();
		add_filter( 'wpcf7_before_send_mail', array( $submit, 'send_cf7_to_mautic' ) );
	}
}

/**
 * Check Contact form 7 Plugin status
 *
 * @since 0.0.1
 * @return bool
 */
function mautic_is_activate_cf7() {
	$activePlugins = get_option('active_plugins');
	$plugin = 'contact-form-7/wp-contact-form-7.php';
	if ( ! array_search( $plugin, $activePlugins ) && file_exists( WP_PLUGIN_DIR. '/'. $plugin ) ) {
		return false;
	} else {
		return true;
	}
}
