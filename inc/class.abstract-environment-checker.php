<?php

abstract class CF7_Mautic_Environment_Checker {

	private $message = '';

	/**
	 * @var bool|WP_Error
	 */
	private $result = false;

	public function run() {
		$this->result = $this->check();
		add_action( 'admin_notices', array( $this, 'admin_notices') );
	}

	/**
	 * @return bool|WP_Error
	 */
	public function get_result() {
		return $this->result;
	}

	/**
	 * @return bool|WP_Error
	 */
	abstract function check();

	/**
	 * Display notice on dashboard.
	 */
	public function admin_notices() {
		if ( is_wp_error( $this->result ) ) {
			$message = sprintf(
				__( '[CF7 Mautic Extention] %s', 'cf7-mautic-extention' ),
				esc_html(  $this->result->get_error_message() )
			);

			echo sprintf( '<div class="error"><p>%s</p></div>', esc_html( $message ) );
		}
	}
}
