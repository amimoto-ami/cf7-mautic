<?php

class CF7_Mautic_CF7_Checker extends CF7_Mautic_Environment_Checker {

	public function check() {
		$active_plugins = get_option( 'active_plugins' );
		$plugin = 'contact-form-7/wp-contact-form-7.php';
		if ( false !== array_search( $plugin, $active_plugins ) and file_exists( WP_PLUGIN_DIR . '/' . $plugin ) ) {
			return true;
		}

		$notice = __(
			'Oops, this plugin need Contact Form 7 Plugin. Please install & activate it first.',
			'cf7-mautic-extention'
		);

		return new WP_Error( 'cf7_mautic_check_cf7_installed', $notice );
	}
}
