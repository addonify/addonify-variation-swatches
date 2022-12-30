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

/**
 * Include settings options fields.
 */
require_once trailingslashit( plugin_dir_path( dirname( __FILE__ ) ) ) . 'setting-functions/fields/settings-tab.php';

/**
 * Include styles options fields.
 */
require_once trailingslashit( plugin_dir_path( dirname( __FILE__ ) ) ) . 'setting-functions/fields/styles-tab.php';

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
				'enable_swatches'                        => true,

				'convert_dropdown_as'                    => 'default',
				'behaviour_for_disabled_variation'       => 'default',
				'behaviour_for_out_of_stock_variation'   => 'default',
				'deselect_reselected_attribute'          => false,

				'show_swatches_on_archives'              => false,
				'align_swatches_on_archives'             => 'left',
				'display_variation_name_and_variations_on_archives' => 'default',
				'display_variation_description_on_archives' => false,

				'show_swatches_on_single'                => false,
				'align_swatches_on_single'               => 'left',
				'display_variation_name_and_variations_on_single' => 'default',

				'load_styles_from_plugin'                => false,

				'enable_tooltip_in_color_attribute'      => false,
				'color_attribute_height'                 => 0,
				'color_attribute_width'                  => 0,
				'color_attribute_border_width'           => 0,
				'color_attribute_border_style'           => 'none',
				'color_attribute_border_color'           => '#000000',
				'color_attribute_border_color_on_hover'  => '#000000',
				'color_attribute_css_classes'            => '#000000',

				'enable_tooltip_in_image_attribute'      => false,
				'image_attribute_height'                 => 0,
				'image_attribute_width'                  => 0,
				'image_attribute_background_color'       => '#000000',
				'image_attribute_border_width'           => 0,
				'image_attribute_border_style'           => 'none',
				'image_attribute_border_color'           => '#000000',
				'image_attribute_border_color_on_hover'  => '#000000',
				'image_attribute_padding'                => 0,
				'image_attribute_css_classes'            => '',

				'enable_tooltip_in_button_attribute'     => false,
				'button_attribute_height'                => 0,
				'button_attribute_width'                 => 0,
				'button_attribute_background_color'      => '#000000',
				'button_attribute_text_color'            => '#000000',
				'button_attribute_background_color_on_hover' => '#000000',
				'button_attribute_text_color_on_hover'   => '#000000',
				'button_attribute_border_width'          => 0,
				'button_attribute_border_style'          => 'none',
				'button_attribute_border_color'          => '#000000',
				'button_attribute_border_color_on_hover' => '#000000',
				'button_attribute_padding'               => 0,
				'button_attribute_font_size'             => 0,
				'button_attribute_css_classes'           => '',

				'tooltip_background_color'               => '#000000',
				'tooltip_text_color'                     => '#000000',
				'tooltip_padding'                        => 0,
				'tooltip_font_size'                      => 0,

				'custom_css'                             => '',
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

	$settings_fields = array_merge( $settings_fields, addonify_variation_swatches_general_option_fields() );

	$settings_fields = array_merge( $settings_fields, addonify_variation_swatches_attribute_settings_fields() );

	$settings_fields = array_merge( $settings_fields, addonify_variation_swatches_shop_catalog_settings_fields() );

	$settings_fields = array_merge( $settings_fields, addonify_variation_swatches_product_single_settings_fields() );

	$settings_fields = array_merge( $settings_fields, addonify_variation_general_styles_settings_fields() );

	$settings_fields = array_merge( $settings_fields, addonify_variation_color_type_attribute_styles_settings_fields() );

	$settings_fields = array_merge( $settings_fields, addonify_variation_image_type_attribute_styles_settings_fields() );

	$settings_fields = array_merge( $settings_fields, addonify_variation_button_type_attribute_styles_settings_fields() );

	$settings_fields = array_merge( $settings_fields, addonify_variation_tooltip_styles_settings_fields() );

	$settings_fields = array_merge( $settings_fields, addonify_variation_custom_styles_settings_fields() );

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
					'sections' => array(
						'general'        => array(
							'title'  => __( 'General Options', 'addonify-wishlist' ),
							'fields' => addonify_variation_swatches_general_option_fields(),
						),
						'attributes'     => array(
							'title'  => __( 'Attributes Options', 'addonify-wishlist' ),
							'fields' => addonify_variation_swatches_attribute_settings_fields(),
						),
						'shop_catalog'   => array(
							'title'  => __( 'Shop catalogue Options', 'addonify-wishlist' ),
							'fields' => addonify_variation_swatches_shop_catalog_settings_fields(),
						),
						'single_product' => array(
							'title'  => __( 'Single Product Options', 'addonify-wishlist' ),
							'fields' => addonify_variation_swatches_product_single_settings_fields(),
						),
					),
				),
				'styles'   => array(
					'sections' => array(
						'general'               => array(
							'title'  => __( 'General', 'addonify-wishlist' ),
							'fields' => addonify_variation_general_styles_settings_fields(),
						),
						'color_type_attribute'  => array(
							'title'     => __( 'Color Type Attribute', 'addonify-wishlist' ),
							'type'      => 'options-box',
							'dependent' => array( 'load_styles_from_plugin' ),
							'fields'    => addonify_variation_color_type_attribute_styles_settings_fields(),
						),
						'image_type_attribute'  => array(
							'title'     => __( 'Image Type Attribute', 'addonify-wishlist' ),
							'type'      => 'options-box',
							'dependent' => array( 'load_styles_from_plugin' ),
							'fields'    => addonify_variation_image_type_attribute_styles_settings_fields(),
						),
						'button_type_attribute' => array(
							'title'     => __( 'Button Type Attribute', 'addonify-wishlist' ),
							'type'      => 'options-box',
							'dependent' => array( 'load_styles_from_plugin' ),
							'fields'    => addonify_variation_button_type_attribute_styles_settings_fields(),
						),
						'tooltip'               => array(
							'title'     => __( 'Tooltip', 'addonify-wishlist' ),
							'type'      => 'options-box',
							'dependent' => array( 'load_styles_from_plugin' ),
							'fields'    => addonify_variation_tooltip_styles_settings_fields(),
						),
						'custom'                => array(
							'title'     => __( 'Custom CSS', 'addonify-wishlist' ),
							'type'      => 'options-box',
							'dependent' => array( 'load_styles_from_plugin' ),
							'fields'    => addonify_variation_custom_styles_settings_fields(),
						),
					),
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
