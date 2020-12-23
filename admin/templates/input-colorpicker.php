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

<div class="colorpicker-group">
	<?php
	if ( isset( $arg['label'] ) ) {
		printf(
			'<p>%s</p>',
			esc_attr( $arg['label'] )
		);
	}

	printf(
		'<input type="text" value="%2$s" name="%1$s" id="%1$s" class="color-picker" data-alpha="%3$s" />',
		esc_attr( $arg['name'] ),
		esc_attr( $db_value ),
		esc_attr( $transparency )
	);
	?>

</div>
