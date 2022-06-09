
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		
		<?php anderson_display_thumbnail_and_categories_single(); ?>
		
		<div class="post-content">

			<?php the_title( '<h1 class="entry-title post-title">', '</h1>' ); ?>
			
			<div class="entry-meta postmeta"><?php anderson_display_postmeta(); ?></div>
			
			<div class="entry clearfix">
				<?php the_content(); ?>
				<!-- <?php trackback_rdf(); ?> -->
				<div class="page-links"><?php wp_link_pages(); ?></div>			
			</div>
			
			<div class="post-tags clearfix"><?php anderson_display_tags(); ?></div>
						
		</div>

	</article>