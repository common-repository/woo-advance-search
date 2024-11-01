<?php
/**
 * @return array|mixed|object|void
 * function to return setting option which is saved in db
 */
function setting_getters_option() {
	$setting_array = get_option( 'woo_advanced_search_setting' );
	$setting_array = json_decode( $setting_array, true );

	return $setting_array;
}

function setting_setters_option( $setting_array ) {
	update_option( 'woo_advanced_search_setting', $setting_array );
}

function get_search_shortcode_html() {
	$setting_array            = setting_getters_option();
	$product_category         = $setting_array['Woo_Advance_Search_Product_Category_Enable'];
	$product_tag              = $setting_array['Woo_Advance_Search_Product_Tag_Enable'];
	$Advance_Search_Live_Ajax = $setting_array['Woo_Advance_Search_Live_Ajax'];
	$Advanced_search_filter   = $setting_array['Woo_Advance_Search_Filter'];
	$Woo_Advance_order_by   = $setting_array['Woo_Advance_order_by'];
	$category_display_class   = ( ! empty( $product_category ) && 'Active' === $product_category ) ? 'category_display_yes' : 'category_display_no';
	$tag_display_class        = ( ! empty( $product_tag ) && 'Active' === $product_tag ) ? 'tag_display_yes' : 'tag_display_no';
	$action                   = get_permalink( wc_get_page_id( 'shop' ) );
	$search_keyword           = filter_input( INPUT_GET, 's', FILTER_SANITIZE_STRING );
	$search_text              = ! empty( $search_keyword ) ? sanitize_text_field( wp_unslash( $search_keyword ) ) : '';
	$setting_array_compact    = compact( 'Advance_Search_Live_Ajax' );
	ob_start();
	?>

	<div class="Advance_search_for_woo_display_main">
		<form name="woo_advance_search_form" id="woo_advance_search_form" class="woocommerce" action="<?php echo esc_url( $action ); ?>" method="get">
			<div class="Default_search_preview_tab">
				<input type="hidden" name="post_type" value="product"/>
				<input type="hidden" name="was" value="was"/>

				<input type="hidden" name="setting_data_array" value='<?php echo wp_json_encode( $setting_array_compact ); ?>'/>
				<div class="text-box">
					<input type="text" placeholder="Search" name="s" value="<?php echo esc_attr( $search_text ); ?>" id="woo_advance_default_preview_set_search_text" class="woo_advance_default_preview_set_search_text" autocomplete="off">
				</div>
				<div class="button-box">
					<input type="submit" value="<?php echo esc_attr( 'submit', 'woo-advance-search' ) ?>" class="advance_search_for_woocommerce_save_btn">
				</div>
				<ul class="autocomplete_suggesions"></ul>
			</div>


			<div class="advance_default_search_advance_search_option">
				<?php
				$product_cats                  = filter_input( INPUT_GET, 'product_cat', FILTER_SANITIZE_STRING );
				$product_tags                  = filter_input( INPUT_GET, 'product_tag', FILTER_SANITIZE_STRING );
				$order_by_filter               = filter_input( INPUT_GET, 'order_by_filter', FILTER_SANITIZE_STRING );
				$advance_search_filter_result  = filter_input( INPUT_GET, 'advance_search_filter_results', FILTER_SANITIZE_STRING );
				$product_cat                   = ! empty( $product_cats ) ? $product_cats : '';
				$product_tag                   = ! empty( $product_tags ) ? $product_tags : '';
				$order_by_filter_results       = ! empty( $order_by_filter ) ? $order_by_filter : $Woo_Advance_order_by;
				$advance_search_filter_results = ! empty( $advance_search_filter_result ) ? $advance_search_filter_result : $Advanced_search_filter;
				$prod_cat_args                 = array(
					'taxonomy' => 'product_cat',
					'orderby'  => 'name',
					'empty'    => 0,
				);
				$woo_categories                = get_categories( $prod_cat_args );
				?>

				<div class="Advance_Search_Button"></div>
				<div class="Advance_search_select_category">
					<select name="product_cat" class="advance_search_category_preview_html <?php esc_attr_e( $category_display_class, 'woo-advance-search' ); ?>">
						<option value=""><?php esc_html_e( 'Select Category', 'woo-advance-search' ); ?></option>
						<?php
						if ( is_array( $woo_categories ) ):
							foreach ( $woo_categories as $woo_cat ) {
								$woo_cat_name = $woo_cat->name; //category name
								$woo_cat_slug = $woo_cat->slug; //category slug
								?>
								<option value="<?php echo esc_attr( $woo_cat_slug, 'woo-advance-search' ); ?>" <?php
								selected( $woo_cat_slug, $product_cat ); ?>>
									<?php esc_attr_e( $woo_cat_name, 'woo-advance-search' ); ?></option>
							<?php }
						endif;
						?>
					</select>

					<select name="product_tag" class="advance_search_category_tag_html <?php esc_attr_e( $tag_display_class, 'woo-advance-search' ); ?>">
						<option value=""><?php esc_attr_e( 'Select Tag', 'woo-advance-search' ); ?></option>
						<?php
						$terms = get_terms( 'product_tag' );
						if ( ( ! empty( $terms ) && ! is_wp_error( $terms ) ) && is_array( $terms ) ) {
							foreach ( $terms as $term ) {
								?>
								<option value="<?php echo esc_attr( $term->slug, 'woo-advance-search' ); ?>" <?php
								selected( $term->slug, $product_tag ); ?> >
									<?php esc_attr_e( $term->name, 'woo-advance-search' ); ?></option>
								<?php
							}
						}
						?>
					</select>
				</div>

				<div class="order_by_filter">
					<p class="order_by_filter_inner_html"><label><?php esc_html_e( 'Order by', 'woo-advance-search' ); ?></label>
						<select name="order_by_filter" class="order_by_dropdown">
							<?php
							$option_array = array(
								"Asc"  => "Ascending",
								"Desc" => "Descending",
							);
							if ( is_array( $option_array ) ) {
								foreach ( $option_array as $key => $single_option_array ) { ?>
									<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $order_by_filter_results, $key ); ?>><?php echo esc_html__( $single_option_array ) ?></option>
									<?php
								}
							}
							?>
						</select>
					</p>
				</div>

				<div class="advace_search_filter_html">
					<p class="order_by_filter_inner_html"><label><?php esc_html_e( 'Filter', 'woo-advance-search' ); ?></label>
						<select name="advance_search_filter_results" class="advance_search_filter_dropdown">
							<?php
							$option_array_order_by = array(
								"Order_By_Price" => "Order by price",
								"Order_by_date"   => "Order by date",
								"Product_Title"  => "Order by title",
							);
							if ( is_array( $option_array_order_by ) ) {
								foreach ( $option_array_order_by as $key => $single_option_array_order_by ) { ?>
									<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $advance_search_filter_results, $key ); ?>><?php echo esc_html__( $single_option_array_order_by ) ?></option>
									<?php
								}
							}
							?>
						</select>
					</p>
				</div>

			</div>
		</form>
	</div>
	<?php

	return ob_get_clean();
}

