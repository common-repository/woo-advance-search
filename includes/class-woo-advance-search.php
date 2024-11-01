<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Woo_Advance_Search
 * @subpackage Woo_Advance_Search/includes
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
 * @since      1.0.0
 * @package    Woo_Advance_Search
 * @subpackage Woo_Advance_Search/includes
 * @author     multidots <info@multidots.in>
 */
class Woo_Advance_Search {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Woo_Advance_Search_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
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

		$this->plugin_name = 'woo-advance-search';
		$this->version     = '2.0.3';

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
	 * - Woo_Advance_Search_Loader. Orchestrates the hooks of the plugin.
	 * - Woo_Advance_Search_i18n. Defines internationalization functionality.
	 * - Woo_Advance_Search_Admin. Defines all hooks for the admin area.
	 * - Woo_Advance_Search_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woo-advance-search-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woo-advance-search-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-woo-advance-search-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-woo-advance-search-public.php';
		/**
		 * The class responsible for defining all actions that occur in the admin static function
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/woo-advanced-search-admin-functions.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing side of the site.
		 *
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-woo-advance-search-user-feedback.php';
		
		$this->loader = new Woo_Advance_Search_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Woo_Advance_Search_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Woo_Advance_Search_i18n();

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

		$plugin_admin = new Woo_Advance_Search_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'asfw_custom_menu_free_version' );
		$this->loader->add_action( 'wp_ajax_Save_advance_search_settings_free', $plugin_admin, 'Save_advance_search_settings_free' );
		$this->loader->add_action( 'advance_search_for_woocommerce_about', $plugin_admin, 'advance_search_for_woocommerce_about' );
		$this->loader->add_action( 'admin_print_footer_scripts', $plugin_admin, 'advance_search_for_woocommerce_pointers_footer' );
		$this->loader->add_action( 'plugin_row_meta', $plugin_admin, 'advance_search_plugin_row_meta', 10, 2 );
		$this->loader->add_action( 'wp_ajax_woo_setting_ajax_checked', $plugin_admin, 'woo_setting_ajax_checked' );
		$this->loader->add_action( 'wp_ajax_nopriv_woo_setting_ajax_checked', $plugin_admin, 'woo_setting_ajax_checked' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'dotstore_advanced_seach_plugin_update_checker' );


	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Woo_Advance_Search_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'woocommerce_before_shop_loop', $plugin_public, 'woo_advance_search_html', 30 );

		$this->loader->add_action( 'woo-advance-search', $plugin_public, 'woo_advance_search_html' );
		$this->loader->add_action( 'woocommerce_product_query', $plugin_public, 'set_woo_advance_search_order_by', 10 );
		$search_by_sku_or_product = filter_input( INPUT_GET, 'search_by_sku_or_product', FILTER_SANITIZE_STRING );
		if ( ! empty ( $search_by_sku_or_product ) && 'product_sku' === $search_by_sku_or_product ) {
			$this->loader->add_filter( 'posts_search', $plugin_public, 'woo_advance_search_filter_search', 10, 2 );
		}
		$was = filter_input( INPUT_GET, 'was', FILTER_SANITIZE_STRING );
		if ( ! empty ( $was ) ) {
			$this->loader->add_filter( 'pre_get_posts', $plugin_public, 'woo_advance_search_filter_post' );

		}
		if(! is_admin()){
			$this->loader->add_shortcode( 'woo-advance-search', $plugin_public, 'woo_advanced_search_shortcode');
		}

		$setting_array            = setting_getters_option();
		$Advance_Search_Live_Ajax = ( ! empty( $setting_array['Woo_Advance_Search_Live_Ajax'] ) ) ? $setting_array['Woo_Advance_Search_Live_Ajax'] : '';
		if ( isset( $Advance_Search_Live_Ajax ) && 'Active' === $Advance_Search_Live_Ajax ) {
			$this->loader->add_action( 'wp_ajax_woo_advanced_search_live_ajax_data', $plugin_public, 'woo_advanced_search_live_ajax_data' );
			$this->loader->add_action( 'wp_ajax_nopriv_woo_advanced_search_live_ajax_data', $plugin_public, 'woo_advanced_search_live_ajax_data' );
		}



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
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Woo_Advance_Search_Loader    Orchestrates the hooks of the plugin.
	 * @since     1.0.0
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version() {
		return $this->version;
	}
}
