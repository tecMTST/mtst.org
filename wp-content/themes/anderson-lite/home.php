<?php get_header(); ?>

	<div id="wrap" class="container clearfix">

		<section id="content" class="primary" role="main">

		<?php if ( function_exists( 'themezee_breadcrumbs' ) ) themezee_breadcrumbs(); ?>

		<?php // Get Theme Options from Database
			$theme_options = anderson_theme_options();

			// Display Featured Post Slideshow if activated
			if ( isset($theme_options['slider_active']) and $theme_options['slider_active'] == true ) :

				get_template_part( 'featured-content-slider' );

			endif;
		?>

		<?php // Display Magazine Homepage Widgets.
		if ( ! is_paged() && is_active_sidebar( 'magazine-homepage' ) ) : ?>

			<div id="magazine-homepage-widgets" class="widget-area clearfix">

				<?php dynamic_sidebar( 'magazine-homepage' ); ?>

			</div><!-- #magazine-homepage-widgets -->

		<?php endif; ?>

		<?php if (have_posts()) : while (have_posts()) : the_post();

			get_template_part( 'content', $theme_options['posts_length'] );

			endwhile;

		anderson_display_pagination();

		endif; ?>

		</section>

		<?php get_sidebar(); ?>
	</div>

<?php get_footer(); ?>