function was_get_products_data_object( $product_id ) {
	$_products = wc_get_product( $product_id );

	return $_products;
}
function was_get_product_type( $product_id ) {
	$_products = was_get_products_data_object( $product_id );
	if ( isset( $_products ) ) {
		if ( $_products->is_type( 'simple' ) ) {
			$product_type = 'simple';
		} elseif ( $_products->is_type( 'variable' ) ) {
			$product_type = 'variable';
		} elseif ( $_products->is_type( 'grouped' ) ) {
			$product_type = 'grouped';
		} elseif ( $_products->is_type( 'external' ) ) {
			$product_type = 'external';
		}

	}


	return $product_type;
}
function was_get_product_price( $product_id ) {
	$_products    = was_get_products_data_object( $product_id );
	$price        = '';
	$woo_currency = get_woocommerce_currency_symbol( get_woocommerce_currency() );
	$prduct_type  = was_get_product_type( $product_id );
	if ( 'variable' === $prduct_type ) {
		$max_price = $_products->get_variation_price( 'max', true );
		$max_price = ( ! empty( $max_price ) && isset( $max_price ) ) ? $max_price : '';
		$min_price = $_products->get_variation_price( 'min', true );
		$min_price = ( ! empty( $min_price ) && isset( $min_price ) ) ? $min_price : '';
		if ( '' !== $min_price && '' !== $max_price ) {
			$price = ( $min_price === $max_price ) ? sprintf( '%2$s%1$s', $max_price, $woo_currency ) : sprintf( '%3$s%1$s - %3$s%2$s', $min_price, $max_price, $woo_currency );
		}
	} elseif ( 'grouped' === $prduct_type ) {
		$children_products = $_products->get_children();
		$price             = array();
		foreach ( $children_products as $children_product ) {
			$_children_products         = was_get_products_data_object( $children_product );
			$_children_sale_price       = $_children_products->get_sale_price();
			$_children_regular_price    = $_children_products->get_price();
			$final_price                = ( ! empty( $_children_sale_price ) ) ? $_children_sale_price : $_children_regular_price;
			$price[ $children_product ] = $final_price;
		}

		$price = array( min( $price ), max( $price ) );
		$price = $price[0] !== $price[1] ? sprintf( '%1$s%2$s - %1$s%3$s', $woo_currency, $price[0], $price[1] ) : sprintf( '%2$s%1$s', $price[1], $woo_currency );
	} else {
		$regular_price = $_products->get_price();
		$sale_price    = $_products->get_sale_price();
		$price         = ! empty( $sale_price ) ? $sale_price : $regular_price;

	}

	return $price;
}

