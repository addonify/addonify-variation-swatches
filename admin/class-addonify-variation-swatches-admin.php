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
class Addonify_Variation_Swatches_Admin {

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
	 * Settings page slug
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $settings_page_slug    Default settings page slug for this plugin
	 */
	private $settings_page_slug = 'addonify_variation_swatches';

	/**
	 * Store default values for input fields in admin screen
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $default_input_values
	 */
	private $default_input_values;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		// load styles in this plugin page only.
		if ( isset( $_GET['page'] ) && $_GET['page'] === $this->settings_page_slug ) {
			// toggle switch.
			wp_enqueue_style( 'lc_switch', plugin_dir_url( __FILE__ ) . 'css/lc_switch.css', array(), $this->version );

			/*
				Built in wp color picker
				Requires atleast WordPress 3.5
			*/
			wp_enqueue_style( 'wp-color-picker' );

			// admin css.
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/addonify-variation-swatches-admin.css', array(), $this->version, 'all' );
		}

		// admin menu icon fix.
		wp_enqueue_style( 'addonify-icon-fix', plugin_dir_url( __FILE__ ) . 'css/addonify-icon-fix.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		// load scripts in plugin page only.
		if ( isset( $_GET['page'] ) && $_GET['page'] === $this->settings_page_slug ) {

			if ( isset( $_GET['tabs'] ) && 'styles' === $_GET['tabs'] ) {
				// requires atleast WordPress 4.9.0.
				wp_enqueue_code_editor( array( 'type' => 'text/css' ) );
				wp_enqueue_script( 'wp-color-picker' );
			}

			// toggle switch.
			wp_enqueue_script( 'lc_switch', plugin_dir_url( __FILE__ ) . 'js/lc_switch.min.js', array( 'jquery' ), $this->version, false );

			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/addonify-variation-swatches-admin.js', array( 'jquery' ), time(), false );

		}

	}


	/**
	 * Check if woocommerce is active
	 *
	 * @since    1.0.0
	 */
	private function is_woocommerce_active() {
		return ( class_exists( 'woocommerce' ) ) ? true : false;
	}


