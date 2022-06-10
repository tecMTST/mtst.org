<?php
/**
 * Theme Customizer Functions
 *
 */

/*========================== CUSTOMIZER SANITIZE FUNCTIONS ==========================*/

// Sanitize checkboxes
function anderson_pro_sanitize_checkbox( $value ) {

	if ( $value == 1) :
        return 1;
	else:
		return '';
	endif;
}


// Sanitize footer content textarea
function anderson_pro_sanitize_footer_text( $value ) {

	if ( current_user_can('unfiltered_html') ) :
		return $value;
	else :
		return stripslashes( wp_filter_post_kses( addslashes($value) ) );
	endif;
}


// Sanitize available fonts value.
function anderson_pro_sanitize_available_fonts( $value ) {

	if ( ! in_array( $value, array( 'default', 'favorites', 'popular', 'all' ), true ) ) :
        $value = 'favorites';
	endif;

    return $value;
}


?>