<?php
/**
 * The class to define REST API endpoints used in settings page.
 * This is used to define REST API endpoints used in admin settings page to get and update settings values.
 *
 * @since      1.0.7
 * @package    Addonify_Wishlist
 * @subpackage Addonify_Wishlist/includes/setting-functions
 * @author     Addonify <contact@addonify.com>
 */

if ( ! function_exists( 'addonify_variation_settings_defaults' ) ) {
	/**
	 * Default Settings
	 *
	 * @param int $setting_id Setting ID.
	 */
	function addonify_variation_settings_defaults( $setting_id = '' ) {

		$defaults = apply_filters(
			'addonify_variation_setting_defaults',
			array(
				'show_on_archives'              => true,
				'archive_show_single_attribute' => false,
				'archive_visible_attributes'    => '',
				'display_position'              => 'after_add_to_cart',
				'swatches_align'                => 'left',
				'archive_attribute_width'       => 30,
				'archive_attribute_height'      => 30,
				'archive_attribute_font_size'   => 16,
				'archive_attributes_limit'      => 0,

				'load_styles_from_plugin'       => false,

				'tooltip_text_color'            => '#ffffff',
				'tooltip_bck_color'             => '#000000',
				'item_text_color'               => '#000000',
				'item_bck_color'                => '#ffffff',
				'item_text_color_hover'         => '#000000',
				'item_bck_color_hover'          => '#ffffff',
				'selected_item_text_color'      => '#000000',
				'selected_item_bck_color'       => '#ffffff',
				'selected_item_border_color'    => '#000000',

				'custom_css'                    => '',
			)
		);

		return ( $setting_id && isset( $defaults[ $setting_id ] ) ) ? $defaults[ $setting_id ] : $defaults;
	}
}


if ( ! function_exists( 'addonify_variation_get_option' ) ) {
	/**
	 * Get stored option from db or return default if not.
	 *
	 * @param int $setting_id Setting ID.
	 * @return mixed Option value.
	 */
	function addonify_variation_get_option( $setting_id ) {

		return get_option( ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . $setting_id, addonify_variation_settings_defaults( $setting_id ) );
	}
}


if ( ! function_exists( 'addonify_variation_get_settings_values' ) ) {
	/**
	 * Get setting values.
	 *
	 * @return array Option values.
	 */
	function addonify_variation_get_settings_values() {

		if ( addonify_variation_settings_defaults() ) {

			$settings_values = array();

			$setting_fields = addonify_variation_settings_fields();

			foreach ( addonify_variation_settings_defaults() as $id => $value ) {

				$setting_type = $setting_fields[ $id ]['type'];

				switch ( $setting_type ) {
					case 'switch':
						$settings_values[ $id ] = ( (int) addonify_variation_get_option( $id ) === 1 ) ? true : false;
						break;
					case 'number':
						$settings_values[ $id ] = addonify_variation_get_option( $id );
						break;
					default:
						$settings_values[ $id ] = addonify_variation_get_option( $id );
				}
			}

			return $settings_values;
		}
	}
}


if ( ! function_exists( 'addonify_variation_update_settings' ) ) {
	/**
	 * Update settings
	 *
	 * @param string $settings Setting.
	 * @return bool true on success, false otherwise.
	 */
	function addonify_variation_update_settings( $settings = '' ) {

		if (
			is_array( $settings ) &&
			count( $settings ) > 0
		) {
			$setting_fields = addonify_variation_settings_fields();

			foreach ( $settings as $id => $value ) {

				$sanitized_value = null;

				$setting_type = $setting_fields[ $id ]['type'];

				switch ( $setting_type ) {
					case 'text':
						$sanitized_value = sanitize_text_field( $value );
						break;
					case 'textarea':
						$sanitized_value = sanitize_textarea_field( $value );
						break;
					case 'switch':
						$sanitized_value = ( true === $value ) ? '1' : '0';
						break;
					case 'number':
						$sanitized_value = (int) $value;
						break;
					case 'color':
						$sanitized_value = sanitize_text_field( $value );
						break;
					case 'select':
						$setting_choices = $setting_fields[ $id ]['choices'];
						$sanitized_value = ( array_key_exists( $value, $setting_choices ) ) ? sanitize_text_field( $value ) : $setting_choices[0];
						break;
					default:
						$sanitized_value = sanitize_text_field( $value );
				}

				if ( ! update_option( ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . $id, $sanitized_value ) ) {
					return false;
				}
			}

			return true;
		}
	}
}


if ( ! function_exists( 'addonify_variation_settings_fields' ) ) {
	/**
	 * Return settings fields.
	 *
	 * @return array Fields.
	 */
	function addonify_variation_settings_fields() {

		return apply_filters( 'addonify_variation_settings_fields', array() );
	}
}


/**
 * Add setting fields into the global setting fields array.
 *
 * @since 1.0.0
 * @param mixed $settings_fields Setting fields.
 * @return array
 */
function addonify_variation_add_fields_to_settings_fields( $settings_fields ) {

	return $settings_fields;
}
add_filter( 'addonify_variation_settings_fields', 'addonify_variation_add_fields_to_settings_fields' );


if ( ! function_exists( 'addonify_variation_get_settings_fields' ) ) {
	/**
	 * Define settings sections and respective settings fields.
	 *
	 * @since 1.0.7
	 * @return array
	 */
	function addonify_variation_get_settings_fields() {

		return array(
			'settings_values' => addonify_variation_get_settings_values(),
			'tabs'            => array(
				'settings' => array(
					'title'    => __( 'Settings', 'addonify-variation' ),
					'sections' => array(),
				),
				'styles'   => array(
					'sections' => array(),
				),
				'products' => array(
					'recommended' => array(
						// Recommend plugins here.
						'content' => __( 'Coming soon....', 'addonify-variation' ),
					),
				),
				'tools'    => array(
					'sections' => array(
						'reset-options'  => array(
							'type'   => 'options-box',
							'fields' => array(
								'reset-options' => array(
									'label'          => esc_html__( 'Reset Options', 'addonify-variation' ),
									'confirmText'    => esc_html__( 'Are you sure you want to reset all settings?', 'addonify-variation' ),
									'confirmYesText' => esc_html__( 'Yes', 'addonify-variation' ),
									'confirmNoText'  => esc_html__( 'No', 'addonify-variation' ),
									'type'           => 'reset-option',
									'description'    => esc_html__( 'All the settings will be set to default.', 'addonify-variation' ),
								),
							),
						),
						'export-options' => array(
							'type'   => 'options-box',
							'fields' => array(
								'export-options' => array(
									'label'       => esc_html__( 'Export Options', 'addonify-variation' ),
									'description' => esc_html__( 'Backup all settings that can be imported in future.', 'addonify-variation' ),
									'type'        => 'export-option',
								),
							),
						),
						'import-options' => array(
							'type'   => 'options-box',
							'title'  => esc_html__( 'Import Options', 'addonify-variation' ),
							'fields' => array(
								'import-options' => array(
									'label'       => esc_html__( 'Import Options', 'addonify-variation' ),
									'caption'     => esc_html__( 'Drop a file here or click here to upload.', 'addonify-variation' ),
									'note'        => esc_html__( 'Only .json file is permitted.', 'addonify-variation' ),
									'description' => esc_html__( 'Drag or upload the .json file that you had exported.', 'addonify-variation' ),
									'type'        => 'import-option',
									'className'   => 'fullwidth',
								),
							),
						),
					),
				),
			),
		);
	}
}
