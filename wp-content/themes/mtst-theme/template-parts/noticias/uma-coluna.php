            <?php $last_posts = wp_get_recent_posts(array(
            'numberposts' => 1, // Restringir ao último post
            'post_status' => 'publish' // Restringir a posts publicados
            )); 
            foreach( $last_posts as $post_item ) :
            $category = get_the_category($post_item['ID']); 
            $name = $category[0]->cat_name;
            $cat_id = get_cat_ID( $name );
            $link = get_category_link( $cat_id ); ?>            
            <section class="noticias-uma-coluna">
                <div class="container">
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
            </section>
            <?php endforeach; ?>