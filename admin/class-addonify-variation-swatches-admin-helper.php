<?php
/**
 * The admin-specific helper functionality of the plugin.
 *
 * @link       https://www.addonify.com
 * @since      1.0.0
 *
 * @package    Addonify_Variation_Swatches_Helper
 * @subpackage Addonify_Variation_Swatches/admin
 */

/**
 * The admin-specific helper functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Addonify_Variation_Swatches_Helper
 * @subpackage Addonify_Variation_Swatches/admin
 * @author     Addonify <info@addonify.com>
 */
class Addonify_Variation_Swatches_Admin_Helper {

	/**
	 * Check if woocommerce is active
	 *
	 * @since    1.0.0
	 */
	protected function is_woocommerce_active() {
		return ( class_exists( 'woocommerce' ) ) ? true : false;
	}


	/**
	 * This will create settings section, fields and register that settings in a database from the provided array data
	 *
	 * @since    1.0.0
	 * @param string $args Options for settings field.
	 */
	protected function create_settings( $args ) {

		add_settings_section( $args['section_id'], $args['section_label'], $args['section_callback'], $args['screen'] );

		foreach ( $args['fields'] as $field ) {
			// create label.
			add_settings_field( $field['field_id'], $field['field_label'], $field['field_callback'], $args['screen'], $args['section_id'], $field['field_callback_args'] );

			foreach ( $field['field_callback_args'] as $sub_field ) {
				$this->default_input_values[ $sub_field['name'] ] = ( isset( $sub_field['default'] ) ) ? $sub_field['default'] : '';
				register_setting(
					$args['settings_group_name'],
					$sub_field['name'],
					array(
						'sanitize_callback' => ( isset( $sub_field['sanitize_callback'] ) ) ? array( $this, $sub_field['sanitize_callback'] ) : 'sanitize_text_field',
					)
				);
			}
		}
	}


	/**
	 * This will return array of all available thumbnail sizes
	 *
	 * @since    1.0.0
	 */
	protected function list_thumbnail_sizes() {

		$return_sizes = get_transient( $this->plugin_name . '_list_thumbnail_sizes' );

		if ( false === $return_sizes ) {
			$return_sizes = array();
			foreach ( get_intermediate_image_sizes() as $s ) {
				$return_sizes[ $s ] = $s;
			}

			// store data for 10 minutes.
			set_transient( $this->plugin_name . '_list_thumbnail_sizes', $return_sizes, MINUTE_IN_SECONDS * 10 );

		}

		return $return_sizes;
	}


	/**
	 * This will return array of all attributes used in woocommerce
	 *
	 * @since    1.0.0
	 */
	protected function get_all_attributes() {

		$return_data = get_transient( $this->plugin_name . '_get_all_attributes' );
		if ( false === $return_data ) {

			$return_data    = array();
			$all_attributes = array();
			$args = array( 
				'type' => array( 'variable' ),
			);

			foreach ( wc_get_products( $args ) as $product ) {

				foreach ( $product->get_attributes() as $attr_name => $attr ) {

					if ( ! in_array( $attr_name, $all_attributes ) ) {
						$all_attributes[] = $attr_name;
						$return_data[]    = array( 
							'label' => wc_attribute_label( $attr_name ),
							'name'  => $attr['name'],
						);
					}
				}
			}
			
			// store data for 1 week.
			// clear cache if attributes is updated or added in post.
			set_transient( $this->plugin_name . '_get_all_attributes', $return_data, WEEK_IN_SECONDS );

		}

		return $return_data;
	}



	// -------------------------------------------------
	// form helpers for admin setting screen
	// -------------------------------------------------

	/**
	 * Output markups for text field
	 *
	 * @since    1.0.0
	 * @param string $arguments Options for generating contents.
	 */
	public function text_box( $arguments ) {
		foreach ( $arguments as $args ) {
			$default  = isset( $args['default'] ) ? $args['default'] : '';
			$db_value = get_option( $args['name'], $default );

			if ( ! isset( $args['css_class'] ) ) {
				$args['css_class'] = '';
			}

			if ( ! isset( $args['type'] ) ) {
				$args['type'] = 'text';
			}

			if ( ! isset( $args['end_label'] ) ) {
				$args['end_label'] = '';
			}

			if ( ! isset( $args['other_attr'] ) ) {
				$args['other_attr'] = '';
			}

			require dirname( __FILE__ ) . '/templates/input_textbox.php';
		}
	}

