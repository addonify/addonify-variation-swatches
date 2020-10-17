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
	 * Store default values for input fields in admin screen
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $default_input_values
	 */
	protected $default_input_values;

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
	 * @since 1.0.0
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		require_once dirname( __FILE__ ) . '/class-addonify-variation-swatches-admin-helper.php';

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->helper      = new Addonify_Variation_Swatches_Admin_Helper( $plugin_name, $version );

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

		} elseif ( isset( $_GET['taxonomy'] ) && isset( $_GET['post_type'] ) ) {

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

		} elseif ( isset( $_GET['taxonomy'] ) && isset( $_GET['post_type'] ) && 'product' === strval( $_GET['post_type'] ) ) {
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_media();
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/addonify-variation-swatches-admin.js', array( 'jquery' ), time(), false );
		}

	}

	/**
	 * Generate admin menu for this plugin
	 *
	 * @since    1.0.0
	 */
	public function add_menu_callback() {

		$helper = $this->helper;

		// do not show menu if woocommerce is not active.
		if ( $helper->is_woocommerce_active() !== true ) {
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
	 * Functions that needs to be run on admin init hook
	 *
	 * @since    1.0.0
	 */
	public function admin_init_callback() {

		// show settings page ui.
		$this->settings_page_ui();

		// show woocommerce not active notice.
		$this->show_woocommerce_not_active_notice_callback();

		// show custom form element for all attributes.
		$this->register_action_for_custom_term_fields();

		// register action to show custom column in term table.
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

		$helper = $this->helper;

		// ---------------------------------------------
		// General Options
		// ---------------------------------------------

		$settings_args = array(
			'settings_group_name' => 'variation_swatches_settings',
			'section_id'          => 'global_options',
			'section_label'       => __( 'Global Options', 'addonify-variation-swatches' ),
			'section_callback'    => '',
			'screen'              => $this->settings_page_slug . '-global_options',
			'fields'              => array(
				array(
					'field_id'            => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'enable_tooltip',
					'field_label'         => __( 'Enable Tooltip', 'addonify-variation-swatches' ),
					'field_callback'      => array( $this, 'toggle_switch' ),
					'field_callback_args' => array(
						array(
							'name'      => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'enable_tooltip',
						),
					),
				),
				array(
					'field_id'            => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'tooltip_image_width',
					'field_label'         => __( 'Tooltip image width', 'addonify-variation-swatches' ),
					'field_callback'      => array( $this, 'text_box' ),
					'field_callback_args' => array(
						array(
							'name'      => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'tooltip_image_width',
							'default'   => 100,
							'end_label' => __( 'px', 'addonify-variation-swatches' ),
							'css_class' => 'number',
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
							'name'      => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'shape',
							'options'   => array(
								'rounded' => __( 'Rounded Shape', 'addonify-variation-swatches' ),
								'square'  => __( 'Square Shape', 'addonify-variation-swatches' ),
							),
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
							'options'  => $helper->list_thumbnail_sizes(),
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
							'end_label' => __( 'px', 'addonify-variation-swatches' ),
							'css_class' => 'number',
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
							'end_label' => __( 'px', 'addonify-variation-swatches' ),
							'css_class' => 'number',
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
							'end_label' => __( 'px', 'addonify-variation-swatches' ),
							'css_class' => 'number',
						),
					),
				),
			),
		);

		// create settings fields.
		$helper->create_settings( $settings_args );

		// ---------------------------------------------
		// Archives Page Options.
		// ---------------------------------------------

		$settings_args = array(
			'settings_group_name' => 'variation_swatches_settings',
			'section_id'          => 'archives_page_options',
			'section_label'       => __( 'Archives / Shop Page Options', 'addonify-variation-swatches' ),
			'section_callback'    => '',
			'screen'              => $this->settings_page_slug . '-archives_page_settings',
			'fields'              => array(
				array(
					'field_id'            => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'show_on_archives',
					'field_label'         => __( 'Show on archives', 'addonify-variation-swatches' ),
					'field_callback'      => array( $this, 'toggle_switch' ),
					'field_callback_args' => array(
						array(
							'name'      => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'show_on_archives',
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
						),
					),
				),
				array(
					'field_id'            => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'archive_attribute_width',
					'field_label'         => __( 'Width', 'addonify-variation-swatches' ),
					'field_callback'      => array( $this, 'text_box' ),
					'field_callback_args' => array(
						array(
							'name'      => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'archive_attribute_width',
							'default'   => 30,
							'end_label' => __( 'px', 'addonify-variation-swatches' ),
							'css_class' => 'number',
						),
					),
				),
				array(
					'field_id'            => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'archive_attribute_height',
					'field_label'         => __( 'Height', 'addonify-variation-swatches' ),
					'field_callback'      => array( $this, 'text_box' ),
					'field_callback_args' => array(
						array(
							'name'      => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'archive_attribute_height',
							'default'   => 30,
							'end_label' => __( 'px', 'addonify-variation-swatches' ),
							'css_class' => 'number',
						),
					),
				),
				array(
					'field_id'            => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'archive_attribute_font_size',
					'field_label'         => __( 'Font Size', 'addonify-variation-swatches' ),
					'field_callback'      => array( $this, 'text_box' ),
					'field_callback_args' => array(
						array(
							'name'      => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'archive_attribute_font_size',
							'default'   => 16,
							'end_label' => __( 'px', 'addonify-variation-swatches' ),
							'css_class' => 'number',
						),
					),
				),
				array(
					'field_id'            => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'visible_attributes',
					'field_label'         => __( 'Visible Attributes', 'addonify-variation-swatches' ),
					'field_callback'      => array( $this, 'checkbox_group' ),
					'field_callback_args' => $helper->get_all_attributes(),
				),
			),
		);

		// create settings fields.
		$helper->create_settings( $settings_args );


		// ---------------------------------------------
		// Styles Options
		// ---------------------------------------------

		$settings_args = array(
			'settings_group_name'	=> 'variation_swatches_styles',
			'section_id' 			=> 'style_options',
			'section_label'			=> __('STYLE OPTIONS', 'addonify-variation-swatches'),
			'section_callback'		=> '',
			'screen'				=> $this->settings_page_slug.'-styles',
			'fields'				=> array(
				array(
					'field_id'				=> ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'load_styles_from_plugin',
					'field_label'			=> __('Load Styles From Plugin', 'addonify-variation-swatches'),
					'field_callback'		=> array($this, "toggle_switch"),
					'field_callback_args'	=> array( 
						array(
							'name' 				=> ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'load_styles_from_plugin', 
							'checked' 			=> 0,
						)
					) 
				),
			)
		);

		// create settings fields
		$helper->create_settings( $settings_args );


		// ---------------------------------------------
		// Content Colors
		// ---------------------------------------------

		$settings_args = array(
			'settings_group_name' => 'variation_swatches_settings',
			'section_id'          => 'content_color_options',
			'section_label'       => __( 'Content Colors', 'addonify-variation-swatches' ),
			'section_callback'    => '',
			'screen'              => $this->settings_page_slug . '-content-colors',
			'fields'				=> array(
				array(
					'field_id'				=> ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'variation_swatches_btn_colors',
					'field_label'			=> __('Tooltip', 'addonify-variation-swatches'),
					'field_callback'		=> array($this, "color_picker_group"),
					'field_callback_args'	=> array( 
						array(
							'label'				=> __('Text Color', 'addonify-variation-swatches'),
							'name'				=> ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'tooltip_text_color',
							'default'			=> '#fff',
						),
						array(
							'label'				=> __('Background Color', 'addonify-variation-swatches'),
							'name'				=> ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'tooltip_bck_color',
							'default'			=> '#000',
						),
					),
				),
				array(
					'field_id'				=> ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'item_colors',
					'field_label'			=> __( 'Attributes / Item', 'addonify-variation-swatches' ),
					'field_callback'		=> array($this, "color_picker_group"),
					'field_callback_args'	=> array( 
						array(
							'label'				=> __('Text Color', 'addonify-variation-swatches'),
							'name'				=> ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'item_text_color',
							'default'			=> '#000000',
						),
						array(
							'label'				=> __('Background Color', 'addonify-variation-swatches'),
							'name'				=> ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'item_bck_color',
							'default'			=> '#000000',
						),
					),
				),
				array(
					'field_id'				=> ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'item_colors_hover',
					'field_label'			=> '',
					'field_callback'		=> array($this, "color_picker_group"),
					'field_callback_args'	=> array( 
						array(
							'label'				=> __('Text Color On Hover', 'addonify-variation-swatches'),
							'name'				=> ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'item_text_color_hover',
							'default'			=> '#000000',
						),
						array(
							'label'				=> __('Background Color On Hover', 'addonify-variation-swatches'),
							'name'				=> ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'item_bck_color_hover',
							'default'			=> '#000000',
						),
					),
				),
				array(
					'field_id'				=> ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'selected_item_colors',
					'field_label'			=> __( 'Selected Attribute / Item', 'addonify-variation-swatches' ),
					'field_callback'		=> array($this, "color_picker_group"),
					'field_callback_args'	=> array( 
						array(
							'label'				=> __('Text Color', 'addonify-variation-swatches'),
							'name'				=> ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'selected_item_text_color',
							'default'			=> '#000000',
						),
						array(
							'label'				=> __('Background Color', 'addonify-variation-swatches'),
							'name'				=> ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'selected_item_bck_color',
							'default'			=> '#000000',
						),
						array(
							'label'				=> __('Border Color', 'addonify-variation-swatches'),
							'name'				=> ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'selected_item_border_color',
							'default'			=> '#000000',
						),
					),
				),
				array(
					'field_id'				=> ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'selected_item_border_width',
					'field_label'			=> '',
					'field_callback'		=> array($this, "text_box"),
					'field_callback_args'	=> array( 
						array(
							'label'	    => __('Border Width', 'addonify-variation-swatches'),
							'name'      => ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'selected_item_border_width',
							'default'   => '1',
							'end_label' => __( 'px', 'addonify_variation-swatches' ),
							'css_class' => 'number',
						),
						
					),
				),
				array(
					'field_id'				=> ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'custom_css',
					'field_label'			=> __('Custom CSS', 'addonify-variation-swatches'),
					'field_callback'		=> array($this, "text_area"),
					'field_callback_args'	=> array( 
						array(
							'name'				=> ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . 'custom_css',
							'attr'				=> 'rows="5" class="large-text code"',
						),
						
					),
				),
			)
		);

		// create settings fields.
		$helper->create_settings( $settings_args );

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
		$helper = $this->helper;

		if ( ! $helper->is_woocommerce_active() ) {
			add_action(
				'admin_notices',
				function() {
					require dirname( __FILE__ ) . '/templates/woocommerce_not_active_notice.php';
				}
			);
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

		$helper = $this->helper;

		foreach ( $helper->available_attributes_types() as $key => $options ) {
			$selector[ $key ] = $options['title'];
		}

		return $selector;
	}


	/**
	 * Register action to show custom form fields in product attributes term page
	 *
	 * @since    1.0.0
	 */
	private function register_action_for_custom_term_fields() {

		$helper = $this->helper;

		foreach ( $helper->get_all_attributes() as $attr ) {

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

		$helper = $this->helper;

		$attribute_type = '';
		foreach ( $helper->get_all_attribute_taxonomies() as $attr ) {
			if ( isset( $_GET['taxonomy'] ) && 'pa_' . $attr->attribute_name === $_GET['taxonomy'] ) {
				$attribute_type = strtolower( $attr->attribute_type );
				break;
			}
		}

		if ( ! $attribute_type || $attribute_type === 'select' || $attribute_type === 'button' ) {
			return;
		}

		$helper->taxonomy_form_markup( $attribute_type, $is_edit_form );

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

		$helper = $this->helper;

		foreach ( $helper->get_all_attributes() as $attr ) {
			$term_name = 'pa_' . $attr['name'];
			add_filter( 'manage_edit-' . $term_name . '_columns', array( $this, 'custom_column_heading_for_attributes' ) );
			add_filter( 'manage_' . $term_name . '_custom_column', array( $this, 'my_custom_taxonomy_columns_content' ), 10, 3 );
		}

	}


	/**
	 * Show custom "column" in term table
	 *
	 * @since    1.0.0
	 * @param string $columns Name of columns for term table.
	 */
	public function custom_column_heading_for_attributes( $columns ) {

		$helper = $this->helper;

		if ( ! isset( $_GET['taxonomy'] ) ) {
			return;
		}

		$attribute_type = '';
		foreach ( $helper->get_all_attribute_taxonomies() as $attr ) {
			if ( strval( wp_unslash( $_GET['taxonomy'] ) ) === 'pa_' . $attr->attribute_name ) {
				$attribute_type = strtolower( $attr->attribute_type );
				break;
			}
		}

		if ( ! $attribute_type ) {
			return;
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
		$helper = $this->helper;
		return $helper->get_attr_type_preview_for_term( $column_name, $term_id );
	}
	
}
