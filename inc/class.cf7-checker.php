<?php

/**
 * Class CF7_Mautic_CF7_Checker
 *
 * Check installed Contact Form 7.
 *
 * @since 0.1.0
 */
class CF7_Mautic_CF7_Checker extends CF7_Mautic_Environment_Checker {

	/**
	 * Check installed cf7.
	 *
	 * @return bool|WP_Error
	 */
	public function check() {

		if ( defined( 'WPCF7_PLUGIN' ) and 'contact-form-7/wp-contact-form-7.php' === plugin_basename( WPCF7_PLUGIN ) ) {
			return true;
		}

		$notice = __(
			'Oops, this plugin need Contact Form 7 Plugin. Please install & activate it first.',
			'cf7-mautic-extention'
		);

		return new WP_Error( 'cf7_mautic_check_cf7_installed', $notice );
	}
}
