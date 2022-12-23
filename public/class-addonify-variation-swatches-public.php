<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.addonify.com
 * @since      1.0.0
 *
 * @package    Addonify_Variation_Swatches
 * @subpackage Addonify_Variation_Swatches/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Addonify_Variation_Swatches
 * @subpackage Addonify_Variation_Swatches/public
 * @author     Addonify <info@addonify.com>
 */
class Addonify_Variation_Swatches_Public extends Addonify_Variation_Swatches_Helper {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	protected $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Is Tooltip enabled ?
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $enable_tooltip    Is tooltip enabled ?
	 */
	private $enable_tooltip;

	/**
	 * Show in archive ?
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $show_in_archive    Show in archive or shop page ?
	 */
	private $show_in_archive;

	/**
	 * Show single attribute ?
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $show_single_attribute    Show single attribute ?
	 */
	private $show_single_attribute;

	/**
	 * Display position of variation in shop or archives
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $display_position_in_shop    Variation display position
	 */
	private $display_position_in_shop;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		if ( ! is_admin() ) {
			$this->enable_tooltip           = intval( $this->get_db_values( 'enable_tooltip' ) );
			$this->show_in_archive          = intval( $this->get_db_values( 'show_on_archives' ) );
			$this->show_single_attribute    = intval( $this->get_db_values( 'archive_show_single_attribute' ) );
			$this->display_position_in_shop = strval( $this->get_db_values( 'display_position' ) );
		}

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		if ( is_rtl() ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/build/css/addonify-variation-swatches-public-rtl.css', array(), $this->version );
		} else {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/build/css/addonify-variation-swatches-public.css', array(), $this->version );
		}

		$this->generate_custom_styles_callback();

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		if ( $this->enable_tooltip ) {

			// popper js.
			wp_enqueue_script( '__ADDONIFY__CORE__POPPER__', plugin_dir_url( __FILE__ ) . 'assets/build/js/conditional/popper.min.js', array( 'jquery' ), $this->version, false );

			// tippy js.
			wp_enqueue_script( '__ADDONIFY__CORE__TIPPY__', plugin_dir_url( __FILE__ ) . 'assets/build/js/conditional/tippy-bundle.min.js', array( 'jquery' ), $this->version, false );
		}

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/build/js/addonify-variation-swatches-public.min.js', array( 'jquery' ), $this->version, false );

		$localize_args = array(
			'ajax_url'              => admin_url( 'admin-ajax.php' ),
			'enable_tooltip'        => $this->enable_tooltip,
			'show_single_attribute' => $this->show_single_attribute,
			'is_shop_page'          => ( ( is_shop() || is_archive() ) ? 1 : 0 ),
			'shop_page_url'         => wc_get_page_permalink( 'shop' ),
		);

