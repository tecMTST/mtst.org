<?php
/**
 * Featured Post Slider
 *
 */

// Get our Featured Content posts
$slider_posts = anderson_get_featured_content();

// Check if there is Featured Content
if ( empty( $slider_posts ) and current_user_can( 'edit_theme_options' ) ) : ?>

	<p class="frontpage-slider-empty-posts">
		<?php esc_html_e( 'There is no featured content to be displayed in the slider. To set up the slider, go to Appearance &#8594; Customize &#8594; Theme Options, and add a featured tag in the Post Slider section. The slideshow displays all your posts which are tagged with that keyword.', 'anderson-lite' ); ?>
	</p>
	
<?php
	return;
endif;

// Limit the number of words in slideshow post excerpts
add_filter('excerpt_length', 'anderson_slideshow_excerpt_length');

// Display Slider
?>
	<div id="post-slider-container">
	
		<div id="post-slider-wrap" class="clearfix">
		
			<div id="post-slider" class="zeeflexslider">
				
				<ul class="zeeslides">

			<?php foreach ( $slider_posts as $post ) : setup_postdata( $post ); ?>

				<li id="slide-<?php the_ID(); ?>" class="zeeslide" data-thumb="<?php echo $thumbnail; ?>">

					<?php // Display Post Thumbnail or default thumbnail
						if( '' != get_the_post_thumbnail() ) :

							the_post_thumbnail('anderson-slider-image', array('class' => 'slide-image'));

						else: ?>

							<img src="<?php echo get_template_directory_uri(); ?>/images/default-slider-image.png" class="slide-image wp-post-image" alt="default-image" />

					<?php endif; ?>
				
					<div class="image-post-categories post-categories">
						<?php echo get_the_category_list(''); ?>
					</div>

					<div class="slide-content clearfix">

						<?php the_title( sprintf( '<h2 class="entry-title post-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
						
						<div class="post-content">
							
							<div class="entry-meta postmeta"><?php anderson_display_postmeta(); ?></div>
							
							<div class="entry clearfix">
								<?php the_excerpt(); ?>
							</div>
							
						</div>
									
					</div>

				</li>

			<?php endforeach; ?>

				</ul>
				
			</div>
			
			<div class="post-slider-controls"></div>

		</div>
		
	</div>

<?php
// Remove excerpt filter
remove_filter('excerpt_length', 'anderson_slideshow_excerpt_length');

// Reset Postdata
wp_reset_postdata();

?>