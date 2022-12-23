<?php
/**
 * Template for the admin part of the plugin.
 *
 * @link       https://www.addonify.com
 * @since      1.0.0
 *
 * @package    Addonify_Variation_Swatches
 * @subpackage Addonify_Variation_Swatches/admin/templates
 */

/**
 * Template for the admin part of the plugin.
 *
 * @since      1.0.0
 * @package    Addonify_Variation_Swatches
 * @subpackage Addonify_Variation_Swatches/admin/templates
 * @author     Addonify <info@addonify.com>
 */

// direct access is disabled.
defined( 'ABSPATH' ) || exit;
?>

<div class="addonify-vs-image-field-wrapper">
	<div class="image-preview"><img data-placeholder="<?php echo esc_url( $default_img ); ?>" src="<?php echo esc_url( ( isset( $img_url ) ? $img_url : $default_img ) ); ?>" width="60px" height="60px"/></div>
	<div class="button-wrapper">
		<input type="hidden" id="addonify-vs-term-image-id" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_url( isset( $img_url ) ); ?>"/>
		<button type="button" class="addonify-vs_select_image_button button button-primary button-small"><?php esc_html_e( 'Upload / Add image', 'addonify-variation-swatches' ); ?></button>
		<button type="button" style="<?php echo ( ! isset( $img_url ) ? 'display:none' : '' ); ?>" class="addonify-vs_remove_image_button button button-danger button-small"><?php esc_html_e( 'Remove image', 'addonify-variation-swatches' ); ?></button>
	</div>
</div>
