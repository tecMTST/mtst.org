<?php
/***
 * Template Tags
 *
 * This file contains several template functions which are used to print out specific HTML markup
 * in the theme. You can override these template functions within your child theme.
 *
 */


if ( ! function_exists( 'anderson_site_logo' ) ) :
    /**
	 * Displays the site logo in the header area
	 */
	function anderson_site_logo() {

		if ( function_exists( 'the_custom_logo' ) ) {

			the_custom_logo();

		}

	}
endif;


if ( ! function_exists( 'anderson_site_title' ) ) :
	/**
	 * Displays the site title in the header area
	 */
	function anderson_site_title() {

		if ( is_home() or is_page_template( 'template-magazine.php' )  ) : ?>

			<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>

		<?php else : ?>

			<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>

		<?php endif;

	}
endif;


if ( ! function_exists( 'anderson_site_description' ) ) :
	/**
	 * Displays the site description in the header area
	 */
	function anderson_site_description() {

		$description = get_bloginfo( 'description', 'display' ); /* WPCS: xss ok. */

		if ( $description || is_customize_preview() ) : ?>

			<p class="site-description"><?php echo $description; ?></p>

		<?php
		endif;
	}
endif;


// Display Custom Header
if ( ! function_exists( 'anderson_display_header_banner' ) ) :

	function anderson_display_header_banner() {

		// Get Theme Options from Database
		$theme_options = anderson_theme_options();

		// Display Header Banner Ad
		if ( isset( $theme_options['header_ad_code'] ) and $theme_options['header_ad_code'] <> '' ) : ?>

			<div id="header-banner" class="clearfix">
				<?php echo do_shortcode( wp_kses_post( $theme_options['header_ad_code'] ) ); ?>
			</div>

		<?php
		endif;

	}

endif;


// Display Custom Header
if ( ! function_exists( 'anderson_display_custom_header' ) ) :

	function anderson_display_custom_header() {

		// Get theme options from database
		$theme_options = anderson_theme_options();

		// Hide header image on front page
		if ( true == $theme_options['custom_header_hide'] and is_front_page() ) {
			return;
		}

		// Check if page is displayed and featured header image is used
		if ( is_page() && has_post_thumbnail() ) : ?>

			<div id="custom-header" class="container">
				<?php the_post_thumbnail( 'anderson-header-image' ); ?>
			</div>

		<?php // Check if there is a custom header image
		elseif ( get_header_image() ) : ?>

			<div id="custom-header" class="container">

				<?php // Check if custom header image is linked
				if ( $theme_options['custom_header_link'] <> '' ) : ?>

					<a href="<?php echo esc_url( $theme_options['custom_header_link'] ); ?>">
						<img src="<?php header_image(); ?>" srcset="<?php echo esc_attr( wp_get_attachment_image_srcset( get_custom_header()->attachment_id, 'full' ) ); ?>" width="<?php echo esc_attr( get_custom_header()->width ); ?>" height="<?php echo esc_attr( get_custom_header()->height ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
					</a>

				<?php else : ?>

					<img src="<?php header_image(); ?>" srcset="<?php echo esc_attr( wp_get_attachment_image_srcset( get_custom_header()->attachment_id, 'full' ) ); ?>" width="<?php echo esc_attr( get_custom_header()->width ); ?>" height="<?php echo esc_attr( get_custom_header()->height ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">

				<?php endif; ?>

			</div>

		<?php
		endif;

	}

endif;


// Display Postmeta Data
if ( ! function_exists( 'anderson_display_postmeta' ) ) :

	function anderson_display_postmeta() {

		// Get Theme Options from Database
		$theme_options = anderson_theme_options();

		// Display Date unless user has deactivated it via settings
		if ( true == $theme_options['meta_date'] ) :

			anderson_meta_date();

		endif;

		// Display Author unless user has deactivated it via settings
		if ( true == $theme_options['meta_author'] ) :

			anderson_meta_author();

		endif;

	}

