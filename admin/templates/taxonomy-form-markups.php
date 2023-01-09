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

if ( $is_edit ) : ?>
	<tr class="form-field form-required">
		<th scope="row" valign="top">
			<label for="<?php echo esc_attr( $name ); ?>">Addonify <?php echo esc_html( $label ); ?></label>
		</th>
		<td>
			<?php echo $input_field_markups; // phpcs:ignore ?>
			<p class="description"><?php echo esc_html( $description ); ?></p>
		</td>
	</tr>

<?php else : ?>
	<div class="form-field">
		<label for="<?php echo esc_attr( $name ); ?>">Addonify <?php echo esc_html( $label ); ?></label>
		<?php echo $input_field_markups; // phpcs:ignore ?>
		<?php
		if ( ! empty( $description ) || '' !== $description ) {
			?>
			<p class="description"><?php echo esc_html( $description ); ?></p>
			<?php
		}
		?>
	</div>

<?php endif; ?>
