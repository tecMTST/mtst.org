            <section class="content">
                <div class="container">
                    <div class="titulo-pagina">
                        <h1><?php the_title(); ?></h1>
                    </div>
                    <div class="date-time">
                        <?php echo get_the_date(); ?>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <?php the_content(); ?>
                            <!-- <div class="author-container">
                                <h3>Sobre o Autor</h3>
                                <?php if ( function_exists( 'wpsabox_author_box' ) ) echo wpsabox_author_box(); ?>
                            </div> -->
                        </div>
                        <div class="col-md-4">
                            <?php get_template_part( 'template-parts/single/sidebar' ); ?>
                        </div>
                    </div> 
                </div>
            </section>