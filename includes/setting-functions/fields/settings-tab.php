<?php
/**
 * The file to define general settings.
 *
 * @since      1.0.7
 * @package    Addonify_Wishlist
 * @subpackage Addonify_Wishlist/includes/setting-functions
 * @author     Addonify <contact@addonify.com>
 */

if ( ! function_exists( 'addonify_variation_swatches_general_option_fields' ) ) {
	/**
	 * General setting fields of plugin.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	function addonify_variation_swatches_general_option_fields() {
		return array(
			'enable_swatches' => array(
				'label' => __( 'Enable Swatches', 'addonify-variation' ),
				'type'  => 'switch',
				'value' => addonify_variation_get_option( 'enable_swatches' ),
			),
		);
	}
}

if ( ! function_exists( 'addonify_variation_swatches_attribute_settings_fields' ) ) {
	/**
	 * Attribute fields.
	 *
	 * @return array
	 */
	function addonify_variation_swatches_attribute_settings_fields() {
		return array(
			'convert_dropdown_as'                  => array(
				'type'        => 'select',
				'label'       => __( 'Convert Dropdown Select To', 'addonify-variation' ),
				'description' => __( 'Convert all dropdown selects to the selected type', 'addonify-variation' ),
				'choices'     => array(
					'default' => __( 'Dropdown Select', 'addonify-variation' ),
					'button'  => __( 'Button', 'addonify-variation' ),
				),
				'dependent'   => array( 'enable_swatches' ),
				'value'       => addonify_variation_get_option( 'convert_dropdown_as' ),
			),
			'behaviour_for_disabled_variation'     => array(
				'type'      => 'select',
				'label'     => __( 'Behaviour for Disabled Variation', 'addonify-variation' ),
				'choices'   => array(
					'default'         => __( 'Hide', 'addonify-variation' ),
					'blur'            => __( 'Blur', 'addonify-variation' ),
					'blur_with_cross' => __( 'Blur with Cross', 'addonify-variation' ),
				),
				'dependent' => array( 'enable_swatches' ),
				'value'     => addonify_variation_get_option( 'behaviour_for_disabled_variation' ),
			),
			'behaviour_for_out_of_stock_variation' => array(
				'type'      => 'select',
				'label'     => __( 'Behaviour for Out of Stock Variation', 'addonify-variation' ),
				'choices'   => array(
					'default'         => __( 'Hide', 'addonify-variation' ),
					'blur'            => __( 'Blur', 'addonify-variation' ),
					'blur_with_cross' => __( 'Blur with Cross', 'addonify-variation' ),
				),
				'dependent' => array( 'enable_swatches' ),
				'value'     => addonify_variation_get_option( 'behaviour_for_disabled_variation' ),
			),
			'deselect_reselected_attribute'        => array(
				'label'       => __( 'Deselect Reselected Attribute', 'addonify-variation' ),
				'description' => __( 'An attribute that has already been selected is selected again, will deselect it', 'addonify-variation' ),
				'type'        => 'switch',
				'dependent'   => array( 'enable_swatches' ),
				'value'       => addonify_variation_get_option( 'deselect_reselected_attribute' ),
			),
		);
	}
}

if ( ! function_exists( 'addonify_variation_swatches_shop_catalog_settings_fields' ) ) {
	/**
	 * Shop catalogue fields.
	 *
	 * @return array
	 */
	function addonify_variation_swatches_shop_catalog_settings_fields() {
		return array(
			'show_swatches_on_archives'                 => array(
				'label'     => __( 'Enable Swatches On Archives', 'addonify-variation' ),
				'type'      => 'switch',
				'dependent' => array( 'enable_swatches' ),
				'value'     => addonify_variation_get_option( 'show_swatches_on_archives' ),
			),
			'align_swatches_on_archives'                => array(
				'label'     => __( 'Align Swatches', 'addonify-variation' ),
				'type'      => 'select',
				'choices'   => array(
					'center' => __( 'Center', 'addonify-variation' ),
					'right'  => __( 'Right', 'addonify-variation' ),
				),
				'dependent' => array( 'enable_swatches', 'show_swatches_on_archives' ),
				'value'     => addonify_variation_get_option( 'behaviour_for_disabled_variation' ),
			),
			'display_variation_name_and_variations_on_archives' => array(
				'label'     => __( 'Display Variation Name and Variations on', 'addonify-variation' ),
				'type'      => 'select',
				'choices'   => array(
					'default'   => __( 'Same Line', 'addonify-variation' ),
					'next_line' => __( 'Next Line', 'addonify-variation' ),
				),
				'dependent' => array( 'enable_swatches', 'show_swatches_on_archives' ),
				'value'     => addonify_variation_get_option( 'behaviour_for_disabled_variation' ),
			),
			'display_variation_description_on_archives' => array(
				'label'     => __( 'Display Variation Description', 'addonify-variation' ),
				'type'      => 'switch',
				'dependent' => array( 'enable_swatches', 'show_swatches_on_archives' ),
				'value'     => addonify_variation_get_option( 'display_variation_description_on_archives' ),
			),
		);
	}
}

if ( ! function_exists( 'addonify_variation_swatches_product_single_settings_fields' ) ) {
	/**
	 * Single Product fields.
	 *
	 * @return array
	 */
	function addonify_variation_swatches_product_single_settings_fields() {
		return array(
			'show_swatches_on_single'  => array(
				'label'     => __( 'Enable Swatches On Single', 'addonify-variation' ),
				'type'      => 'switch',
				'dependent' => array( 'enable_swatches' ),
				'value'     => addonify_variation_get_option( 'show_swatches_on_single' ),
			),
			'align_swatches_on_single' => array(
				'label'     => __( 'Align Swatches', 'addonify-variation' ),
				'type'      => 'select',
				'choices'   => array(
					'left'   => __( 'Left', 'addonify-variation' ),
					'center' => __( 'Center', 'addonify-variation' ),
					'right'  => __( 'Right', 'addonify-variation' ),
				),
				'dependent' => array( 'enable_swatches', 'show_swatches_on_single' ),
				'value'     => addonify_variation_get_option( 'align_swatches_on_single' ),
			),
			'display_variation_name_and_variations_on_single' => array(
				'label'     => __( 'Display Variation Name and Variations on', 'addonify-variation' ),
				'type'      => 'select',
				'choices'   => array(
					'default'   => __( 'Same Line', 'addonify-variation' ),
					'next_line' => __( 'Next Line', 'addonify-variation' ),
				),
				'dependent' => array( 'enable_swatches', 'show_swatches_on_single' ),
				'value'     => addonify_variation_get_option( 'display_variation_name_and_variations_on_single' ),
			),
		);
	}
}

