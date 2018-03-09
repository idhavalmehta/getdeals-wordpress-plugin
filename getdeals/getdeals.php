<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://about.me/idhavalmehta
 * @since             1.0.0
 * @package           GetDeals
 *
 * @wordpress-plugin
 * Plugin Name:       GetDeals
 * Plugin URI:        https://getdeals.co.in
 * Description:       Add the GetDeals search engine and price comparison functionality to your WordPress website.
 * Version:           1.0.0
 * Author:            Dhaval Mehta
 * Author URI:        https://about.me/idhavalmehta
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       getdeals
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
define( 'GETDEALS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-getdeals-activator.php
 */
function activate_getdeals() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-getdeals-activator.php';
	GetDeals_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-getdeals-deactivator.php
 */
function deactivate_getdeals() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-getdeals-deactivator.php';
	GetDeals_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_getdeals' );
register_deactivation_hook( __FILE__, 'deactivate_getdeals' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-getdeals.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_getdeals() {

	$plugin = new GetDeals();
	$plugin->run();

}
run_getdeals();
