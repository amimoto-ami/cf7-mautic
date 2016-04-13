<?php
/**
 * Show Admin Panel Class
 *
 * @package CF7_Mautic
 * @author hideokamoto
 * @since 0.0.1
 **/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Admin Page Class
 *
 * @class CF7_Mautic_Admin
 * @since 0.0.1
 */
class CF7_Mautic_Admin extends CF7_Mautic {
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
	 * WP_Options Param ( cf7_mautic_settings )
	 * @access private
	 */
	private $cf7_mautic_settings = array();

	/**
	 * Constructer
	 * Set text domain on class
	 *
	 * @since 0.0.1
	 */
	private function __construct() {
		self::$text_domain = CF7_Mautic::text_domain();
		$this->cf7_mautic_settings = get_option( 'cf7_mautic_settings' );
	}

	/**
	 * Get Instance Class
	 *
	 * @return CF7_Mautic_Admin
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
	 * Routing function
	 *
	 * @since 0.0.1
	 */
	public function settings_init() {
		$this->_register_admin_panels();
		if ( empty( $_POST ) ) {
			return;
		}
	}

	/**
	 * Register admin setting field
	 *
	 * @since 0.0.1
	 */
	private function _register_admin_panels() {
		register_setting( 'CF7_Mautic', 'cf7_mautic_settings' );
		add_settings_section(
			'cf7_mautic_RelatedScore_settings',
			__( 'Settings', self::$text_domain ),
			array( $this, 'cf7_mautic_settings_url_section_callback' ),
			'CF7_Mautic'
		);
		add_settings_field(
			'url',
			__( 'Mautic URL', self::$text_domain ),
			array( $this, 'mautic_url_render' ),
			'CF7_Mautic',
			'cf7_mautic_RelatedScore_settings'
		);
		add_settings_field(
			'form_id',
			__( 'Mautic Form ID', self::$text_domain ),
			array( $this, 'mautic_form_id_render' ),
			'CF7_Mautic',
			'cf7_mautic_RelatedScore_settings'
		);
	}

	/**
	 * echo input field( Mautic Form ID)
	 *
	 * @since 0.0.1
	 */
	public function mautic_form_id_render() {
		if ( ! isset( $this->cf7_mautic_settings['form_id'] ) ) {
			$this->cf7_mautic_settings['form_id'] = '';
		}
		$cf7_args = array(
			'posts_per_page' => -1,
			'orderby' => 'title',
			'order' => 'ASC',
			'offset' => 0,
		);
		$forms = WPCF7_ContactForm::find( $cf7_args );
		$html  = '';
		$html .= '<table>';
		$html .= '<thead>';
		$html .= '<th>'. __( 'No.', self::$text_domain ). '</th>';
		$html .= '<th>'. __( 'Form Name', self::$text_domain ). '<br/>';
		$html .= __( '( Contact Form 7 )', self::$text_domain ). '</th>';
		$html .= '<th>'. __( 'Form ID', self::$text_domain ). '<br/>';
		$html .= __( '( Mautic )', self::$text_domain ). '</th>';
		$html .= '</thead><tbody>';
		for ( $i = 0 ; $i < 5; $i++ ) {
			$html .= '<tr>';
			$html .= '<th>'. __( 'Mapping-', self::$text_domain ). $i. '</th>';
			$html .= '<td>'. $this->_get_cf7_form_select_box ( $forms, $i ). '</td>';
			$html .= "<td><input type='text' name='cf7_mautic_settings[form_id][{$i}]' value='". $this->cf7_mautic_settings['form_id']. "'></td>";
			$html .= '</tr>';
		}
		$html .= '</tbody></table>';
		echo $html;
	}

	private function _get_cf7_form_select_box ( $forms, $i ) {
		$html  = '';
		$html .= "<select name='cf7_mautic_settings[cf7_id][{$i}]'>";
		foreach ( $forms as $form ) {
			$html .= "<option value='". $form->id(). "'>". $form->title(). '</option>';
		}
		$html .= '</select>';
		return $html;
	}

	/**
	 * echo input field( Mautic URL)
	 *
	 * @since 0.0.1
	 */
	public function mautic_url_render() {
		if ( ! isset( $this->cf7_mautic_settings['url'] ) ) {
			$this->cf7_mautic_settings['url'] = '';
		}
		echo "<input type='url' name='cf7_mautic_settings[url]' value='". $this->cf7_mautic_settings['url']. "' style='width:100%;'>";
	}

	/**
	 * echo Search Score Field Dcf7_mauticiption
	 *
	 * @since 0.0.1
	 */
	public function cf7_mautic_settings_url_section_callback() {
		echo __( 'Set Mautic Information.', self::$text_domain );
	}

	/**
	 * echo form area
	 *
	 * @since 0.0.1
	 */
	public function cf7_mautic_options() {
		echo '<h2>CF7 Mautic</h2>';
		echo "<form action='options.php' method='post'>";
		settings_fields( 'CF7_Mautic' );
		do_settings_sections( 'CF7_Mautic' );
		submit_button();
		echo '</form>';
	}

	/**
	 * Register Admin Option Page
	 *
	 * @since 0.0.1
	 */
	public function add_admin_menu() {
		add_options_page( 'CF7 Mautic', 'CF7 Mautic', 'manage_options', 'cf7_mautic', array( $this, 'cf7_mautic_options' ) );
	}

}
