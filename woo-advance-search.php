<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.thedotstore.com/
 * @since             1.0.0
 * @package           Woo_Advance_Search
 *
 * @wordpress-plugin
 * Plugin Name:       Advance Search for WooCommerce
 * Plugin URI:        multidots.com
 * Description:       Advance Search for WooCommerce plugin allows you to add an advanced search option for WooCommerce Products. With this option you can search products by product tag and category. you can apply filter searcher like Title, order by date, price category and search order by ascending, Descending. You can customize search as per your requirement like enable and disable product category and tag. you can view searcher option by preview option. you can integrated searcher option in your site using a short-code on a page, as the widget in a sidebar or as template tag in a template.
 * Version:           2.0.3
 * Author:            Thedotstore
 * Author URI:        https://www.thedotstore.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo-advance-search
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}

if ( function_exists( 'was_fs' ) ) {
    was_fs()->set_basename( false, __FILE__ );
    return;
}


if ( !function_exists( 'was_fs' ) ) {
    // Create a helper function for easy SDK access.
    function was_fs()
    {
        global  $was_fs ;
        
        if ( !isset( $was_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $was_fs = fs_dynamic_init( array(
                'id'             => '5040',
                'slug'           => 'woo-advance-search',
                'type'           => 'plugin',
                'public_key'     => 'pk_6450d5b47ade3cb3cd60a218dcb6a',
                'is_premium'     => false,
                'premium_suffix' => 'Pro',
                'has_addons'     => false,
                'has_paid_plans' => true,
                'menu'           => array(
                'slug'    => 'advance-search',
                'contact' => false,
                'support' => false,
                'parent'  => array(
                'slug' => 'woocommerce',
            ),
            ),
                'is_live'        => true,
            ) );
        }
        
        return $was_fs;
    }
    
    // Init Freemius.
    was_fs();
    // Signal that SDK was initiated.
    do_action( 'was_fs_loaded' );
    was_fs()->add_action( 'after_uninstall', 'was_fs_uninstall_cleanup' );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woo-advance-search-activator.php
 */
function activate_woo_advance_search()
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-advance-search-activator.php';
    Woo_Advance_Search_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woo-advance-search-deactivator.php
 */
function deactivate_woo_advance_search()
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-advance-search-deactivator.php';
    Woo_Advance_Search_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woo_advance_search' );
register_deactivation_hook( __FILE__, 'deactivate_woo_advance_search' );
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woo-advance-search.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woo_advance_search()
{
    $plugin = new Woo_Advance_Search();
    $plugin->run();
}

/**
 * Check plugin requirement on plugins loaded, this plugin requires WooCommerce to be installed and active.
 *
 * @since    1.0.0
 */
function was_initialize_plugin()
{
    $wc_active = in_array( 'woocommerce/woocommerce.php', get_option( 'active_plugins' ), true );
    
    if ( current_user_can( 'activate_plugins' ) && true !== $wc_active ) {
        add_action( 'admin_notices', 'was_plugin_admin_notice' );
    } else {
        run_woo_advance_search();
    }

}

add_action( 'plugins_loaded', 'was_initialize_plugin' );
/**
 * Admin notices if woocommerce not active or install.
 */
function was_plugin_admin_notice()
{
    $was_plugin = 'Woo Advanced Search';
    $wc_plugin = 'WooCommerce';
    echo  '<div class="error"><p>' . sprintf( wp_kses_post( '%1$s is deactivated as it requires %2$s  to be installed and active.' ), '<strong>' . esc_html( $was_plugin ) . '</strong>', '<strong>' . esc_html( $wc_plugin ) . '</strong>' ) . '</p></div>' ;
}
