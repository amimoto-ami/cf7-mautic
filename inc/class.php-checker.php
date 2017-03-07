<?php

class CF7_Mautic_PHP_Checker extends CF7_Mautic_Environment_Checker {

	public function check() {
		// TODO: Implement check() method.
		if ( version_compare( phpversion(), CF7_MAUTIC_REQUIRE_PHP_VERSION ) > 0 )  {
			return true;
		}

		$notice = sprintf(
			__(
				'Oops, this plugin will soon require PHP %1$s or higher. Your PHP version is %2$s',
				'cf7-mautic-extention'
			),
			CF7_MAUTIC_REQUIRE_PHP_VERSION,
			phpversion()
		);

		return new WP_Error( 'cf7_mautic_check_php_version', $notice );
	}
}
