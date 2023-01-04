<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.addonify.com
 * @since      1.0.0
 *
 * @package    Addonify_Variation_Swatches
 * @subpackage Addonify_Variation_Swatches/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Addonify_Variation_Swatches
 * @subpackage Addonify_Variation_Swatches/admin
 * @author     Addonify <info@addonify.com>
 */
class Addonify_Variation_Swatches_Admin extends Addonify_Variation_Swatches_Helper {

	/**
	 * Settings page slug
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $settings_page_slug    Default settings page slug for this plugin
	 */
	private $settings_page_slug = 'addonify_variation_swatches';

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
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		require_once dirname( __FILE__, 2 ) . '/includes/class-addonify-variation-swatches-helper.php';

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->helper      = new Addonify_Variation_Swatches_Helper( $plugin_name, $version );

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		// load styles in this plugin page only.
		if ( isset( $_GET['page'] ) && $_GET['page'] == $this->settings_page_slug ) {

			wp_enqueue_style(
				"{$this->plugin_name}-icon",
				plugin_dir_url( __FILE__ ) . 'assets/fonts/icon.css',
				array(),
				$this->version,
				'all'
			);

			wp_enqueue_style(
				$this->plugin_name,
				plugin_dir_url( __FILE__ ) . 'assets/css/admin.css',
				array(),
				$this->version,
				'all'
			);
		}

		// load styles in this plugin page only.
		// if ( isset( $_GET['page'] ) && $_GET['page'] === $this->settings_page_slug ) {

		// 	// toggle switch.
		// 	wp_enqueue_style( 'lc_switch', plugin_dir_url( __FILE__ ) . 'css/lc_switch.css', array(), $this->version );

		// 	/*
		// 		Built in wp color picker
		// 		Requires atleast WordPress 3.5
		// 	*/
		// 	wp_enqueue_style( 'wp-color-picker' );

		// 	// admin css.
		// 	wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/addonify-variation-swatches-admin.css', array(), $this->version, 'all' );

		// } elseif ( isset( $_GET['taxonomy'] ) && isset( $_GET['post_type'] ) ) {

		// 	wp_enqueue_style( 'wp-color-picker' );

		// 	// admin css.
		// 	wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/addonify-variation-swatches-admin.css', array(), $this->version, 'all' );
		// }

