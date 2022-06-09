<?php


// Add Category Posts Boxed Widget
class Anderson_Category_Posts_Boxed_Widget extends WP_Widget {

	function __construct() {
		
		// Setup Widget
		$widget_ops = array(
			'classname' => 'anderson_category_posts_boxed', 
			'description' => esc_html__( 'Displays your posts from a selected category in a boxed layout. Please use this widget ONLY in the Magazine Homepage widget area.', 'anderson-lite' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('anderson_category_posts_boxed', sprintf( esc_html__( 'Category Posts: Boxed (%s)', 'anderson-lite' ), 'Anderson' ), $widget_ops);
		
		// Delete Widget Cache on certain actions
		add_action( 'save_post', array( $this, 'delete_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'delete_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'delete_widget_cache' ) );
		
	}

	public function delete_widget_cache() {
		
		wp_cache_delete('widget_anderson_category_posts_boxed', 'widget');
		
	}
	
	private function default_settings() {
	
		$defaults = array(
			'title'				=> '',
			'category'			=> 0,
			'category_link'		=> false,
			'postmeta'			=> true
		);
		
		return $defaults;
		
	}
	
	// Display Widget
	function widget( $args, $instance ) {

		$cache = array();
		
		// Get Widget Object Cache
		if ( ! $this->is_preview() ) {
			$cache = wp_cache_get( 'widget_anderson_category_posts_boxed', 'widget' );
		}
		if ( ! is_array( $cache ) ) {
			$cache = array();
		}

		// Display Widget from Cache if exists
		if ( isset( $cache[ $this->id ] ) ) {
			echo $cache[ $this->id ];
			return;
		}
		
		// Start Output Buffering
		ob_start();
		
		// Get Widget Settings
		$settings = wp_parse_args( $instance, $this->default_settings() );
		
		// Output
		echo $args['before_widget'];
	?>
		<div id="widget-category-posts-boxed" class="widget-category-posts clearfix">
		
			<?php // Display Title
			$this->display_widget_title( $args, $settings ); ?>
			
			<div class="widget-category-posts-content">
			
				<?php $this->render( $settings ); ?>
				
			</div>
			
		</div>
	<?php
		echo $args['after_widget'];
		
		// Set Cache
		if ( ! $this->is_preview() ) {
			$cache[ $this->id ] = ob_get_flush();
			wp_cache_set( 'widget_anderson_category_posts_boxed', $cache, 'widget' );
		} else {
			ob_end_flush();
		}
	
	}
	
	// Render Widget Content
	function render( $settings ) {
		
		// Get latest posts from database
		$query_arguments = array(
			'posts_per_page' => 6,
			'ignore_sticky_posts' => true,
			'cat' => (int)$settings['category']
		);
		$posts_query = new WP_Query( $query_arguments );
		$i = 0;
		
		// Check if there are posts
		if( $posts_query->have_posts() ) :
		
			// Limit the number of words for the excerpt
			add_filter('excerpt_length', 'anderson_category_posts_widgets_excerpt_length');
			
			// Display Posts
			while( $posts_query->have_posts() ) :
				
				$posts_query->the_post(); 
				
				if(isset($i) and $i == 0) : ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class('big-post'); ?>>

						<?php $this->display_thumbnail(); ?>

						<div class="post-content">
							
							<?php the_title( sprintf( '<h2 class="entry-title post-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

							<div class="entry-meta postmeta"><?php $this->display_postmeta( $settings ); ?></div>
							
							<div class="entry clearfix">
								<?php the_excerpt(); ?>
								<a href="<?php esc_url(the_permalink()) ?>" class="more-link"><?php esc_html_e( '&raquo; Read more', 'anderson-lite' ); ?></a>
							</div>
						
						</div>

					</article>

				<div class="small-posts clearfix">

				<?php else: ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class('small-post clearfix'); ?>>

					<?php if ( '' != get_the_post_thumbnail() ) : ?>
						<a href="<?php the_permalink() ?>" rel="bookmark"><?php the_post_thumbnail('anderson-category-posts-widget-small'); ?></a>
					<?php endif; ?>

						<div class="small-posts-content">
							<?php the_title( sprintf( '<h2 class="entry-title post-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
							<div class="entry-meta postmeta"><?php $this->display_meta_date( $settings ); ?></div>
						</div>

					</article>

				<?php
				endif; $i++;
				
			endwhile; ?>
			
				</div><!-- end .small-posts -->
				
			<?php
			// Remove excerpt filter
			remove_filter('excerpt_length', 'anderson_category_posts_widgets_excerpt_length');
			
		endif;
		
		// Reset Postdata
		wp_reset_postdata();
		
	}
	
	// Display Post Thumbnail on Archive Pages
	function display_thumbnail() {
		
		// Display Post Thumbnail if it exists
		if ( has_post_thumbnail() ) : ?>

			<div class="post-image-single">
			
				<a href="<?php esc_url(the_permalink()) ?>" rel="bookmark">
					<?php the_post_thumbnail('anderson-category-posts-widget-big'); ?>
				</a>
				
				<div class="image-post-categories post-categories">
					<?php echo get_the_category_list(''); ?>
				</div>
				
			</div>
		
		<?php endif;
	}
	
	// Display Postmeta
	function display_postmeta( $settings ) { 
		
		if( true == $settings['postmeta'] ) :
		
			$this->display_meta_date( $settings ); ?>
		
			<span class="meta-author">
			<?php printf( '<a class="fn" href="%1$s" title="%2$s" rel="author">%3$s</a>', 
					esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
					esc_attr( sprintf( esc_html__( 'View all posts by %s', 'anderson-lite' ), get_the_author() ) ),
					get_the_author()
				);
			?>
			</span>
	
		<?php endif;
		
	}
	
	// Display Date
	function display_meta_date( $settings ) { 
	
		if( true == $settings['postmeta'] ) : ?>
		
			<span class="meta-date">
			<?php printf( '<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date published updated" datetime="%3$s">%4$s</time></a>',
					esc_url( get_permalink() ),
					esc_attr( get_the_time() ),
					esc_attr( get_the_date( 'c' ) ),
					esc_html( get_the_date() )
				);
			?>
			</span>	
		
		<?php endif;
		
	}
	
	// Link Widget Title to Category
	function display_widget_title( $args, $settings ) {
		
		// Add Widget Title Filter
		$widget_title = apply_filters('widget_title', $settings['title'], $settings, $this->id_base);
		
		if( !empty( $widget_title ) ) :
		
			echo $args['before_title'];
			
			// Link Category Title
			if( $settings['category_link'] == true ) : 
				
				// Check if "All Categories" is selected
				if( $settings['category'] == 0 ) :
				
					$link_title = esc_html__( 'View all posts', 'anderson-lite' );
					
					// Set Link URL to always point to latest posts page
					if ( get_option( 'show_on_front' ) == 'page' ) :
						$link_url = esc_url( get_permalink( get_option( 'page_for_posts' ) ) );
					else : 
						$link_url =	esc_url( home_url('/') );
					endif;
					
				else :
					
					// Set Link URL and Title for Category
					$link_title = sprintf( esc_html__( 'View all posts from category %s', 'anderson-lite' ), get_cat_name( $settings['category'] ) );
					$link_url = esc_url( get_category_link( $settings['category'] ) );
					
				endif;
				
				// Display linked Widget Title
				echo '<a href="'. $link_url .'" title="'. $link_title . '">'. $widget_title . '</a>';
			
			else:
			
				echo $widget_title;
			
			endif;
			
			echo $args['after_title']; 
			
		endif;

	}

	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;
		$instance['title'] = sanitize_text_field($new_instance['title'] );
		$instance['category'] = (int)$new_instance['category'];
		$instance['category_link'] = !empty($new_instance['category_link'] );
		$instance['postmeta'] = !empty($new_instance['postmeta'] );
		
		$this->delete_widget_cache();
		
		return $instance;
	}

	function form( $instance ) {
		
		// Get Widget Settings
		$settings = wp_parse_args( $instance, $this->default_settings() ); 
		?>
		
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e( 'Title:', 'anderson-lite' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $settings['title']; ?>" />
			</label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('category'); ?>"><?php esc_html_e( 'Category:', 'anderson-lite' ); ?></label><br/>
			<?php // Display Category Select
				$args = array(
					'show_option_all'    => esc_html__( 'All Categories', 'anderson-lite' ),
					'show_count' 		 => true,
					'hide_empty'		 => false,
					'selected'           => $settings['category'],
					'name'               => $this->get_field_name('category'),
					'id'                 => $this->get_field_id('category')
				);
				wp_dropdown_categories( $args ); 
			?>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('category_link'); ?>">
				<input class="checkbox" type="checkbox" <?php checked( $settings['category_link'] ) ; ?> id="<?php echo $this->get_field_id('category_link'); ?>" name="<?php echo $this->get_field_name('category_link'); ?>" />
				<?php esc_html_e( 'Link Widget Title to Category Archive page', 'anderson-lite' ); ?>
			</label>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('postmeta'); ?>">
				<input class="checkbox" type="checkbox" <?php checked( $settings['postmeta'] ) ; ?> id="<?php echo $this->get_field_id('postmeta'); ?>" name="<?php echo $this->get_field_name('postmeta'); ?>" />
				<?php esc_html_e( 'Display post date and author', 'anderson-lite' ); ?>
			</label>
		</p>
		
<?php
	}
}

// Register Widget
add_action( 'widgets_init', 'anderson_register_category_posts_boxed_widget' );

function anderson_register_category_posts_boxed_widget() {

	register_widget('Anderson_Category_Posts_Boxed_Widget');
	
}

