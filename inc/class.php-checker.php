<?php

/**
 * Class CF7_Mautic_PHP_Checker
 *
 * @since 0.1.0
 */
class CF7_Mautic_PHP_Checker extends CF7_Mautic_Environment_Checker {

	/**
	 * Support PHP ver.
	 *
	 * @var string
	 */
	private $support_php_version = '5.6';

	/**
	 * Current PHP ver.
	 *
	 * @var string
	 */
	private $current_php_version = '';

	/**
	 * CF7_Mautic_PHP_Checker constructor.
	 */
	public function __construct() {

		if ( defined( 'CF7_MAUTIC_REQUIRE_PHP_VERSION' ) ) {
			$this->set_support_php_version( CF7_MAUTIC_REQUIRE_PHP_VERSION );
		}

		$this->set_current_php_version( phpversion() );
	}

	/**
	 * Setter for current_php_version.
	 *
	 * @param string $current_php_version php version.
	 */
	public function set_current_php_version( $current_php_version ) {
		$this->current_php_version = $current_php_version;
	}


	/**
	 * Setter for support_php_version.
	 *
	 * @param string $support_php_version php version.
	 */
	public function set_support_php_version( $support_php_version ) {
		$this->support_php_version = $support_php_version;
	}

	/**
	 * Check version.
	 *
	 * @return bool|WP_Error
	 */
	public function check() {
		if ( version_compare( $this->current_php_version, $this->support_php_version ) >= 0 ) {
			return true;
		}

		$notice = sprintf(
			__(
				'Oops, this plugin will soon require PHP %1$s or higher. Your PHP version is %2$s',
				'cf7-mautic-extention'
			),
			CF7_MAUTIC_REQUIRE_PHP_VERSION,
			$this->current_php_version
		);

		return new WP_Error( 'cf7_mautic_check_php_version', $notice );
	}
}
