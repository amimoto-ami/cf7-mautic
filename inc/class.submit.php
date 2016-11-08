<?php
/**
 * POST to Mautic
 *
 * @package CF7_Mautic
 * @author hideokamoto
 * @since 0.0.1
 **/

/**
 * POST to Mautic Class
 *
 * @class CF7_Mautic_Submit
 * @since 0.0.1
 */
class CF7_Mautic_Submit extends CF7_Mautic {
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
	 * Constructer
	 * Set text domain on class
	 *
	 * @since 0.0.1
	 */
	private function __construct() {
		self::$text_domain = CF7_Mautic::text_domain();
	}

	/**
	 * Get Instance Class
	 *
	 * @return CF7_Mautic_Submit
	 * @since 0.0.1
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			$c = __CLASS__;
			self::$instance = new $c();
		}
		return self::$instance;
	}

	/**
	 * Subscribe form param to Mautic
	 *
	 * @param object $cf7 Contact Form 7's POST Object
	 * @return object
	 * @since 0.0.1
	 */
	public function send_cf7_to_mautic( $cf7 ) {
		$query = $this->_create_query();
		if ( $query ) {
			$this->_subscribe( $query );
		}
		return $cf7;
	}

	/**
	 * Create query form Contact Form 7's post params
	 *
	 * @return array
	 * @since 0.0.1
	 */
	private function _create_query() {
		$query = array();
		if ( $submission = WPCF7_Submission::get_instance() ) {
			$query = $submission->get_posted_data();
		}
		return apply_filters( 'CF7_Mautic_query_mapping', $query );
	}

	/**
	 * Add Mautic Form ID
	 *
	 * @param array $query POST query
	 * @return array
	 * @since 0.0.1
	 */
	private function _add_mautic_form_id( $query ) {
		$cf7_form_id = $query['_wpcf7'];
		$settings = get_option( 'cf7_mautic_settings' );
		$key = array_search( $cf7_form_id, $settings['cf7_id'] );
		$query['formId'] = $settings['form_id'][ $key ];
		return $query;
	}

	/**
	 * POST to Mautic
	 *
	 * @param array $query POST query
	 * @since 0.0.1
	 */
	private function _subscribe( $query ) {
		$ip = $this->_get_ip();
		if ( ! isset( $query['return'] ) ) {
			$query['return'] = get_home_url();
		}
		$settings = get_option( 'cf7_mautic_settings' );
		$query = $this->_add_mautic_form_id( $query );
		$query = $this->_remove_hyphen( $query );
		$data = array(
			'mauticform' => $query,
		);
		$url = path_join( $settings['url'], "form/submit?formId={$settings['form_id']}" );
		$response = wp_remote_post(
			$url,
			array(
				'method' => 'POST',
				'timeout' => 45,
				'headers' => array(
					'X-Forwarded-For' => $ip,
				),
				'body' => $data,
				'cookies' => array()
			)
		);
		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			error_log( "CF7_Mautic Error: $error_message" );
			error_log( "      posted url: $url" );
		}
	}

	/**
	 * Remove hyphen to fields
	 *
	 * @return string
	 * @since 0.0.3
	 */
	private function _remove_hyphen( $queries ) {
		$return = array();
		foreach ( $queries as $label => $value ) {
			$label = str_replace( '-', '', $label );
			$return[ $label ] = $value;
		}
		return $return;
	}

	/**
	 * Get User's IP
	 *
	 * @return string
	 * @since 0.0.1
	 */
	private function _get_ip() {
		$ip_list = [
            'REMOTE_ADDR',
			'HTTP_CLIENT_IP',
			'HTTP_X_FORWARDED_FOR',
			'HTTP_X_FORWARDED',
			'HTTP_X_CLUSTER_CLIENT_IP',
			'HTTP_FORWARDED_FOR',
			'HTTP_FORWARDED'
		];
		foreach ( $ip_list as $key ) {
			if ( ! isset( $_SERVER[ $key ] ) ) {
				continue;
			}
			$ip = esc_attr( $_SERVER[ $key ] );
			if ( ! strpos( $ip, ',' ) ) {
				$ips =  explode( ',', $ip );
				foreach ( $ips as &$val ) {
					$val = trim( $val );
				}
				$ip = end ( $ips );
			}
			$ip = trim( $ip );
			break;
		}
		return $ip;
	}
}
