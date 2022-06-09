<?php
/**
 * Theme Customizer Functions
 *
 */

/*========================== CUSTOMIZER SANITIZE FUNCTIONS ==========================*/

// Sanitize checkboxes
function anderson_sanitize_checkbox( $value ) {

	if ( $value == 1) :
        return 1;
	else:
		return '';
	endif;
}


// Sanitize the layout sidebar value.
function anderson_sanitize_layout( $value ) {

	if ( ! in_array( $value, array( 'left-sidebar', 'right-sidebar' ) ) ) :
        $value = 'right-sidebar';
	endif;

    return $value;
}


// Sanitize header ad code textarea
function anderson_sanitize_header_ad_code( $value ) {

	if ( current_user_can('unfiltered_html') ) :
		return $value;
	else :
		return stripslashes( wp_filter_post_kses( addslashes($value) ) );
	endif;
}


// Sanitize the post length value.
function anderson_sanitize_post_length( $value ) {

	if ( ! in_array( $value, array( 'index', 'excerpt' ) ) ) :
        $value = 'excerpt';
	endif;

    return $value;
}

// Sanitize the slider animation value.
function anderson_sanitize_slider_animation( $value ) {

	if ( ! in_array( $value, array( 'slide', 'fade' ) ) ) :
        $value = 'slide';
	endif;

    return $value;
}


?>