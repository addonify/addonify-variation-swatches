<?php
/**
 * Over-riding default woocommerce template.
 *
 * @link       https://www.addonify.com
 * @since      1.0.0
 *
 * @package    Addonify_Variation_Swatches
 * @subpackage Addonify_Variation_Swatches/public/templates/woocommerce/single-product/add-to-cart
 */

/**
 * Over-riding default woocommerce template.
 *
 * @since      1.0.0
 * @package    Addonify_Variation_Swatches
 * @subpackage Addonify_Variation_Swatches/public/templates/woocommerce/single-product/add-to-cart
 * @author     Addonify <info@addonify.com>
 */

// direct access is disabled.
defined( 'ABSPATH' ) || exit;
global $product, $limit_variations_markup;

// override by addonify-variation-swatches.
$catalog_mode           = intval( get_option( ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'archive_show_single_attribute' ) );
$limit_variations_count = intval( get_option( ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'archive_attributes_limit' ) );
$selected_attribute     = 'pa_' . strval( get_option( ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'archive_visible_attributes' ) );
// override end.

$attribute_keys  = array_keys( $attributes );
$variations_json = wp_json_encode( $available_variations );
$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="variations_form cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo $variations_attr; // WPCS: XSS ok. ?>">
	<?php do_action( 'woocommerce_before_variations_form' ); ?>

	<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
		<p class="stock out-of-stock"><?php echo esc_html( apply_filters( 'woocommerce_out_of_stock_message', __( 'This product is currently out of stock and unavailable.', 'woocommerce' ) ) ); ?></p>
	<?php else : ?>
		<table class="variations" cellspacing="0">
			<tbody>
				<?php foreach ( $attributes as $attribute_name => $options ) : ?>

					<?php
					// added by addonify-variation-swatches.
					if ( $catalog_mode ) :

						// added by addonify-variation-swatches.
						if ( $selected_attribute == $attribute_name ) :
							?>
						
							<tr>
								<td class="value">
									<?php

									if ( $limit_variations_count > 0 ) {
										$total_variations = count( $options );
										$options = array_slice( $options, 0, $limit_variations_count );

										if ( ( $total_variations - $limit_variations_count ) > 0 ) {

											$limit_variations_markup = '<li class="addonify-vs-item-more"><a href="' . get_permalink( $product->get_id() ) . '">+' . ( $total_variations - $limit_variations_count ) . ' More</a></li>';

											// If "limit_variations_count" is enabled.
											// Show "+1 more" link aside attributes.
											add_action(
												'addonify_vs_end_of_variation_attributes_list',
												function() {
													global $limit_variations_markup;
													echo wp_kses_post( $limit_variations_markup );
												}
											);
										}
									}

									// output markup.
									wc_dropdown_variation_attribute_options(
										array(
											'options'   => $options,
											'attribute' => $attribute_name,
											'product'   => $product,
											'id'		=> sanitize_title( $attribute_name ) . '-' . get_the_ID(),
											'class'     => 'addonify-vs-attributes-options-select',
										)
									);

									?>
								</td>
							</tr>

						<?php endif; ?>
					
					<?php else : ?>
					
						<tr>
							<td class="label"><label for="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>"><?php echo wc_attribute_label( $attribute_name ); // WPCS: XSS ok. ?></label></td>
							<td class="value">
								<?php
									wc_dropdown_variation_attribute_options(

										array(
											'options'   => $options,
											'attribute' => $attribute_name,
											'product'   => $product,
											'id'		=> sanitize_title( $attribute_name ) . '-' . get_the_ID(),
											'class'     => 'addonify-vs-attributes-options-select',
										)
									);
								?>
							</td>
						</tr>
					<?php endif; ?>
					
					<?php
						echo end( $attribute_keys ) === $attribute_name ? '<tr><td>' . wp_kses_post( apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__( 'Clear', 'woocommerce' ) . '</a>' ) ) . '</td></tr>' : '';
					?>
				<?php endforeach; ?>
			</tbody>
		</table>

		<div class="single_variation_wrap">
			<?php
				/**
				 * Hook: woocommerce_before_single_variation.
				 */
				do_action( 'woocommerce_before_single_variation' );

				/**
				 * Hook: woocommerce_single_variation. Used to output the cart button and placeholder for variation data.
				 *
				 * @since 2.4.0
				 * @hooked woocommerce_single_variation - 10 Empty div for variation data.
				 * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
				 */
				do_action( 'woocommerce_single_variation' );

				/**
				 * Hook: woocommerce_after_single_variation.
				 */
				do_action( 'woocommerce_after_single_variation' );
			?>
		</div>
	<?php endif; ?>

	<?php do_action( 'woocommerce_after_variations_form' ); ?>
</form>

<?php
do_action( 'woocommerce_after_add_to_cart_form' );
