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
}

add_filter( 'wpcf7_before_send_mail', 'send_cf7_to_mautic' );
function send_cf7_to_mautic( $cf7 ) {
	if ( $submission = WPCF7_Submission::get_instance() ) {
		$posted_data = $submission->get_posted_data();
		var_dump($posted_data);
	}
	var_dump(true);
	exit;
	return $cf7;
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
