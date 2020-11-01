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
		$this->loader->add_action( 'product_attributes_type_selector', $plugin_admin, 'product_attributes_types_callback');

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
		
		// add_filter( 'addonify_vs_woocommerce_loop_add_to_cart_args', function( $wp_parse_args, $product) {

		// 	echo '<pre>';
		// 	var_dump( $wp_parse_args);
		// 	die;

		// }, 10, 2 );

		// Disable out of stock variation
		add_filter( 'woocommerce_variation_is_active', array( $plugin_public, 'disable_out_of_stock_variations_callback' ), 10, 2 );

		// Add custom styles into header.
		$this->loader->add_action( 'wp_head', $plugin_public, 'generate_custom_styles_callback' );


		// Show variation optins after add to cart button in loop.
		$this->loader->add_action( 'woocommerce_after_shop_loop_item', $plugin_public, 'show_variation_after_add_to_cart_in_loop_callback', 20 );

		// Hide "add to cart" button in loop if product type is variation.
		// add_action( 'woocommerce_after_shop_loop_item', 'remove_add_to_cart_buttons', 1 );

		// function remove_add_to_cart_buttons() {
		// 	if( is_product_category() || is_shop()) { 
		// 		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
		// 	}
		// }


		// Show variation optins before add to cart button in loop.
		// $this->loader->add_action( 'woocommerce_after_shop_loop_item', $plugin_public, 'show_variation_before_add_to_cart_in_loop_callback' );


		// add_filter( 'woocommerce_loop_add_to_cart_args', function($wp_parse_args , $product){
		// 	echo '<pre>';
		// 	var_dump( $wp_parse_args);
		// 	die;
		// }, 20, 2 );
		

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
		add_filter( 'woocommerce_loop_add_to_cart_link', function( $button, $product ) {

			if ( is_shop() && $product->is_type( 'variable' ) ) {
				$product_id = 71; //$product->get_id();
				$product_sku = $product->get_sku();
				$product_url = 'http://localhost/woocommerce/shop/'; //$product->add_to_cart_url();
				$product_url = add_query_arg( 'add-to-cart', $product_id, $product_url );

				$quantity = isset( $args['quantity'] ) ? $args['quantity'] : 1;
				$text = 'Add to cart';

				$button = '<a rel="nofollow" href="' . $product_url . '" data-quantity="' . $quantity . '" data-product_id="' . $product_id . '" data-variation_id="71" data-product_sku="' . $product_sku . '" class="button product_type_simple add_to_cart_button ajax_add_to_cart add-to-cart" aria-label="Add to cart">' . $text . '</a>';
			}
			
			return $button;

		}, 10, 2 );

		// ajax
		add_action('wp_ajax_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');
		add_action('wp_ajax_nopriv_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');

		function woocommerce_ajax_add_to_cart() {

            $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
            $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
            $variation_id = absint($_POST['variation_id']);
            $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
            $product_status = get_post_status($product_id);

            if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id) && 'publish' === $product_status) {

                do_action('woocommerce_ajax_added_to_cart', $product_id);

                if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
                    wc_add_to_cart_message(array($product_id => $quantity), true);
                }

                WC_AJAX :: get_refreshed_fragments();
            } else {

                $data = array(
                    'error' => true,
                    'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id));

                echo wp_send_json($data);
            }

            wp_die();
        }

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
