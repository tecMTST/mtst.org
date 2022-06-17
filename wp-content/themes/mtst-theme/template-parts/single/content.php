            <section class="content">
                <div class="container">
                    <div class="titulo-pagina">
                        <h1><img src="<?php echo get_template_directory_uri() ?>/assets/images/borda-vermelha.png" alt=""><?php the_title(); ?></h1>
                    </div>
                    <?php the_content(); ?>
                    <div class="author-container">
                        <h3>Sobre o Autor</h3>
                        <?php if ( function_exists( 'wpsabox_author_box' ) ) echo wpsabox_author_box(); ?>
                    </div>
                </div>
            </section>