function was_get_formatted_price( $price, $product_id ) {
	$woo_currency = get_woocommerce_currency_symbol( get_woocommerce_currency() );
	$prduct_type  = was_get_product_type( $product_id );
	if ( 'variable' === $prduct_type || 'grouped' === $prduct_type ) {
		$formatted_price = ( ! empty( $price ) && isset( $price ) ) ? $price : '';
	} else {
		$formatted_price = ( ! empty( $price ) && isset( $price ) ) ? $woo_currency . number_format( $price, 2 ) : '';
	}

	return $formatted_price;
}

function was_get_product_description($product_id){
	$_products    = was_get_products_data_object( $product_id );
	$description = $_products->get_description();
	$short_description = $_products->get_short_description();
	$product_description = ! empty($short_description) ? $short_description : $description;
	return $product_description;
}

function was_get_allow_html_in_escaping(){
$allow_html_args = array(
	'input'      => array(
		'type'  => array(
			'checkbox' => true,
			'text'     => true,
			'submit'   => true,
			'button'   => true,
			'file'  => true,
			'hidden' => true,
		),
		'class' => true,
		'name' => true,
		'value' => true,
		'id'    => true,
		'style'    => true,
		'selected' => true,
		'checked' => true,
		'placeholder' => true,
	),
	'select'     => array(
		'id' => true,
		'data-placeholder' => true,
		'name' => true,
		'multiple' => true,
		'class' => true,
		'style' => true
	),
	'form' => array('name' => true, 'id'=> true,'action'=> true, 'method' => true),
	'a'          => array( 'href' => array(), 'title' => array(), 'target' => array() ),
	'b'          => array( 'class' => true ),
	'i'          => array( 'class' => true ),
	'p'          => array( 'class' => true ),
	'blockquote' => array( 'class' => true ),
	'h2'         => array( 'class' => true ),
	'h3'         => array( 'class' => true ),
	'ul'         => array( 'class' => true ),
	'ol'         => array( 'class' => true ),
	'li'         => array( 'class' => true ),
	'option'     => array( 'value' => true, 'selected' => true ),
	'table'      => array( 'class' => true ),
	'td'         => array( 'class' => true ),
	'th'         => array( 'class' => true, 'scope' => true ),
	'tr'         => array( 'class' => true ),
	'tbody'      => array( 'class' => true ),
	'label'      => array( 'for' => true ),
	'strong'     => true,
	'div'      => array(
		'id'    => true,
		'class'    => true,
		'title'    => true,
		'style'    => true,

	),
	'textarea'   => array(
		'id'    => true,
		'class' => true,
		'name'  => true,
		'style' => true
	),
);
	return $allow_html_args;
}
