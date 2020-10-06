<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.addonify.com
 * @since             1.0.0
 * @package           Addonify_Variation_Swatches
 *
 * @wordpress-plugin
 * Plugin Name:       Addonify Variation Swatches
 * Plugin URI:        https://wordpress.org/plugins/addonify-variation-swatches
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Addonify
 * Author URI:        https://www.addonify.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       addonify-variation-swatches
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ADDONIFY_VARIATION_SWATCHES_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-addonify-variation-swatches-activator.php
 */
function activate_addonify_variation_swatches() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-addonify-variation-swatches-activator.php';
	Addonify_Variation_Swatches_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-addonify-variation-swatches-deactivator.php
 */
function deactivate_addonify_variation_swatches() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-addonify-variation-swatches-deactivator.php';
	Addonify_Variation_Swatches_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_addonify_variation_swatches' );
register_deactivation_hook( __FILE__, 'deactivate_addonify_variation_swatches' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-addonify-variation-swatches.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_addonify_variation_swatches() {

	$plugin = new Addonify_Variation_Swatches();
	$plugin->run();

}
run_addonify_variation_swatches();
