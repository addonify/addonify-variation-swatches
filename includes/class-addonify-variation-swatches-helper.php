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
class Addonify_Variation_Swatches_Helper {

	/**
	 * Default values for input fields in admin screen
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $default_input_values
	 */
	protected $default_input_values;


	/**
	 * List all attributes data, used in plugin settings page
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $all_attributes    List all attributes data
	 */
	private $all_attributes;


	/**
	 * Store values of wc_get_attribute_taxonomies()
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string  $all_attribute_taxonomies Store values of wc_get_attribute_taxonomies()
	 */
	protected $all_attribute_taxonomies;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 */
	protected function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}



	/**
	 * Check if woocommerce is active
	 *
	 * @since    1.0.0
	 */
	protected function is_woocommerce_active() {
		return class_exists( 'WooCommerce' );
	}


	/**
	 * This will create settings section, fields and register that settings in a database from the provided array data
	 *
	 * @since    1.0.0
	 * @param array $args Options for settings field.
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
	 * This will return array of all attributes used in woocommerce
	 *
	 * @since    1.0.0
	 */
	protected function get_all_attributes() {

		if ( empty( $this->all_attributes ) ) {

			$this->all_attributes = array();

			foreach ( $this->get_all_attribute_taxonomies() as $attr ) {
				$this->all_attributes[] = array(
					'label' => $attr->attribute_label,
					'name'  => $attr->attribute_name,
					'type'  => $attr->attribute_type,
				);
			}
		}
		return $this->all_attributes;
	}


	/**
	 * Return all attributes
	 *
	 * @since    1.0.0
	 */
	protected function get_all_attribute_taxonomies() {
		if ( ! $this->is_woocommerce_active() ) {
			return array();
		}
		if ( empty( $this->all_attribute_taxonomies ) ) {
			$this->all_attribute_taxonomies = wc_get_attribute_taxonomies();
		}

		return $this->all_attribute_taxonomies;
	}


	/**
	 * Output markups for form fields to show in attribute term add / edit page
	 *
	 * @since    1.0.0
	 * @param string  $attribute_type Type of attributes.
	 * @param boolean $is_edit is this edit form page or not.
	 */
	protected function taxonomy_form_markup( $attribute_type, $is_edit ) {

		$attributes  = $this->available_attributes_types( $attribute_type );
		$id          = $this->plugin_name . '_attr_' . $attribute_type;
		$label       = isset( $attributes['title'] ) ? $attributes['title'] : '';
		$name        = $id;
		$description = isset( $attributes['description'] ) ? $attributes['description'] : '';
		$term_id     = isset( $_GET['tag_ID'] ) ? intval( $_GET['tag_ID'] ) : ''; //phpcs:ignore

		if ( $term_id ) {
			$name .= '_' . $term_id;
		}

		// get markups for input field.

		ob_start();

		if ( 'color' === $attribute_type ) {
			$this->color_picker_group(
				array(
					array(
						'transparency' => false,
						'name'         => $name,
					),
				)
			);
		} elseif ( 'image' === $attribute_type ) {
			$this->wp_media_select( $name, $term_id );
		}

		$input_field_markups = ob_get_clean();
		// end ob buffer.
		// get templates for showing form elements.
		require dirname( __FILE__, 2 ) . '/admin/templates/taxonomy-form-markups.php';

	}


	/**
	 * List of attributes types and their properties
	 *
	 * @since    1.0.0
	 * @param string $type Attribute type to return.
	 */
	protected function available_attributes_types( $type = false ) {
		$types = array();

		$types['color'] = array(
			'title'       => __( 'Color', 'addonify-variation-swatchs' ),
			'description' => __( 'Select a color', 'addonify-variation-swatchs' ),
		);

		$types['image'] = array(
			'title'       => __( 'Image', 'addonify-variation-swatchs' ),
			'description' => __( 'Choose an image', 'addonify-variation-swatchs' ),
		);

		$types['button'] = array(
			'title'       => __( 'Button', 'addonify-variation-swatchs' ),
			'description' => __( 'Button', 'addonify-variation-swatchs' ),
		);

		if ( $type ) {
			return isset( $types[ $type ] ) ? $types[ $type ] : array();
		}

		return $types;
	}


	/**
	 * Output markups to show preview of either image or color in term display table
	 *
	 * @since    1.0.0
	 * @param string $column_name Name of the column to inject the markups.
	 * @param int    $term_id Taxonomy term id.
	 */
	protected function get_attr_type_preview_for_term( $column_name, $term_id ) {

		$cur_taxonomy = isset( $_REQUEST['taxonomy'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['taxonomy'] ) ) : false; //phpcs:ignore
		if ( ! $cur_taxonomy ) {
			return;
		}

		// figure out attribute type.
		$attribute_type = '';
		foreach ( $this->get_all_attribute_taxonomies() as $attr ) {
			if ( 'pa_' . $attr->attribute_name === $cur_taxonomy ) {
				$attribute_type = strtolower( $attr->attribute_type );
				break;
			}
		}

		if ( empty( $attribute_type ) ) {
			return;
		}

		if ( 'addonify_custom_attr' === $column_name ) {
			if ( 'color' === $attribute_type ) {

				echo wp_kses_post(
					sprintf(
						'<div class="addonify-vs-color-preview" style="background-color:%1$s; border: solid 1px #ccc; width: 35px; height: 35px;" ></div>',
						get_option( "{$this->plugin_name}_attr_color_{$term_id}" )
					)
				);
			} elseif ( 'image' === $attribute_type ) {

				$attachment_id = get_option( "{$this->plugin_name}_attr_image_{$term_id}" );
				$img_url       = wp_get_attachment_image_src( $attachment_id )[0];

				return sprintf(
					'<div class="addonify-vs-image-preview" ><img src="%2$s" width="35" height="35" ></div>',
					get_option( "{$this->plugin_name}_attr_color_{$term_id}" ),
					$img_url
				);
			}
		}

	}


	/**
	 * Generate style markups
	 *
	 * @since 1.0.0
	 * @param array $style_args    Style args to be processed.
	 */
	protected function generate_styles_markups( $style_args ) {
		$custom_styles_output = '';
		foreach ( $style_args as $css_sel => $property_value ) {

			$properties = '';

			foreach ( $property_value as $property => $db_field ) {

				$css_unit = '';

				if ( is_array( $db_field ) ) {
					$default  = isset( $db_field[2] ) ? $db_field[2] : '';
					$db_value = $this->get_db_values( $db_field[0], $default );
					$css_unit = isset( $db_field[1] ) ? $db_field[1] : '';
				} else {
					$db_value = $this->get_db_values( $db_field );
				}

				if ( $db_value ) {
					$properties .= $property . ': ' . $db_value . $css_unit . '; ';
				}
			}

			if ( $properties ) {
				$custom_styles_output .= $css_sel . ' {' . $properties . '}';
			}
		}

		return $custom_styles_output;
	}


	/**
	 * Require proper templates for use in front end.
	 *
	 * @since 1.0.0
	 * @param string $template_name Name of the template.
	 * @param string $require_once Require once or not?.
	 * @param string $data Additional data to pass into template.
	 */
	protected function get_public_templates( $template_name, $require_once = true, $data = array() ) {

		// First look for template in themes/addonify/plugin_name/template-name.php.
		$theme_path  = get_stylesheet_directory() . '/addonify/' . $this->plugin_name . '/' . $template_name . '.php';
		$plugin_path = dirname( __FILE__, 2 ) . '/public/templates/' . $template_name . '.php';

		// Extract keys from array to separate local variables.
		foreach ( $data as $data_key => $data_val ) {
			$$data_key = $data_val;
		}

		if ( file_exists( $theme_path ) ) {
			$template_path = $theme_path;
		} else {
			$template_path = $plugin_path;
		}

		if ( $require_once ) {
			require_once $template_path;
		} else {
			require $template_path;
		}
	}



	// -------------------------------------------------
	// form helpers for admin setting screen
	// -------------------------------------------------

	/**
	 * Output markups for text field
	 *
	 * @since    1.0.0
	 * @param array $arguments Options for generating contents.
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

			if ( ! isset( $args['label'] ) ) {
				$args['label'] = '';
			}

			require dirname( __FILE__, 2 ) . '/admin/templates/input-textbox.php';
		}
	}

	/**
	 * Output markups for toggle switch
	 *
	 * @since    1.0.0
	 * @param array $arguments Options for generating contents.
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
	 * @param array $args Options for generating contents.
	 */
	public function color_picker_group( $args ) {
		foreach ( $args as $arg ) {
			$default      = isset( $arg['default'] ) ? $arg['default'] : '';
			$transparency = isset( $arg['transparency'] ) ? $arg['transparency'] : true;
			$db_value     = ( get_option( $arg['name'] ) ) ? get_option( $arg['name'] ) : $default;

			require dirname( __FILE__, 2 ) . '/admin/templates/input-colorpicker.php';
		}
	}


	/**
	 * Output markups for checkbox input field
	 *
	 * @since    1.0.0
	 * @param array $args Options for generating contents.
	 */
	public function checkbox( $args ) {
		$default  = array_key_exists( 'default', $args ) ? $args['default'] : '';
		$db_value = intval( get_option( $args['name'], $default ) );
		$attr     = ( array_key_exists( 'attr', $args ) ) ? $args['attr'] : '';

		if ( 1 === $db_value ) {
			$attr .= 'checked="checked"';
		}

		$end_label = ( array_key_exists( 'end_label', $args ) ) ? $args['end_label'] : '';

		require dirname( __FILE__, 2 ) . '/admin/templates/input-checkbox.php';
	}


	/**
	 * Output markups for select input field
	 *
	 * @since    1.0.0
	 * @param array $arguments Options for generating contents.
	 */
	public function select( $arguments ) {
		foreach ( $arguments as $args ) {
			$options  = ( array_key_exists( 'options', $args ) ) ? $args['options'] : array();
			$default  = ( array_key_exists( 'default', $args ) ) ? $args['default'] : '';
			$db_value = get_option( $args['name'], $default );

			require dirname( __FILE__, 2 ) . '/admin/templates/input-select.php';
		}
	}


	/**
	 * Output markups for text area
	 *
	 * @since    1.0.0
	 * @param array $arguments Options for generating contents.
	 */
	public function text_area( $arguments ) {
		foreach ( $arguments as $args ) {
			$placeholder = isset( $args['placeholder'] ) ? $args['placeholder'] : '';
			$db_value    = get_option( $args['name'], $placeholder );
			$attr        = isset( $args['attr'] ) ? $args['attr'] : '';

			require dirname( __FILE__, 2 ) . '/admin/templates/input-textarea.php';
		}
	}


	/**
	 * Output markups for media select input field
	 *
	 * @since    1.0.0
	 * @param string $name Name of the input field.
	 * @param int    $term_id Taxonomy term id.
	 */
	public function wp_media_select( $name, $term_id ) {
		$default_img   = ADDONIFY_VARIATION_SWATCHES_ROOT_PATH . '/admin/images/placeholder.png';
		$attachment_id = get_option( "{$this->plugin_name}_attr_image_{$term_id}" );
		if ( wp_get_attachment_image_src( $attachment_id ) ) {
			$img_url = wp_get_attachment_image_src( $attachment_id )[0];
		}
		require dirname( __FILE__, 2 ) . '/admin/templates/media-selector.php';
	}

}
