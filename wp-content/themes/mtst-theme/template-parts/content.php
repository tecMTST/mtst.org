<div id="post-<?php the_ID(); ?>" class="entry col-md-6">
	<div class="ultima-noticia ultimas-duas-col">
		<div class="thumb" style="background-image:url(<?php echo get_the_post_thumbnail_url(get_the_ID(),'full'); ?>)">
        </div>
		<div class="titulo-noticia">
			<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
			<div class="ler-mais"><a href="<?php the_permalink(); ?>">LER MAIS >>></a></div>
		</div>
	</div>
</div>