		// // admin menu icon fix.
		// wp_enqueue_style( 'addonify-icon-fix', plugin_dir_url( __FILE__ ) . 'css/addonify-icon-fix.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_register_script(
			"{$this->plugin_name}-manifest",
			plugin_dir_url( __FILE__ ) . 'assets/js/manifest.js',
			null,
			$this->version,
			true
		);

		wp_register_script(
			"{$this->plugin_name}-vendor",
			plugin_dir_url( __FILE__ ) . 'assets/js/vendor.js',
			array(  "{$this->plugin_name}-manifest" ),
			$this->version,
			true
		);

		wp_register_script(
			"{$this->plugin_name}-main",
			plugin_dir_url( __FILE__ ) . 'assets/js/main.js',
			array("{$this->plugin_name}-vendor", 'lodash', 'wp-i18n', 'wp-api-fetch' ),
			$this->version,
			true
		);

		// load scripts in plugin page only.
		if ( isset( $_GET['page'] ) && $_GET['page'] == $this->settings_page_slug ) {

			wp_enqueue_script( "{$this->plugin_name}-manifest" );

			wp_enqueue_script( "{$this->plugin_name}-vendor" );

			wp_enqueue_script( "{$this->plugin_name}-main" );

			wp_localize_script(
				"{$this->plugin_name}-main",
				'ADDONIFY_V_SWATCHES_LOCOLIZER',
				array(
					'admin_url'      => admin_url( '/' ),
					'ajax_url'       => admin_url( 'admin-ajax.php' ),
					'rest_namespace' => 'addonify_variation_swatches_options_api',
					'version_number' => $this->version,
				)
			);
		}

		wp_set_script_translations( "{$this->plugin_name}-main", $this->plugin_name );

		// load scripts in plugin page only.
		// if ( isset( $_GET['page'] ) && $_GET['page'] === $this->settings_page_slug ) {

		// 	if ( isset( $_GET['tabs'] ) && 'styles' === $_GET['tabs'] ) {
		// 		// requires atleast WordPress 4.9.0.
		// 		wp_enqueue_code_editor( array( 'type' => 'text/css' ) );
		// 		wp_enqueue_script( 'wp-color-picker' );
		// 	}

		// 	// toggle switch.
		// 	wp_enqueue_script( 'lc_switch', plugin_dir_url( __FILE__ ) . 'js/lc_switch.min.js', array( 'jquery' ), $this->version, false );

		// 	wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/addonify-variation-swatches-admin.js', array( 'jquery' ), time(), false );

		// } else {}
		
		if ( 
			isset( $_GET['taxonomy'] ) &&
			isset( $_GET['post_type'] ) &&
			'product' === sanitize_text_field( wp_unslash( $_GET['post_type'] )
			)
		) {
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_media();
			wp_enqueue_script(
				$this->plugin_name,
				plugin_dir_url( __FILE__ ) . 'js/addonify-variation-swatches-admin.js',
				array( 'jquery' ),
				time(),
				false
			);
		}

	}

	/**
	 * Generate admin menu for this plugin
	 *
	 * @since    1.0.0
	 */
	public function add_menu_callback() {

		// do not show menu if woocommerce is not active.
		if ( $this->is_woocommerce_active() !== true ) {

			add_action( 'admin_notices', array( $this, 'set_admin_woocommerce_not_activated_alert' ) );
			return;
		}

		global $admin_page_hooks;

		$parent_menu_slug = array_search( 'addonify', $admin_page_hooks, true );

		if ( ! $parent_menu_slug ) {

			add_menu_page(
				'Addonify Settings',
				'Addonify',
				'manage_options',
				$this->settings_page_slug,
				array( $this, 'get_settings_screen_contents' ),
				'dashicons-superhero',
				70
			);

			add_submenu_page(
				$this->settings_page_slug,
				'Swatches Settings',
				'Swatches',
				'manage_options',
				$this->settings_page_slug,
				array( $this, 'get_settings_screen_contents' ),
				1
			);

		} else {

			add_submenu_page(
				$parent_menu_slug,
				'Swatches Settings',
				'Swatches',
				'manage_options',
				$this->settings_page_slug,
				array( $this, 'get_settings_screen_contents' ),
				1
			);
		}
	}

	/**
	 * Get contents from settings page templates and print it
	 * Called from "add_menu_callback".
	 *
	 * @since    1.0.0
	 */
	public function get_settings_screen_contents() {
		?>
		<div id="___adfy-variation-swatches-app___"></div>
		<?php
	}



	/**
	 * Functions that needs to be run on admin init hook
	 *
	 * @since    1.0.0
	 */
	public function admin_init_callback() {

		// // show woocommerce not active notice.
		$this->show_woocommerce_not_active_notice_callback();

		// // show custom form element for all attributes.
		$this->register_action_for_custom_term_fields();

		// // register action to show custom column in term table.
		$this->register_filters_for_custom_columns();
	}


	/**
	 * Print "settings" link in plugins.php admin page
	 *
	 * @since    1.0.0
	 * @param string $links Plugin link.
	 * @param string $file  filename of plugin.
	 */
	public function custom_plugin_link_callback( $links, $file ) {
		if ( plugin_basename( dirname( __FILE__, 2 ) . '/addonify-variation-swatches.php' ) === $file ) {

			// add "Settings" link.
			$links[] = '<a href="admin.php?page=' . $this->settings_page_slug . '">' . __( 'Settings', 'addonify-variation-swatches' ) . '</a>';
		}

		return $links;
	}

	/**
	 * Show notification after form submission
	 *
	 * @since    1.0.0
	 */
	public function form_submission_notification_callback() {
		if ( isset( $_GET['page'] ) && $_GET['page'] == $this->settings_page_slug ) {
			settings_errors();
		}
	}


	/**
	 * Show admin error message if woocommerce is not active
	 *
	 * @since    1.0.0
	 */
	public function show_woocommerce_not_active_notice_callback() {

		if ( ! $this->is_woocommerce_active() ) {
			add_action( 'admin_notices', array( $this, 'set_admin_woocommerce_not_activated_alert' ) );
		}
	}



	// -------------------------------------------------
	// add / edit attributes screen.
	// -------------------------------------------------


	/**
	 * Show "type" dropdown field in "add attributes" page
	 *
	 * @since    1.0.0
	 * @param string $selector filter callback options.
	 */
	public function product_attributes_types_callback( $selector ) {

		if ( isset( $_GET['page'] ) && 'product_attributes' == $_GET['page'] ) {
			foreach ( $this->available_attributes_types() as $key => $options ) {
				$selector[ $key ] = $options['title'];
			}
		}
		return $selector;
	}


	/**
	 * Register action to show custom form fields in product attributes term page
	 *
	 * @since    1.0.0
	 */
	private function register_action_for_custom_term_fields() {

		foreach ( $this->get_all_attributes() as $attr ) {

			$term_name = 'pa_' . $attr['name'];

			// show form.
			add_action( $term_name . '_add_form_fields', array( $this, 'term_add_custom_form_fields' ) );
			add_action( $term_name . '_edit_form_fields', array( $this, 'term_edit_custom_form_fields' ) );

			// on create or update.
			add_action( 'edited_' . $term_name, array( $this, 'term_save_custom_form_fields' ) );
			add_action( 'create_' . $term_name, array( $this, 'term_save_custom_form_fields' ) );

			add_action( 'delete_' . $term_name, array( $this, 'term_is_deleted' ) );
		}

	}


	/**
	 * Show custom form fields in edit-tags.php page
	 *
	 * @since    1.0.0
	 */
	public function term_add_custom_form_fields() {
		$this->term_show_custom_form_fields();
	}


	/**
	 * Show custom form fields in edit taxonomy form
	 *
	 * @since    1.0.0
	 */
	public function term_edit_custom_form_fields() {
		$this->term_show_custom_form_fields( true );
	}


	/**
	 * Output markups for custom form fields to show in term add / edit page
	 *
	 * @since    1.0.0
	 * @param boolean $is_edit_form Is this edit form or not.
	 */
	private function term_show_custom_form_fields( $is_edit_form = false ) {

		$attribute_type = '';
		foreach ( $this->get_all_attribute_taxonomies() as $attr ) {
			if ( isset( $_GET['taxonomy'] ) && 'pa_' . $attr->attribute_name === $_GET['taxonomy'] ) {
				$attribute_type = strtolower( $attr->attribute_type );
				break;
			}
		}

		if ( ! $attribute_type || 'select' === $attribute_type || 'button' === $attribute_type ) {
			return;
		}

		$this->taxonomy_form_markup( $attribute_type, $is_edit_form );

	}


	/**
	 * Woocommerce - Save custom form fields
	 *
	 * @since    1.0.0
	 * @param int $term_id Term ID.
	 */
	public function term_save_custom_form_fields( $term_id ) {
		if ( ! is_admin() ) {
			return;
		}

		$option_key = '';

		if ( ! isset( $_POST ) ) {
			return;
		}

		foreach ( $_POST as $post_key => $post_val ) {
			if ( 0 === strpos( $post_key, $this->plugin_name ) ) {

				$option_key = $post_key;

				if ( false === strpos( $post_key, strval( $term_id ) ) ) {
					$option_key .= '_' . $term_id;
				}

				update_option( $option_key, $post_val );
			}
		}

	}


	/**
	 * Woocommerce taxonomy is deleted, Delete all custom attributes data
	 *
	 * @since    1.0.0
	 * @param int $term   term id.
	 */
	public function term_is_deleted( $term ) {

		// delete all custom attributes data.
		delete_option( "{$this->plugin_name}_attr_color_{$term}" );
		delete_option( "{$this->plugin_name}_attr_image{$term}" );
		delete_option( "{$this->plugin_name}_attr_button{$term}" );
	}


	// -------------------------------------------------
	// custom columns.
	// -------------------------------------------------

	/**
	 * Register action to show custom columns in "attributes" terms display table
	 *
	 * @since    1.0.0
	 */
	private function register_filters_for_custom_columns() {

		foreach ( $this->get_all_attributes() as $attr ) {
			$term_name = 'pa_' . $attr['name'];
			add_filter( 'manage_edit-' . $term_name . '_columns', array( $this, 'custom_column_heading_for_attributes' ) );
			add_filter( 'manage_' . $term_name . '_custom_column', array( $this, 'my_custom_taxonomy_columns_content' ), 10, 3 );
		}

	}


	/**
	 * Show custom "column" in term table
	 *
	 * @since    1.0.0
	 * @param array $columns Name of columns for term table.
	 */
	public function custom_column_heading_for_attributes( $columns ) {

		$taxonomy = isset( $_REQUEST['taxonomy'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['taxonomy'] ) ) : null;

		if ( empty( $taxonomy ) ) {
			return $columns;
		}

		$attribute_type = '';
		foreach ( $this->get_all_attribute_taxonomies() as $attr ) {
			if ( sanitize_text_field( wp_unslash( $taxonomy ) ) === 'pa_' . $attr->attribute_name ) {
				$attribute_type = strtolower( $attr->attribute_type );
				break;
			}
		}

		if ( ! $attribute_type ) {
			return $columns;
		}

		$new = array();
		foreach ( $columns as $key => $title ) {

			if ( 'name' === $key && in_array( $attribute_type, array( 'color', 'image' ) ) ) {
				$new['addonify_custom_attr'] = '';
			}

			$new[ $key ] = $title;
		}

		return $new;
	}


	/**
	 * Show "type" dropdown field in  "attributes" page in admin
	 *
	 * @since    1.0.0
	 * @param string $content Content to outout.
	 * @param string $column_name Name of the column.
	 * @param int    $term_id Term ID.
	 */
	public function my_custom_taxonomy_columns_content( $content, $column_name, $term_id ) {
		return $this->get_attr_type_preview_for_term( $column_name, $term_id );
	}


	/**
	 * Get all attributes to show in select dropdown.
	 *
	 * @since    1.0.0
	 */
	private function get_all_attributes_dropdown() {
		$return_data = array();
		foreach ( $this->get_all_attribute_taxonomies() as $attr ) {
			$return_data[ $attr->attribute_name ] = $attr->attribute_label;
		}

		return $return_data;
	}

	/**
	 * Woocommerce not activated alert.
	 */
	public function set_admin_woocommerce_not_activated_alert() {
		?>
		<div class="notice notice-error is-dismissible">
			<p><?php echo esc_html__( 'Addonify Variation Swatches requires WooCommerce in order to work.', 'addonify-variation-swatches' ); ?></p>
		</div>
		<?php
	}
}
