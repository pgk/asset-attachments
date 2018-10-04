<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://kountanis.com
 * @since             1.0.0
 * @package           Asset_Attachments
 *
 * @wordpress-plugin
 * Plugin Name:       asset-attachments
 * Plugin URI:        https://kountanis.com/projects/plugins/asset-attachments
 * Description:       Upload js and css into media library. Attach js and css files to a post.
 * Version:           1.0.0
 * Author:            Panos Kountanis
 * Author URI:        https://kountanis.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       asset-attachments
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
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-asset-attachments-activator.php
 */
function activate_Asset_Attachments() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-asset-attachments-activator.php';
	Asset_Attachments_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-asset-attachments-deactivator.php
 */
function deactivate_Asset_Attachments() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-asset-attachments-deactivator.php';
	Asset_Attachments_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_Asset_Attachments' );
register_deactivation_hook( __FILE__, 'deactivate_Asset_Attachments' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-asset-attachments.php';

function asset_attachments() {
	return Asset_Attachments::instance();
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_Asset_Attachments() {
	asset_attachments()->run();
}
run_Asset_Attachments();
