<?php
	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	$all_attributes = $product->get_attributes();
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

	$available_variations = array_values( $product->get_available_variations() );
	$selected = '';
	$catalog_mode = false;
	
?>

<div class="variations variations_form wvs-archive-variation-wrapper" data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo htmlspecialchars( wp_json_encode( $available_variations ) ) ?>">
	<?php foreach ( $attributes as $attribute_name => $options ) : ?>
		<!-- <ul class="variations hello addonify-vs-attributes-options addonify-vs-attributes-options-image"> -->

			<?php

				if ( $catalog_mode ) {
					if ( 'pa_size' == $attribute_name ) {
						wc_dropdown_variation_attribute_options( array( 'options' => $options, 'attribute' => $attribute_name, 'product' => $product, 'is_archive' => true, 'class' => 'addonify-vs-attributes-options-select' ) );
					}
				} else {
					wc_dropdown_variation_attribute_options( array( 'options' => $options, 'attribute' => $attribute_name, 'product' => $product, 'selected' => $selected, 'is_archive' => true, 'class' => 'addonify-vs-attributes-options-select' ) );
				}
			?>
		<!-- </ul> -->
	<?php endforeach; ?>
</div>