<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The public-facing functionality of the plugin.
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @link       http://www.multidots.com/
 * @since      1.0.0
 * @package    Woo_Advance_Search
 * @subpackage Woo_Advance_Search/public
 * @author     multidots <info@multidots.in>
 */
class Woo_Advance_Search_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 *
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Advance_Search_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Advance_Search_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woo-advance-search-public.css', array(), $this->version, 'all' );
		wp_enqueue_script( 'jquery-ui' );
		$suffix       = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$localize_arr = array(
			'adminajaxjsurl' => admin_url( 'admin-ajax.php' ),
		);

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woo-advance-search-public' . $suffix . '.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'adminajaxjs', $localize_arr );
		$setting_array_options = setting_getters_option();
		$wp_custom_css         = $setting_array_options['Woo_Advance_Custom_Css'];
		wp_add_inline_style( 'woo-advance-search', $wp_custom_css );
	}

	/**
	 * Function to return shortcode of HTML for search functionality
	 */
	public function woo_advance_search_html() {
		echo wp_kses( get_search_shortcode_html(), was_get_allow_html_in_escaping() );
	}

	public function woo_advanced_search_shortcode() {
		return get_search_shortcode_html();
	}

	/**
	 * @param $query
	 * Function to return search filter in posts
	 */
	public function woo_advance_search_filter_post( $query ) {
		$Product_search_by_sku = get_option( 'Woo_Advance_Search_Product_Sku_Enable' );

		if ( ! is_admin() ) {

			if ( ! $query->is_main_query() ) {
				return;
			}

			$query_args   = array();
			$product_cats = filter_input( INPUT_GET, 'product_cat', FILTER_SANITIZE_STRING );
			$product_tags = filter_input( INPUT_GET, 'product_tag', FILTER_SANITIZE_STRING );
			$product_cat  = ! empty( $product_cats ) ? $product_cats : '';
			$product_tag  = ! empty( $product_tags ) ? $product_tags : '';

			// Basic arguments
			$query_args['post_type']   = 'product';
			$query_args['post_status'] = 'publish';

			// Pagination
			$paged               = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
			$query_args['paged'] = $paged;
			$search_keyword      = filter_input( INPUT_GET, 's', FILTER_SANITIZE_STRING );
			if ( $search_keyword && strlen( $search_keyword ) > 0 ) {
				$query_args['s'] = $search_keyword;
			} else {
				$query_args['s'] = '';
			}

			if ( ! empty( $Product_search_by_sku ) && 'All' === $Product_search_by_sku ) {
				$query_args['meta_query']['relation'] = 'OR';
				$query_args['meta_query'][]           = array(
					'key'     => '_sku',
					'value'   => $query_args['s'],
					'compare' => 'LIKE',
				);
			}

			// Check selected taxonomies
			if ( ! empty( $product_cat ) && 'all' !== $product_cat && '' !== $product_cat ) {
				$query_args['tax_query']['relation'] = 'AND';
				$query_args['tax_query'][]           = array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => $product_cat,
				);
			}

			// Check selected tag
			if ( ! empty( $product_tag ) && 'all' !== $product_tag && '' !== $product_tag ) {
				$query_args['tax_query']['relation'] = 'AND';
				$query_args['tax_query'][]           = array(
					'taxonomy' => 'product_tag',
					'field'    => 'slug',
					'terms'    => $product_tag,
				);
			}

			// Set query variables
			if ( is_array( $query_args ) ) {
				foreach ( $query_args as $key => $value ) {
					$query->set( $key, $value );
				}
			}

		}
	}

	/**
	 * Function to override woocomerce product query hooks
	 *
	 * @param $q
	 */
	public function set_woo_advance_search_order_by( $q ) {
		$order_by_filter               = filter_input( INPUT_GET, 'order_by_filter', FILTER_SANITIZE_STRING );
		$advance_search_filter_results = filter_input( INPUT_GET, 'advance_search_filter_results', FILTER_SANITIZE_STRING );
		$Product_order_by              = ! empty( $order_by_filter ) ? $order_by_filter : '';
		$Product_price_order           = ! empty( $advance_search_filter_results ) ? $advance_search_filter_results : '';
		if ( ! empty( $Product_price_order ) && "_price" === $Product_price_order ) {
			$q->set( 'orderby', 'meta_value_num' );
			$q->set( 'order', $Product_order_by );
			$q->set( 'meta_key', $Product_price_order );
		} else if ( ! empty( $Product_price_order ) && $Product_price_order === 'title' ) {
			$q->set( 'orderby', $Product_price_order );
			$q->set( 'order', $Product_order_by );
		} else {
			$q->set( 'orderby', 'date' );
			$q->set( 'order', $Product_order_by );
		}
	}

	/**
	 * Function to override wordpress search query hooks
	 *
	 * @param $term
	 *
	 * @return string
	 */
	public function woo_advance_search_filter_search( $term ) {
		$search = "";

		return $search;
	}

	function woo_advanced_search_live_ajax_data() {
		$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );
		if ( isset( $action ) && 'woo_advanced_search_live_ajax_data' === $action ) {
			$_selector_val   = filter_input( INPUT_POST, 'selectorVal', FILTER_SANITIZE_STRING );
			$_category_value = filter_input( INPUT_POST, 'categoryValue', FILTER_SANITIZE_STRING );
			$_category_tag   = filter_input( INPUT_POST, 'categoryTag', FILTER_SANITIZE_STRING );
			if ( '' !== $_selector_val ) {
				if ( '' !== $_category_value && '' === $_category_tag ) {

					$the_query = new WP_Query( array(
						'posts_per_page' => 100 ,
						's'              => esc_attr( $_selector_val ),
						'post_type'      => 'product',
						'suppress_filters' => false,

						'tax_query' => [
							[
								'taxonomy' => 'product_cat',
								'field'    => 'slug',
								'terms'    => $_category_value,

							],
						],
					) );
				} elseif ( '' !== $_category_tag && '' === $_category_value ) {
					$the_query = new WP_Query( array(
						'posts_per_page' => 100,
						's'              => esc_attr( $_selector_val ),
						'post_type'      => 'product',
						'suppress_filters' => false,

						'tax_query' => [
							[
								'taxonomy' => 'product_tag',
								'field'    => 'slug',
								'terms'    => $_category_tag,

							],
						],
					) );
				} elseif ( '' !== $_category_tag && '' !== $_category_value ) {
					$the_query = new WP_Query( array(
						'posts_per_page' => 100,
						's'              => esc_attr( $_selector_val ),
						'post_type'      => 'product',
						'suppress_filters' => false,

						'tax_query' => [
							'relation' => 'AND',
							[
								'taxonomy' => 'product_tag',
								'field'    => 'slug',
								'terms'    => $_category_tag,

							],
							[
								'taxonomy' => 'product_cat',
								'field'    => 'slug',
								'terms'    => $_category_value,

							],
						],
					) );
				} else {
					$the_query = new WP_Query( array(
						'posts_per_page' => 100,
						's'              => esc_attr( $_selector_val ),
						'post_type'      => 'product',
						'suppress_filters' => false,

					) );
				}

				$product_html_variable = array();
				if ( is_array( $the_query->posts ) && ! empty( $the_query->posts ) ) {
					foreach ( $the_query->posts as $result_data ) {
						$post_name               = $result_data->post_title;
						$post_permalink          = get_the_permalink( $result_data->ID );
						$image_path              = wp_get_attachment_image_src( get_post_thumbnail_id( $result_data->ID ) );
						$image_src               = $image_path[0];
						$image_src               = ( ! empty( $image_src ) ) ? $image_src : plugins_url() . '/woo-advance-search/public/images/image-default.png';
						$product_price           = was_get_product_price( $result_data->ID );
						$product_formatted_price = was_get_formatted_price( $product_price, $result_data->ID );
						$product_description     = was_get_product_description( $result_data->ID );
						$setting_array                = setting_getters_option();
						$limit                   = $setting_array['Woo_Advance_search_ajax_pro_des_limit'];
						$limit =! empty($limit) ? $limit : 100;
						if ( strlen( $product_description ) > $limit ) {
							$product_description = substr( $product_description, 0, strrpos( substr( $product_description, 0, $limit ), ' ' ) ) . '...';
						}

						$setting_array                = setting_getters_option();
						$Advance_search_ajax_showcase = $setting_array['Woo_Advance_search_ajax_showcase'];
						$product_html_variable[]      = array(
							'product_name'          => $post_name,
							'product_image'         => $image_src,
							'product_link'          => $post_permalink,
							'product_image_setting' => $Advance_search_ajax_showcase,
							'product_price'         => '<span>' . $product_formatted_price . '</span>',
							'product_description'   => $product_description
						);

					}
					wp_reset_query();

				}


			}
			$result = array(
				'message'               => 'woo-advanced-search_live_ajax_data',
				'product_html_variable' => $product_html_variable,

			);
			wp_send_json_success( $result );
			wp_die();
		}
	}
}
