<?php
/**
 * Template for the admin part of the plugin.
 *
 * @link       https://www.addonify.com
 * @since      1.0.0
 *
 * @package    Addonify_Variation_Swatches
 * @subpackage Addonify_Variation_Swatches/public/templates
 */

/**
 * Template for the admin part of the plugin.
 *
 * @since      1.0.0
 * @package    Addonify_Variation_Swatches
 * @subpackage Addonify_Variation_Swatches/public/templates
 * @author     Addonify <info@addonify.com>
 */

// direct access is disabled.
defined( 'ABSPATH' ) || exit;
?>
<ul class="<?php echo esc_attr( $css_class ); ?>" >
	<?php do_action( 'addonify_vs_start_of_variation_attributes_list', 'image' ); ?>
	<?php foreach ( $options as $option_slug => $option_name ) : ?>
		<li data-value="<?php echo esc_attr( $option_slug ); ?>" data-title="<?php echo esc_attr( $option_name[1] ); ?>" ><img class="adfy-vs adfy-image-vs" src="<?php echo esc_attr( $option_name[0] ); ?>" ></li>
	<?php endforeach; ?>
	<?php do_action( 'addonify_vs_end_of_variation_attributes_list', 'image' ); ?>
</ul>
