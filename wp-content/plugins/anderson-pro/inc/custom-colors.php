<?php
/***
 * Custom Color Options
 *
 * Get custom colors from theme options and embed CSS color settings
 * in the <head> area of the theme.
 *
 */


// Add Custom Colors
add_action('wp_head', 'anderson_pro_custom_colors');
function anderson_pro_custom_colors() {

	// Get Theme Options from Database
	$theme_options = anderson_pro_theme_options();

	// Set Color CSS Variable
	$color_css = '';

	// Set Link Color
	if ( isset($theme_options['link_color']) and $theme_options['link_color'] <> '#dd2727' ) :

		$color_css .= '
			a, a:link, a:visited, #topnav-icon, #topnav-toggle, .archive-title span,
			#header-social-icons .social-icons-menu li a:hover, #header-social-icons .social-icons-menu li a:hover:before {
				color: '. $theme_options['link_color']  .';
			}
			input[type="submit"], #image-nav .nav-previous a, #image-nav .nav-next a,
			#commentform #submit, .more-link, #frontpage-intro .entry .button, .post-tags a,
			.post-categories a:hover, .post-categories a:active,
			.post-slider-controls .zeeflex-control-paging li a.zeeflex-active, .post-slider-controls .zeeflex-direction-nav a {
				background-color: '. $theme_options['link_color']  .';
			}
			a:hover, a:active, #magazine-homepage-widgets .widgettitle a:link, #magazine-homepage-widgets .widgettitle a:visited {
				color: #222;
			}
			#magazine-homepage-widgets .widgettitle a:hover, #magazine-homepage-widgets .widgettitle a:active {
				color: '. $theme_options['link_color']  .';
			}';

	endif;

	// Set Navigation Color
	if ( isset($theme_options['navi_color']) and $theme_options['navi_color'] <> '#dd2727' ) :

		$color_css .= '
			#mainnav-icon:hover, #mainnav-toggle:hover, .main-navigation-menu a:hover,
			.main-navigation-menu li.current_page_item a, .main-navigation-menu li.current-menu-item a,
			.main-navigation-menu li a:hover:before, .main-navigation-menu li a:hover:after, .main-navigation-menu .submenu-dropdown-toggle:hover:before,
			.post-pagination a:hover, .post-pagination .current {
				color: '. $theme_options['navi_color'] .';
			}
			.main-navigation-menu a:hover, .main-navigation-menu li.current_page_item a, .main-navigation-menu li.current-menu-item a,
			.post-pagination a:hover, .post-pagination .current {
				border-top: 5px solid '. $theme_options['navi_color'] .';
			}
			.main-navigation-menu ul a:hover, .main-navigation-menu ul a:active {
				background: '. $theme_options['navi_color'] .';
			}
			@media only screen and (max-width: 60em) {
				.main-navigation-menu ul a:hover, .main-navigation-menu ul a:active {
					color: '. $theme_options['navi_color'] .';
					background: none;
				}
				.main-navigation-menu a:hover, .main-navigation-menu li.current_page_item a, .main-navigation-menu li.current-menu-item a {
					border-top: none;
				}
			}
			';

	endif;

	// Set Post Title Color
	if ( isset($theme_options['title_color']) and $theme_options['title_color'] <> '#dd2727' ) :

		$color_css .= '
			#logo .site-title, .post-title, .entry-title {
				background: '. $theme_options['title_color'].';
			}';

	endif;

	// Set Widget Title Color
	if ( isset($theme_options['widget_title_color']) and $theme_options['widget_title_color'] <> '#dd2727' ) :

		$color_css .= '
			.widgettitle span, .page-header .archive-title {
				border-bottom: 5px solid '. $theme_options['widget_title_color'] .';
			}
	';

	endif;

	// Set Widget Link Color
	if ( isset($theme_options['widget_link_color']) and $theme_options['widget_link_color'] <> '#dd2727' ) :

		$color_css .= '
			.widget a:link, .widget a:visited {
				color: '. $theme_options['widget_link_color'].';
			}
			.tagcloud a, .social-icons-menu li a {
				background-color: '. $theme_options['widget_link_color']  .';
			}
			.widget a:hover, .widget a:active {
				color: #222;
			}
			.tagcloud a:link, .tagcloud a:visited,
			.post-categories a:link, .post-categories a:visited {
				color: #fff;
			}
			.widget-category-posts .post-title a:link, .widget-category-posts .post-title a:visited,
			.widget-category-posts .post-title:hover, .widget-category-posts .post-title:active {
				color: #fff;
			}
			.tzwb-tabbed-content .tzwb-tabnavi li a:hover,
			.tzwb-tabbed-content .tzwb-tabnavi li a:active,
			.tzwb-tabbed-content .tzwb-tabnavi li a.current-tab,
			.tzwb-social-icons-menu li a:hover {
				background: '. $theme_options['widget_link_color']  .';
			}
			';

	endif;


	// Print Color CSS
	if ( isset($color_css) and $color_css <> '' ) :

		echo '<style type="text/css">';
		echo $color_css;
		echo '</style>';

	endif;

}