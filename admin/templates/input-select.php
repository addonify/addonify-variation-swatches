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

<select name="<?php echo esc_attr( $args['name'] ); ?>" id="<?php echo esc_attr( $args['name'] ); ?>" >
<?php
foreach ( $options as $value => $label ) {
	echo '<option value="' . esc_attr( $value ) . '" ';

	if ( $db_value === $value ) {
		echo 'selected';
	}

	echo ' >' . esc_html( $label ) . '</option>';
}
?>
</select>

<?php
if ( isset( $args['end_label'] ) ) {
	echo '<span class="label-after-input">' . esc_attr( $args['end_label'] ) . '</span>';
}
