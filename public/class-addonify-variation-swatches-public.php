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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		if ( ! is_admin() ) {
			$this->enable_tooltip           = intval( $this->get_db_values( 'enable_tooltip', 1 ) );
			$this->show_in_archive          = intval( $this->get_db_values( 'show_on_archives', 1 ) );
			$this->show_single_attribute    = intval( $this->get_db_values( 'archive_show_single_attribute', 1 ) );
			$this->display_position_in_shop = strval( $this->get_db_values( 'display_position', 'before_add_to_cart' ) );
		}

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		if ( $this->enable_tooltip ) {
			// Tippyjs.
			wp_enqueue_style( 'tippyjs', plugin_dir_url( __FILE__ ) . 'assets/tippyjs/tippy.css', array(), $this->version );
		}

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/build/css/addonify-variation-swatches-public.css', array(), time(), 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		if ( $this->enable_tooltip ) {
			// Popperjs.
			wp_enqueue_script( 'popperjs',  plugin_dir_url( __FILE__ ) . 'assets/build/js/popper.min.js', array('jquery'), $this->version );

			// Tippyjs.
			wp_enqueue_script( 'tippyjs',  plugin_dir_url( __FILE__ ) . 'assets/tippyjs/tippy.umd.min.js', array('jquery'), $this->version );
		}

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/build/js/addonify-variation-swatches-public.min.js', array( 'jquery' ), time() );



		$localize_args = array(
			'ajax_url'       		=> admin_url( 'admin-ajax.php' ),
			'enable_tooltip' 		=> $this->enable_tooltip,
			'show_single_attribute' => $this->show_single_attribute,
		);

		// localize script
		wp_localize_script(
			$this->plugin_name,
			'addonify_vs_object',
			$localize_args
		);

	}

	// markups for our custom variation output
	public function filter_dropdown_variation_attributes_contents( $html, $args ) {

		if( is_archive() || is_shop() ){

			// if "Show on archives" options is activated
			if ( 1 !== intval( $this->get_db_values( 'show_on_archives', 1 ) ) ) {
				return $html;
			}
		}

		$attribute_type = '';

		foreach ( $this->get_all_attributes() as $attr ) {
			if ( in_array( str_replace( 'pa_', '', $args['attribute'] ), $attr ) ) {
				$attribute_type = $attr['type'];
				break;
			}
		}

		// If auto dropdown to Button is enabled.
		$auto_dropdown_to_btn = intval( $this->get_db_values( 'auto_dropdown_to_btn', 1 ) );
		if ( $auto_dropdown_to_btn ) {
			if ( $attribute_type === 'select' || empty( $attribute_type ) ) {
				$attribute_type = 'button';
			}
		}


		// css class to output in template
		$css_class =  array( 'addonify-vs-attributes-options', "addonify-vs-attributes-options-{$attribute_type}" );

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
				'options' => $data,
				'css_class' => implode(' ', $css_class ),
			) 
		);

		return $html;
	}


	// Filter markups of main variation dropdown field.
	public function filter_variation_dropdown_html_callback( $args ) {

		$args['class'] = 'hide hidden addonify-vs-attributes-options-select';

		return $args;  
	}


	/**
	 * Generate custom style tag and print it in header of the website
	 *
	 * @since    1.0.0
	 */
	public function generate_custom_styles_callback(){

		// add attribute options as css class into body tag
		add_filter( 'body_class', function( $classes ) {
			$css_classes = array(
				'addonify-vs-attributes-style-' . $this->get_db_values( 'shape', 'rounded' ),
				'addonify-vs-disabled-' . $this->get_db_values( 'attribute_behavior', 'blur_with_cross' ),
			);

			return array_merge( $classes, $css_classes );
		} );


		// CSS for attributes width, height and font size.
		$style_args = array(

			// tooltip.
			'.tippy-box' => array(
				'background-color' 	=> 'tooltip_bck_color',
				'color' 			=> 'tooltip_text_color',
			),
			'.tippy-box > .tippy-arrow::before' => array(
				'border-top-color' 	=> array( 'tooltip_bck_color', ' !important'),
			),
			'.tippy-box > .tippy-svg-arrow' => array(
				'fill' 	=> 'tooltip_bck_color',
			),

			'ul.addonify-vs-attributes-options li > *' => array(
				'width' 	=> array( 'attribute_width', 'px', 30 ),
				'height' 	=> array( 'attribute_height', 'px', 30 ),
			),

			'ul.addonify-vs-attributes-options li' => array(
				'font-size' => array( 'attribute_font_size', 'px', 16 ),
			),
			
		);

		echo "<style id=\"{$this->plugin_name}-attributes-options-styles\"  media=\"screen\"> \n" . $this->generate_styles_markups( $style_args ) . "\n </style>\n";

		
		// CSS for archive or shop page
		if ( $this->show_in_archive && ( is_shop() || is_archive() ) ) {

			// add attribute options as css class into body tag
			add_filter( 'body_class', function( $classes ) {

				$css_classes = array(
					'addonify-vs-archive-align-' . $this->get_db_values( 'swatches_align', 'left' ),
				);

				return array_merge( $classes, $css_classes );

			} );

			// css styles
			$style_args = array(
				'body.archive ul.addonify-vs-attributes-options li > *' => array(
					'width' 	=> array( 'archive_attribute_width', 'px', 30 ),
					'height' 	=> array( 'archive_attribute_height', 'px', 30 ),
				),

				'body.archive ul.addonify-vs-attributes-options li' => array(
					'font-size' => array( 'archive_attribute_font_size', 'px', 16 ),
				),
				
			);

			echo "<style id=\"{$this->plugin_name}-attributes-options-styles\"  media=\"screen\"> \n" . $this->generate_styles_markups( $style_args ) . "\n </style>\n";
		}


		// do not continue if plugin styles are disabled by user
		if( ! $this->get_db_values( 'load_styles_from_plugin' ) ) return;


		$custom_css = $this->get_db_values( 'custom_css' );

		$style_args = array(
			'ul.addonify-vs-attributes-options li' => array(
				'color' 			=> 'item_text_color',
				'background-color'	=> 'item_bck_color',
			),
			'ul.addonify-vs-attributes-options li:hover' => array(
				'color' 			=> 'item_text_color_hover',
				'background-color'	=> 'item_bck_color_hover',
			),
			'ul.addonify-vs-attributes-options li.selected' => array(
				'color' 			=> 'selected_item_text_color',
				'background-color'	=> 'selected_item_bck_color',
				'border-color'		=> 'selected_item_border_color',
				'border-width'		=> array( 'selected_item_border_width', 'px' ),
			),
			
		);

		$custom_styles_output = $this->generate_styles_markups( $style_args );

		// avoid empty style tags
		if( $custom_styles_output || $custom_css ){
			echo "<style id=\"addonify-compare-products-styles\"  media=\"screen\"> \n" . $custom_styles_output .  $custom_css . "\n </style>\n";
		}

	}


	/**
	 * Disable out of stock variations
	 *
	 * @since    1.0.0
	 * @param    $active    	Active
	 * @param    $variation		Variation
	 */
	function disable_out_of_stock_variations_callback( $active, $variation ) {
		if ( ! $variation->is_in_stock() ) {
			return false;
		}
		return $active;
	}


	// this will show custom variation markup after add to cart button
	public function show_variation_before_add_to_cart_in_loop_callback( $args ) {

		if ( 'before_add_to_cart' === $this->display_position_in_shop ) {
			global $product;

			if ( ! $product->is_type( 'variable' ) ) {
				return;
			}


			if( is_archive() || is_shop() ){

				// if "Show on archives" options is activated
				if ( 1 === $this->show_in_archive ) {
					// show variation table in shop loop.
					woocommerce_variable_add_to_cart();
				}
			}

		}
	}


	// this will show custom variation markup after add to cart button in shop page
	public function show_variation_after_add_to_cart_in_loop_callback( $args ) {

		if ( 'after_add_to_cart' === $this->display_position_in_shop ) {
			global $product;

			if ( ! $product->is_type( 'variable' ) ) {
				return;
			}


			if( is_archive() || is_shop() ){

				// if "Show on archives" options is activated
				if ( 1 === $this->show_in_archive ) {
					// show variation table in shop loop.
					woocommerce_variable_add_to_cart();
				}
			}


		}
	}

	// override woocommerce template
	public function override_woo_template( $template, $template_name, $template_path ) {

		// if "Show on archives" options is activated
		if ( 1 !== $this->show_in_archive ) {
			return $template;
		}


		if( !is_shop() && !is_archive() ) return $template;

		// Removing add to cart button and quantities only.
		remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20 );
		
		global $woocommerce;

		$_template = $template;

		if ( ! $template_path ) {
			$template_path = $woocommerce->template_url;
		}
	
		$plugin_path  = untrailingslashit( plugin_dir_path( __DIR__ ) )  . '/public/templates/woocommerce/';
	
		// first look within our plugin.
		// then look in theme.

		if( file_exists( $plugin_path . $template_name ) ) {
			$template = $plugin_path . $template_name;
		}
		else{

			$template = locate_template(
				array(
					$template_path . $template_name,
					$template_name
				)
			);

		}

		if ( ! $template ) {
			$template = $_template;
		}

		return $template;
	}


	public function show_add_to_cart_btn_in_shop_page( $button, $product ) {

		if ( 1 !== $this->show_in_archive ) {
			return $button;
		}

		if ( is_archive() && $product->is_type( 'variable' ) ) {

			$product_id = $product->get_id();
			$product_sku = $product->get_sku();
			$product_url = wc_get_page_permalink( 'shop' );
			$product_url = add_query_arg( 'add-to-cart', $product_id, $product_url );

			$quantity = isset( $args['quantity'] ) ? $args['quantity'] : 1;
			$text = __( 'Add to cart', 'addonify-variation-swatches' );
			
			$button .= '<a rel="nofollow" href="' . $product_url . '" data-quantity="' . $quantity . '" data-product_id="' . $product_id . '" data-variation_id="71" data-product_sku="' . $product_sku . '" class="addonify_vs-add_to_cart-button button product_type_simple add_to_cart_button ajax_add_to_cart add-to-cart" aria-label="Add to cart" style="display: none;">' . $text . '</a>';
			
		}
		
		return $button;

	}



	/**
	 * Get Database values for selected fields
	 *
	 * @since    1.0.0
	 * @param    $field_name    Database Option Name
	 * @param    $default		Default Value
	 */
	protected function get_db_values($field_name, $default = NULL ){
		return get_option( ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . $field_name, $default );
	}



}