	/**
	 * Output markups for toggle switch
	 *
	 * @since    1.0.0
	 * @param string $arguments Options for generating contents.
	 */
	public function toggle_switch( $arguments ) {
		foreach ( $arguments as $args ) {
			$args['attr'] = ' class="lc_switch"';
			$this->checkbox( $args );
		}
	}


	/**
	 * Output markups for color picker input field
	 *
	 * @since    1.0.0
	 * @param string $args Options for generating contents.
	 */
	public function color_picker_group( $args ) {
		foreach ( $args as $arg ) {
			$default  = isset( $arg['default'] ) ? $arg['default'] : '';
			$db_value = ( get_option( $arg['name'] ) ) ? get_option( $arg['name'] ) : $default;

			require dirname( __FILE__ ) . '/templates/input_colorpicker.php';
		}
	}


	/**
	 * Output markups for checkbox input field
	 *
	 * @since    1.0.0
	 * @param string $args Options for generating contents.
	 */
	public function checkbox( $args ) {
		$default_state = ( array_key_exists( 'checked', $args ) ) ? $args['checked'] : 1;
		$db_value      = get_option( $args['name'], $default_state );
		$is_checked    = ( $db_value ) ? 'checked' : '';
		$attr          = ( array_key_exists( 'attr', $args ) ) ? $args['attr'] : '';
		$end_label     = ( array_key_exists( 'end_label', $args ) ) ? $args['end_label'] : '';

		require dirname( __FILE__ ) . '/templates/input_checkbox.php';
	}

	/**
	 * Output markups for checkbox group
	 *
	 * @since    1.0.0
	 * @param string $args Options for generating contents.
	 */
	public function checkbox_group( $args ) {
		foreach ( $args as $arg ) {
			require dirname( __FILE__ ) . '/templates/checkbox_group.php';
		}
	}


	/**
	 * Output markups for select input field
	 *
	 * @since    1.0.0
	 * @param string $arguments Options for generating contents.
	 */
	public function select( $arguments ) {
		foreach ( $arguments as $args ) {
			$options  = ( array_key_exists( 'options', $args ) ) ? $args['options'] : array();
			$default  = ( array_key_exists( 'default', $args ) ) ? $args['default'] : '';
			$db_value = get_option( $args['name'], $default );

			require dirname( __FILE__ ) . '/templates/input_select.php';
		}
	}


	/**
	 * Output markups for select pages select field
	 *
	 * @since    1.0.0
	 * @param string $arguments Options for generating contents.
	 */
	public function select_page( $arguments ) {

		$options = array();

		foreach ( get_pages() as $page ) {
			$options[ $page->ID ] = $page->post_title;
		}

		$args                     = $arguments[0];
		$db_value                 = get_option( $args['name'] );
		$default_wishlist_page_id = get_option( ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'page_id' );

		if ( ! $db_value ) {
			$db_value = $default_wishlist_page_id;
		}

		if ( $db_value != $default_wishlist_page_id ) {
			$args['end_label'] = esc_html( 'Please insert "[addonify_wishlist]" shortcode into the content area of the page' );
		}

		require dirname( __FILE__ ) . '/templates/input_select.php';
	}


	/**
	 * Output markups for text area
	 *
	 * @since    1.0.0
	 * @param string $arguments Options for generating contents.
	 */
	public function text_area( $arguments ) {
		foreach ( $arguments as $args ) {
			$placeholder = isset( $args['placeholder'] ) ? $args['placeholder'] : '';
			$db_value    = get_option( $args['name'], $placeholder );
			$attr        = isset( $args['attr'] ) ? $args['attr'] : '';

			require dirname( __FILE__ ) . '/templates/input_textarea.php';
		}
	}


	public function taxonomy_form_markup( $options ) {

		$input_field_markups = '';

		if ( $options['input_field_type'] == 'select' ) {
			ob_start();
			$this->select( 
				array( 
					array(
						'name' => $options['name'],
						'options' => $options['options'],
					),
				),
			);
			$input_field_markups = ob_get_clean();
		}

		$label       = isset( $options['label'] ) ? $options['label'] : '';
		$name        = isset( $options['name'] ) ? $options['name'] : '';
		$description = isset( $options['description'] ) ? $options['description'] : '';

		require dirname( __FILE__ ) . '/templates/taxonomy-form-markups.php';
	}

}
