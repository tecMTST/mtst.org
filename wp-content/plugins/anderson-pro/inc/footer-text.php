<?php
/*
 * Footer Text
 * 
 * Removes default Credit Link and displays Footer Text
 *
 */

// Remove default footer text
remove_action( 'anderson_footer_text', 'anderson_display_footer_text' );

// Display Footer Widgets on Anderson
add_action( 'anderson_footer_text', 'anderson_pro_display_footer_text' );

// Display Site Title
function anderson_pro_display_footer_text() { 

	// Get Theme Options from Database
	$theme_options = anderson_pro_theme_options();
				
	if ( isset( $theme_options['footer_text'] ) and $theme_options['footer_text'] <> '' ) :
		
		echo do_shortcode(wp_kses_post($theme_options['footer_text']));
			
	endif; 
	
	// Call Credit Link function of theme if credit link is activated
	if ( isset($theme_options['credit_link']) and $theme_options['credit_link'] == true ) :
	
		if ( function_exists( 'anderson_display_footer_text' ) ) :
		
			anderson_display_footer_text();
			
		endif;
		
	endif;

}

?>