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
		$this->rest_api();

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
	 * - Addonify_Variation_Swatches_Rest_API. Defines all hooks for registering the admin side rest api.
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
		 * The class responsible for defining rest api functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-addonify-variation-swatches-rest-api.php';

		/**
		 * The class responsible for defining admin settings functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/setting-functions/settings.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-addonify-variation-swatches-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-addonify-variation-swatches-public.php';

		/**
		 * User data processing.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/udp/init.php';

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

		// on admin init.
		$this->loader->add_action( 'plugins_loaded', $plugin_admin, 'admin_init_callback' );

		// show admin notices after form submission.
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'form_submission_notification_callback' );

		// show "type" in "attributes" page in admin.
		$this->loader->add_filter( 'product_attributes_type_selector', $plugin_admin, 'product_attributes_types_callback' );
	}

	/**
	 * Register rest api endpoints for admin settings page.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function rest_api() {

		new Addonify_Variation_Swatches_Rest_API();
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		$plugin_public = new Addonify_Variation_Swatches_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// Change the contents of default variation select input field.
		$this->loader->add_action( 'woocommerce_dropdown_variation_attribute_options_html', $plugin_public, 'filter_dropdown_variation_attributes_contents', 10, 2 );

		// Customize variation dropdown html.
		$this->loader->add_action( 'woocommerce_dropdown_variation_attribute_options_args', $plugin_public, 'filter_variation_dropdown_html_callback' );

		// Disable out of stock variation.
		$this->loader->add_filter( 'woocommerce_variation_is_active', $plugin_public, 'disable_out_of_stock_variations_callback', 10, 2 );

		// Show variation options before add to cart button in loop.
		$this->loader->add_action( 'woocommerce_after_shop_loop_item', $plugin_public, 'show_variation_before_add_to_cart_in_loop_callback' );

		// Show variation options after add to cart button in loop.
		$this->loader->add_action( 'woocommerce_after_shop_loop_item', $plugin_public, 'show_variation_after_add_to_cart_in_loop_callback', 20 );

		// make woocommerce to load template file from our plugin.
		$this->loader->add_filter( 'woocommerce_locate_template', $plugin_public, 'override_woo_template', 10, 3 );

		// show "custom" add to cart button in shop / archive.
		$this->loader->add_filter( 'woocommerce_loop_add_to_cart_link', $plugin_public, 'show_add_to_cart_btn_in_shop_page', 10, 2 );

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
