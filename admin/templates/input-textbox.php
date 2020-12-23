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

printf(
	'<p class="label">%1$s</p></label><input type="%2$s" class="regular-text %3$s" name="%4$s" id="%4$s" value="%5$s" %6$s /><span class="label-after-input">%7$s</span>',
	esc_html( $args['label'] ),
	esc_attr( $args['type'] ),
	esc_attr( $args['css_class'] ),
	esc_attr( $args['name'] ),
	esc_attr( $db_value ),
	wp_kses_post( $args['other_attr'] ),
	esc_html( $args['end_label'] )
);
