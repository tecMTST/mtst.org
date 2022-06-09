<!DOCTYPE html><!-- HTML 5 -->
<html <?php language_attributes(); ?>>

<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v3.2&appId=2089725654423970&autoLogAppEvents=1"></script>

<div id="wrapper" class="hfeed">

	<div id="header-wrap">

		<div id="topheader" class="container clearfix">
			<?php locate_template( '/inc/top-navigation.php', true ); ?>
		</div>

		<header id="header" class="container clearfix" role="banner">

			<div id="logo">

				<?php anderson_site_logo(); ?>
				<?php anderson_site_title(); ?>
				<?php anderson_site_description(); ?>

			</div>

			<?php // Display Header Banner Ad
				anderson_display_header_banner(); ?>

		</header>

	</div>

	<div id="navigation-wrap">

		<nav id="mainnav" class="container clearfix" role="navigation">
			<?php
			// Display Main Navigation
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container' => false,
				'menu_id' => 'mainnav-menu',
				'menu_class' => 'main-navigation-menu',
				'echo' => true,
				'fallback_cb' => 'anderson_default_menu',
			) );
			?>
		</nav>

	</div>

	<?php // Display Custom Header Image
		anderson_display_custom_header(); ?>
