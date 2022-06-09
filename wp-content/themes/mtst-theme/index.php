<?php get_header(); ?>
<div>
  <?php if ( have_posts() ) : while ( have_posts() ) :   the_post(); ?>
    <h2>
      <a href="<?php the_permalink() ?>">
        <?php the_title(); ?>
      </a>
    </h2>
    <?php the_content(); ?>
  <?php endwhile; else: ?>
    <p>Não há posts para exibir</p>
  <?php endif; ?>
</div>
<?php get_footer(); ?>