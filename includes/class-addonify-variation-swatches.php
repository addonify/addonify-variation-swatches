<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.addonify.com
 * @since      1.0.0
 *
 * @package    Addonify_Variation_Swatches
 * @subpackage Addonify_Variation_Swatches/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Addonify_Variation_Swatches
 * @subpackage Addonify_Variation_Swatches/includes
 * @author     Addonify <info@addonify.com>
 */
class Addonify_Variation_Swatches {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Addonify_Variation_Swatches_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'ADDONIFY_VARIATION_SWATCHES_VERSION' ) ) {
			$this->version = ADDONIFY_VARIATION_SWATCHES_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'addonify-variation-swatches';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Addonify_Variation_Swatches_Loader. Orchestrates the hooks of the plugin.
	 * - Addonify_Variation_Swatches_i18n. Defines internationalization functionality.
	 * - Addonify_Variation_Swatches_Admin. Defines all hooks for the admin area.
	 * - Addonify_Variation_Swatches_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * Helper Class
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-addonify-variation-swatches-helper.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-addonify-variation-swatches-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-addonify-variation-swatches-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-addonify-variation-swatches-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-addonify-variation-swatches-public.php';

		

		$this->loader = new Addonify_Variation_Swatches_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Addonify_Variation_Swatches_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Addonify_Variation_Swatches_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Addonify_Variation_Swatches_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// admin menu.
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu_callback', 20 );

		// custom link in plugins.php page in wp-admin.
		$this->loader->add_action( 'plugin_action_links', $plugin_admin, 'custom_plugin_link_callback', 10, 2 );

		// on admin init
		$this->loader->add_action( 'admin_init', $plugin_admin, 'admin_init_callback' );

		// show admin notices after form submission.
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'form_submission_notification_callback' );

		// show "type" in "attributes" page in admin
		$this->loader->add_filter( 'product_attributes_type_selector', $plugin_admin, 'product_attributes_types_callback');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Addonify_Variation_Swatches_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// Change the contents of default variation select input field.
		$this->loader->add_action( 'woocommerce_dropdown_variation_attribute_options_html', $plugin_public, 'filter_dropdown_variation_button_callback', 10, 2 );


		// Customize variation dropdown html.
		$this->loader->add_action( 'woocommerce_dropdown_variation_attribute_options_args', $plugin_public, 'filter_variation_dropdown_html_callback' );
		

		// Disable out of stock variation
		add_filter( 'woocommerce_variation_is_active', array( $plugin_public, 'disable_out_of_stock_variations_callback' ), 10, 2 );


		// Add custom styles into header.
		$this->loader->add_action( 'wp_head', $plugin_public, 'generate_custom_styles_callback' );

		
		// Show variation optins before add to cart button in loop.
		$this->loader->add_action( 'woocommerce_after_shop_loop_item', $plugin_public, 'show_variation_before_add_to_cart_in_loop_callback' );


		// Show variation options after add to cart button in loop.
		$this->loader->add_action( 'woocommerce_after_shop_loop_item', $plugin_public, 'show_variation_after_add_to_cart_in_loop_callback', 20 );



		// make woocommerce to load template file from our plugin.
		add_filter( 'woocommerce_locate_template', 'woo_adon_plugin_template', 1, 3 );
		function woo_adon_plugin_template( $template, $template_name, $template_path ) {

			if( !is_shop() && !is_archive() ) return $template;

			// Removing add to cart button and quantities only.
			remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20 );
			
			global $woocommerce;

			$_template = $template;

			if ( ! $template_path ) 
				$template_path = $woocommerce->template_url;
		
			$plugin_path  = untrailingslashit( plugin_dir_path( __DIR__ ) )  . '/public/templates/woocommerce/';
		
			// Look within passed path within the theme - this is priority
			$template = locate_template(
				array(
					$template_path . $template_name,
					$template_name
				)
			);

			if( ! $template && file_exists( $plugin_path . $template_name ) ) {
				$template = $plugin_path . $template_name;
			}
			
			if ( ! $template ) {
				$template = $_template;
			}

			return $template;
		}

		

		// add_action( 'woocommerce_single_product_summary', 'hide_add_to_cart_button_variable_product', 1, 0 );
		// function hide_add_to_cart_button_variable_product() {

		// 	// Removing add to cart button and quantities only
		// 	remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20 );
		// }





		// show image preview for each variation

		// add_action( 'woocommerce_after_shop_loop_item', 'loop_display_variation_attribute_and_thumbnail' );
		// function loop_display_variation_attribute_and_thumbnail() {
		// 	global $product;
		// 	if( $product->is_type('variable') ){
		// 		foreach ( $product->get_visible_children() as $variation_id ){
		// 			// Get an instance of the WC_Product_Variation object
		// 			$variation = wc_get_product( $variation_id );

		// 			// Get "color" product attribute term name value
		// 			$color = $variation->get_attribute('color');

		// 			if( ! empty($color) ){
		// 				// Display "color" product attribute term name value
		// 				echo $color;

		// 				// Display the product thumbnail with a defined size. Default is thumbnail
		// 				echo $variation->get_image();
		// 			}
		// 		}
		// 	}
		// }


		// Remove "Select options" button from (variable) products on the main WooCommerce shop page.
		// show add to cart button in shop page.
		add_filter( 'woocommerce_loop_add_to_cart_link', function( $button, $product ) {

			if ( is_archive() && $product->is_type( 'variable' ) ) {
				$product_id = 71; //$product->get_id();
				$product_sku = $product->get_sku();
				$product_url = 'http://localhost/woocommerce/shop/'; //$product->add_to_cart_url();
				$product_url = add_query_arg( 'add-to-cart', $product_id, $product_url );

				$quantity = isset( $args['quantity'] ) ? $args['quantity'] : 1;
				$text = 'Add to cart';

				$button .= '<a rel="nofollow" href="' . $product_url . '" data-quantity="' . $quantity . '" data-product_id="' . $product_id . '" data-variation_id="71" data-product_sku="' . $product_sku . '" class="addonify_vs-add_to_cart-button button product_type_simple add_to_cart_button ajax_add_to_cart add-to-cart" aria-label="Add to cart" style="display: none;">' . $text . '</a>';
			}
			
			return $button;

		}, 10, 2 );


	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Addonify_Variation_Swatches_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
