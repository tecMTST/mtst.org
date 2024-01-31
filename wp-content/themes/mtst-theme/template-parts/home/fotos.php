            <section class="fotos" id="fotos">
                <div class="container">
                    <h2 class="titulo-secao">FOTOS</h2>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="foto foto-maior desktop-view">
                                <div class="swiper swiper-foto">
                                    <div class="swiper-wrapper">
                                    <?php $cont = 0; 
                                    if( have_rows('fotos') ): 
                                    while( have_rows('fotos') && $cont < 3 ): the_row(); ?>
                                        <div class="swiper-slide">
                                            <img src="<?php the_sub_field('imagem'); ?>" alt="Foto do Instagram">
                                        </div>
                                    <?php $cont++;
                                    endwhile; 
                                    endif; ?>
                                    </div>
                                    <div class="swiper-pagination"></div>
                                    <div class="swiper-button-prev"></div>
                                    <div class="swiper-button-next"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 desktop-view">
                            <?php $cont = 0;
                            if( have_rows('fotos') ): 
                            while( have_rows('fotos') && $cont < 2 ): the_row(); ?>
                            <div class="foto foto-menor">
                                <img src="<?php the_sub_field('imagem'); ?>" alt="Foto do Instagram">
                            </div>
                            <?php $cont++;
                            endwhile; 
                            endif; ?>
                        </div>
                        <div class="col-md-3 desktop-view">
                            <?php $cont = 0;
                            if( have_rows('fotos') ): 
                            while( have_rows('fotos') && $cont < 2 ): the_row(); ?>
                            <div class="foto foto-menor">
                                <img src="<?php the_sub_field('imagem'); ?>" alt="Foto do Instagram">
                            </div>
                            <?php $cont++;
                            endwhile; 
                            endif; ?>
                        </div>
                    </div>
                    <div class="fotos-mobile mobile-view">
                        <div class="swiper swiper-fotos-mob">
                            <div class="swiper-wrapper">
                            <?php if( have_rows('fotos') ): 
                            while( have_rows('fotos') ): the_row(); ?>
                                <div class="swiper-slide">
                                    <img src="<?php the_sub_field('imagem'); ?>" alt="Foto do Instagram">
                                </div>
                            <?php endwhile; 
                            endif; ?>
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>
                    <div class="ver-tudo">
                        <a target="_blank" href="https://www.instagram.com/mtstbrasil/">VER TUDO >>></a>
                    </div>
                </div>
            </section>