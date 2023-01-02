<?php
/**
 * The file to define style settings.
 *
 * @since      1.0.7
 * @package    Addonify_Wishlist
 * @subpackage Addonify_Wishlist/includes/setting-functions
 * @author     Addonify <contact@addonify.com>
 */

if ( ! function_exists( 'addonify_variation_general_styles_settings_fields' ) ) {
	/**
	 * Style setting fields of plugin.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	function addonify_variation_general_styles_settings_fields() {

		return array(
			'load_styles_from_plugin' => array(
				'type'        => 'switch',
				'className'   => '',
				'label'       => __( 'Enable Styles from Plugin', 'addonify-variation' ),
				'description' => __( 'Enable to apply styles and colors from the plugin.', 'addonify-variation' ),
				'value'       => addonify_variation_get_option( 'load_styles_from_plugin' ),
			),
		);
	}
}

if ( ! function_exists( 'addonify_variation_color_type_attribute_styles_settings_fields' ) ) {
	/**
	 * Color Type Attribute Style setting fields of plugin.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	function addonify_variation_color_type_attribute_styles_settings_fields() {
		return array(
			'enable_tooltip_in_color_attribute'     => array(
				'label' => __( 'Enable Tooltip', 'addonify-variation' ),
				'type'  => 'switch',
				'value' => addonify_variation_get_option( 'enable_tooltip_in_color_attribute' ),
			),
			'color_attribute_height'                => array(
				'type'  => 'number',
				'label' => __( 'Height', 'addonify-variation' ),
				'value' => addonify_variation_get_option( 'color_attribute_height' ),
			),
			'color_attribute_width'                 => array(
				'type'  => 'number',
				'label' => __( 'Width', 'addonify-variation' ),
				'value' => addonify_variation_get_option( 'color_attribute_width' ),
			),
			'color_attribute_border_width'          => array(
				'type'  => 'number',
				'label' => __( 'Border Width', 'addonify-variation' ),
				'value' => addonify_variation_get_option( 'color_attribute_border_width' ),
			),
			'color_attribute_border_style'          => array(
				'type'    => 'select',
				'label'   => __( 'Border Style', 'addonify-variation' ),
				'choices' => array(
					'none'   => __( 'None', 'addonify-variation' ),
					'dotted' => __( 'Dotted', 'addonify-variation' ),
					'dashed' => __( 'Dashed', 'addonify-variation' ),
					'groove' => __( 'Groove', 'addonify-variation' ),
					'solid'  => __( 'Solid', 'addonify-variation' ),
					'double' => __( 'Double', 'addonify-variation' ),
					'ridge'  => __( 'Ridge', 'addonify-variation' ),
					'inset'  => __( 'Inset', 'addonify-variation' ),
					'outset' => __( 'Outset', 'addonify-variation' ),
				),
				'value'   => addonify_variation_get_option( 'color_attribute_border_style' ),
			),
			'color_attribute_border_color'          => array(
				'type'          => 'color',
				'label'         => __( 'Border Color', 'addonify-variation' ),
				'isAlphaPicker' => true,
				'value'         => addonify_variation_get_option( 'color_attribute_border_color' ),
			),
			'color_attribute_border_color_on_hover' => array(
				'type'          => 'color',
				'label'         => __( 'Border Color on Hover', 'addonify-variation' ),
				'isAlphaPicker' => true,
				'value'         => addonify_variation_get_option( 'color_attribute_border_color_on_hover' ),
			),
			'color_attribute_css_classes'           => array(
				'type'  => 'text',
				'label' => __( 'Custom CSS Classes', 'addonify-variation' ),
				'value' => addonify_variation_get_option( 'color_attribute_css_classes' ),
			),
		);
	}
}

if ( ! function_exists( 'addonify_variation_image_type_attribute_styles_settings_fields' ) ) {
	/**
	 * Image Type Attribute Style setting fields of plugin.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	function addonify_variation_image_type_attribute_styles_settings_fields() {
		return array(
			'enable_tooltip_in_image_attribute'     => array(
				'label' => __( 'Enable Tooltip', 'addonify-variation' ),
				'type'  => 'switch',
				'value' => addonify_variation_get_option( 'enable_tooltip_in_image_attribute' ),
			),
			'image_attribute_height'                => array(
				'type'  => 'number',
				'label' => __( 'Height', 'addonify-variation' ),
				'value' => addonify_variation_get_option( 'image_attribute_height' ),
			),
			'image_attribute_width'                 => array(
				'type'  => 'number',
				'label' => __( 'Width', 'addonify-variation' ),
				'value' => addonify_variation_get_option( 'image_attribute_width' ),
			),
			'image_attribute_background_color'      => array(
				'type'          => 'color',
				'label'         => __( 'Background Color', 'addonify-variation' ),
				'isAlphaPicker' => true,
				'value'         => addonify_variation_get_option( 'image_attribute_background_color' ),
			),
			'image_attribute_border_width'          => array(
				'type'  => 'number',
				'label' => __( 'Border Width', 'addonify-variation' ),
				'value' => addonify_variation_get_option( 'image_attribute_border_width' ),
			),
			'image_attribute_border_style'          => array(
				'type'    => 'select',
				'label'   => __( 'Border Style', 'addonify-variation' ),
				'choices' => array(
					'none'   => __( 'None', 'addonify-variation' ),
					'dotted' => __( 'Dotted', 'addonify-variation' ),
					'dashed' => __( 'Dashed', 'addonify-variation' ),
					'groove' => __( 'Groove', 'addonify-variation' ),
					'solid'  => __( 'Solid', 'addonify-variation' ),
					'double' => __( 'Double', 'addonify-variation' ),
					'ridge'  => __( 'Ridge', 'addonify-variation' ),
					'inset'  => __( 'Inset', 'addonify-variation' ),
					'outset' => __( 'Outset', 'addonify-variation' ),
				),
				'value'   => addonify_variation_get_option( 'image_attribute_border_style' ),
			),
			'image_attribute_border_color'          => array(
				'type'          => 'color',
				'label'         => __( 'Border Color', 'addonify-variation' ),
				'isAlphaPicker' => true,
				'value'         => addonify_variation_get_option( 'image_attribute_border_color' ),
			),
			'image_attribute_border_color_on_hover' => array(
				'type'          => 'color',
				'label'         => __( 'Border Color on Hover', 'addonify-variation' ),
				'isAlphaPicker' => true,
				'value'         => addonify_variation_get_option( 'image_attribute_border_image_on_hover' ),
			),
			'image_attribute_padding'               => array(
				'type'  => 'number',
				'label' => __( 'Padding', 'addonify-variation' ),
				'value' => addonify_variation_get_option( 'image_attribute_padding' ),
			),
			'image_attribute_css_classes'           => array(
				'type'  => 'text',
				'label' => __( 'Custom CSS Classes', 'addonify-variation' ),
				'value' => addonify_variation_get_option( 'image_attribute_css_classes' ),
			),
		);
	}
}

if ( ! function_exists( 'addonify_variation_button_type_attribute_styles_settings_fields' ) ) {
	/**
	 * Button Type Attribute Style setting fields of plugin.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	function addonify_variation_button_type_attribute_styles_settings_fields() {
		return array(
			'enable_tooltip_in_button_attribute'         => array(
				'label' => __( 'Enable Tooltip', 'addonify-variation' ),
				'type'  => 'switch',
				'value' => addonify_variation_get_option( 'enable_tooltip_in_button_attribute' ),
			),
			'button_attribute_height'                    => array(
				'type'  => 'number',
				'label' => __( 'Height', 'addonify-variation' ),
				'value' => addonify_variation_get_option( 'button_attribute_height' ),
			),
			'button_attribute_width'                     => array(
				'type'  => 'number',
				'label' => __( 'Width', 'addonify-variation' ),
				'value' => addonify_variation_get_option( 'button_attribute_width' ),
			),
			'button_attribute_background_color'          => array(
				'type'          => 'color',
				'label'         => __( 'Background Color', 'addonify-variation' ),
				'isAlphaPicker' => true,
				'value'         => addonify_variation_get_option( 'button_attribute_background_color' ),
			),
			'button_attribute_text_color'                => array(
				'type'          => 'color',
				'label'         => __( 'Text Color', 'addonify-variation' ),
				'isAlphaPicker' => true,
				'value'         => addonify_variation_get_option( 'button_attribute_text_color' ),
			),
			'button_attribute_background_color_on_hover' => array(
				'type'          => 'color',
				'label'         => __( 'Background Color On Hover', 'addonify-variation' ),
				'isAlphaPicker' => true,
				'value'         => addonify_variation_get_option( 'button_attribute_background_color_on_hover' ),
			),
			'button_attribute_text_color_on_hover'       => array(
				'type'          => 'color',
				'label'         => __( 'Text Color On Hover', 'addonify-variation' ),
				'isAlphaPicker' => true,
				'value'         => addonify_variation_get_option( 'button_attribute_text_color_on_hover' ),
			),
			'button_attribute_border_width'              => array(
				'type'  => 'number',
				'label' => __( 'Border Width', 'addonify-variation' ),
				'value' => addonify_variation_get_option( 'button_attribute_border_width' ),
			),
			'button_attribute_border_style'              => array(
				'type'    => 'select',
				'label'   => __( 'Border Style', 'addonify-variation' ),
				'choices' => array(
					'none'   => __( 'None', 'addonify-variation' ),
					'dotted' => __( 'Dotted', 'addonify-variation' ),
					'dashed' => __( 'Dashed', 'addonify-variation' ),
					'groove' => __( 'Groove', 'addonify-variation' ),
					'solid'  => __( 'Solid', 'addonify-variation' ),
					'double' => __( 'Double', 'addonify-variation' ),
					'ridge'  => __( 'Ridge', 'addonify-variation' ),
					'inset'  => __( 'Inset', 'addonify-variation' ),
					'outset' => __( 'Outset', 'addonify-variation' ),
				),
				'value'   => addonify_variation_get_option( 'button_attribute_border_style' ),
			),
			'button_attribute_border_color'              => array(
				'type'          => 'color',
				'label'         => __( 'Border Color', 'addonify-variation' ),
				'isAlphaPicker' => true,
				'value'         => addonify_variation_get_option( 'button_attribute_border_color' ),
			),
			'button_attribute_border_color_on_hover'     => array(
				'type'          => 'color',
				'label'         => __( 'Border Color on Hover', 'addonify-variation' ),
				'isAlphaPicker' => true,
				'value'         => addonify_variation_get_option( 'button_attribute_border_button_on_hover' ),
			),
			'button_attribute_padding'                   => array(
				'type'  => 'number',
				'label' => __( 'Padding', 'addonify-variation' ),
				'value' => addonify_variation_get_option( 'button_attribute_padding' ),
			),
			'button_attribute_font_size'                 => array(
				'type'  => 'number',
				'label' => __( 'Font Size', 'addonify-variation' ),
				'value' => addonify_variation_get_option( 'button_attribute_font_size' ),
			),
			'button_attribute_css_classes'               => array(
				'type'  => 'text',
				'label' => __( 'Custom CSS Classes', 'addonify-variation' ),
				'value' => addonify_variation_get_option( 'button_attribute_css_classes' ),
			),
		);
	}
}

if ( ! function_exists( 'addonify_variation_tooltip_styles_settings_fields' ) ) {
	/**
	 * Color Type Attribute Style setting fields of plugin.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	function addonify_variation_tooltip_styles_settings_fields() {
		return array(
			'tooltip_background_color' => array(
				'type'          => 'color',
				'label'         => __( 'Background Color', 'addonify-variation' ),
				'isAlphaPicker' => true,
				'value'         => addonify_variation_get_option( 'tooltip_background_color' ),
			),
			'tooltip_text_color'       => array(
				'type'          => 'color',
				'label'         => __( 'Text Color', 'addonify-variation' ),
				'isAlphaPicker' => true,
				'value'         => addonify_variation_get_option( 'tooltip_text_color' ),
			),
			'tooltip_padding'          => array(
				'type'  => 'number',
				'label' => __( 'Padding', 'addonify-variation' ),
				'value' => addonify_variation_get_option( 'tooltip_padding' ),
			),
			'tooltip_font_size'        => array(
				'type'  => 'number',
				'label' => __( 'Font Size', 'addonify-variation' ),
				'value' => addonify_variation_get_option( 'tooltip_font_size' ),
			),
		);
	}
}

if ( ! function_exists( 'addonify_variation_custom_styles_settings_fields' ) ) {
	/**
	 * Custom Style setting fields of plugin.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	function addonify_variation_custom_styles_settings_fields() {
		return array(
			'custom_css' => array(
				'type'        => 'textarea',
				'className'   => '',
				'label'       => __( 'Custom CSS', 'addonify-variation' ),
				'description' => __( 'Custom CSS is directly applied to pages.', 'addonify-variation' ),
				'value'       => addonify_variation_get_option( 'custom_css' ),
			),
		);
	}
}

