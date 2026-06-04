<?php

/**
 * The CF7-specific functionality of the plugin.
 *
 * @link       https://www.fathomconversions.com
 * @since      1.0
 *
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/wpcf7
 */

/**
 * The cf7-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the wpcf7-specific stylesheet and JavaScript.
 *
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/wpcf7
 * @author     Duncan Isaksen-Loxton <duncan@sixfive.io>
 */
class Fathom_Analytics_Conversions_WPCF7 {

	/**
	 * The ID of this plugin.
	 *
	 * @var      string $plugin_name The ID of this plugin.
	 * @since    1.0.0
	 * @access   private
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @var      string $version The current version of this plugin.
	 * @since    1.0.0
	 * @access   private
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		// Add form attribute.
		add_filter( 'wpcf7_form_additional_atts', [
			$this,
			'fac_wpcf7_form_additional_atts',
		] );

		// Add js to track the form submission.
		//add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'wp_footer', [ $this, 'enqueue_scripts' ] );

	}

	/**
	 * Add custom form attribute - form title.
	 *
	 * @param array $atts Array of attributes.
	 *
	 * @since    1.2
	 */
	public function fac_wpcf7_form_additional_atts( $atts ) {
		if ( function_exists( 'wpcf7_get_current_contact_form' ) ) {
			$form              = wpcf7_get_current_contact_form();
			$atts['data-name'] = $form->title();
		}

		return $atts;
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		if ( FAC_Options::get( FAC4WP_OPTION_INTEGRATE_WPCF7 ) && is_fac_fathom_analytic_active() ) {
			if ( ! fac_fathom_is_excluded_from_tracking() ) { // Track visits by administrators!

				$js  = 'window.addEventListener("load", (event) => {' . "\n\t";
				$js .= 'document.addEventListener( "wpcf7mailsent", function( e ) {' . "\n\t\t";
				$js .= 'let form_name = e.target.dataset.name;' . "\n\t\t";
				$js .= 'fathom.trackEvent(form_name +" ["+e.detail.contactFormId+"]");' . "\n\t";
				$js .= '}, false );' . "\n\t";
				$js .= '});';

				wp_print_inline_script_tag( $js, [
					'id'                      => 'fac-wpcf7',
					'data-cfasync'            => 'false',
					'data-pagespeed-no-defer' => true,
				] );
			}
		}
	}

}
