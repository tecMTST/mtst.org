<?php /* Template Name: Projetos */ 
global $post; ?>
<?php get_header(); ?>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/ultimas-noticias.css">
    <main class="projetos-template">
        <?php if( have_rows('conteudo_projetos') ): 
        while( have_rows('conteudo_projetos') ): the_row(); ?>

            <?php // Case: Imagem.
            if( get_row_layout() == 'imagem_projetos' ): ?>
                <img src="<?php echo get_sub_field('imagem'); ?>" alt="Imagens MTST" class="full-width">
                

            <?php // Case: Texto.
            elseif( get_row_layout() == 'texto_projetos' ): ?>
                <div class="container">
                    <?php echo get_sub_field('p_texto_projeto'); ?>
                </div> 
            <?php endif; ?>

        <?php endwhile; 
        endif; ?>
        <div class="noticias-relacionadas container">
            <?php if ($post->post_name == 'cozinhas-solidarias'): ?>
                <?php $new_query = new WP_Query( array(
                'posts_per_page' => -1,
                'post_type'      => 'post',
                'category_name'  => 'cat-cozinhas-solidarias'
                ) ); ?>
                <div class="swiper swiper-post-rel">
                    <div class="swiper-wrapper">
                        <?php while ( $new_query->have_posts() ) : $new_query->the_post(); ?>
                        <div class="swiper-slide">
                            <div class="ultima-noticia noticias-quatro-col">
                                <div class="thumb" style="background-image:url(<?php echo get_the_post_thumbnail_url(); ?>)">
                                    <?php // echo '<a class="link-categoria" href="'. esc_url( $link ) .'"><div class="categoria">'. $name .'</div></a>'; ?>
                                </div>
                                <div class="titulo-noticia">
                                    <a href="<?php echo get_permalink(); ?>"><h3><?php echo get_the_title(); ?></h3></a>
                                    <div class="ler-mais"><a href="<?php echo get_permalink(); ?>">LER MAIS >>></a></div>
                                </div>                            
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-pagination"></div>
                </div>
            <?php endif; ?>
        </div>
    </main>
<style>
    .swiper-slide {
        padding: 2px;
    }
</style>
<?php get_footer(); ?>