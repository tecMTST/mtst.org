
    <?php wp_footer(); ?>
	<?php global $post; ?>
	<?php get_template_part( 'template-parts/footer/site-footer' ); ?>	
	<script type='text/javascript' src='<?php echo get_template_directory_uri(); ?>/assets/bootstrap/bootstrap-4.6.1-dist/js/bootstrap.bundle.min.js?ver=6.0' id='bootstrap-js'></script>
	<script type='text/javascript' src='<?php echo get_template_directory_uri(); ?>/assets/js/<?php echo $post->post_name; ?>.js'></script>
	</body>
</html>