endif;


// Display Post Date
if ( ! function_exists( 'anderson_meta_date' ) ) :

	function anderson_meta_date() {

		$time_string = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date published updated" datetime="%3$s">%4$s</time></a>',
			esc_url( get_permalink() ),
			esc_attr( get_the_time() ),
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() )
		);

		$posted_on = sprintf( esc_html_x( 'Posted on %s', 'post date', 'anderson-lite' ), $time_string );

		echo '<span class="meta-date">' . $posted_on . '</span>';

	}

endif;


// Display Post Author
if ( ! function_exists( 'anderson_meta_author' ) ) :

	function anderson_meta_author() {

		$author_string = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( esc_html__( 'View all posts by %s', 'anderson-lite' ), get_the_author() ) ),
			esc_html( get_the_author() )
		);

		$byline = sprintf( esc_html_x( 'by %s', 'post author', 'anderson-lite' ), $author_string );

		echo '<span class="meta-author"> ' . $byline . '</span>';

	}

endif;


// Display Tags
if ( ! function_exists( 'anderson_display_tags' ) ) :

	function anderson_display_tags() {

		// Get Theme Options from Database
		$theme_options = anderson_theme_options();

		// Display Date unless user has deactivated it via settings
		if ( isset( $theme_options['meta_tags'] ) and $theme_options['meta_tags'] == true ) :

			echo get_the_tag_list( '', ' ' );

		endif;

	}

endif;


// Display Post Thumbnail on Archive Pages
function anderson_display_thumbnail_and_categories_index() {

	// Get Theme Options from Database
	$theme_options = anderson_theme_options();

	// Display Post Thumbnail if it exists and feature is activated
	if ( has_post_thumbnail() and
		(isset( $theme_options['post_thumbnails_index'] ) and $theme_options['post_thumbnails_index'] == true ) ) : ?>

		<div class="post-image">

			<a href="<?php esc_url( the_permalink() ) ?>" rel="bookmark">
				<?php the_post_thumbnail( 'post-thumbnail', array( 'class' => 'alignleft' ) ); ?>
			</a>

			<div class="image-post-categories post-categories">
				<?php echo get_the_category_list( '' ); ?>
			</div>

		</div>

<?php // Otherwise only display Categories
	else : ?>

		<div class="single-post-categories post-categories clearfix">
			<?php echo get_the_category_list( '' ); ?>
		</div>

<?php
	endif;

}


// Display Post Thumbnail on single posts
function anderson_display_thumbnail_and_categories_single() {

	// Get Theme Options from Database
	$theme_options = anderson_theme_options();

	// Display Post Thumbnail if it exists and feature is activated
	if ( has_post_thumbnail() and
		(isset( $theme_options['post_thumbnails_single'] ) and $theme_options['post_thumbnails_single'] == true ) ) : ?>

		<div class="post-image-single">

			<?php the_post_thumbnail( 'anderson-slider-image' ); ?>

			<div class="image-post-categories post-categories">
				<?php echo get_the_category_list( '' ); ?>
			</div>

		</div>

<?php // Otherwise only display Categories
	else : ?>

		<div class="single-post-categories post-categories clearfix">
			<?php echo get_the_category_list( '' ); ?>
		</div>

<?php
	endif;

}


// Display Single Post Navigation
if ( ! function_exists( 'anderson_display_post_navigation' ) ) :

	function anderson_display_post_navigation() {

		// Get Theme Options from Database
		$theme_options = anderson_theme_options();

		if ( true == $theme_options['post_navigation'] ) {

			the_post_navigation( array( 'prev_text' => '&laquo; %title', 'next_text' => '%title &raquo;' ) );

		}
	}

endif;


// Display ThemeZee Related Posts plugin
if ( ! function_exists( 'anderson_display_related_posts' ) ) :

	function anderson_display_related_posts() {

		if ( function_exists( 'themezee_related_posts' ) ) {

			themezee_related_posts( array(
				'class' => 'related-posts type-page clearfix',
				'before_title' => '<h2 class="related-posts-title">',
				'after_title' => '</h2>',
			) );

		}
	}

