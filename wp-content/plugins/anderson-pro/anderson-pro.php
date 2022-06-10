<?php
/*
Plugin Name: Anderson Pro
Plugin URI: http://themezee.com/themes/anderson/#PROVersion-1
Description: Adds additional features like footer widgets, custom colors, fonts and logo upload to the Anderson theme.
Author: ThemeZee
Author URI: http://themezee.com/
Version: 1.2.1
Text Domain: anderson-pro
Domain Path: /languages/
License: GPL v3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Anderson Pro Plugin
Copyright(C) 2015, ThemeZee.com - support@themezee.com

*/

// Define Plugin Name
define( 'ANDERSON_PRO_NAME', 'Anderson Pro');

// Define Version Number
define( 'ANDERSON_PRO_VERSION', '1.2.1' );

// Define Plugin Name
define( 'ANDERSON_PRO_PRODUCT_ID', 22945 );

// Define Update API URL
define( 'ANDERSON_PRO_STORE_API_URL', 'https://themezee.com' );

// Include Admin Files
require( dirname( __FILE__ ) . '/inc/admin/class-plugin-updater.php' );
require( dirname( __FILE__ ) . '/inc/admin/class-settings.php' );
require( dirname( __FILE__ ) . '/inc/admin/class-settings-page.php' );
require( dirname( __FILE__ ) . '/inc/admin/class-admin-notices.php' );

// Setup Plugin
add_action( 'init', 'anderson_pro_setup' );

function anderson_pro_setup() {

	// Add Translation
	load_plugin_textdomain( 'anderson-pro', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	// Return early if Anderson Theme is not active
	if ( ! get_theme_support( 'anderson-pro' ) ) {
		return;
	}

	// include Theme Customizer Options
	require( dirname( __FILE__ ) . '/inc/customizer/customizer.php' );
	require( dirname( __FILE__ ) . '/inc/customizer/default-options.php' );

	// include Custom Colors, Fonts and Logo Files
	require( dirname( __FILE__ ) . '/inc/custom-colors.php' );
	require( dirname( __FILE__ ) . '/inc/custom-fonts.php' );
	require( dirname( __FILE__ ) . '/inc/custom-logo.php' );

	// Include Footer Files
	require( dirname( __FILE__ ) . '/inc/footer-widgets.php' );
	require( dirname( __FILE__ ) . '/inc/footer-text.php' );

}


// Load Anderson Pro Stylesheet
add_action( 'wp_enqueue_scripts', 'anderson_pro_enqueue_scripts', 11 );

function anderson_pro_enqueue_scripts() {

	// Return early if Anderson Theme is not active
	if ( ! current_theme_supports( 'anderson-pro'  ) ) {
		return;
	}

	// Register and Enqueue Stylesheet
	wp_enqueue_style( 'anderson-pro-stylesheet', plugins_url( '/css/anderson-pro.css', __FILE__ ), array(), ANDERSON_PRO_VERSION );

}


// Register Widgets
add_action( 'widgets_init', 'anderson_pro_register_widgets', 20 );

function anderson_pro_register_widgets() {

	$theme_support = get_theme_support( 'anderson-pro' );

	// Return early if Anderson Theme is not active
	if ( ! $theme_support ) {
		return;
	}

	//Register Footer Widgets
	register_sidebar( array(
		'name' => __( 'Footer Left', 'anderson-pro' ),
		'id' => 'footer-left',
		'description' => __( 'Appears on footer on the left hand side.', 'anderson-pro' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s clearfix">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widgettitle"><span>',
		'after_title' => '</span></h3>'
	));
	register_sidebar( array(
		'name' => __( 'Footer Center Left', 'anderson-pro' ),
		'id' => 'footer-center-left',
		'description' => __( 'Appears on footer on center left position.', 'anderson-pro' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s clearfix">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widgettitle"><span>',
		'after_title' => '</span></h3>'
	));
	register_sidebar( array(
		'name' => __( 'Footer Center Right', 'anderson-pro' ),
		'id' => 'footer-center-right',
		'description' => __( 'Appears on footer on center right position.', 'anderson-pro' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s clearfix">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widgettitle"><span>',
		'after_title' => '</span></h3>'
	));
	register_sidebar( array(
		'name' => __( 'Footer Right', 'anderson-pro' ),
		'id' => 'footer-right',
		'description' => __( 'Appears on footer on the right hand side.', 'anderson-pro' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s clearfix">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widgettitle"><span>',
		'after_title' => '</span></h3>'
	));
}


// Add automatic plugin updater from ThemeZee Store API
add_action( 'admin_init', 'anderson_pro_plugin_updater', 0 );

function anderson_pro_plugin_updater() {

	if( ! is_admin() ) :
		return;
	endif;

	$options = Anderson_Pro_Settings::instance();

	if( $options->get( 'license_key' ) <> '' ) :

		$license_key = trim( $options->get( 'license_key' ) );

		// setup the updater
		$anderson_pro_updater = new Anderson_Pro_Plugin_Updater( ANDERSON_PRO_STORE_API_URL, __FILE__, array(
				'version' 	=> ANDERSON_PRO_VERSION,
				'license' 	=> $license_key,
				'item_name' => ANDERSON_PRO_NAME,
				'item_id'   => ANDERSON_PRO_PRODUCT_ID,
				'author' 	=> 'ThemeZee'
			)
		);

	endif;

}
