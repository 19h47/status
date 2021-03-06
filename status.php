<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link    https://github.com/19h47/status
 * @since   1.0.0
 * @package Status
 *
 * @wordpress-plugin
 * Plugin Name: Status
 * Plugin URI:  https://github.com/19h47/status
 * Description: Tweet to WordPress Post
 * Version:     2.0.0
 * Author:      Jérémy Levron
 * Author URI:  http://19h47.fr
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: status
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
define( 'STATUS', '1.0.0' );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-status-activator.php
 */
function activate_status() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-status-activator.php';
	Status_Activator::activate();
}


/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-status-deactivator.php
 */
function deactivate_status() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-status-deactivator.php';
	Status_Deactivator::deactivate();
}


register_activation_hook( __FILE__, 'activate_status' );
register_deactivation_hook( __FILE__, 'deactivate_status' );


/**
 * Autoload
 */
require_once __DIR__ . '/vendor/autoload.php';


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-status.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.0.0
 */
function run_status() {
	$plugin = new Status();
	$plugin->run();
}
run_status();
