<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Fired during plugin activation
 *
 * @link       http://www.multidots.com/
 * @since      1.0.0
 *
 * @package    Woo_Advance_Search
 * @subpackage Woo_Advance_Search/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Woo_Advance_Search
 * @subpackage Woo_Advance_Search/includes
 * @author     multidots <info@multidots.in>
 */
class Woo_Advance_Search_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() { 
		
		if( !in_array( 'woocommerce/woocommerce.php',apply_filters('active_plugins',get_option('active_plugins')),true) && !is_plugin_active_for_network( 'woocommerce/woocommerce.php' )   ) {
			wp_die( "<strong>Woo Advance Search</strong> Plugin requires <strong>WooCommerce</strong> <a href='".esc_url(get_admin_url(null, 'plugins.php'))."'>Plugins page</a>." );
		}
		set_transient( '_advance_search_for_woocommerce_welcome_screen', true, 30 );

	}

}
