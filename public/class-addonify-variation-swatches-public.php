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
			$this->enable_tooltip = intval( $this->get_db_values( 'enable_tooltip', 1 ) );
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
			'ajax_url'       => admin_url( 'admin-ajax.php' ),
			'enable_tooltip' => $this->enable_tooltip,
		);

		// localize script
		wp_localize_script(
			$this->plugin_name,
			'addonify_vs_object',
			$localize_args
		);

	}


	public function filter_dropdown_variation_button_callback( $html, $args ) {

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

		foreach ( $args['options'] as $option ) {

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

		// if ( is_product() ) {
			$args['class'] = 'hide hidden addonify-vs-attributes-options-select';
		// }

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
			'ul.addonify-vs-attributes-options li > *' => array(
				'width' 	=> array( 'attribute_width', 'px', 30 ),
				'height' 	=> array( 'attribute_height', 'px', 30 ),
			),

			'ul.addonify-vs-attributes-options li' => array(
				'font-size' => array( 'attribute_font_size', 'px', 16 ),
			),
			
		);

		echo "<style id=\"{$this->plugin_name}-attributes-options-styles\"  media=\"screen\"> \n" . $this->generate_styles_markups( $style_args ) . "\n </style>\n";


		// do not continue if "Enable Product Comparision" is not checked
		// do not continue if plugin styles are disabled by user
		if( ! $this->get_db_values( 'load_styles_from_plugin' ) ) return;

		return;
		


		$custom_css = $this->get_db_values('custom_css');

		$style_args = array(
			'button.addonify-cp-button' => array(
				'background' 	=> 'compare_btn_bck_color',
				'color' 		=> 'compare_btn_text_color',
				'left' 			=> 'compare_products_btn_left_offset',
				'right' 		=> 'compare_products_btn_right_offset',
				'top' 			=> 'compare_products_btn_top_offset',
				'bottom'		=> 'compare_products_btn_bottom_offset'
			),
			'#addonify-compare-modal, #addonify-compare-search-modal' => array(
				'background' 	=> 'modal_overlay_bck_color'
			),
			'.addonify-compare-model-inner, .addonify-compare-search-model-inner' => array(
				'background' 	=> 'modal_bck_color',
			),
			'#addonofy-compare-products-table th a' => array(
				'color'		 	=> 'table_title_color',
			),
			'.addonify-compare-all-close-btn svg' => array(
				'color' 		=> 'close_btn_text_color',
			),
			'.addonify-compare-all-close-btn' => array(
				'background'	=> 'close_btn_bck_color',
			),
			'.addonify-compare-all-close-btn:hover svg' => array(
				'color'		 	=> 'close_btn_text_color_hover',
			),
			'.addonify-compare-all-close-btn:hover' => array(
				'background' 	=> 'close_btn_bck_color_hover',
			),
			
		);

		$custom_styles_output = $this->generate_styles_markups( $style_args );

		// avoid empty style tags
		if( $custom_styles_output || $custom_css ){
			echo "<style id=\"addonify-compare-products-styles\"  media=\"screen\"> \n" . $custom_styles_output .  $custom_css . "\n </style>\n";
		}

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


	public function show_variation_after_add_to_cart_in_loop_callback( $args ) {

		if ( 'before_add_to_cart' === $this->get_db_values( 'display_position', 'before_add_to_cart' ) ) {
			global $product;

			if ( ! $product->is_type( 'variable' ) ) {
				return;
			}

			// $this->get_public_templates( 'archive-variation-template', false, array( 'product' => $product ) );

			// show variation table in shop loop.
			woocommerce_variable_add_to_cart();

		}
	}


}
