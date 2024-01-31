        <section class="banners">
            <div class="container">
                <div class="swiper swiper-banners">
                    <div class="swiper-wrapper">
                    <?php if( have_rows('banners') ): 
                    while( have_rows('banners') ): the_row(); ?>
                        <div class="swiper-slide">
                            <a href="<?php the_sub_field("link_do_banner"); ?>" >
                                <img class="desktop-view" src="<?php the_sub_field("imagem_do_banner"); ?>" alt="Banner">
                                <img class="mobile-view" src="<?php the_sub_field("imagem_do_banner_mobile"); ?>" alt="Banner">
                            </a>
                        </div>
                    <?php endwhile; 
                    endif; ?>
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>
            </div>
        </section>