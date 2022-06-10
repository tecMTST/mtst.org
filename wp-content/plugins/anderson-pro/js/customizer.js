/*
 * Customizer.js to reload changes on Theme Customizer Preview asynchronously.
 *
 */

( function( $ ) {

	/* Theme Colors */
	wp.customize( 'anderson_theme_options[link_color]', function( value ) {
		value.bind( function( newval ) {
			$('.entry a, .entry a:link, .entry a:visited, #topnav-icon, #topnav-toggle, .archive-title span, #topnav-menu a, #footer a')
				.css('color', newval );
			$('input[type="submit"], #image-nav .nav-previous a, #image-nav .nav-next a, #commentform #submit, .more-link, #frontpage-intro .entry .button, .post-tags a, .post-slider-controls .zeeflex-control-paging li a.zeeflex-active, .post-slider-controls .zeeflex-direction-nav a')
				.css('background', newval )
				.css('color', '#ffffff' );

			$('#header-social-icons .social-icons-menu li a, #header-social-icons .social-icons-menu li a:before, #magazine-homepage-widgets .widgettitle a')
				.hover( function() { $( this ).css('color', newval ); },
						function() { $( this ).css('color', '#222222' ); }
				);
			$('.entry a, #topnav-menu a, #footer a')
				.hover( function() { $( this ).css('color', '#222222' ); },
						function() { $( this ).css('color', newval ); }
				);

			$('.post-categories a')
				.hover( function() { $( this ).css('background', newval ).css('color', '#ffffff' ); },
						function() { $( this ).css('background', '#222222' ).css('color', '#ffffff' ); }
				);
			$('input[type="submit"], #image-nav .nav-previous a, #image-nav .nav-next a, #commentform #submit, .more-link, #frontpage-intro .entry .button, .post-tags a, .post-slider-controls .zeeflex-direction-nav a')
				.hover( function() { $( this ).css('background', '#222222' ).css('color', '#ffffff' ); },
						function() { $( this ).css('background', newval ).css('color', '#ffffff' ); }
				);
		} );
	} );

	wp.customize( 'anderson_theme_options[navi_color]', function( value ) {
		value.bind( function( newval ) {
			$('.main-navigation-menu li.current_page_item a, .main-navigation-menu li.current-menu-item a, .post-pagination .current')
				.css('color', newval );
			$('.main-navigation-menu li.current_page_item a, .main-navigation-menu li.current-menu-item a, .post-pagination .current')
				.css('border-top', '5px solid ' + newval );
			$('.main-navigation-menu a, .post-pagination a')
				.hover( function() { $( this ).css('border-color', newval ); },
						function() { $( this ).css('border-color', 'transparent' ); }
				);
			$('#mainnav-icon, #mainnav-toggle, .main-navigation-menu a')
				.hover( function() { $( this ).css('color', newval ); },
						function() { $( this ).css('color', '#222222' ); }
				);
			$('.main-navigation-menu ul a')
				.hover( function() { $( this ).css('background', newval ).css('color', '#ffffff' ); },
						function() { $( this ).css('background', '#222222' ).css('color', '#ffffff' ); }
				);
		} );
	} );

	wp.customize( 'anderson_theme_options[title_color]', function( value ) {
		value.bind( function( newval ) {
			$('#logo .site-title, .post-title, .entry-title')
				.css('background', newval );
			$('#logo .site-title, .post-title, .entry-title')
				.hover( function() { $( this ).css('background', '#222222' ).css('color', '#ffffff' ); },
						function() { $( this ).css('background', newval ).css('color', '#ffffff' ); }
				);
		} );
	} );

	wp.customize( 'anderson_theme_options[widget_title_color]', function( value ) {
		value.bind( function( newval ) {
			$('.widgettitle span, .page-header .archive-title')
				.css('border-color', newval );
		} );
	} );

	wp.customize( 'anderson_theme_options[widget_link_color]', function( value ) {
		value.bind( function( newval ) {
			$('.widget a:link, .widget a:visited')
				.not( $('.widget-category-posts a:link, .widget-category-posts a:visited') )
				.css('color', newval );
			$('.tagcloud a, .widget-social-icons .social-icons-menu li a')
				.css('background', newval )
				.css('color', '#ffffff' );
			$('.widget a')
				.hover( function() { $( this ).css('color', '#222222'); },
						function() { $( this ).css('color', newval ); }
				);
			$('.tagcloud a, .widget-social-icons .social-icons-menu li a')
				.hover( function() { $( this ).css('background', '#222222' ).css('color', '#ffffff' ); },
						function() { $( this ).css('background', newval ).css('color', '#ffffff' ); }
				);
			$('.tzwb-tabbed-content .tzwb-tabnavi li a, .tzwb-social-icons-menu li a')
				.hover( function() { $( this ).css('background', newval ).css('color', '#ffffff' ); },
						function() { $( this ).css('background', '#222222' ).css('color', '#ffffff' ); }
				);
			$('.tzwb-tabbed-content .tzwb-tabnavi li a.current-tab')
				.css('background', newval );
			$('.tzwb-tabbed-content .tzwb-tabnavi li a')
				.css('color', '#ffffff' );
		} );
	} );


	/* Theme Fonts */
	wp.customize( 'anderson_theme_options[text_font]', function( value ) {
		value.bind( function( newval ) {

			// Embed Font
			var fontFamilyUrl = newval.split(" ").join("+");
			var googleFontPath = "https://fonts.googleapis.com/css?family="+fontFamilyUrl;
			var googleFontSource = "<link id='anderson-pro-custom-text-font' href='"+googleFontPath+"' rel='stylesheet' type='text/css'>";
			var checkLink = $("head").find("#anderson-pro-custom-text-font").length;

			if (checkLink > 0) {
				$("head").find("#anderson-pro-custom-text-font").remove();
			}
			$("head").append(googleFontSource);

			// Set Font.
			var systemFont = '-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif';
			var newFont = newval === 'SystemFontStack' ? systemFont : newval;

			// Set CSS
			$('body, input, textarea, .main-navigation-menu ul a')
				.css('font-family', newFont );

		} );
	} );

	wp.customize( 'anderson_theme_options[title_font]', function( value ) {
		value.bind( function( newval ) {

			// Embed Font
			var fontFamilyUrl = newval.split(" ").join("+");
			var googleFontPath = "https://fonts.googleapis.com/css?family="+fontFamilyUrl;
			var googleFontSource = "<link id='anderson-pro-custom-title-font' href='"+googleFontPath+"' rel='stylesheet' type='text/css'>";
			var checkLink = $("head").find("#anderson-pro-custom-title-font").length;

			if (checkLink > 0) {
				$("head").find("#anderson-pro-custom-title-font").remove();
			}
			$("head").append(googleFontSource);

			// Set Font.
			var systemFont = '-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif';
			var newFont = newval === 'SystemFontStack' ? systemFont : newval;

			// Set CSS
			$('#logo .site-title, .page-title, .post-title, .archive-title, #comments .comments-title, #respond #reply-title')
				.css('font-family', newFont );

		} );
	} );

	wp.customize( 'anderson_theme_options[navi_font]', function( value ) {
		value.bind( function( newval ) {

			// Embed Font
			var fontFamilyUrl = newval.split(" ").join("+");
			var googleFontPath = "https://fonts.googleapis.com/css?family="+fontFamilyUrl;
			var googleFontSource = "<link id='anderson-pro-custom-navi-font' href='"+googleFontPath+"' rel='stylesheet' type='text/css'>";
			var checkLink = $("head").find("#anderson-pro-custom-navi-font").length;

			if (checkLink > 0) {
				$("head").find("#anderson-pro-custom-navi-font").remove();
			}
			$("head").append(googleFontSource);

			// Set Font.
			var systemFont = '-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif';
			var newFont = newval === 'SystemFontStack' ? systemFont : newval;

			// Set CSS
			$('#mainnav-icon, #mainnav-toggle, .main-navigation-menu a')
				.css('font-family', newFont );

		} );
	} );

	wp.customize( 'anderson_theme_options[widget_title_font]', function( value ) {
		value.bind( function( newval ) {

			// Embed Font
			var fontFamilyUrl = newval.split(" ").join("+");
			var googleFontPath = "https://fonts.googleapis.com/css?family="+fontFamilyUrl;
			var googleFontSource = "<link id='anderson-pro-custom-widget-title-font' href='"+googleFontPath+"' rel='stylesheet' type='text/css'>";
			var checkLink = $("head").find("#anderson-pro-custom-widget-title-font").length;

			if (checkLink > 0) {
				$("head").find("#anderson-pro-custom-widget-title-font").remove();
			}
			$("head").append(googleFontSource);

			// Set Font.
			var systemFont = '-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif';
			var newFont = newval === 'SystemFontStack' ? systemFont : newval;

			// Set CSS
			$('.widgettitle span')
				.css('font-family', newFont );

		} );
	} );

} )( jQuery );
