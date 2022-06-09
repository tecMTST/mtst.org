<?php
/**
 * Implement Theme Customizer
 *
 */

// Load Customizer Helper Functions
require( dirname( __FILE__ ) . '/functions/custom-controls.php' );
require( dirname( __FILE__ ) . '/functions/sanitize-functions.php' );

// Load Customizer Settings
require( dirname( __FILE__ ) . '/sections/customizer-general.php' );
require( dirname( __FILE__ ) . '/sections/customizer-header.php' );
require( dirname( __FILE__ ) . '/sections/customizer-colors.php' );
require( dirname( __FILE__ ) . '/sections/customizer-fonts.php' );

// Embed JS file to make Theme Customizer preview reload changes asynchronously.
add_action( 'customize_preview_init', 'anderson_pro_customize_preview_js' );

function anderson_pro_customize_preview_js() {
	wp_enqueue_script( 'anderson-pro-customizer-js', plugins_url('/js/customizer.js', dirname(dirname(__FILE__)) ), array( 'customize-preview' ), ANDERSON_PRO_VERSION, true );
}

// Embed CSS styles for Theme Customizer
add_action( 'customize_controls_print_styles', 'anderson_pro_customize_preview_css' );

function anderson_pro_customize_preview_css() {
	wp_enqueue_style( 'anderson-pro-customizer-css', plugins_url('/css/customizer.css', dirname(dirname(__FILE__)) ), array(), ANDERSON_PRO_VERSION );
}

// Remove Anderson PRO upgrade section
remove_action( 'customize_register', 'anderson_customize_register_upgrade_settings' );
