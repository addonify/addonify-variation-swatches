<?php
	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	$all_attributes = $product->get_attributes(); //variations[ 'attributes' ];

	$attributes = array();
	
	foreach ( $all_attributes as $attr_key => $attr ) {
		
		foreach ($attr->get_data()['options']  as $term_key => $term_id ) {

			if ( is_numeric( $term_id ) ) {
				$term = get_term_by( 'id', $term_id, $attr_key );
				$attributes[ $attr_key ][ $term_key ] = $term->slug;
			} else {
				$attributes[ $attr_key ][ $term_key ] = $term_id;
			}

		}
	}


	// 	$attribute_keys       = array_keys( $attributes );
	$available_variations = $product->get_available_variations();
// 	$product              = $args[ 'product' ];
	
// 	if ( empty( $available_variations ) && false !== $available_variations ) {
// 		return;
// 	}
	
// 	$show_clear   = (bool) woo_variation_swatches()->get_option( 'show_clear_on_archive' );
	$catalog_mode = false; // (bool) woo_variation_swatches()->get_option( 'enable_catalog_mode' );
	// $catalog_attribute     = 'attribute_pa_size'; //woo_variation_swatches()->get_option( 'catalog_mode_attribute' );
	
//     // Global Catalog Attribute
//     $catalog_attribute     = wc_variation_attribute_name( woo_variation_swatches()->get_option( 'catalog_mode_attribute' ) );
// 	$has_catalog_attribute = false;
	
	
// 	if ( $catalog_mode ) {
// 		$product_settings = wvs_pro_get_product_option( $product->get_id() );
// 		if ( isset( $product_settings[ 'catalog_attribute' ] ) && ! empty( $product_settings[ 'catalog_attribute' ] ) ) {
// 			$catalog_attribute = wc_variation_attribute_name( $product_settings[ 'catalog_attribute' ] );
// 		}
		
// 		foreach ( $attributes as $attribute_name => $options ) {
// 			if ( $catalog_attribute == wc_variation_attribute_name( $attribute_name ) ) {
// 				$has_catalog_attribute = true;
// 			}
// 		}
		
// 		if ( ! $has_catalog_attribute ) {
// 			return;
// 		}
// 	}
// ?>

<div class="variations_form wvs-archive-variation-wrapper" data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo htmlspecialchars( wp_json_encode( $available_variations ) ) ?>">
    <ul class="variations">
		<?php
			
			foreach ( $attributes as $attribute_name => $options ) :

				echo '<pre>';
				var_dump( $options );
				die;
				
				$selected = '';//isset( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ? wc_clean( stripslashes( urldecode( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ) ) : $product->get_variation_default_attribute( $attribute_name );
				
				if ( $catalog_mode ) {

					// if ( $catalog_attribute == wc_variation_attribute_name($attribute_name) ) {
					if ( 'pa_size' == $attribute_name ) {
						echo '<li>';
						wc_dropdown_variation_attribute_options( array( 'options' => $options, 'attribute' => $attribute_name, 'product' => $product, 'is_archive' => true ) );
						echo '</li>';
					}
				} else {
					echo '<li>';
					wc_dropdown_variation_attribute_options( array( 'options' => $options, 'attribute' => $attribute_name, 'product' => $product, 'selected' => $selected, 'is_archive' => true ) );
					echo '</li>';
				}
			endforeach;
			
			// if ( $show_clear && ! $catalog_mode ):
			// 	echo apply_filters( 'woocommerce_reset_variations_link', '<li class="reset_variations woo_variation_swatches_archive_reset_variations"><a href="#">' . esc_html__( 'Clear', 'woocommerce' ) . '</a></li>' );
			// endif;
		?>
    </ul>
</div>