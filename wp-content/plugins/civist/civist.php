<?php
/**
 *
 * Plugin Name: Civist
 * Description: With Civist you create petitions directly in WordPress, raise funds and build strong supporter networks.
 * Version:     7.3.0
 * Author:      Civist
 * Author URI:  https://civist.com
 * License:     MIT
 * Text Domain: civist
 *
 * @package civist
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * The plugin's uninstall handler.
 */
function uninstall_civist() {
	delete_option( 'civist' );
}

register_uninstall_hook( __FILE__, 'uninstall_civist' );

/**
 * Instantiate the plugin class
 */
function run_civist() {
	$version = '7.3.0';
	$plugin_name = 'Civist';
	$plugin_slug = 'civist';
	$plugin_text_domain = 'civist';
	$plugin_file = __FILE__;
	$stackdriver_service_name = 'civist-wordpress-plugin';
	$stackdriver_key = 'AIzaSyCuoiw70inJ30m6wDRgSPBkRulPT1ZeXH8';
	$stackdriver_project_id = 'civist-saas';
	$registration_url = 'https://registration.civist.cloud/';
	$geoip_url = 'https://geoip.civist.cloud/';
	$widget_supported_languages = json_decode( '["en","en-US","en-GB","de","es","pt","fr","it","eu","ca","pl","sk","cs","nl","nl-NL-x-formal","ko","nb","ro","hr"]' );
	$enforce_https = true;
	if ( defined( 'CIVIST_ENFORCE_HTTPS' ) ) {
		$enforce_https = CIVIST_ENFORCE_HTTPS;
	}

	require_once( 'class-civist.php' );
	$plugin = new Civist( $version, $plugin_name, $plugin_slug, $plugin_text_domain, $plugin_file, $registration_url, $stackdriver_service_name, $stackdriver_key, $stackdriver_project_id, $geoip_url, $widget_supported_languages, $enforce_https );
	$plugin->run();
}
run_civist();
