<?php
/**
 * Plugin Name: CF7 Mautic Extention
 * Version: 0.0.3
 * Description: Simple extention to subscribe Contact Form 7's information to Mautic Form.
 * Author: hideokamoto
 * Author URI: http://wp-kyoto.net/
 * Plugin URI: https://github.com/megumiteam/cf7-mautic/
 * Text Domain: cf7-mautic-extention
 * Support PHP Version: 5.6
 * Required Plugin: contact-form-7
 * Domain Path: /languages
 * @package Cf7-mautic-extention
 */

/**
 * If Contact Form 7 is deactivated,
 * This Plugin doesn't work
 **/
$this_plugin_info = get_file_data( __FILE__, array(
	'minimum_php' => 'Support PHP Version',
));
if ( ! mautic_is_activate_cf7() ) {
	$plugin_notice = __(
		'Oops, this plugin need Contact Form 7 Plugin. Please install & activate it first.',
		'cf7-mautic-extention'
	);
	register_activation_hook(
		__FILE__,
		create_function(
			'',
			"deactivate_plugins('" . plugin_basename( __FILE__ ) . "'); wp_die('{$plugin_notice}');"
		)
	);
	return;
} elseif ( version_compare( phpversion(), $this_plugin_info['minimum_php'] ) <= 0 ) {
	$plugin_notice = sprintf(
		__(
			'Oops, this plugin will soon require PHP %1$s or higher. Your PHP version is %2$s',
			'cf7-mautic-extention'
		),
		$this_plugin_info['minimum_php'],
		phpversion()
	);
	register_activation_hook(
		__FILE__,
		create_function(
			'',
			"deactivate_plugins('" . plugin_basename( __FILE__ ) . "'); wp_die('{$plugin_notice}');"
		)
	);
	return;
} else {
	define( 'CF7_MAUTIC_ROOT', __FILE__ );
	require_once 'inc/class.admin.php';
	require_once 'inc/class.submit.php';
	$cf7_mautic = CF7_Mautic::get_instance();
	$cf7_mautic->init();
}

/**
 * Base Class
 *
 * @class CF7_Mautic
 * @since 0.0.1
 */
class CF7_Mautic {
	/**
	 * Instance Class
	 * @access private
	 */
	private static $instance;

	/**
	 * text domain
	 * @access private
	 */
	private static $text_domain;

	/**
	 * Get Instance
	 *
	 * @since 0.0.1
	 * @return CF7_Mautic
	 */
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
			$data = get_file_data( CF7_MAUTIC_ROOT , array( 'text_domain' => 'Text Domain' ) );
			$text_domain = $data['text_domain'];
		}
		return $text_domain;
	}

	/**
	 * Initilize Plugin Settings
	 *
	 * @since 0.0.1
	 */
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
	$active_plugins = get_option( 'active_plugins' );
	$plugin = 'contact-form-7/wp-contact-form-7.php';
	if ( false === array_search( $plugin, $active_plugins ) || ! file_exists( WP_PLUGIN_DIR . '/' . $plugin ) ) {
		return false;
	} else {
		return true;
	}
}
