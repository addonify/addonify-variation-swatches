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
global $product;
$attribute_keys  = array_keys( $attributes );
$variations_json = wp_json_encode( $available_variations );
$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );

$display_variation_position = 'default';
if ( is_archive() ) {
	$display_variation_position = addonify_variation_get_option( 'display_variation_name_and_variations_on_archives' );
}
if ( is_singular() ) {
	$display_variation_position = addonify_variation_get_option( 'display_variation_name_and_variations_on_single' );
}

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="variations_form cart addonify_plugin_template" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo $variations_attr; //phpcs:ignore  ?>">
	<?php do_action( 'woocommerce_before_variations_form' ); ?>

	<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
		<p class="stock out-of-stock"><?php echo esc_html( apply_filters( 'woocommerce_out_of_stock_message', __( 'This product is currently out of stock and unavailable.', 'woocommerce' ) ) ); ?></p>
	<?php else : ?>
		<table class="variations" cellspacing="0">
			<tbody>
				<?php foreach ( $attributes as $attribute_name => $options ) : ?>
					<tr>
						<td class="label" <?php echo ( 'default' === $display_variation_position ) ? '' : 'style="display:none;"'; ?>>
							<label for="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>">
								<?php echo wc_attribute_label( $attribute_name ); //phpcs:ignore ?>
							</label>
						</td>
						<td class="value">
					<?php
					if ( 'default' !== $display_variation_position ) {
						?>
						<p>
							<label for="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>">
								<?php echo wc_attribute_label( $attribute_name ); //phpcs:ignore ?>
							</label>
						</p>
						<?php
					}
						wc_dropdown_variation_attribute_options(
							array(
								'options'   => $options,
								'attribute' => $attribute_name,
								'product'   => $product,
								'id'        => sanitize_title( $attribute_name ) . '-' . get_the_ID(),
								'class'     => 'addonify-vs-attributes-options-select',
							)
						);
					?>
						</td>
					</tr>
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
