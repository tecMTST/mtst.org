
    <?php wp_footer(); ?>
	<footer>
		<?php get_template_part( 'template-parts/footer/newsletter' ); ?>
		<?php get_template_part( 'template-parts/footer/site-footer' ); ?>
	</footer>
	<?php if (slug()=='home' || slug()=='o-mtst' || slug()=='fake-news' || slug()=='ultimas-noticias') : ?>
	<script type='text/javascript' src='<?php echo get_template_directory_uri(); ?>/assets/js/<?php echo $post->post_name; ?>.js'></script>
	<?php endif; ?>
	<?php if (slug()=='home-2' || slug()=='o-mtst' || slug()=='fake-news' || slug()=='ultimas-noticias') : ?>
	<script type='text/javascript' src='<?php echo get_template_directory_uri(); ?>/assets/js/<?php echo $post->post_name; ?>.js'></script>
	<?php endif; ?>
	<?php if (is_single()) : ?>
	<script type='text/javascript' src='<?php echo get_template_directory_uri(); ?>/assets/js/single.js'></script>
	<?php endif; ?>
	<script type='text/javascript' src="<?php echo get_template_directory_uri(); ?>/assets/js/geral.js"></script>
	</body>
</html>