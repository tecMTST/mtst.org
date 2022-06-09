<?php
/*
 * Custom Logo
 * 
 * Removes default Site Logo of Anderson and replaces it with Site Logo Feature
 *
 */

// Remove default site title
remove_action( 'anderson_site_title', 'anderson_display_site_title' );

// Display Footer Widgets on Anderson
add_action( 'anderson_site_title', 'anderson_pro_display_site_logo' );

// Display Site Title
function anderson_pro_display_site_logo() { 

	// Get Theme Options from Database
	$theme_options = anderson_pro_theme_options();
?>

	<a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
				
	<?php // Display Logo Image or Site Title
		if ( isset($theme_options['header_logo']) and $theme_options['header_logo'] <> '' ) : ?>
			<img class="site-logo" src="<?php echo esc_url($theme_options['header_logo']); ?>" alt="<?php esc_attr(bloginfo('name')); ?>" />
	<?php else: ?>
			<h1 class="site-title"><?php bloginfo('name'); ?></h1>
	<?php endif; ?>
	
	</a>

<?php
}

?>