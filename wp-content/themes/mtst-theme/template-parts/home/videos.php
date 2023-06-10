        <section class="videos desktop-view" id="videos">
            <div class="container">
                <h2 class="titulo-secao">VÍDEOS</h2>
                <div class="row">
                    <?php $cont = 0;
                    if( have_rows('videos') ): 
                    while( have_rows('videos') && $cont == 0 ): the_row(); ?>
                    <div class="col-md-6">
                        <a href="https://youtu.be/<?php the_sub_field('id_video'); ?>" class="vp-a">
                            <div class="youtube-video" style="background:url(https://img.youtube.com/vi/<?php the_sub_field('id_video'); ?>/maxresdefault.jpg)">
                                <img class="player-video-big" src="<?php echo get_template_directory_uri(); ?>/assets/images/player-b.png" alt="">
                            </div>
                        </a>
                        <div class="titulo-video">
                            <h3><?php the_sub_field('titulo_do_video'); ?></h3>
                        </div>
                        <div class="desc">
                            <?php the_sub_field('descricao'); ?>
                        </div>
                    </div>
                    <?php $cont++; 
                    endwhile; 
                    endif; ?>
                    <div class="col-md-6">
                        <?php if( have_rows('videos') ): 
                        while( have_rows('videos') ): the_row(); ?>
                        <ul class="videos">
                            <li> 
                                <a href="https://youtu.be/<?php the_sub_field('id_video'); ?>" class="vp-a">
                                    <div class="youtube-video ltt" style="background-image:url(https://img.youtube.com/vi/<?php the_sub_field('id_video'); ?>/hqdefault.jpg)">
                                        <img class="player-video" src="<?php echo get_template_directory_uri(); ?>/assets/images/player.png" alt="">
                                    </div>
                                </a>
                            </li>
                            <li>
                                <div class="txt-video-r">
                                    <div class="titulo-video ltt">
                                        <h3><?php the_sub_field('titulo_do_video'); ?></h3>
                                    </div>  
                                    <div class="desc ltt">
                                        <?php the_sub_field('descricao'); ?>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <?php
                        $cont++;
                        endwhile;
                        endif; ?>
                    </div>
                </div>
                <div class="ver-tudo">
                    <a target="_blank" href="https://www.youtube.com/channel/UC3OzrZMhnmEgVtxpJoDRkeg">VER TUDO >>></a>
                </div>
            </div>
            <div class="container">
                <?php if( have_rows('banner_final_videos') ): 
                while( have_rows('banner_final_videos') ): the_row(); ?>
                <div class="banner-medium">
                    <a href="<?php the_sub_field('link_do_banner_fn');?>" target="_blank"><img src="<?php the_sub_field('imagem_desktop');?>" alt="Apoie o MTST"></a>
                </div>
                <?php endwhile;
                endif; ?>
            </div>
        </section>

        <section class="videos mobile-view" id="videos-mobile">
            <div class="container">
                <h2 class="titulo-secao">VÍDEOS</h2>
                <div class="swiper swiper-videos">
                    <div class="swiper-wrapper">
                        <?php if( have_rows('videos') ): 
                        while( have_rows('videos') ): the_row(); ?>
                        <div class="swiper-slide">
                            <a href="https://youtu.be/<?php the_sub_field('id_video'); ?>" class="vp-a">
                                <div class="youtube-video-mobile" style="background-image:url(https://img.youtube.com/vi/<?php the_sub_field('id_video'); ?>/hqdefault.jpg)">
                                    <img class="player-video" src="<?php echo get_template_directory_uri(); ?>/assets/images/player.png" alt="">
                                </div>
                            </a>
                            <div class="txt-video-r">
                                <div class="titulo-video ltt">
                                    <h3><?php the_sub_field('titulo_do_video'); ?></h3>
                                </div>  
                                <div class="desc ltt">
                                    <?php the_sub_field('descricao'); ?>
                                </div>
                            </div>
                        </div>
                        <?php
                        endwhile;
                        endif; ?>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </section>


        


        