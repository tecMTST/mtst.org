<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://codeboxr.com
 * @since             1.0.0
 * @package           CBXPetition
 *
 * @wordpress-plugin
 * Plugin Name:       CBX Petition
 * Plugin URI:        https://codeboxr.com/product/cbx-petition-for-wordpress/
 * Description:       A plugin to create, manage petition and collect signatures for petition
 * Version:           1.0.3
 * Author:            Codeboxr
 * Author URI:        http://codeboxr.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cbxpetition
 * Domain Path:       /languages
 */

//CBX Petition

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

defined( 'CBXPETITION_PLUGIN_NAME' ) or define( 'CBXPETITION_PLUGIN_NAME', 'cbxpetition' );
defined( 'CBXPETITION_PLUGIN_VERSION' ) or define( 'CBXPETITION_PLUGIN_VERSION', '1.0.3' );
defined( 'CBXPETITION_BASE_NAME' ) or define( 'CBXPETITION_BASE_NAME', plugin_basename( __FILE__ ) );
defined( 'CBXPETITION_ROOT_PATH' ) or define( 'CBXPETITION_ROOT_PATH', plugin_dir_path( __FILE__ ) );
defined( 'CBXPETITION_ROOT_URL' ) or define( 'CBXPETITION_ROOT_URL', plugin_dir_url( __FILE__ ) );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cbxpetition-activator.php
 */
function activate_cbxpetition() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cbxpetition-activator.php';
	CBXPetition_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cbxpetition-deactivator.php
 */
function deactivate_cbxpetition() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cbxpetition-deactivator.php';
	CBXPetition_Deactivator::deactivate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cbxpetition-uninstall.php
 */
function uninstall_cbxpetition() {

	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cbxpetition-uninstall.php';
	CBXPetition_Uninstall::uninstall();
}

register_activation_hook( __FILE__, 'activate_cbxpetition' );
register_deactivation_hook( __FILE__, 'deactivate_cbxpetition' );
register_uninstall_hook( __FILE__, 'uninstall_cbxpetition' ); //delets all custom table and custom option values created by this plugin

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cbxpetition.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cbxpetition() {

	$plugin = new CBXPetition();
	$plugin->run();

}

run_cbxpetition();