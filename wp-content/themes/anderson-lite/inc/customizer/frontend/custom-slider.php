<?php 
/***
 * Custom Javascript Options
 *
 * Passing Variables from custom Theme Options to the javascript files
 * of theme navigation and frontpage slider. 
 *
 */

// Passing Variables to Featured Post Slider Slider ( js/slider.js)
add_action( 'wp_enqueue_scripts', 'anderson_custom_slider_params' );

if ( ! function_exists( 'anderson_custom_slider_params' ) ) :

function anderson_custom_slider_params() { 
	
	// Get Theme Options from Database
	$theme_options = anderson_theme_options();
	
	// Set Parameters array
	$params = array();
	
	// Define Slider Animation
	$params['animation'] = $theme_options['slider_animation'];
	
	// Define Slider Speed
	$params['speed'] = $theme_options['slider_speed'];
	
	// Passing Parameters to Javascript
	wp_localize_script( 'anderson-lite-post-slider', 'anderson_slider_params', $params );
	
}

endif;



// Passing Variables to jQuery responsiveMenu ( js/navigation.js)
add_action( 'wp_enqueue_scripts', 'anderson_custom_navigation_params' );

if ( ! function_exists( 'anderson_custom_navigation_params' ) ) :

function anderson_custom_navigation_params() { 
	
	// Set Parameters array
	$params = array();
	
	// Set Menu Titles
	$params['mainnav_title'] = esc_html__( 'Menu', 'anderson-lite' );
	$params['topnav_title'] = esc_html__( 'Menu', 'anderson-lite' );
	
	// Passing Parameters to Javascript
	wp_localize_script( 'anderson-lite-navigation', 'anderson_navigation_params', $params );
	
}

endif;