<?php
/**
 * Returns theme options
 *
 * Uses sane defaults in case the user has not configured any theme options yet.
 */


// Return theme options
function anderson_pro_theme_options() {
    
	// Merge Theme Options Array from Database with Default Options Array
	$theme_options = wp_parse_args( 
		
		// Get saved theme options from WP database
		get_option( 'anderson_theme_options', array() ), 
		
		// Merge with Default Options if setting was not saved yet
		anderson_pro_default_options() 
		
	);

	// Return theme options
	return $theme_options;
	
}


// Build default options array
function anderson_pro_default_options() {

	$default_options = array(
		'header_logo' 						=> '',
		'footer_text'						=> '',
		'credit_link' 						=> true,
		'text_font' 						=> 'Carme',
		'title_font' 						=> 'Share',
		'navi_font' 						=> 'Share',
		'widget_title_font' 				=> 'Share',
		'available_fonts'					=> 'favorites'
	);
	
	return $default_options;
}


?>