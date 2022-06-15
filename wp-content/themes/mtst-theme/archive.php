<?php
get_header();
?>

<main id="main" class="site-main" role="main">

	<?php 
	if ( have_posts() ) : ?>

		<div class="page-header container">
			<h1><?php the_archive_title(); ?></h1>
        </div>
        <div class="container">
            <div class="row">
                <?php
                while ( have_posts() ) : the_post(); ?>
                    
                            <?php get_template_part( 'template-parts/content/content', 'archive' ); ?>
                        

                <?php endwhile; ?>
            </div>
            <div class="pagination">
                <?php the_posts_pagination( ); ?>
            </div>
        </div>
	
	<?php else: ?>

		<p>Sorry, but nothing matched your search terms.</p>
	
	<?php
	endif;
	?>
    
</main>

<?php
get_sidebar();
get_footer(); ?>