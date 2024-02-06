<?php /* Template Name: Projetos */ ?>
<?php get_header(); ?>
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
    </main>
<?php get_footer(); ?>