endif;


// Display Content Pagination
if ( ! function_exists( 'anderson_display_pagination' ) ) :

	function anderson_display_pagination() {

		global $wp_query;

		$big = 999999999; // need an unlikely integer

		 $paginate_links = paginate_links( array(
				'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format' => '?paged=%#%',
				'current' => max( 1, get_query_var( 'paged' ) ),
				'total' => $wp_query->max_num_pages,
				'next_text' => '&raquo;',
				'prev_text' => '&laquo',
				'add_args' => false,
		 ) );

		 // Display the pagination if more than one page is found
		 if ( $paginate_links ) : ?>

			  <div class="post-pagination clearfix">
					<?php echo $paginate_links; ?>
			  </div>

			<?php
		endif;

	}

endif;


// Display Footer Text
add_action( 'anderson_footer_text', 'anderson_display_footer_text' );

function anderson_display_footer_text() {
	?>

	<span class="credit-link">
		<?php printf( esc_html__( 'Powered by %1$s and %2$s.', 'anderson-lite' ),
			'<a href="http://wordpress.org" title="WordPress">WordPress</a>',
			'<a href="https://themezee.com/themes/anderson/" title="Anderson WordPress Theme">Anderson</a>'
		); ?>
	</span>

<?php
}


// Display Social Icons
function anderson_display_social_icons() {

	// Check if there is a social_icons menu
	if ( has_nav_menu( 'social' ) ) :

		// Display Social Icons Menu
		wp_nav_menu( array(
			'theme_location' => 'social',
			'container' => false,
			'menu_class' => 'social-icons-menu',
			'echo' => true,
			'fallback_cb' => '',
			'before' => '',
			'after' => '',
			'link_before' => '<span class="screen-reader-text">',
			'link_after' => '</span>',
			'depth' => 1,
			)
		);

	else : // Display Hint how to configure Social Icons ?>

		<p class="social-icons-hint">
			<?php esc_html_e( 'Please go to Appearance &#8594; Menus and create a new custom menu with custom links to all your social networks. Then click on "Manage Locations" tab and assign your created menu to the "Social Icons" location.', 'anderson-lite' ); ?>
		</p>
<?php
	endif;

}


// Custom Template for comments and pingbacks.
if ( ! function_exists( 'anderson_list_comments' ) ) :
	function anderson_list_comments( $comment, $args, $depth ) {

		$GLOBALS['comment'] = $comment;

		if ( $comment->comment_type == 'pingback' or $comment->comment_type == 'trackback' ) : ?>

			<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
				<p><?php esc_html_e( 'Pingback:', 'anderson-lite' ); ?> <?php comment_author_link(); ?>
				<?php edit_comment_link( esc_html__( '(Edit)', 'anderson-lite' ), '<span class="edit-link">', '</span>' ); ?>
				</p>

		<?php else : ?>

		<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">

			<div id="div-comment-<?php comment_ID(); ?>" class="comment-body">

				<div class="comment-author vcard">
					<?php echo get_avatar( $comment, 56 ); ?>
					<?php printf( '<span class="fn">%s</span>', get_comment_author_link() ); ?>
				</div>

		<?php if ( $comment->comment_approved == '0' ) : ?>
				<p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'anderson-lite' ); ?></p>
		<?php endif; ?>

				<div class="comment-meta commentmetadata">
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><?php printf( esc_html__( '%1$s at %2$s', 'anderson-lite' ), get_comment_date(),  get_comment_time() ) ?></a>
					<?php edit_comment_link( esc_html__( '(Edit)', 'anderson-lite' ),'  ','' ) ?>
				</div>

				<div class="comment-content"><?php comment_text(); ?></div>

				<div class="reply">
					<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ) ?>
				</div>

			</div>
<?php
	endif;

	}
endif;
