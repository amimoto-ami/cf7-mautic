<?php

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
