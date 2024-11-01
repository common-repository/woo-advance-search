<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The admin-specific functionality of the plugin.
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @link       http://www.multidots.com/
 * @since      1.0.0
 *
 * @author     multidots <info@multidots.in>
 */
class Woo_Advance_Search_Admin {

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
	 * @param string $plugin_name The name of this plugin.
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
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles( $hook ) {


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
		if ( isset( $hook ) && ! empty( $hook ) && ( 'woocommerce_page_advance-search' === $hook || 'dashboard_page_advance-search-for-woocommerce' === $hook ) ) {

			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woo-advance-search-admin.css', array( 'wp-jquery-ui-dialog' ), $this->version, 'all' );
			wp_enqueue_style( 'wp-pointer' );
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			wp_enqueue_script( 'jquery-ui' );
			wp_enqueue_script( 'jquery-ui-tabs' );
			wp_enqueue_script( 'jquery-ui-dialog' );
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woo-advance-search-admin' . $suffix . '.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'copytoclipboard', plugin_dir_url( __FILE__ ) . 'js/clipboard.min.js', array( 'jquery' ), $this->version );
			wp_localize_script( $this->plugin_name, 'adminajaxjs', array( 'adminajaxjsurl' => admin_url( 'admin-ajax.php' ) ) );
			wp_enqueue_script( 'wp-pointer' );
		}
	}


	/**
	 * Custom menu in woocommerce setting
	 *
	 */
	public function asfw_custom_menu_free_version() {
		$wbm_page = 'woocommerce';
		add_submenu_page( $wbm_page, __( 'Advance Search', 'advance-search' ), __( 'Advance Search', 'advance-search' ), 'manage_options', 'advance-search', array(
			&$this,
			'custom_aswf_submenu_page_callback_free_version',
		) );
		add_dashboard_page(
			'Advance Search for WooCommerce Dashboard', 'Advance Search for WooCommerce Dashboard', 'read', 'advance-search-for-woocommerce', array(
			$this,
			'welcome_screen_content_advance_search_for_woocommerce',
		) );
		remove_submenu_page( 'index.php', 'advance-search-for-woocommerce' );
	}

	/**
	 * Woo advance search call back function
	 *
	 */
	public function custom_aswf_submenu_page_callback_free_version() {

		$setting_array                = setting_getters_option();
		$product_category             = ( ! empty( $setting_array['Woo_Advance_Search_Product_Category_Enable'] ) ) ? $setting_array['Woo_Advance_Search_Product_Category_Enable'] : '';
		$product_tag                  = ( ! empty( $setting_array['Woo_Advance_Search_Product_Tag_Enable'] ) ) ? $setting_array['Woo_Advance_Search_Product_Tag_Enable'] : '';
		$Advance_Search_Filter        = ( ! empty( $setting_array['Woo_Advance_Search_Filter'] ) ) ? $setting_array['Woo_Advance_Search_Filter'] : '';
		$Advance_order_by             = ( ! empty( $setting_array['Woo_Advance_order_by'] ) ) ? $setting_array['Woo_Advance_order_by'] : '';
		$Advance_Custom_Css           = ( ! empty( $setting_array['Woo_Advance_Custom_Css'] ) ) ? $setting_array['Woo_Advance_Custom_Css'] : '';
		$Advance_Search_Live_Ajax     = ( ! empty( $setting_array['Woo_Advance_Search_Live_Ajax'] ) ) ? $setting_array['Woo_Advance_Search_Live_Ajax'] : '';
		$Advance_search_ajax_showcase = ( ! empty( $setting_array['Woo_Advance_search_ajax_showcase'] ) ) ? $setting_array['Woo_Advance_search_ajax_showcase'] : '';
		$Advanced_woo_search_product_des_limit = ( ! empty( $setting_array['Woo_Advance_search_ajax_pro_des_limit'] ) ) ? $setting_array['Woo_Advance_search_ajax_pro_des_limit'] : 100;
		?>
		<div class="wrap woocommerce">
			<form method="post" id="mainform" action="" enctype="multipart/form-data">
				<?php wp_nonce_field( basename( __FILE__ ), 'woo_advance_search' ); ?>
				<div class="icon32 icon32-woocommerce-settings" id="icon-woocommerce"><br></div>
				<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
					<a class="nav-tab"
					   id="woo-advance-search-overview-tab"><?php esc_html_e( 'Overview', 'woo-advance-search' ); ?></a> 
					<a class="nav-tab nav-tab-active"
					   id="advance_search_open_setting"><?php esc_html_e( 'Setting', 'woo-advance-search' ); ?></a>  
					<a class="nav-tab"
					   id="advance_search_open_preview"><?php esc_html_e( 'Preview', 'woo-advance-search' ); ?></a>
					<a class="nav-tab"
					   id="advance_search_open_shortcode"><?php esc_html_e( 'Shortcode', 'woo-advance-search' ); ?></a>
					<a class="nav-tab"
					   id="advance_search_open_custom_css"><?php esc_html_e( 'Custom CSS', 'woo-advance-search' ); ?></a>
				</h2>

				<br class="clear">
				<div class="woo-advance-search-setting-tab">
					<div id="message" class="updated woo_advance_save_record_messgae"></div>
					<h3><?php esc_html_e( 'Advance Search Settings', 'woo-advance-search' ); ?></h3>
					<table class="advance_woocommerce_serach_table form-table">
						<tbody>
						<tr class="advance_ajax">
							<th class="advance_">
								<label for="advance_search_live_ajax">
									<?php esc_html_e( 'Enable/Disable Live ajax data', 'woo-advance-search' ); ?>
								</label>
							</th>
							<span class="woocommerce-help-tip"></span>
							<td class="advance_search_html"><input type="checkbox" id="advance_search_live_ajax"
							                                       name="advance_search_live_ajax"
							                                       value="Active" <?php checked( $Advance_Search_Live_Ajax, 'Active', true );
								?>/></td>
						</tr>
						<?php
						if ( 'Active' === $Advance_Search_Live_Ajax ) { ?>
							<tr class="showcase_ajax">
								<th class="advance_">
									<label>
										<?php esc_html_e( 'Data Showcase', 'woo-advance-search' ); ?>
									</label>
								</th>
								<td class="forminp forminp-checkbox">
									<fieldset>
										<legend class="screen-reader-text">
											<span><?php esc_html_e( 'Product Ratings', 'woo-advance-search' ); ?></span></legend>
										<label for="with_image">
											<input type="radio" name="advance_search_ajax_showcase" id="with_image" value="with_image" <?php
											checked( $Advance_search_ajax_showcase, 'with_image', true );
											?> /><?php esc_html_e( 'With image', 'woo-advance-search' ); ?>
										</label>
									</fieldset>
									<fieldset class="hidden_option">
										<label for="without_image">
											<input type="radio" name="advance_search_ajax_showcase" id="without_image" value="without_image" <?php
											checked( $Advance_search_ajax_showcase, 'without_image', true );
											?> /><?php esc_html_e( 'Without image', 'woo-advance-search' ); ?>
										</label>
									</fieldset>
								</td>
							</tr>
							<tr class="showcase_ajax">
								<th class="advance_">
									<label for="advanced_search_product_description_limit">
										<?php esc_html_e( 'Product Description Limit', 'woo-advance-search' ); ?>
									</label>
								</th>
								<span class="woocommerce-help-tip"></span>
								<td class="advance_search_html"><input type="number" name="advanced_search_product_description_limit" id="advanced_search_product_description_limit" value="<?php echo esc_attr($Advanced_woo_search_product_des_limit);?>"></td>
							</tr>
						<?php }
						?>
						<tr class="condition_html">
							<th class="advance_">
								<label for="advance_search_product_category">
									<?php esc_html_e( 'Enable/Disable search by product category', 'woo-advance-search' ); ?>
								</label>
							</th>
							<span class="woocommerce-help-tip"></span>
							<td class="advance_search_html"><input type="checkbox" id="advance_search_product_category"
							                                       name="advance_search_product_category"
							                                       value="Active" <?php checked( $product_category, 'Active', true );
								?>/></td>
						</tr>
						<tr class="condition_html">
							<th class="advance_">
								<label for="advance_search_product_tag">
									<?php esc_html_e( 'Enable/Disable search by product tag', 'woo-advance-search' ); ?>
								</label>
							</th>
							<td class="advance_search_html"><input type="checkbox" id="advance_search_product_tag"
							                                       name="advance_search_product_tag"
							                                       value="Active" <?php checked( $product_tag, 'Active', true );
								?>/></td>
						</tr>
						<tr class="condition_html">
							<th class="advance_">
								<label>
									<?php esc_html_e( 'Apply search filter', 'woo-advance-search' ); ?>
								</label>
							</th>
							<td class="forminp forminp-checkbox">
								<fieldset>
									<legend class="screen-reader-text">
										<span><?php esc_html_e( 'Product Ratings', 'woo-advance-search' ); ?></span></legend>
									<label for="order_by_title">
										<input type="radio" name="advance_search_filter" id="order_by_title" <?php
										checked( $Advance_Search_Filter, 'Product_Title', true );
										?> value="Product_Title"/><?php esc_html_e( 'Order by title', 'woo-advance-search' ); ?>
									</label>
								</fieldset>
								<fieldset class="hidden_option">
									<label for="order_by_date">
										<input type="radio" name="advance_search_filter" id="order_by_date" value="Order_by_date" <?php
										checked( $Advance_Search_Filter, 'Order_by_date', true );
										?> /><?php esc_html_e( 'Order by date', 'woo-advance-search' ); ?>
									</label>
								</fieldset>
								<fieldset class="hidden_option">
									<label for="order_by_price">
										<input type="radio" name="advance_search_filter" id="order_by_price" value="Order_By_Price" <?php
										checked( $Advance_Search_Filter, 'Order_By_Price', true );
										?> /><?php esc_html_e( 'Order by price', 'woo-advance-search' ); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr class="condition_html">
							<th class="advance_">
								<label for="Selectorder_by">
									<?php esc_html_e( 'Search order by', 'woo-advance-search' ); ?>
								</label>
							</th>
							<td class="advance_search_html">
								<select name="Selectorder_by"
								        class="advance_search_filter_order_by_html" id="Selectorder_by">
									<?php
									$option_array = array(
										"Asc"  => "Ascending",
										"Desc" => "Descending"
									);
									if ( ! empty( $option_array ) && is_array( $option_array ) ) {
										foreach ( $option_array as $key => $single_option_array ) { ?>
											<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $Advance_order_by, $key ); ?>><?php echo esc_html__( $single_option_array ); ?></option>
											<?php
										}
									}

									?></select>
							</td>
						</tr>
						</tbody>
					</table>
			</form>
		</div>
	    <div class="woo-advance-search-overview-tab">
			<h3><?php esc_html_e( 'Advance Search Overview', 'woo-advance-search' ); ?></h3>
			<div class="wc-feature feature-section col three-col">
					<div>
						<p class="advance_search_for_woocommerce_overview"><?php esc_html_e( 'Advance Search for WooCommerce plugin allows you to add an advanced search option for WooCommerce Products. With this option you can search products by product tag and category. you can apply filter searcher like Title, order by date, price category and search order by ascending, Descending. You can customize search as per your requirement like enable and disable product category and tag. you can view searcher option by preview option. you can integrated searcher option in your site using a short-code on a page, as the widget in a sidebar or as template tag in a template.', 'woo-advance-search' ); ?></p>
					</div>
					<p class="advance_search_for_woocommerce_overview">
						<strong><?php esc_html_e( 'Plugin Functionality:', 'woo-advance-search' ); ?></strong></p>
					<div class="advance_search_for_woocommerce_content_ul">
						<ul>
							<li><?php esc_html_e( 'Advance search across all your WooCommerce products', 'woo-advance-search' ); ?></li>
							<li><?php esc_html_e( 'Advance Search by Category', 'woo-advance-search' ); ?></li>
							<li><?php esc_html_e( 'Advance Search by Product tag', 'woo-advance-search' ); ?></li>
							<li><?php esc_html_e( 'Filter by Title, Date, Price', 'woo-advance-search' ); ?></li>
							<li><?php esc_html_e( 'Filter by Ascending and Descending', 'woo-advance-search' ); ?></li>
							<li><?php esc_html_e( 'Enable or disable searches by product category and tag.', 'woo-advance-search' ); ?></li>
							<li><?php esc_html_e( 'you can preview of advanced searcher option as per your setting.', 'woo-advance-search' ); ?></li>
							<li><?php esc_html_e( 'Shortcode - Use shortcode to place search option anywhere you want', 'woo-advance-search' ); ?></li>
							<li><?php esc_html_e( 'you can apply custom CSS for advanced searcher option.', 'woo-advance-search' ); ?></li>
							<li><?php esc_html_e( 'you can search product on live ajax search with suggestions.', 'woo-advance-search' ); ?></li>
						</ul>
					</div>

				</div>
		</div>		
		<div class="woo-advance-search-preview-tab">
			<h3><?php esc_html_e( 'Advance Search Preview', 'woo-advance-search' ); ?></h3>
			<form>
				<?php wp_nonce_field( basename( __FILE__ ), 'woo_advance_search' ); ?>
				<table class="advance_woocommerce_serach_table form-table">
					<tbody>
					<tr>
						<td>
							<input type="text" placeholder="Search by product">
							<input type="button" name="Search" class="button button-primary" value="Search"
							       id="Search_Button_Preview">
						</td>
					</tr>
					<tr>
						<td>
							<select name="SelectCategory" id="advance_search_category_preview_html">
								<option value="Select Category"><?php esc_html_e( 'Select Category', 'woo-advance-search' ); ?></option>
								<option value="Select Category"><?php esc_html_e( 'Test Category1', 'woo-advance-search' ); ?></option>
								<option value="Select Category"><?php esc_html_e( 'Test Category2', 'woo-advance-search' ); ?></option>
								<option value="Select Category"><?php esc_html_e( 'Test Category3', 'woo-advance-search' ); ?></option>
							</select>
							<select name="Selecttag" id="advance_search_category_tag_html">
								<option value="Select Tag"><?php esc_html_e( 'Select Tag', 'woo-advance-search' ); ?></option>
								<option value="Test Tag1"><?php esc_html_e( 'Test Tag1', 'woo-advance-search' ); ?></option>
								<option value="Test Tag2"><?php esc_html_e( 'Test Tag2', 'woo-advance-search' ); ?></option>
								<option value="Test Tag3"><?php esc_html_e( 'Test Tag3', 'woo-advance-search' ); ?></option>
							</select>

						</td>
					</tr>
					<tr>
						<td>
							<select name="orderby" id="advance_search_order_by_preview_html">
								<option value="Ascending"><?php esc_html_e( 'Ascending', 'woo-advance-search' ); ?></option>
								<option value="Descending"><?php esc_html_e( 'Descending', 'woo-advance-search' ); ?></option>
							</select>

							<select name="filter" id="advance_search_filter_tag_html">
								<option value="Product_title"><?php esc_html_e( 'Product_Title', 'woo-advance-search' ); ?></option>
								<option value="Product_price"><?php esc_html_e( 'Product_Price', 'woo-advance-search' ); ?></option>
								<option value="Product_date"><?php esc_html_e( 'Product_Date', 'woo-advance-search' ); ?></option>
							</select>
						</td>
					</tr>
					</tbody>
				</table>
		</div>
		<div class="woo-advance-search-shortcode-tab">

			<h3><?php esc_html_e( 'Advance Search Shortcode', 'woo-advance-search' ); ?></h3>
			<table class="advance_woocommerce_serach_table form-table">
				<tbody>
				<tr>
					<td>
						<input type="text" id="clipboard" readonly value="[woo-advance-search]" width="50%">
						<input data-clipboard-target="#clipboard"
						       class="button button-primary zclip js-textareacopybtn btn" data-zclip-text=""
						       type="button" value="<?php esc_html_e( 'Copy to clipboard', 'woo-advance-search' ); ?>">
					</td>
				</tr>
				</tbody>
			</table>
		</div>
		<div class="woo-advance-search-custom-css">
			<h3><?php esc_html_e( 'Advance Search Custom CSS', 'woo-advance-search' ); ?></h3>
			<table class="advance_woocommerce_serach_table form-table">
				<tbody>
				<tr>
					<td>
                            <textarea name="woo-advance-search-custom-css" id="woo-advance-search-custom-id" rows="10"
                                      cols="50"><?php echo wp_kses_post( $Advance_Custom_Css ); ?></textarea>
					</td>
				</tr>
				</tbody>
			</table>
		</div>
		<p class="submit">
			<input name="save" class="button-primary advance_search_for_woocommerce_save_btn" type="button" value="Save changes">
		</p>

		<?php
	}

	/**
	 * Function For save data in option table.
	 *
	 */
	public function Save_advance_search_settings_free() {
		$create_nonce       = wp_create_nonce( 'woo_advance_search' );
		$woo_advance_search = filter_input( INPUT_POST, 'woo_advance_search', FILTER_SANITIZE_STRING );
		if ( wp_verify_nonce( $create_nonce, sanitize_text_field( wp_unslash( $woo_advance_search ) ) ) ) {
			die( 'Failed security check' );
		}
		$product_category         = filter_input( INPUT_POST, 'ProductCategory', FILTER_SANITIZE_STRING );
		$product_category         = ! empty( $product_category ) ? $product_category : '';
		$product_tag              = filter_input( INPUT_POST, 'ProductTag', FILTER_SANITIZE_STRING );
		$product_tag              = ! empty( $product_tag ) ? $product_tag : '';
		$Advance_search_filter    = filter_input( INPUT_POST, 'AdvanceSearchFilter', FILTER_SANITIZE_STRING );
		$Advance_search_filter    = ! empty( $Advance_search_filter ) ? $Advance_search_filter : '';
		$order_by                 = filter_input( INPUT_POST, 'orderBy', FILTER_SANITIZE_STRING );
		$order_by                 = ! empty( $order_by ) ? $order_by : '';
		$customCss                = filter_input( INPUT_POST, 'customCss', FILTER_SANITIZE_STRING );
		$customCss                = ! empty( $customCss ) ? $customCss : '';
		$advance_search_live_ajax = filter_input( INPUT_POST, 'advanceSearchLiveAjax', FILTER_SANITIZE_STRING );

		$advance_search_live_ajax = ( ! empty( $advance_search_live_ajax ) ) ? $advance_search_live_ajax : '';

		$Advance_search_ajax_showcase = filter_input( INPUT_POST, 'AdvanceSearchAjaxShowcase', FILTER_SANITIZE_STRING );
		$Advance_search_ajax_showcase = ! empty( $Advance_search_ajax_showcase ) ? $Advance_search_ajax_showcase : 'with_image';
		$Advance_search_ajax_pro_des_limit = filter_input( INPUT_POST, 'AdvanceSearchAjaxProDesLimit', FILTER_SANITIZE_STRING );
		$Advance_search_ajax_pro_des_limit = ! empty( $Advance_search_ajax_pro_des_limit ) ? $Advance_search_ajax_pro_des_limit : 100;
		$setting_array                = array(
			'Woo_Advance_Search_Product_Tag_Enable'      => $product_tag,
			'Woo_Advance_Search_Product_Category_Enable' => $product_category,
			'Woo_Advance_Search_Filter'                  => $Advance_search_filter,
			'Woo_Advance_order_by'                       => $order_by,
			'Woo_Advance_Custom_Css'                     => $customCss,
			'Woo_Advance_Search_Live_Ajax'               => $advance_search_live_ajax,
			'Woo_Advance_search_ajax_showcase'           => $Advance_search_ajax_showcase,
			'Woo_Advance_search_ajax_pro_des_limit' => $Advance_search_ajax_pro_des_limit
		);
		$setting_array                = wp_json_encode( $setting_array );
		setting_setters_option( $setting_array );
		echo "<p><strong>" . esc_html__( 'Your settings have been saved.', 'woo-advance-search' ) . "</strong></p>";
		die();
	}


	/**
	 * Function to return welcome screnn while plugin first time activated.
	 */
	public function welcome_screen_content_advance_search_for_woocommerce() {
		?>
		<div class="wrap about-wrap">
			<h1 class="welcome_message_plugin"><?php printf( esc_html__( 'Welcome to Advance Search for WooCommerce', 'woo-advance-search' ) ); ?></h1>

			<div class="about-text woocommerce-about-text">
				<img class="version_logo_img"
				     src="<?php echo wp_kses_post( plugin_dir_url( __FILE__ ) . 'images/woo-advance-search.png' ); ?>">
				<?php

				$message = '';
				printf( esc_html__( '%s Advance Search for WooCommerce plugin allows you to add an advanced search option for WooCommerce Products.', 'woo-advance-search' ), wp_kses_post( $message ) );
				?>

			</div>

			<?php
			$setting_tabs_wc = apply_filters( 'advance_search_for_woocommerce_setting_tab', array(
				"about"         => "Overview",
			) );
			$custom_tab      = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
			$current_tab_wc  = ( isset( $custom_tab ) ) ? $custom_tab : 'general';
			?>
			<h2 id="woo-extra-cost-tab-wrapper" class="nav-tab-wrapper">
				<?php
				if ( is_array( $setting_tabs_wc ) ) {
					foreach ( $setting_tabs_wc as $name => $label ) {
						echo '<a  href="' . esc_url( home_url( 'wp-admin/index.php?page=advance-search-for-woocommerce&tab=' . esc_attr( $name ) ) ) . '" class="nav-tab ' . ( $current_tab_wc === $name ? 'nav-tab-active' : '' ) . '">' . esc_attr( $label ) . '</a>';
					}
				}

				?>
			</h2>
			<?php
			if(is_array($setting_tabs_wc) && ! empty($setting_tabs_wc)){
				foreach ( $setting_tabs_wc as $setting_tabkey_wc => $setting_tabvalue ) {
					$setting_tabvalue = $setting_tabkey_wc;
					switch ( $setting_tabvalue ) {
						case $current_tab_wc:
							do_action( 'advance_search_for_woocommerce_' . $current_tab_wc );
							break;
					}
				}
			}

			?>
			<hr/>
			<div class="return-to-dashboard">
				<a href="<?php echo esc_url( home_url( '/wp-admin/admin.php?page=advance-search' ) ); ?>"><?php esc_html_e( 'Go to Advance Search for WooCommerce Settings', 'woo-advance-search' ); ?></a>
			</div>
		</div>
		<?php
	}

	/**
	 * function for welcome page about us tag content
	 */
	public function advance_search_for_woocommerce_about() {
		?>
		<div class="changelog">
			</br>
			<div class="changelog about-integrations">
				<div class="wc-feature feature-section col three-col">
					<div>
						<p class="advance_search_for_woocommerce_overview"><?php esc_html_e( 'Advance Search for WooCommerce plugin allows you to add an advanced search option for WooCommerce Products. With this option you can search products by product tag and category. you can apply filter searcher like Title, order by date, price category and search order by ascending, Descending. You can customize search as per your requirement like enable and disable product category and tag. you can view searcher option by preview option. you can integrated searcher option in your site using a short-code on a page, as the widget in a sidebar or as template tag in a template.', 'woo-advance-search' ); ?></p>
					</div>
					<p class="advance_search_for_woocommerce_overview">
						<strong><?php esc_html_e( 'Plugin Functionality:', 'woo-advance-search' ); ?></strong></p>
					<div class="advance_search_for_woocommerce_content_ul">
						<ul>
							<li><?php esc_html_e( 'Advance search across all your WooCommerce products', 'woo-advance-search' ); ?></li>
							<li><?php esc_html_e( 'Advance Search by Category', 'woo-advance-search' ); ?></li>
							<li><?php esc_html_e( 'Advance Search by Product tag', 'woo-advance-search' ); ?></li>
							<li><?php esc_html_e( 'Filter by Title, Date, Price', 'woo-advance-search' ); ?></li>
							<li><?php esc_html_e( 'Filter by Ascending and Descending', 'woo-advance-search' ); ?></li>
							<li><?php esc_html_e( 'Enable or disable searches by product category and tag.', 'woo-advance-search' ); ?></li>
							<li><?php esc_html_e( 'you can preview of advanced searcher option as per your setting.', 'woo-advance-search' ); ?></li>
							<li><?php esc_html_e( 'Shortcode - Use shortcode to place search option anywhere you want', 'woo-advance-search' ); ?></li>
							<li><?php esc_html_e( 'you can apply custom CSS for advanced searcher option.', 'woo-advance-search' ); ?></li>
							<li><?php esc_html_e( 'you can search product on live ajax search with suggestions.', 'woo-advance-search' ); ?></li>
						</ul>
					</div>

				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * function to return js in footer
	 */
	public function advance_search_for_woocommerce_pointers_footer() {
		$admin_pointers = advance_search_for_woocommerce_admin_pointers();
		?>
		<script type="text/javascript">
			(function($) {
				<?php
				if(is_array( $admin_pointers ) && ! empty($admin_pointers)) {
				foreach ($admin_pointers as $pointer => $array) {
				if ($array['active']) {
				?>
				$('<?php echo wp_kses_post( $array['anchor_id'] ); ?>').pointer({
					content: '<?php echo wp_kses_post( $array['content'] ); ?>',
					position: {
						edge: '<?php echo wp_kses_post( $array['edge'] ); ?>',
						align: '<?php echo wp_kses_post( $array['align'] ); ?>',
					},
					close: function() {
						$.post(ajaxurl, {
							pointer: '<?php echo wp_kses_post( $pointer ); ?>',
							action: 'dismiss-wp-pointer',
						});
					},
				}).pointer('open');
				<?php
				}
				}

				} ?>

			})(jQuery);
		</script>
		<?php
	}

	/**
	 * @param $links
	 * @param $file
	 *
	 * @return array
	 * function to return links in plugin page for support.
	 */
	function advance_search_plugin_row_meta( $links, $file ) {

		if ( strpos( $file, 'woo-advance-search.php' ) !== false ) {
			$new_links = array(
				'support' => '<a href="https://www.thedotstore.com/support/" target="_blank">Support</a>',
			);

			$links = array_merge( $links, $new_links );
		}

		return $links;
	}

	/**
	 * funnction to change older data in to new date after code refactoring
	 */
	function dotstore_advanced_seach_plugin_update_checker() {
		$advanced_search_version = get_option( 'advanced_search_version' );
		if ( empty( $advanced_search_version ) ) {
			update_option( 'advanced_search_version', '1.0' );
		}
		$setting_array_options = setting_getters_option();
		$setting_array         = array();
		$product_category      = get_option( 'Woo_Advance_Search_Product_Category_Enable' );
		$product_tag           = get_option( 'Woo_Advance_Search_Product_Tag_Enable' );
		$Advance_Search_Filter = get_option( 'Woo_Advance_Search_Filter' );
		$Advance_order_by      = get_option( 'Woo_Advance_order_by' );
		$customCss             = get_option( 'Woo_Advance_Custom_Css' );
		if ( '1.0' === $advanced_search_version || empty( $advanced_search_version ) ) {
			if ( ( isset( $product_category ) && ! empty( $product_category ) ) && ( ! isset( $setting_array_options ) ) ) {
				$setting_array['Woo_Advance_Search_Product_Category_Enable'] = $product_category;
			} else {
				$setting_array['Woo_Advance_Search_Product_Category_Enable'] = $setting_array_options['Woo_Advance_Search_Product_Category_Enable'];
			}
			if ( ( isset( $product_tag ) && ! empty( $product_tag ) ) && ( ! isset( $setting_array_options ) ) ) {
				$setting_array['Woo_Advance_Search_Product_Tag_Enable'] = $product_tag;
			} else {
				$setting_array['Woo_Advance_Search_Product_Tag_Enable'] = $setting_array_options['Woo_Advance_Search_Product_Tag_Enable'];
			}
			if ( ( isset( $Advance_Search_Filter ) && ! empty( $Advance_Search_Filter ) ) && ( ! isset( $setting_array_options ) ) ) {
				$setting_array['Woo_Advance_Search_Filter'] = $Advance_Search_Filter;
			} else {
				$setting_array['Woo_Advance_Search_Filter'] = $setting_array_options['Woo_Advance_Search_Filter'];
			}
			if ( ( isset( $Advance_order_by ) && ! empty( $Advance_order_by ) ) && ( ! isset( $setting_array_options ) ) ) {
				$setting_array['Woo_Advance_order_by'] = $Advance_order_by;
			} else {
				$setting_array['Woo_Advance_order_by'] = $setting_array_options['Woo_Advance_order_by'];
			}
			if ( ( isset( $customCss ) && ! empty( $customCss ) ) && ( ! isset( $setting_array_options ) ) ) {
				$setting_array['Woo_Advance_Custom_Css'] = $customCss;
			} else {
				$setting_array['Woo_Advance_Custom_Css'] = $setting_array_options['Woo_Advance_Custom_Css'];
			}
			$setting_array = wp_json_encode( $setting_array );
			if ( '1.0' === $advanced_search_version || empty( $advanced_search_version ) ) {
				setting_setters_option( $setting_array );
				update_option( 'advanced_search_version', '2.0.2' );
				delete_option( 'Woo_Advance_Search_Product_Category_Enable' );
				delete_option( 'Woo_Advance_Search_Product_Tag_Enable' );
				delete_option( 'Woo_Advance_Search_Filter' );
				delete_option( 'Woo_Advance_order_by' );
				delete_option( 'Woo_Advance_Custom_Css' );
			}
		}


		if ( ! get_transient( '_advance_search_for_woocommerce_welcome_screen' ) ) {
			return;
		}

		delete_transient( '_advance_search_for_woocommerce_welcome_screen' );

		$activate_multi = filter_input( INPUT_GET, 'activate-multi', FILTER_SANITIZE_STRING );
		if ( is_network_admin() || isset( $activate_multi ) ) {
			return;
		}
		wp_safe_redirect(admin_url( 'admin.php?page=advance-search' ));
		exit();

	}


	function woo_setting_ajax_checked() {
		$action = filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING );
		if ( isset( $action ) && 'woo_setting_ajax_checked' === $action ) {
			$_checkbox_val = filter_input( INPUT_POST, '_checkbox_val', FILTER_SANITIZE_STRING );
			if ( 'checked' === $_checkbox_val ) {
				$setting_array                = setting_getters_option();
				$Advance_search_ajax_showcase = $setting_array['Woo_Advance_search_ajax_showcase'];
				$Advanced_woo_search_product_des_limit = ( ! empty( $setting_array['Woo_Advance_search_ajax_pro_des_limit'] ) ) ? $setting_array['Woo_Advance_search_ajax_pro_des_limit'] : 100;
				ob_start();
				?>
				<tr class="showcase_ajax">
					<th class="advance_">
						<label>
							<?php esc_html_e( 'Data Showcase', 'woo-advance-search' ); ?>
						</label>
					</th>
					<td class="forminp forminp-checkbox">
						<fieldset>
							<legend class="screen-reader-text">
								<span><?php esc_html_e( 'Product Ratings', 'woo-advance-search' ); ?></span></legend>
							<label for="with_image">
								<input type="radio" name="advance_search_ajax_showcase" id="with_image" value="with_image" <?php
								checked( $Advance_search_ajax_showcase, 'with_image', true );
								?> /><?php esc_html_e( 'With image', 'woo-advance-search' ); ?>
							</label>
						</fieldset>
						<fieldset class="hidden_option">
							<label for="without_image">
								<input type="radio" name="advance_search_ajax_showcase" id="without_image" value="without_image" <?php
								checked( $Advance_search_ajax_showcase, 'without_image', true );
								?> /><?php esc_html_e( 'Without image', 'woo-advance-search' ); ?>
							</label>
						</fieldset>
					</td>
				</tr>
				<tr class="showcase_ajax">
					<th class="advance_">
						<label for="advanced_search_product_description_limit">
							<?php esc_html_e( 'Product Description Limit', 'woo-advance-search' ); ?>
						</label>
					</th>
					<span class="woocommerce-help-tip"></span>
					<td class="advance_search_html"><input type="number" name="advanced_search_product_description_limit" id="advanced_search_product_description_limit" value="<?php echo esc_attr($Advanced_woo_search_product_des_limit);?>"></td>
				</tr>

				<?php
				$html = ob_get_clean();
			} else {
				$html = '';
			}

			$result = array(
				'message'      => 'success_message_ajax',
				'checkbox_val' => $_checkbox_val,
				'html'         => $html
			);
			wp_send_json_success( $result );
			wp_die();
		}

	}
}


function advance_search_for_woocommerce_admin_pointers() {

	$dismissed           = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
	$version             = '1_0';
	$prefix              = 'advance_search_for_woocommerce_admin_pointers' . $version . '_';
	$new_pointer_content = '<h3>' . __( 'Welcome to Advance Search for WooCommerce', 'woo-advance-search' ) . '</h3>';
	$new_pointer_content .= '<p>' . __( 'Advance Search for WooCommerce plugin allows you to add an advanced search option for WooCommerce Products.', 'woo-advance-search' ) . '</p>';

	return array(
		$prefix . 'advance_search_for_woocommerce_admin_pointers' => array(
			'content'   => $new_pointer_content,
			'anchor_id' => '#toplevel_page_woocommerce',
			'edge'      => 'left',
			'align'     => 'left',
			'active'    => ( ! in_array( $prefix . 'advance_search_for_woocommerce_admin_pointers', $dismissed, true ) ),
		),
	);
}