	/**
	 * Generate admin menu for this plugin
	 *
	 * @since    1.0.0
	 */
	public function add_menu_callback() {

		// do not show menu if woocommerce is not active.
		if ( $this->is_woocommerce_active() !== true ) {
			return;
		}

		global $menu;
		$parent_menu_slug = null;

		foreach ( $menu as $item ) {
			if ( 'addonify' === strtolower( $item[0] ) ) {
				$parent_menu_slug = $item[2];
				break;
			}
		}

		if ( ! $parent_menu_slug ) {
			add_menu_page( 'Addonify Settings', 'Addonify', 'manage_options', $this->settings_page_slug, array( $this, 'get_settings_screen_contents' ), plugin_dir_url( __FILE__ ) . '/templates/addonify-logo.svg', 76 );

			add_submenu_page( $this->settings_page_slug, 'Addonify Wishlist Settings', 'Wishlist', 'manage_options', $this->settings_page_slug, array( $this, 'get_settings_screen_contents' ), 1 );
		} else {
			// sub menu.
			// redirects to main plugin link.
			add_submenu_page( $parent_menu_slug, 'Addonify Wishlist Settings', 'Wishlist', 'manage_options', $this->settings_page_slug, array( $this, 'get_settings_screen_contents' ), 1 );
		}
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
	 * Get contents from settings page templates and print it
	 *
	 * @since    1.0.0
	 */
	public function get_settings_screen_contents() {
		$current_tab = ( isset( $_GET['tabs'] ) ) ? sanitize_text_field( wp_unslash( $_GET['tabs'] ) ) : 'settings';
		$tab_url     = "admin.php?page=$this->settings_page_slug&tabs=";

		require_once dirname( __FILE__ ) . '/templates/settings-screen.php';

	}


	/**
	 * Generate form elements for settings page from array
	 *
	 * @since    1.0.0
	 */
	public function settings_page_ui() {

		// ---------------------------------------------
		// General Options
		// ---------------------------------------------

		$settings_args = array(
			'settings_group_name' => 'wishlist_settings',
			'section_id'          => 'general_options',
			'section_label'       => __( 'General Options', 'addonify-variation-swatches' ),
			'section_callback'    => '',
			'screen'              => $this->settings_page_slug . '-general_options',
			'fields'              => array(
				array(
					'field_id'            => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'enable_tooltip',
					'field_label'         => __( 'Enable Tooltip', 'addonify-variation-swatches' ),
					'field_callback'      => array( $this, 'toggle_switch' ),
					'field_callback_args' => array(
						array(
							'name'      => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'enable_tooltip',
							'end_label' => __( 'Enable Tooltip on each product attributes', 'addonify-variation-swatches' ),
						),
					),
				),
				array(
					'field_id'            => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'auto_dropdown_to_btn',
					'field_label'         => __( 'Auto Dropdowns to Button', 'addonify-variation-swatches' ),
					'field_callback'      => array( $this, 'toggle_switch' ),
					'field_callback_args' => array(
						array(
							'name'      => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'auto_dropdown_to_btn',
							'end_label' => __( 'Convert default dropdowns to button type', 'addonify-variation-swatches' ),
						),
					),
				),
				array(
					'field_id'            => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'shape',
					'field_label'         => __( 'Attributes style', 'addonify-variation-swatches' ),
					'field_callback'      => array( $this, 'select' ),
					'field_callback_args' => array(
						array(
							'name'     => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'shape',
							'options'  => array(
								'rounded' => __( 'Rounded Shape', 'addonify-variation-swatches' ),
								'square'  => __( 'Square Shape', 'addonify-variation-swatches' ),
							),
							'end_label'=> __( 'Attribute Shape Style', 'addonify-variation-swatches' ),
						),
					),
				),
				array(
					'field_id'            => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'attribute_behavior',
					'field_label'         => __( 'Attribute behavior', 'addonify-variation-swatches' ),
					'field_callback'      => array( $this, 'select' ),
					'field_callback_args' => array(
						array(
							'name'     => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'attribute_behavior',
							'options'  => array(
								'blur_with_cross'    => __( 'Blur with cross', 'addonify-variation-swatches' ),
								'blur_without_cross' => __( 'Blur without cross', 'addonify-variation-swatches' ),
								'hide'               => __( 'Hide', 'addonify-variation-swatches' ),
							),
							'end_label' => __( 'Disabled attribute will be hide / blur.', 'addonify-variation-swatches' ),
						),
					),
				),
				array(
					'field_id'            => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'attribute_image_size',
					'field_label'         => __( 'Attribute image size', 'addonify-variation-swatches' ),
					'field_callback'      => array( $this, 'select' ),
					'field_callback_args' => array(
						array(
							'name'     => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'attribute_image_size',
							'options'  => array(
								'thumbnail' => __( 'Thumbnail', 'addonify-variation-swatches' ),
								'medium'    => __( 'Medium', 'addonify-variation-swatches' ),
								'large'     => __( 'Large', 'addonify-variation-swatches' ),
							),
							'end_label' => __( 'Attribute image size.', 'addonify-variation-swatches' ),
						),
					),
				),
				array(
					'field_id'            => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'attribute_width',
					'field_label'         => __( 'Width', 'addonify-variation-swatches' ),
					'field_callback'      => array( $this, 'text_box' ),
					'field_callback_args' => array(
						array(
							'name'      => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'attribute_width',
							'default'   => 30,
							'end_label' => __( 'Variation item width.', 'addonify-variation-swatches' ),
							'css_class' => 'number'
						),
					),
				),
				array(
					'field_id'            => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'attribute_height',
					'field_label'         => __( 'Height', 'addonify-variation-swatches' ),
					'field_callback'      => array( $this, 'text_box' ),
					'field_callback_args' => array(
						array(
							'name'      => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'attribute_height',
							'default'   => 30,
							'end_label' => __( 'Variation item height.', 'addonify-variation-swatches' ),
							'css_class' => 'number'
						),
					),
				),
				array(
					'field_id'            => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'attribute_font_size',
					'field_label'         => __( 'Font Size', 'addonify-variation-swatches' ),
					'field_callback'      => array( $this, 'text_box' ),
					'field_callback_args' => array(
						array(
							'name'      => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'attribute_font_size',
							'default'   => 16,
							'end_label' => __( 'Single product variation item font size.', 'addonify-variation-swatches' ),
							'css_class' => 'number'
						),
					),
				),
			),
		);

		// create settings fields.
		$this->create_settings( $settings_args );

		// ---------------------------------------------
		// Archives Page Options.
		// ---------------------------------------------

		$settings_args = array(
			'settings_group_name' => 'wishlist_settings',
			'section_id'          => 'button_options',
			'section_label'       => __( 'Archives / Shop Page Options', 'addonify-variation-swatches' ),
			'section_callback'    => '',
			'screen'              => $this->settings_page_slug . '-button_settings',
			'fields'              => array(
				array(
					'field_id'            => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'show_on_archives',
					'field_label'         => __( 'Show on archives', 'addonify-variation-swatches' ),
					'field_callback'      => array( $this, 'toggle_switch' ),
					'field_callback_args' => array(
						array(
							'name'      => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'show_on_archives',
							'end_label' => __( 'Show swatches on archives/shop page.', 'addonify-variation-swatches' ),
						),
					),
				),
				array(
					'field_id'            => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'display_position',
					'field_label'         => __( 'Display position', 'addonify-variation-swatches' ),
					'field_callback'      => array( $this, 'select' ),
					'field_callback_args' => array(
						array(
							'name'      => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'display_position',
							'options'   => array(
								'before_add_to_cart' => __( 'Before Add to Cart button', 'addonify-variation-swatches' ),
								'after_add_to_cart'  => __( 'After Add to Cart button', 'addonify-variation-swatches' ),
							),
							'default'   => 'after_add_to_cart',
							'end_label' => __( 'Show swatches position on archives/shop page.', 'addonify-variation-swatches' ),
						),
					),
				),
				array(
					'field_id'            => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'swatches_align',
					'field_label'         => __( 'Align', 'addonify-variation-swatches' ),
					'field_callback'      => array( $this, 'select' ),
					'field_callback_args' => array(
						array(
							'name'      => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'swatches_align',
							'options'   => array(
								'left'   => __( 'Left', 'addonify-variation-swatches' ),
								'right'  => __( 'Right', 'addonify-variation-swatches' ),
								'center' => __( 'Center', 'addonify-variation-swatches' ),
							),
							'end_label' => __( 'Swatches align on archives/shop page.', 'addonify-variation-swatches' ),
						),
					),
				),
				array(
					'field_id'            => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'visible_attributes',
					'field_label'         => __( 'Visible Attributes', 'addonify-variation-swatches' ),
					'field_callback'      => array( $this, 'checkbox_group' ),
					'field_callback_args' => array(
						array(
							'label' => __( 'Attrinute Name', 'addonify-quick-view' ),
							'name'  => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'attrinute_name',
						),
						array(
							'label' => __( 'Attrinute Name 1', 'addonify-quick-view' ),
							'name'  => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'attrinute_name 1',
						),
					),
				),
			),
		);

		// create settings fields.
		$this->create_settings( $settings_args );
	}


	/**
	 * This will create settings section, fields and register that settings in a database from the provided array data
	 *
	 * @since    1.0.0
	 * @param string $args Options for settings field.
	 */
	private function create_settings( $args ) {

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
			add_action(
				'admin_notices',
				function() {
					require dirname( __FILE__ ) . '/templates/woocommerce_not_active_notice.php';
				}
			);
		}
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
		foreach( $args as $arg ) {
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

}
