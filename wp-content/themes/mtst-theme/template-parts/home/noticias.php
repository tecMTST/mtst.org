        <?php $last_posts = wp_get_recent_posts(array(
            'numberposts' => 1, // Restringir ao último post
            'post_status' => 'publish' // Restringir a posts publicados
        )); ?>
        <?php foreach( $last_posts as $post_item ) :
            $category = get_the_category($post_item['ID']); 
            $name = $category[0]->cat_name;
            $cat_id = get_cat_ID( $name );
            $link = get_category_link( $cat_id ); ?>
        <section class="noticias" id="noticias">
            <div class="container">
                <h2 class="titulo-secao">ÚLTIMAS NOTÍCIAS</h2>
                <div class="row">
                    <div class="col-md-6">
                        <div class="ultima-noticia">
                            <div class="thumb" style="background-image:url(<?php echo get_the_post_thumbnail_url($post_item['ID']) ?>)">
                                <?php echo '<a class="link-categoria" href="'. esc_url( $link ) .'"><div class="categoria">'. $name .'</div></a>'; ?>
                            </div>
                            <div class="titulo-noticia">
                                <a href="<?php echo get_permalink($post_item['ID']) ?>"><h3><?php echo $post_item['post_title'] ?></h3></a>
                                <div class="ler-mais"><a href="<?php echo get_permalink($post_item['ID']) ?>">LER MAIS >>></a></div>
                            </div>                            
                        </div>
                    </div>
                    <div class="col-md-6">
                        <a href="#" target="_blank" rel="noopener noreferrer">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/banner-lojinha.jpg" alt="Lojinha do MTST">
                        </a>
                    </div>
                </div>
            </div>
         </section>
         <?php endforeach; ?>

        <?php $recent_posts = wp_get_recent_posts(array(
            'numberposts' => 5, // Restringir aos 5 últimos posts
            'post_status' => 'publish' // Restringir a posts publicados
        )); ?>

         <section class="mais-noticias">
            <div class="container">
                <div class="row">
                <?php foreach( $recent_posts as $index => $post_item ) :
                    $category = get_the_category($post_item['ID']); 
                    $name = $category[0]->cat_name;
                    $cat_id = get_cat_ID( $name );
                    $link = get_category_link( $cat_id ); ?>
                    <?php if ($index != 0) : // Não exibir o post mais recente ?> 
                    <div class="col-md-3">
                        <div class="ultima-noticia">
                            <div class="thumb" style="background-image:url(<?php echo get_the_post_thumbnail_url($post_item['ID']) ?>)">
                                <?php echo '<a class="link-categoria" href="'. esc_url( $link ) .'"><div class="categoria">'. $name .'</div></a>'; ?>
                            </div>
                            <div class="titulo-noticia">
                                <a href="<?php echo get_permalink($post_item['ID']) ?>"><h3><?php echo $post_item['post_title'] ?></h3></a>
                                <div class="ler-mais"><a href="<?php echo get_permalink($post_item['ID']) ?>">LER MAIS >>></a></div>
                            </div>                            
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
                </div>
                <div class="ver-tudo"><a href="<?php echo home_url() ?>/noticias">VER TUDO >>></a></div>  
            </div>
         </section>

         