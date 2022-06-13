            <?php $recent_posts = wp_get_recent_posts(array(
            'numberposts' => 9, // Restringir aos 9 últimos posts
            'post_status' => 'publish' // Restringir a posts publicados
            )); ?>
            <section class="noticais-quatro-cols">
                <div class="container">
                    <div class="row">
                        <?php foreach( $recent_posts as $index => $post_item ) :
                        $category = get_the_category($post_item['ID']); 
                        $name = $category[0]->cat_name;
                        $cat_id = get_cat_ID( $name );
                        $link = get_category_link( $cat_id ); ?>
                        <?php if ($index > 4) : // Não exibir o post mais recente ?> 
                        <div class="col-md-3">
                            <div class="ultima-noticia noticias-quatro-col">
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
                </div>
            </section>