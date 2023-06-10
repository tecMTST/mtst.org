        <section class="programas" id="programas">
            <div class="container">
                <h2 class="titulo-secao">PROGRAMAS</h2>
                <div class="row">
                    <?php if( have_rows('programas') ): 
                    while( have_rows('programas') ): the_row(); ?>
                    <div class="col-md-6">
                        <div class="embed-iframe">
                            <iframe style="border-radius:12px" src="https://open.spotify.com/embed/episode/<?php the_sub_field('id_podcast'); ?>?utm_source=generator&theme=0" width="100%" height="152" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>
                        </div>
                    </div>
                    <?php endwhile; 
                    endif; ?>
                </div>
                <div class="ver-tudo"><a target="_blank" href="https://open.spotify.com/show/5X2nnU5w9uptyMiApbPCWj">VER TUDO >>></a></div>
            </div>
        </section>

         