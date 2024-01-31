<?php if (get_post_type() != 'page') :
	$the_cat = get_the_category(); 
	$category_name = $the_cat[0]->cat_name;
	$category_link = get_category_link( $the_cat[0]->cat_ID ); 
endif; ?>

<div id="post-<?php the_ID(); ?>" class="entry col-md-6">
	<div class="ultima-noticia ultimas-duas-col">
		<div class="thumb" style="background-image:url(<?php echo get_the_post_thumbnail_url(get_the_ID(),'full'); ?>)">
			<?php if (get_post_type() != 'page') : ?>
			<a class="link-categoria" href="<?php echo $category_link; ?>"><div class="categoria"><?php echo $category_name; ?></div></a>
			<?php endif; ?>
        </div>
		<div class="titulo-noticia">
			<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			<div class="ler-mais"><a href="<?php the_permalink(); ?>">LER MAIS >>></a></div>
		</div>
	</div>
</div>