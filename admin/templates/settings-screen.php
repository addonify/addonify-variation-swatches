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

<div class="wrap">

	<h1><?php esc_html_e( 'Addonify Variation Swatches', 'addonify-variation-swatches' ); ?></h1>

	<div id="addonify-settings-wrapper">
			
		<ul id="addonify-settings-tabs">
			<li>
				<a href="<?php echo esc_url( $tab_url . 'settings' ); ?>" class="<?php echo ( 'settings' === $current_tab ? 'active' : '' ); ?>" > 
					<?php esc_html_e( 'Settings', 'addonify-variation-swatches' ); ?> 
				</a>
			</li>
			<li>
				<a href="<?php echo esc_url( $tab_url . 'styles' ); ?>" class="<?php echo ( 'styles' === $current_tab ? 'active' : '' ); ?>" > 
					<?php esc_html_e( 'Styles', 'addonify-variation-swatches' ); ?> 
				</a>
			</li>
		</ul>

		<?php if ( 'settings' === $current_tab ) : ?>

			<!-- settings tabs -->
			<form method="POST" action="options.php">
			
				<!-- generate nonce -->
				<?php settings_fields( 'variation_swatches_settings' ); ?>

				<div class="addonify-content">
					<!-- display form fields -->

					<div class="addonify-section">
						<?php do_settings_sections( $this->settings_page_slug . '-global_options' ); ?>
					</div>

					<div class="addonify-section">
						<?php do_settings_sections( $this->settings_page_slug . '-archives_page_settings' ); ?>
					</div>

				</div><!--addonify-settings-container-->

				<?php submit_button(); ?>

			</form>
		
		<?php elseif ( 'styles' === $current_tab ) : ?>

			<!-- styles tabs -->
			<form method="POST" action="options.php">
			
				<!-- generate nonce -->
				<?php settings_fields( 'variation_swatches_styles' ); ?>

				<div id="addonify-styles-container" class="addonify-content">

					<div id="addonify-style-options-container" class="addonify-section ">
						<?php do_settings_sections( $this->settings_page_slug . '-styles' ); ?>
					</div>

					<div id="addonify-content-colors-container" class="addonify-section">
						<?php do_settings_sections( $this->settings_page_slug . '-content-colors' ); ?>
					</div>
				</div><!--addonify-styles-container-->

				<?php submit_button(); ?>

			</form>

		<?php endif; ?>
	
	</div><!--addonify-settings-wrapper-->
	
</div> <!--wrap-->