		// localize script.
		wp_localize_script(
			$this->plugin_name,
			'addonify_vs_object',
			$localize_args
		);

	}

	/**
	 * Markups for our custom variation output
	 *
	 * @since    1.0.0
	 * @param string $html HTML to output.
	 * @param array  $args Additional arguments.
	 */
	public function filter_dropdown_variation_attributes_contents( $html, $args ) {

		if ( is_archive() || is_shop() ) {

			// if "Show on archives" options is activated.
			if ( 1 !== $this->show_in_archive ) {
				return $html;
			}
		}

		$attribute_type = '';

		foreach ( $this->get_all_attributes() as $attr ) {
			if ( in_array( str_replace( 'pa_', '', $args['attribute'] ), $attr, true ) ) {
				$attribute_type = $attr['type'];
				break;
			}
		}

		// If auto dropdown to Button is enabled.
		$auto_dropdown_to_btn = intval( $this->get_db_values( 'auto_dropdown_to_btn' ) );
		if ( $auto_dropdown_to_btn ) {
			if ( 'select' === $attribute_type || empty( $attribute_type ) ) {
				$attribute_type = 'button';
			}
		} else {
			if ( empty( $attribute_type ) ) {
				return $html;
			}
		}

		// css class to output in template.
		$css_class = array( 'addonify-vs-attributes-options', "addonify-vs-attributes-options-{$attribute_type}" );

		$data = array();

		foreach ( $args['options'] as $option_key => $option ) {

			$term = get_term_by( 'slug', $option, $args['attribute'] );

			if ( 'image' === $attribute_type ) {
				$attachment_id = intval( get_option( "{$this->plugin_name}_attr_image_{$term->term_id}" ) );

				if ( ! $attachment_id ) {
					return $html;
				}

				// Data to outout.
				$data[ $option ] = array( wp_get_attachment_image_src( $attachment_id )[0], $term->name );

			} else {

				if ( $term ) {
					// Data to outout.
					$data[ $option ] = $term->name;
				} else {
					// Data to outout.
					$data[ $option ] = $option;
				}
			}
		}

		$html .= $this->get_public_templates(
			"attributes-options-{$attribute_type}",
			false,
			array(
				'options'   => $data,
				'css_class' => implode( ' ', $css_class ),
			)
		);

		return $html;
	}


	/**
	 * Filter markups of main variation dropdown field.
	 *
	 * @since    1.0.0
	 * @param array $args Arguments.
	 */
	public function filter_variation_dropdown_html_callback( $args ) {

		// if "auto dropdown to button" is disabled.
		if ( 1 !== intval( $this->get_db_values( 'auto_dropdown_to_btn' ) ) ) {

			$attribute_type = '';

			foreach ( $this->get_all_attributes() as $attr ) {
				if ( in_array( str_replace( 'pa_', '', $args['attribute'] ), $attr, true ) ) {
					$attribute_type = $attr['type'];
					break;
				}
			}

			if ( empty( $attribute_type ) ) {
				return $args;
			}
		}

		$args['class'] = 'hide hidden addonify-vs-attributes-options-select';
		return $args;
	}


	/**
	 * Generate custom style tag and print it in header of the website
	 *
	 * @since    1.0.0
	 */
	private function generate_custom_styles_callback() {

		// add attribute options as css class into body tag.
		add_filter(
			'body_class',
			function( $classes ) {
				$css_classes = array(
					'addonify-vs-attributes-style-' . $this->get_db_values( 'shape' ),
					'addonify-vs-disabled-' . $this->get_db_values( 'attribute_behavior' ),
				);

				return array_merge( $classes, $css_classes );
			}
		);

		// CSS for attributes width, height and font size.
		$style_args = array(

			// tooltip.
			'.tippy-box'                           => array(
				'background-color' => 'tooltip_bck_color',
				'color'            => 'tooltip_text_color',
			),
			'.tippy-box > .tippy-arrow::before'    => array(
				'border-top-color' => array( 'tooltip_bck_color', ' !important' ),
			),
			'.tippy-box > .tippy-svg-arrow'        => array(
				'fill' => 'tooltip_bck_color',
			),
			'ul.addonify-vs-attributes-options li .adfy-vs' => array(
				'width'  => array( 'attribute_width', 'px', 30 ),
				'height' => array( 'attribute_height', 'px', 30 ),
			),
			'ul.addonify-vs-attributes-options li' => array(
				'font-size' => array( 'attribute_font_size', 'px', 16 ),
			),
		);

		// append custom styles into main plugin css file.
		wp_add_inline_style( $this->plugin_name, $this->generate_styles_markups( $style_args ) );

		// CSS for archive or shop page.
		if ( $this->show_in_archive && ( is_shop() || is_archive() ) ) {

			// add attribute options as css class into body tag.
			add_filter(
				'body_class',
				function( $classes ) {
					$css_classes = array(
						'addonify-vs-archive-align-' . $this->get_db_values( 'swatches_align' ),
					);
					return array_merge( $classes, $css_classes );
				}
			);

			// css styles.
			$style_args = array(
				'ul.addonify-vs-attributes-options li .adfy-vs' => array(
					'width'  => array( 'archive_attribute_width', 'px', 30 ),
					'height' => array( 'archive_attribute_height', 'px', 30 ),
				),

				'ul.addonify-vs-attributes-options li' => array(
					'font-size' => array( 'archive_attribute_font_size', 'px', 16 ),
				),
			);

			// appnend custom styles into main plugin css file.
			wp_add_inline_style( $this->plugin_name, $this->generate_styles_markups( $style_args ) );
		}

		// do not continue if plugin styles are disabled.
		if ( ! $this->get_db_values( 'load_styles_from_plugin' ) ) {
			return;
		}

		$custom_css = $this->get_db_values( 'custom_css' );

		$style_args = array(
			'ul.addonify-vs-attributes-options li'       => array(
				'color'            => 'item_text_color',
				'background-color' => 'item_bck_color',
			),
			'ul.addonify-vs-attributes-options li:hover' => array(
				'color'            => 'item_text_color_hover',
				'background-color' => 'item_bck_color_hover',
			),
			'ul.addonify-vs-attributes-options li.selected' => array(
				'color'            => 'selected_item_text_color',
				'background-color' => 'selected_item_bck_color',
				'border-color'     => 'selected_item_border_color',
			),
		);

		$custom_styles_output = $this->generate_styles_markups( $style_args );

		// avoid empty style tags.
		if ( $custom_styles_output || $custom_css ) {
			// append custom styles into main plugin css file.
			wp_add_inline_style( $this->plugin_name, $custom_styles_output . $custom_css );
		}
	}


	/**
	 * Disable out of stock variations
	 *
	 * @since    1.0.0
	 * @param string $active    Active.
	 * @param object $variation Variation.
	 */
	public function disable_out_of_stock_variations_callback( $active, $variation ) {
		if ( ! $variation->is_in_stock() ) {
			return false;
		}
		return $active;
	}


	/**
	 * This will show custom variation markup after add to cart button
	 *
	 * @since    1.0.0
	 */
	public function show_variation_before_add_to_cart_in_loop_callback() {

		if ( 'before_add_to_cart' === $this->display_position_in_shop ) {
			global $product;

			if ( ! $product->is_type( 'variable' ) ) {
				return;
			}

			if ( is_archive() || is_shop() ) {

				// If "Show on archives" options is activated.
				if ( 1 === $this->show_in_archive ) {
					// show variation table in shop loop.
					woocommerce_variable_add_to_cart();
				}
			}
		}
	}


	/**
	 * This will show custom variation markup after add to cart button in shop page.
	 *
	 * @since    1.0.0
	 */
	public function show_variation_after_add_to_cart_in_loop_callback() {

		if ( 'after_add_to_cart' === $this->display_position_in_shop ) {
			global $product;

			if ( ! $product->is_type( 'variable' ) ) {
				return;
			}

			if ( is_archive() || is_shop() ) {

				// if "Show on archives" options is activated.
				if ( 1 === $this->show_in_archive ) {

					// show variation table in shop loop.
					woocommerce_variable_add_to_cart();
				}
			}
		}
	}

	/**
	 * Override woocommerce template
	 *
	 * @since    1.0.0
	 * @param string $template  Template.
	 * @param string $template_name Template name.
	 * @param string $template_path Template path.
	 */
	public function override_woo_template( $template, $template_name, $template_path ) {

		// Do not continue, if "Show on archives" options is not activated.
		if ( 1 !== $this->show_in_archive ) {
			return $template;
		}

		// Continue, only if is shop or is archive template.
		if ( ! is_shop() && ! is_archive() ) {
			return $template;
		}

		// Removing add to cart button and quantities from default html markups.
		remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20 );

		global $woocommerce;

		$_template = $template;

		if ( ! $template_path ) {
			$template_path = $woocommerce->template_url;
		}

		$theme_path  = untrailingslashit( get_stylesheet_directory() . '/addonify/' . $this->plugin_name . '/woocommerce/' );
		$plugin_path = untrailingslashit( plugin_dir_path( __DIR__ ) ) . '/public/templates/woocommerce/';

		// first look in themes/addonify/plugin_name/woocommerce/ as first priority.
		if ( file_exists( $theme_path . $template_name ) ) {
			$template = $plugin_path . $template_name;
		} elseif ( file_exists( $plugin_path . $template_name ) ) {
			// then look in our plugins public templates folder / woocommerce folder.
			$template = $plugin_path . $template_name;
		} else {
			// load default woocommerce template.
			$template = locate_template(
				array(
					$template_path . $template_name,
					$template_name,
				)
			);
		}

		if ( ! $template ) {
			$template = $_template;
		}

		return $template;
	}


	/**
	 * Show add to cart button in shop page
	 *
	 * @since    1.0.0
	 * @param string $button  Database Option Name.
	 * @param object $product Default Value.
	 */
	public function show_add_to_cart_btn_in_shop_page( $button, $product ) {

		if ( 1 !== $this->show_in_archive ) {
			return $button;
		}

		if ( is_archive() && $product->is_type( 'variable' ) ) {

			$product_id  = $product->get_id();
			$product_sku = $product->get_sku();
			$product_url = wc_get_page_permalink( 'shop' );
			$product_url = add_query_arg( 'add-to-cart', $product_id, $product_url );

			$quantity = isset( $args['quantity'] ) ? $args['quantity'] : 1;
			$text     = __( 'Add to cart', 'addonify-variation-swatches' );

			$button .= '<a rel="nofollow" href="' . $product_url . '" data-quantity="' . $quantity . '" data-product_id="' . $product_id . '" data-product_sku="' . $product_sku . '" class="addonify_vs-add_to_cart-button button product_type_simple add_to_cart_button ajax_add_to_cart add-to-cart" aria-label="Add to cart" style="display: none;">' . $text . '</a>';

		}

		return $button;

	}


	/**
	 * Get Database values for selected fields
	 *
	 * @since    1.0.0
	 * @param string $field_name                Database Option Name.
	 * @param array  $default                   Default Value.
	 * @param bool   $get_default_automatically Get default value if "$default" is empty.
	 */
	protected function get_db_values( $field_name, $default = null, $get_default_automatically = true ) {

		if ( empty( $default ) && true === $get_default_automatically ) {

			$default = $this->get_default_values( $field_name );
		}

		return get_option( ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . $field_name, $default );
	}


	/**
	 * Get Default values for selected options in admin page
	 *
	 * @since    1.0.0
	 * @param    string $field_name Database Option Name.
	 */
	private function get_default_values( $field_name ) {

		if ( empty( $this->default_input_values ) ) {
			$this->default_input_values = get_option( ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'default_values' );
		}

		return $this->default_input_values[ ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . $field_name ];
	}


}
