<?php 
add_action('wp_head', 'anderson_css_layout');
function anderson_css_layout() {
	
	// Get Theme Options from Database
	$theme_options = anderson_theme_options();
	
	// Change Theme Layout to Left Sidebar
	if ( isset($theme_options['layout']) and $theme_options['layout'] == 'left-sidebar' ) :
	
		echo '<style type="text/css">
			@media only screen and (min-width: 60em) {
				#content {
					float: right;
					padding-right: 0;
					padding-left: 2em;
				}
				#sidebar {
					float: left;
				}
			}
		</style>';
	
	endif;
	
	
	// Add Grayscale Filter for featured images
	if ( isset($theme_options['grayscale_filter']) and $theme_options['grayscale_filter'] == true ) :
	
		echo '<style type="text/css">
			.wp-post-image, .slide-image {
				filter: url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'grayscale\'><feColorMatrix type=\'matrix\' values=\'0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0\'/></filter></svg>#grayscale"); /* Firefox 10+, Firefox on Android */    
				-webkit-filter: grayscale(60%);
				-moz-filter: grayscale(60%);
				-ms-filter: grayscale(60%);
				filter: grayscale(60%);
				filter: gray; /* IE 6-9 */
				-webkit-transition: all 0.4s ease; /* Fade to color for Chrome and Safari */
				transition: all 0.4s ease;
				-webkit-backface-visibility: hidden; /* Fix for transition flickering */
			}
			.single .wp-post-image, .wp-post-image:hover, .post-image:hover .wp-post-image, #post-slider-wrap:hover .slide-image, .widget-category-posts .big-post:hover img {
				-moz-filter: none; 
				-ms-filter: none; 
				-o-filter: none; 
				-webkit-filter: none; 
				filter: none;
			}
		</style>';
	
	endif;

}


?>