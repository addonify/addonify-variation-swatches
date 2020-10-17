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
class Addonify_Variation_Swatches_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Instance of helper class
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $helper    Instance of helper class
	 */
	private $helper;

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

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/build/css/addonify-variation-swatches-public.css', array(), time(), 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Addonify_Variation_Swatches_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Addonify_Variation_Swatches_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/build/js/addonify-variation-swatches-public.min.js', array( 'jquery' ), time(), false );

	}


	public function filter_dropdown_variation_button_callback( $html, $args ) {

		$attribute_type = 'button';

		$helper = $this->get_helper_class();

		foreach( $helper->get_all_attributes() as $attr ) {
			if ( in_array( str_replace( 'pa_', '', $args['attribute'] ), $attr ) ) {
				$attribute_type = $attr['type'];
				break;
			}
		}

		if($attribute_type === 'select' ){
			$attribute_type = 'button';
		}

		$css_class =  array( 'addonify-vs-attributes-options' );
		
		if ( $attribute_type === 'button' ) {
			$css_class[] = 'addonify-vs-attributes-options-button';
		} elseif ( $attribute_type === 'color' ) {
			$css_class[] = 'addonify-vs-attributes-options-color';
		}

		$data = array();
		foreach ( $args['options'] as $option ) {
			$data[] = $option;
		}
		
		$html .= $this->get_templates( 
			"attributes-options-{$attribute_type}",
			false, 
			array( 
				'options' => $data,
				'css_class' => implode(' ', $css_class ),
			) 
		);

		return $html;
	}


	public function filter_variation_dropdown_html_callback( $args ) {

		if ( is_product() ) {
			$args['class'] = 'hide hidden';
		}

		return $args;  
	}


	private function get_helper_class() {
		require_once dirname( __FILE__, 2 ) . '/admin/class-addonify-variation-swatches-admin-helper.php';
		
		if ( empty( $this->helper ) ) {
			$this->helper = new Addonify_Variation_Swatches_Admin_Helper( $this->plugin_name, $this->version );
		}

		return $this->helper;
	}


	// require proper templates for use in front end
	private function get_templates( $template_name, $require_once = true, $data=array() ){

		// first look for template in themes/addonify/plugin_name/template-name.php
		$theme_path = get_stylesheet_directory() . '/addonify/' . $this->plugin_name . '/' . $template_name .'.php';
		$plugin_path = dirname( __FILE__ ) .'/templates/' . $template_name .'.php';

		extract($data);

		if( file_exists( $theme_path ) ){
			$template_path = $theme_path;
		}
		else{
			$template_path = $plugin_path;
		}

		if( $require_once ){
			require_once $template_path;
		}
		else{
			require $template_path;
		}
	}

}
