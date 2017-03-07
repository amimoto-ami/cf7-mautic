<?php

/**
 * Class CF7_Mautic_CF7_Checker
 *
 * Check installed Contact Form 7.
 *
 * @since 0.1.0
 */
class CF7_Mautic_CF7_Surveyor extends CF7_Mautic_Environment_Surveyor {

	/**
	 * Contact Form 7 basename.
	 *
	 * @var string
	 */
	private $cf7_plugin_basename = '';

	/**
	 * Check installed cf7.
	 *
	 * @return bool|WP_Error
	 */
	public function check() {

		if ( 'wp-contact-form-7.php' === basename( $this->cf7_plugin_basename ) ) {
			return true;
		}

		$notice = __(
			'Oops, this plugin need Contact Form 7 Plugin. Please install & activate it first.',
			'cf7-mautic-extention'
		);

		return new WP_Error( 'cf7_mautic_check_cf7_installed', $notice );
	}

	/**
	 * @param string $cf7_plugin_basename Contact Form 7 basename.
	 */
	public function set_cf7_plugin_basename( $cf7_plugin_basename ) {
		$this->cf7_plugin_basename = $cf7_plugin_basename;
	}
}
