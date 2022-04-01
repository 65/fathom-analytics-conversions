<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.fathomconversions.com
 * @since      0.5
 *
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      0.5
 * @package    Fathom_Analytics_Conversions
 * @subpackage Fathom_Analytics_Conversions/includes
 * @author     Duncan Isaksen-Loxton <duncan@sixfive.com.au>
 */
class Fathom_Analytics_Conversions {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Fathom_Analytics_Conversions_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'FATHOM_ANALYTICS_CONVERSIONS_VERSION' ) ) {
			$this->version = FATHOM_ANALYTICS_CONVERSIONS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'fathom-analytics-conversions';

        define( 'FAC4WP_OPTIONS', 'fac4wp-options' );
        define( 'FAC4WP_OPTION_API_KEY_CODE', 'fac-api-key-code' );

        define( 'FAC4WP_OPTION_INTEGRATE_WPCF7', 'integrate-wpcf7' );

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Fathom_Analytics_Conversions_Loader. Orchestrates the hooks of the plugin.
	 * - Fathom_Analytics_Conversions_i18n. Defines internationalization functionality.
	 * - Fathom_Analytics_Conversions_Admin. Defines all hooks for the admin area.
	 * - Fathom_Analytics_Conversions_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-fathom-analytics-conversions-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-fathom-analytics-conversions-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-fathom-analytics-conversions-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-fathom-analytics-conversions-public.php';

        /**
         * The core functions available on both the front-end and admin
         */
        require_once FAC4WP_PATH . '/includes/fac-core-functions.php';

		$this->loader = new Fathom_Analytics_Conversions_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Fathom_Analytics_Conversions_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Fathom_Analytics_Conversions_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Fathom_Analytics_Conversions_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

        define( 'FAC4WP_ADMINSLUG', 'fac4wp-settings' );
        define( 'FAC4WP_ADMIN_GROUP', 'fac4wp-admin-group' );

        define( 'FAC4WP_ADMIN_GROUP_GENERAL', 'fac4wp-admin-group-general' );
        define( 'FAC4WP_ADMIN_GROUP_API_KEY', 'fac4wp-admin-group-api-key' );
        define( 'FAC4WP_ADMIN_GROUP_INTEGRATION', 'fac4wp-admin-group-integration' );

        define( 'FAC4WP_PHASE_STABLE', 'fac4wp-phase-stable' );

        // admin settings/sections
        $this->loader->add_action( 'admin_init', $plugin_admin, 'fac4wp_admin_init' );
        // admin menu page
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'fac_admin_menu' );
        // admin notices
        $this->loader->add_action( 'admin_notices', $plugin_admin, 'fac_admin_notices' );
        // add meta box to CF7 form admin
        $this->loader->add_filter( 'wpcf7_editor_panels', $plugin_admin, 'fac_cf7_meta_box' );
        // save FAC CF7 options
        $this->loader->add_action( 'wpcf7_after_save', $plugin_admin, 'fac_cf7_save_options' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Fathom_Analytics_Conversions_Public( $this->get_plugin_name(), $this->get_version() );

		//$this->loader->add_action( 'wp_footer', $plugin_public, 'fac_wp_footer', 52, 0 );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

        // add hidden field to CF7 form - frontend
        $this->loader->add_filter( 'wpcf7_form_hidden_fields', $plugin_public, 'fac_cf7_hidden_fields' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Fathom_Analytics_Conversions_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
